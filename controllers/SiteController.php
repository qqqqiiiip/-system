<?php

namespace app\controllers;

use app\models\dao\GetInfo;
use app\models\dao\Expertinfo;
use app\models\dao\Expertpoint;
use app\models\dao\Expertpointdx;
use app\models\dao\Museumdata;
use app\models\dao\Museumdatadx;
use app\models\dao\Museumdlpoint;
use app\models\dao\Museumdxpoint;
use app\models\dao\Museuminfo;
use app\models\Sysuser;
use app\models\UploadForm;
use app\models\User;
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
    public static $dl = ['博物馆记录号','评审年份','藏品搜集数量/件','藏品修复数量/件','省部级以上研究项目（包含国际合作研究项目） /项','横向合作研究项目/项','其他研究项目/项','省部级以上获奖成果/项','著作/部','图录/本','论文/篇','科普读物、教材/本','获得专利数/项','举办国际性学术会议/次','举办国内学术会议/次','参加国际性学术会议/人次','参加国内学术会议/人次','省部级以上获奖陈列展览/个','原创性临时展览/个','引进临时展览/个','输出原创性展览/个','观众数/万人','专题讲座、论坛/项','中小学教育项目/项','家庭教育项目/项','社区教育项目/项','教师培训项目/项','其他教育项目/项','获省部级（含）以上的荣誉称号和获奖者（50岁以下） /人','高级职称者（45岁以下） /人','出国进修（培训）人员（含访问学者） /人','国内进修（培训） 人员/人'];
    public static $key_dl = ['mid','myear','mdl111','mdl121','mdl211','mdl212','mdl213','mdl221','mdl222','mdl223','mdl224','mdl225','mdl226','mdl231','mdl232','mdl233','mdl234','mdl311','mdl312','mdl313','mdl314','mdl315','mdl321','mdl322','mdl323','mdl324','mdl325','mdl326','mdl411','mdl412','mdl421','mdl422'];

    public static $key_dx = ['mid','myear','ep11','ep12','ep13','ep21','ep22','ep31','ep32','ep33','ep34','ep41','ep42','ep43','ep51','ep52','ep53','ep54'];
    public static $dx = ['专家记录号','博物馆记录号','评审年份','藏品搜集打分','藏品保护打分','藏品保管打分','学术活动打分','代表性研究成果打分','基本陈列打分','代表性原创临时展览打分','博物馆讲解打分','教育项目打分','公共关系打分','公众服务打分','博物馆网站打分','发展规划打分','制度建设打分','安全管理打分','人才培养打分'];

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
        unset($info['r']);
        return Sysuser::add($info);
    }


    public function actionDelete()
    {
        $info =Yii::$app->request->post();
        $db = new \yii\db\Query();
        $db->createCommand()->delete('`sysuser`','uname=:uname',[':uname'=>$info['uname']])->execute();

        echo true;
    }

    public function actionDeletemus()
    {
        $info =Yii::$app->request->post();
        $db = new \yii\db\Query();
        $db->createCommand()->delete('`museumdata`','mid=:mid and myear=:myear',[':mid'=>$info['mid'],':myear'=>$info['myear']])->execute();
        echo true;
    }

    public function actionDeleteexp()
    {
        $info =Yii::$app->request->post();
        $db = new \yii\db\Query();
        $db->createCommand()->delete('`expertpoint`','eid=:eid and mid=:mid and myear=:myear',[':mid'=>$info['mid'],':eid'=>$info['eid'],':myear'=>$info['myear']])->execute();
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

        require dirname(dirname(__FILE__)).'/vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        $objPHPExcelReader = \PHPExcel_IOFactory::load($file[0]->tempName);
        $content = "导入结果:</br>";
        foreach($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
        {
            $k = 0;
            foreach($sheet->getRowIterator() as $row)  //逐行处理
            {
                if($row->getRowIndex() == 1)  //确定从哪一行开始读取
                {
                    foreach($row->getCellIterator() as $cell)  //逐列读取
                    {
                        $k++;
                        if ($cell->getValue() === '评审年份'){
                            break;
                        }
                    }
                    continue;
                }
                $year = '';
                $n = 0;
                foreach($row->getCellIterator() as $cell)  //逐列读取
                {
                    if (($n == 0 && empty($cell->getValue()))){
                        continue 2;
                    }
                    if (!isset($key[$n+1])){
                        break;
                    }
                    $data = $cell->getValue(); //获取cell中数据
                    if ($k == $n + 1){
                        $year .= $data;
                    }
                    $ret[$key[$n++]] = $data;
                }
                try{
                    $db = new \yii\db\Query();
                    $db->createCommand()->insert($table, $ret)->execute();
                    $content .= $year . ' 导入成功' . "</br>";
                }catch (Exception $e){
//                    var_dump($year);
                    $content .= $year . ' 导入失败 reason:已有记录 请先执行删除操作'."</br>";
                }
            }
        }
        return $content;

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
    public function actionJisuan()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->render('jisuan');
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

    public function getInfo($key_list,$table,$conditicon){
        $data = [];
        try{
            $num = $conditicon['num'];
            unset($conditicon['num']);
            $db = new \yii\db\Query();
            $cookies1 = Yii::$app->request->cookies;
            $username = $cookies1->getValue('username');
            $self = \Yii::$app->db->createCommand("SELECT * FROM `sysuser` where uname = '".$username."'")->queryAll();
            $keys = implode(',',$key_list);
            if ($self[0]['utype'] == '专家' && ($table == 'expertpoint' || $table == 'expertpointdx')){
                $fenlei = isset(explode(',',$self[0]['content'])[0]) ? explode(',',$self[0]['content'])[0]:'';

                $key = $table == 'expertpoint' ? self::$key_dl : self::$key_dx;
                $mean = $table == 'expertpoint' ? self::$dl : self::$dx;
                $keys = 'eid,mid,myear';
                for ($i = 0 ; $i < count($mean); $i++){
                    if ($fenlei  == $mean[$i]){
                        $res = $key[$i];
                    }
                }
                if (empty($res)){
                    $key = self::$key_dx ;
                    $mean = self::$dx;
                    $keys = 'eid,mid,myear';
                    for ($i = 0 ; $i < count($mean); $i++){
                        if ($fenlei  == $mean[$i]){
                            $res = $key[$i];
                        }
                    }
                }
                $keys .=  ','. $res;

            }
            $db = $db->select($keys);

            $id = 0;
            if (!Yii::$app->user->isGuest && $cookies1->has('uid')) {
                $id = $cookies1->getValue('uid');
            }
            if (($table === 'museumdata' || $table === 'museumdatadx') && Yii::$app->user->identity->username === '博物馆'){
                $db = $db->where(['mid' => $id]);
            }
            if (($table === 'expertpoint' || $table === 'expertpointdx') && Yii::$app->user->identity->username === '专家'){
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

            $data = $db->from($table)->all();
        }catch (Exception $e){
            echo $e->getMessage();
            exit;
        }
        return $data;
    }
    public function getth($table,$type)
    {
        $cookies1 = Yii::$app->request->cookies;
        $username = $cookies1->getValue('username');
        $self = \Yii::$app->db->createCommand("SELECT * FROM `sysuser` where uname = '" . $username . "'")->queryAll();
        if ($self[0]['utype'] == '专家') {
            $fenlei = isset(explode(',', $self[0]['content'])[0]) ? explode(',', $self[0]['content'])[0] : '';
            $key = $table == 'dl' ? self::$key_dl : self::$key_dx ;
            $mean = $table == 'dl' ? self::$dl : self::$dx ;

            for ($i = 0; $i < count($mean); $i++) {
                if ($fenlei . '打分' == $mean[$i]) {
                    $fenlei = $key[$i];
                }
            }
            $keys = 'eid,mid,myear,' . $fenlei;
            $return = explode(',', $keys);
            var_dump($return);
            $m = [];
            $k = 0;
            for ($i = 0; $k < count($return); $i++) {
                if (!isset($key[$i])){
                    break;
                }
                if ($return[$k] == $key[$i]) {
                    $k++;
                    $m[] = $mean[$i];
                    var_dump($mean[$i]);
                    $i = 0;
                }
            }

            return $type != 1 ? $return : $m;
        }
        if ($table == 'dl') {
            if ($type == 1) {
                return self::$dl;
            }
            return self::$key_dl;
         }else{
            if ($type == 1) {
                return self::$dx;
            }
            return self::$key_dx;
        }
    }

    public function actionShenbao()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Museumdata();
        $condition = $this->postData();
        $data = $this->getInfo(self::$key_dl,'museumdata',$condition);
        if (isset($_GET['_debug'])){
            return json_encode($data);
        }
        return $this->render('shenbao',['model'=>$model,'data'=>$data,'th' => $this->getth('dl',1),'keys' => $this->getth('dl',2),'condition' => $condition]);
    }

    public function actionShenbaodx()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Museumdatadx();
        $condition = $this->postData();
        $data = $this->getInfo(self::$key_dx,'museumdatadx',$condition);
        if (isset($_GET['_debug'])){
            return json_encode($data);
        }
        return $this->render('shenbaodx',['model'=>$model,'data'=>$data,'th' => $this->getth('dx',1),'keys' => $this->getth('dx',2),'condition' => $condition]);
    }

    public function actionExport(){
        if (!isset($_GET['condition']) || !isset($_GET['type'])){
            exit;
        }
        ob_start();
        switch ($_GET['type']){
            case 'muse':
                $table = 'museumdata';
                $key = self::$key_dl;
                $title = self::$dl;
                break;
            case 'musedx':
                $table = 'museumdatadx';
                $key = self::$key_dx;
                $title = self::$dx;
                break;
            case 'expert':
                $table = 'expertpoint';
                $key = self::$key_dl;
                $title = self::$dl;
                break;
            case 'expertdx':
                $table = 'expertpointdx';
                $key = self::$key_dx;
                $title = self::$dx;
                break;
        }
        if (empty($key) || empty($table) || empty($title)){
            return;
        }
        $condition = json_decode($_GET['condition'],true);
        $data =  $this->getInfo($key,$table,$condition);
        $result = [];
        foreach ($data as $k => $v){
            $tmp = [];
            foreach ($v as $kk => $vv){
                $tmp[] = $vv;
            }
            $result[] = $tmp;
        }
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '21312');
        $num = 1;
        foreach ($title as $k => $v){
            $num_chr = $num;
            if($k + 65 > 90){
                $num_chr = chr(($k  - 26) + 65).$num;
                $k = 0;
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(chr($k + 65).$num_chr, $v);
        }
        foreach ($result as $k => $v) {
            $num++;
            foreach ($v as $kk => $vv){
                $num_chr = $num;
                if($kk + 65 > 90){
                    $num_chr = chr(($kk  - 26) + 65).$num;
                    $kk = 0;
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue(chr($kk + 65).$num_chr, $vv);
            }

        }
//        $objPHPExcel->getActiveSheet()->setTitle('');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        ob_end_flush();
    }

    public function actionDafen()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Expertpoint();
        $condition = $this->postData();
        $data = $this->getInfo(self::$key_dl,'expertpoint',$condition);
        if (isset($_GET['_debug'])){
            return json_encode($data);
        }
        return $this->render('dafen',['model'=>$model,'data'=>$data,'th' => $this->getth('dl',1),'keys' => $this->getth('dl',2),'condition' => $condition]);
    }

    public function actionDafendx()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Expertpointdx();
        $condition = $this->postData();
        $data = $this->getInfo(self::$key_dx,'expertpointdx',$condition);
        if (isset($_GET['_debug'])){
            return json_encode($data);
        }
        return $this->render('dafendx',['model'=>$model,'data'=>$data,'th' => $this->getth('dx',1),'keys' => $this->getth('dx',2),'condition' => $condition]);
    }
}
