<?php

namespace app\controllers;

use app\models\dao\GetInfo;
use app\models\dao\Expertinfo;
use app\models\dao\Expertpoint;
use app\models\dao\Museumdata;
use app\models\dao\Museumdlpoint;
use app\models\dao\Museumdxpoint;
use app\models\dao\Museuminfo;
use app\models\UploadForm;
use yii\web\UploadedFile;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

use app\models\ContactForm;
require dirname(dirname(__FILE__)).'/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
class SiteController extends Controller
{
    public static $key_museumdata = ['mid','myear','mdl111','mdl121','mdl211','mdl212','mdl213','mdl221','mdl222','mdl223','mdl224','mdl225','mdl226','mdl231','mdl232','mdl233','mdl234','mdl311','mdl312','mdl313','mdl314','mdl315','mdl321','mdl322','mdl323','mdl324','mdl325','mdl326','mdl411','mdl412','mdl421','mdl422'];
    public static $key_expertpoint = ['eid','mid','myear','ep11','ep12','ep13','ep21','ep22','ep31','ep32','ep33','ep34','ep41','ep42','ep43','ep51','ep52','ep53','ep54'];


    public function init(){
        $this->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Expertinfo();
        return $this->render('index',[
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionChange()
    {
        $info =Yii::$app->request->post();

        $db = new \yii\db\Query();
        if (!empty($info['utype'])) {
            $db->createCommand()->update('`sysuser`', ['utype' => $info['utype']], 'uname=:uname', [':uname' => $info['uname']])->execute();
        }
        if (!empty($info['upassword'])) {
            $db->createCommand()->update('`sysuser`', ['upassword' => $info['upassword']], 'uname=:uname', [':uname' => $info['uname']])->execute();
        }

        $db->createCommand()->update('`sysuser`',['content'=>$info['ucontent']],'uname=:uname',[':uname'=>$info['uname']])->execute();

        return true;
    }

    public function actionAdd()
    {
        $info =Yii::$app->request->post();

        $db = new \yii\db\Query();
        $db->createCommand()->insert('`sysuser`',['utype'=>$info['utype'],'upassword'=>123456,'content'=>$info['ucontent'],'uname'=>$info['uname']])->execute();
        return true;
    }


    public function actionDelete()
    {
        $info =Yii::$app->request->post();
        $db = new \yii\db\Query();
        $db->createCommand()->delete('`sysuser`','uname=:uname',[':uname'=>$info['uname']])->execute();

        echo true;
    }


    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionGetinfo()
    {
        $model = new GetInfo();
        $model = json_encode($model->attributeLabels());
        return $model;
    }
    public function actionExpertinfo()
    {
        $model = new Expertinfo();
        $model = json_encode($model->attributeLabels());
        return $model;
    }
    public function upload($key,$file,$table){
        if (empty($key) || empty($file)){
            return false;
        }
        $ret = [];
        $n = 0;
        $objPHPExcelReader = \PHPExcel_IOFactory::load($file['tmp_name']);
        foreach($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
        {
            foreach($sheet->getRowIterator() as $row)  //逐行处理
            {
                if($row->getRowIndex()<2)  //确定从哪一行开始读取
                {
                    continue;
                }
                foreach($row->getCellIterator() as $cell)  //逐列读取
                {
                    if (!isset($key[$n+1])){
                        break 3;
                    }
                    $data = $cell->getValue(); //获取cell中数据
                    $ret[$key[$n++]] = $data;
                }
            }
        }
        try{
            $db = new \yii\db\Query();
            $db->createCommand()->insert($table, $ret)->execute();
        }catch (Exception $e){
            return '导入失败---已有记录';
        }
        return true;

    }
    public function actionExpertpoint()
    {
        $file = $_FILES['file'];
        return $this->upload(self::$key_expertpoint,$file,'expertpoint');
    }


    public function actionMuseumdata()
    {
        $file = $_FILES['file'];
        return $this->upload(self::$key_museumdata,$file,'museumdata');
    }
    public function actionMuseumdlpoint()
    {
        $model = new Museumdlpoint();
        $model = json_encode($model->attributeLabels());
        return $model;
    }
    public function actionMuseumdxpoint()
    {
        $model = new Museumdxpoint();
        $model = json_encode($model->attributeLabels());
        return $model;
    }
    public function actionMuseuminfo()
    {
        $model = new Museuminfo();
        $model = json_encode($model->attributeLabels());
        return $model;
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionZhanghao()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->render('zhanghao');
    }

    public function actionTongji()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->render('tongji',[
            'model' => $this->actionGetinfo(),
        ]);
    }

    public function actionShenbao()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $db = new \yii\db\Query();
        try{
            $data = $db->select(implode(',',self::$key_museumdata))->from('museumdata')->all();
            if (isset($_GET['_debug'])){
                return json_encode($data);
            }
        }catch (Exception $e){
            $data = [];
        }
        return $this->render('shenbao',['data'=>$data]);
    }

    public function actionDafen()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $db = new \yii\db\Query();
        try{
            $data = $db->select(implode(',',self::$key_expertpoint))->from('expertpoint')->all();
            if (isset($_GET['_debug'])){
                return json_encode($data);
            }
        }catch (Exception $e){
            $data = [];
        }

        return $this->render('dafen',['data'=>$data]);
    }
}
