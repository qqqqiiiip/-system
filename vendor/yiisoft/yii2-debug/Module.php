<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\Json;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\web\ForbiddenHttpException;

/**
 * The Yii Debug Module provides the debug toolbar and debugger
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    const DEFAULT_IDE_TRACELINE = '<a href="ide://open?url=file://{file}&line={line}">{text}</a>';

    /**
     * @var array the list of IPs that are allowed to access this module.
     * Each array element represents a single IP filter which can be either an IP address
     * or an address with wildcard (e.g. 192.168.0.*) to represent a network segment.
     * The default value is `['127.0.0.1', '::1']`, which means the module can only be accessed
     * by localhost.
     */
    public $allowedIPs = ['127.0.0.1', '::1'];
    /**
     * @var array the list of hosts that are allowed to access this module.
     * Each array element is a hostname that will be resolved to an IP address that is compared
     * with the IP address of the user. A use case is to use a dynamic DNS (DDNS) to allow access.
     * The default value is `[]`.
     */
    public $allowedHosts = [];
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'yii\debug\controllers';
    /**
     * @var LogTarget
     */
    public $logTarget;
    /**
     * @var array|Panel[] list of debug panels. The array keys are the panel IDs, and values are the corresponding
     * panel class names or configuration arrays. This will be merged with [[corePanels()]].
     * You may reconfigure a core panel via this property by using the same panel ID.
     * You may also disable a core panel by setting it to be false in this property.
     */
    public $panels = [];
    /**
     * @var string the name of the panel that should be visible when opening the debug panel.
     * The default value is 'log'.
     * @since 2.0.7
     */
    public $defaultPanel = 'log';
    /**
     * @var string the directory storing the debugger data files. This can be specified using a path alias.
     */
    public $dataPath = '@runtime/debug';
    /**
     * @var integer the permission to be set for newly created debugger data files.
     * This value will be used by PHP [[chmod()]] function. No umask will be applied.
     * If not set, the permission will be determined by the current environment.
     * @since 2.0.6
     */
    public $fileMode;
    /**
     * @var integer the permission to be set for newly created directories.
     * This value will be used by PHP [[chmod()]] function. No umask will be applied.
     * Defaults to 0775, meaning the directory is read-writable by owner and group,
     * but read-only for other users.
     * @since 2.0.6
     */
    public $dirMode = 0775;
    /**
     * @var integer the maximum number of debug data files to keep. If there are more files generated,
     * the oldest ones will be removed.
     */
    public $historySize = 50;
    /**
     * @var boolean whether to enable message logging for the requests about debug module actions.
     * You normally do not want to keep these logs because they may distract you from the logs about your applications.
     * You may want to enable the debug logs if you want to investigate how the debug module itself works.
     */
    public $enableDebugLogs = false;
    /**
     * @var mixed the string with placeholders to be be substituted or an anonymous function that returns the trace line string.
     * The placeholders are {file}, {line} and {text} and the string should be as follows:
     *
     * `File: {file} - Line: {line} - Text: {text}`
     *
     * The signature of the anonymous function should be as follows:
     *
     * ```php
     * function($trace, $panel) {
     *     // compute line string
     *     return $line;
     * }
     * ```
     * @since 2.0.7
     */
    public $traceLine = self::DEFAULT_IDE_TRACELINE;

    /**
     * @var string Yii logo URL
     */
    private static $_yiiLogo = '';

    /**
     * Returns the logo URL to be used in `<img src="`
     *
     * @return string the logo URL
     */
    public static function getYiiLogo()
    {
        return self::$_yiiLogo;
    }

    /**
     * Sets the logo URL to be used in `<img src="`
     *
     * @param string $logo the logo URL
     */
    public static function setYiiLogo($logo)
    {
        self::$_yiiLogo = $logo;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->dataPath = Yii::getAlias($this->dataPath);

        if (Yii::$app instanceof \yii\web\Application) {
            $this->initPanels();
        }
    }

    /**
     * Initializes panels.
     */
    protected function initPanels()
    {
        // merge custom panels and core panels so that they are ordered mainly by custom panels
        if (empty($this->panels)) {
            $this->panels = $this->corePanels();
        } else {
            $corePanels = $this->corePanels();
            foreach ($corePanels as $id => $config) {
                if (isset($this->panels[$id])) {
                    unset($corePanels[$id]);
                }
            }
            $this->panels = array_filter(array_merge($corePanels, $this->panels));
        }

        foreach ($this->panels as $id => $config) {
            if (is_string($config)) {
                $config = ['class' => $config];
            }
            $config['module'] = $this;
            $config['id'] = $id;
            $this->panels[$id] = Yii::createObject($config);
            if ($this->panels[$id] instanceof Panel && !$this->panels[$id]->isEnabled()) {
                unset($this->panels[$id]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->logTarget = $app->getLog()->targets['debug'] = new LogTarget($this);

        // delay attaching event handler to the view component after it is fully configured
        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            $app->getView()->on(View::EVENT_END_BODY, [$this, 'renderToolbar']);
            $app->getResponse()->on(Response::EVENT_AFTER_PREPARE, [$this, 'setDebugHeaders']);
        });

        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'route' => $this->id,
                'pattern' => $this->id,
            ],
            [
                'class' => 'yii\web\UrlRule',
                'route' => $this->id . '/<controller>/<action>',
                'pattern' => $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>',
            ]
        ], false);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!$this->enableDebugLogs) {
            foreach (Yii::$app->getLog()->targets as $target) {
                $target->enabled = false;
            }
        }

        if (!parent::beforeAction($action)) {
            return false;
        }

        // do not display debug toolbar when in debug view mode
        Yii::$app->getView()->off(View::EVENT_END_BODY, [$this, 'renderToolbar']);
        Yii::$app->getResponse()->off(Response::EVENT_AFTER_PREPARE, [$this, 'setDebugHeaders']);

        if ($this->checkAccess()) {
            $this->resetGlobalSettings();
            return true;
        }

        if ($action->id === 'toolbar') {
            // Accessing toolbar remotely is normal. Do not throw exception.
            return false;
        }

        throw new ForbiddenHttpException('You are not allowed to access this page.');
    }

    /**
     * Setting headers to transfer debug data in AJAX requests
     * without interfering with the request itself.
     *
     * @param \yii\base\Event $event
     * @since 2.0.7
     */
    public function setDebugHeaders($event)
    {
        if (!$this->checkAccess() || !Yii::$app->getRequest()->getIsAjax()) {
            return;
        }
        $url = Url::toRoute(['/' . $this->id . '/default/view',
            'tag' => $this->logTarget->tag,
        ]);
        $event->sender->getHeaders()
            ->set('X-Debug-Tag', $this->logTarget->tag)
            ->set('X-Debug-Duration', number_format((microtime(true) - YII_BEGIN_TIME) * 1000 + 1))
            ->set('X-Debug-Link', $url);
    }

    /**
     * Resets potentially incompatible global settings done in app config.
     */
    protected function resetGlobalSettings()
    {
        Yii::$app->assetManager->bundles = [];
    }

    /**
     * Gets toolbar HTML
     * @since 2.0.7
     */
    public function getToolbarHtml()
    {
        $url = Url::toRoute(['/' . $this->id . '/default/toolbar',
            'tag' => $this->logTarget->tag,
        ]);
        return '<div id="yii-debug-toolbar" data-url="' . Html::encode($url) . '" style="display:none" class="yii-debug-toolbar-bottom"></div>';
    }

    /**
     * Renders mini-toolbar at the end of page body.
     *
     * @param \yii\base\Event $event
     */
    public function renderToolbar($event)
    {
        if (!$this->checkAccess() || Yii::$app->getRequest()->getIsAjax()) {
            return;
        }

        /* @var $view View */
        $view = $event->sender;
        echo $view->renderDynamic('return Yii::$app->getModule("' . $this->id . '")->getToolbarHtml();');

        // echo is used in order to support cases where asset manager is not available
        echo '<style>' . $view->renderPhpFile(__DIR__ . '/assets/toolbar.css') . '</style>';
        echo '<script>' . $view->renderPhpFile(__DIR__ . '/assets/toolbar.js') . '</script>';
    }

    /**
     * Checks if current user is allowed to access the module
     * @return bool if access is granted
     */
    protected function checkAccess()
    {
        $ip = Yii::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }
        foreach ($this->allowedHosts as $hostname) {
            $filter = gethostbyname($hostname);
            if ($filter === $ip) {
                return true;
            }
        }
        Yii::warning('Access to debugger is denied due to IP address restriction. The requesting IP address is ' . $ip, __METHOD__);
        return false;
    }

    /**
     * @return array default set of panels
     */
    protected function corePanels()
    {
        return [
            'config' => ['class' => 'yii\debug\panels\ConfigPanel'],
            'request' => ['class' => 'yii\debug\panels\RequestPanel'],
            'log' => ['class' => 'yii\debug\panels\LogPanel'],
            'profiling' => ['class' => 'yii\debug\panels\ProfilingPanel'],
            'db' => ['class' => 'yii\debug\panels\DbPanel'],
            'assets' => ['class' => 'yii\debug\panels\AssetPanel'],
            'mail' => ['class' => 'yii\debug\panels\MailPanel'],
            'timeline' => ['class' => 'yii\debug\panels\TimelinePanel'],
            'user' => ['class' => 'yii\debug\panels\UserPanel'],
            'router' => ['class' => 'yii\debug\panels\RouterPanel']
        ];
    }

    /**
     * @inheritdoc
     * @since 2.0.7
     */
    protected function defaultVersion()
    {
        $packageInfo = Json::decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'composer.json'));
        $extensionName = $packageInfo['name'];
        if (isset(Yii::$app->extensions[$extensionName])) {
            return Yii::$app->extensions[$extensionName]['version'];
        }
        return parent::defaultVersion();
    }
}
