<?php

namespace app\controllers;

use app\models\GetInfo;
use app\models\Sysuser;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

use app\models\ContactForm;

class SiteController extends Controller
{
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
        $model = new GetInfo;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // 验证 $model 收到的数据

            // 做些有意义的事 ...

        }

        return $this->render('index');
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
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionGetInfo()
    {
        $model = new GetInfo();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
