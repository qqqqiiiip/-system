<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js">
    </script>
    <?php $this->head() ?>
</head>

<style>
    th,td{
        padding-left: 10px;
    }
</style>

<body>
<?php $this->beginBody() ?>

<div class="wrap" background="bgimage.jpg">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $cookies1 = Yii::$app->request->cookies;
    $username = '';
    if (!Yii::$app->user->isGuest && $cookies1->has('username')) {
        $username = $cookies1->getValue('username');
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            !Yii::$app->user->isGuest ? ['label' => '主页', 'url' => ['/site/index']] : '',
            !Yii::$app->user->isGuest  ? ['label' => '申报表管理', 'url' => ['/site/shenbao']] : '',
            (!Yii::$app->user->isGuest && Yii::$app->user->identity->username ==='专家') ||
            (!Yii::$app->user->isGuest && Yii::$app->user->identity->username ==='管理员') ? ['label' => '打分表管理功能', 'url' => ['/site/dafen']] : '',
            (!Yii::$app->user->isGuest && Yii::$app->user->identity->username ==='管理员')  ?['label' => 'jisuan', 'url' => ['/site/jisuan']] : '',
            !Yii::$app->user->isGuest ?['label' => '统计', 'url' => ['/site/contact']] : '',
            !Yii::$app->user->isGuest ? ['label' => '账号管理', 'url' => ['/site/zhanghao']] : '',
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' .$username . ' - '. Yii::$app->user->identity->username  .  ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content  ?>
    </div>
</div>

<footer class="footer">
    <div class="constainer">

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
