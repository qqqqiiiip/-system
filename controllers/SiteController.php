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
class SiteController extends Controller
{
    public static $museumdata = ['博物馆记录号','评审年份','藏品搜集数量/件','藏品修复数量/件','省部级以上研究项目（包含国际合作研究项目） /项','横向合作研究项目/项','其他研究项目/项','省部级以上获奖成果/项','著作/部','图录/本','论文/篇','科普读物、教材/本','获得专利数/项','举办国际性学术会议/次','举办国内学术会议/次','参加国际性学术会议/人次','参加国内学术会议/人次','省部级以上获奖陈列展览/个','原创性临时展览/个','引进临时展览/个','输出原创性展览/个','观众数/万人','专题讲座、论坛/项','中小学教育项目/项','家庭教育项目/项','社区教育项目/项','教师培训项目/项','其他教育项目/项','获省部级（含）以上的荣誉称号和获奖者（50岁以下） /人','高级职称者（45岁以下） /人','出国进修（培训）人员（含访问学者） /人','国内进修（培训） 人员/人'];
    public static $key_museumdata = ['mid','myear','mdl111','mdl121','mdl211','mdl212','mdl213','mdl221','mdl222','mdl223','mdl224','mdl225','mdl226','mdl231','mdl232','mdl233','mdl234','mdl311','mdl312','mdl313','mdl314','mdl315','mdl321','mdl322','mdl323','mdl324','mdl325','mdl326','mdl411','mdl412','mdl421','mdl422'];

    public static $expertpoint = ['专家记录号','博物馆记录号','评审年份','藏品搜集打分','藏品保护打分','藏品保管打分','学术活动打分','代表性研究成果打分','基本陈列打分','代表性原创临时展览打分','博物馆讲解打分','教育项目打分','公共关系打分','公众服务打分','博物馆网站打分','发展规划打分','制度建设打分','安全管理打分','人才培养打分'];
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
    public static function upload($key,$file,$table){
        if (empty($key) || empty($file)){
            return false;
        }
        $ret = [];
        $n = 0;

        require dirname(dirname(__FILE__)).'/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        $objPHPExcelReader = \PHPExcel_IOFactory::load($file[0]->tempName);
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

    }


    public function actionMuseumdata()
    {

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

    public function postData(){
        $condition = ['num' => 0];
        if (!isset($_POST['key_list'])){
            return $condition;
        }
        $key_list = explode(',',$_POST['key_list']);
        foreach ($key_list as $v){
            if (isset($_POST[$v])){
                $condition[$v] = $_POST[$v];
            }
        }
        return $condition;
    }

    public function getInfo($key,$table,$conditicon){
        $data = [];
        try{
            $num = $conditicon['num'];
            unset($conditicon['num']);
            $db = new \yii\db\Query();
            $db = $db->select(implode(',',$key));
            $cookies1 = Yii::$app->request->cookies;
            $id = 0;
            if (!Yii::$app->user->isGuest && $cookies1->has('uid')) {
                $id = $cookies1->getValue('uid');
            }
            if ($table === 'museumdata' && Yii::$app->user->identity->username === '博物馆'){
                $db = $db->where(['mid' => $id]);
            }
            if ($table === 'expertpoint' && Yii::$app->user->identity->username === '专家'){
                $db = $db->where(['eid' => $id]);
            }
            if (!empty($conditicon)) {
                foreach ($conditicon as $k => $v) {
                    if (empty($v)){
                        continue;
                    }
                    $db = $db->where([$k => $v]);
                }
            }

            $data = $db->offset($num * 10)->limit(10)->from($table)->all();
//            var_dump($conditicon);exit;
        }catch (Exception $e){
            echo $e->getMessage();
            exit;
        }
        return $data;
    }

    public function actionShenbao()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Museumdata();
        $condition = $this->postData();
        $data = $this->getInfo(self::$key_museumdata,'museumdata',$condition);
        if (isset($_GET['_debug'])){
            return json_encode($data);
        }
        return $this->render('shenbao',['model'=>$model,'data'=>$data,'th' => self::$museumdata,'keys' => self::$key_museumdata]);
    }

    public function actionDafen()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Expertpoint();
        $condition = $this->postData();
        $data = $this->getInfo(self::$key_expertpoint,'expertpoint',$condition);
        if (isset($_GET['_debug'])){
            return json_encode($data);
        }
        return $this->render('dafen',['model'=>$model,'data'=>$data,'th' => self::$expertpoint,'keys' => self::$key_expertpoint]);
    }
}
