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

    public static $key_dx = ['eid','mid','myear','ep11','ep12','ep13','ep21','ep22','ep31','ep32','ep33','ep34','ep41','ep42','ep43','ep51','ep52','ep53','ep54'];
    public static $dx = ['专家记录号','博物馆记录号','评审年份','藏品搜集打分','藏品保护打分','藏品保管打分','学术活动打分','代表性研究成果打分','基本陈列打分','代表性原创临时展览打分','博物馆讲解打分','教育项目打分','公共关系打分','公众服务打分','博物馆网站打分','发展规划打分','制度建设打分','安全管理打分','人才培养打分'];

    public static $percent = [
        'ep11'=>0.13,
        'ep12'=>0.13,
        'ep13'=>0.13,
        'ep21'=>0.13,
        'ep22'=>0.13,
        'ep31'=>0.13,
        'ep32'=>0.13,
        'ep33'=>0.13,
        'ep34'=>0.13,
        'ep41'=>0.13,
        'ep42'=>0.13,
        'ep43'=>0.13,
        'ep51'=>0.13,
        'ep52'=>0.13,
        'ep53'=>0.13,
        'ep54'=>0.13,];
    public static $percent2 = [
        'ep11'=>0.13,
        'ep12'=>0.13,
        'ep13'=>0.13,
        'ep21'=>0.13,
        'ep22'=>0.13,
        'ep31'=>0.13,
        'ep32'=>0.13,
        'ep33'=>0.13,
        'ep34'=>0.13,
        'ep41'=>0.13,
        'ep42'=>0.13,
        'ep43'=>0.13,
        'ep51'=>0.13,
        'ep52'=>0.13,
        'ep53'=>0.13,
        'ep54'=>0.13,];
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
        $db->createCommand()->delete('`expertpointdx`','eid=:eid and mid=:mid and myear=:myear',[':mid'=>$info['mid'],':eid'=>$info['eid'],':myear'=>$info['myear']])->execute();
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
        $ret = [];
        $condition = ' 1=1 ';

        $data = \Yii::$app->db->createCommand('SELECT * FROM `sysuser`')->queryAll();
        foreach ($data as $v){
            $content[] = array(
                'uid' => $v['uid'],
                'username' => $v['uname'],
                'password' => $v['upassword'],
                'content' => $v['content'],
                'type' => $v['utype'],
            );
        }
        $muse = [];
        foreach ($content as $v){
            if ($v['type'] == '博物馆'){
                $muse[$v['uid']] = array(
                    'uid' => $v['uid'],
                    'username' => $v['username'],
                    'password' => $v['password'],
                    'content' => isset(explode(',',$v['content'])[2]) ? explode(',',$v['content'])[2] : '',
                    'level' => isset(explode(',',$v['content'])[1]) ?  explode(',',$v['content'])[1]:'',
                    'fenlei' => isset(explode(',',$v['content'])[0]) ? explode(',',$v['content'])[0]:'',
                    'type' => $v['type'],
                );
            }
        }


        if(isset($_POST['muse']) && !empty($_POST['muse'])){
            $name = trim($_POST['muse'],' ');
            $people = \Yii::$app->db->createCommand("SELECT * FROM `sysuser` where ".$condition ." and uname like '%".$name."%'")->queryAll();
            $tmp = [-1];
            foreach ($people as $v){
                $tmp[] = $v['uid'];
            }
            $condition .= ' and mid in(' . implode(',',$tmp).')';
        }
        if(isset($_POST['myear']) && !empty($_POST['myear'])){
            $condition .= " and myear = " . $_POST['myear'];
        }


        $dl = \Yii::$app->db->createCommand("SELECT * FROM `resultdl_1` where ".$condition)->queryAll();
        $dx = \Yii::$app->db->createCommand("SELECT * FROM `resultdx_1` where ".$condition)->queryAll();
        $sum_dl = [];
        foreach ($dl as $v){
            $sum_dl[$v['mid'].'-'.$v['myear']] = 0;
            foreach ($v as $kk => $vv){
                if (in_array($kk,['mid','myear'])){
                    continue;
                }
                $sum_dl[$v['mid'].'-'.$v['myear']] += intval($vv);
            }

        }
        $sum_dx = [];
        foreach ($dx as $v) {
            $sum_dx[$v['mid'].'-'.$v['myear']] = 0;
            foreach ($v as $kk => $vv){
                if (in_array($kk,['mid','myear'])){
                    continue;
                }
                $sum_dx[$v['mid'].'-'.$v['myear']] += intval($vv);
            }
        }

        if (empty($_POST['type'])){
            foreach ($sum_dl as $k => $v){
                if (!empty($sum_dl[$k]) && !empty($sum_dx[$k])) {
                    $ret[$k] = 0.7 * $sum_dl[$k] + 0.3 * $sum_dx[$k];
                }
            }
            krsort($ret);
        }elseif ($_POST['type'] == 'dl'){
            ksort($sum_dl);
            foreach ($sum_dl as $k => $v){
                if (!empty($sum_dl[$k]) && !empty($sum_dx[$k])) {
                    $ret[$k] = 0.7 * $sum_dl[$k] + 0.3 * $sum_dx[$k];
                }
            }
        }elseif ($_POST['type'] == 'dx'){
            krsort($sum_dx);
            foreach ($sum_dx as $k => $v){
                if (!empty($sum_dl[$k]) && !empty($sum_dx[$k])) {
                    $ret[$k] = 0.7 * $sum_dl[$k] + 0.3 * $sum_dx[$k];
                }
            }
        }

        $res = [];
        $num = 1;
        foreach ($ret as $k => $v){
            $mid = explode('-',$k)[0];
            $res[] = [
                0 => $num++,
                'myear' => explode('-',$k)[1],
                'mid' => $mid,
                'score' => $v,
                'uname' => $muse[$mid]['username'],
                'fenlei' => $muse[$mid]['fenlei'],
                'level' => $muse[$mid]['level'],
                'dx' => $sum_dx[$k],
                'dl' => $sum_dl[$k],
            ];
        }

        return $this->render('contact', [
            'data' => $res ,
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
                $ret = [];
                $year = '';
                $n = -1;
                foreach($row->getCellIterator() as $cell)  //逐列读取
                {
                    $n++;
                    if ($n > count($key)){
                        break;
                    }
                    if ($n == 0){
                        $cookies1 = Yii::$app->request->cookies;
                        $id = $cookies1->getValue('uid');
                        if ($id != $cell->getValue() && Yii::$app->user->identity->username != '管理员'){
                            $content .= $year . ' 导入失败 reason:没有权限导入其他用户数据'."</br>";
                            continue 2;
                        }
                    }
                    if (empty($cell->getValue()) || !isset($key[$n])){
                        continue;
                    }
                    $data = $cell->getValue(); //获取cell中数据
                    if ($k == $n + 1){
                        $year .= $data;
                    }
                    $ret[$key[$n]] = $data;
                }
                try{
                    $db = new \yii\db\Query();
                    $db->createCommand()->insert($table, $ret)->execute();
                    $content .= $year . ' 导入成功' ."</br>";
                }catch (Exception $e){
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

        return $this->render('jisuan',['score' => 1]);
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
                if (!empty($res)) {
                    $keys .= ',' . $res;
                }

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

                    $db = $db->andWhere([$k => $v]);
                }

            }
//            var_dump($db); exit;

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
        if ($self[0]['utype'] == '专家' && $self[0]['utype'] != '专家') {
            $fenlei = isset(explode(',', $self[0]['content'])[0]) ? explode(',', $self[0]['content'])[0] : '';

            $key = $table == 'dl' ? self::$key_dl : self::$key_dx ;
            $mean = $table == 'dl' ? self::$dl : self::$dx ;

            for ($i = 0; $i < count($mean); $i++) {
                if ($fenlei . '打分' == $mean[$i]) {
                    $fenlei = $key[$i];
                }
            }
            if (!empty($fenlei)) {
                $keys = 'mid,myear,' . $fenlei;
            }else{
                $keys = 'mid,myear';
            }
            $return = explode(',', $keys);
            $m = [];
            $k = 0;
            for ($i = 0; $k < count($return); $i++) {
                if (!isset($key[$i])){
                    break;
                }
                if ($return[$k] == $key[$i]) {
                    $k++;
                    $m[] = $mean[$i];
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

    public function actionCal(){
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $year = $_GET['year'];
        $people = \Yii::$app->db->createCommand("SELECT * FROM `sysuser` where utype = '专家'")->queryAll();
        $arry = [];
        $key = $_GET['type'] == 'dl' ? self::$key_dl : self::$key_dx;
        $mean = $_GET['type'] == 'dl' ? self::$dl : self::$dx;
        $table = $_GET['type'] == 'dl' ? 'museumdata' : 'expertpointdx';
        foreach ($people as $v){
            $k = isset(explode(',',$v['content'])[0]) ? explode(',',$v['content'])[0] : '';
            for ($i = 0;$i < count($mean) ;$i++){
                if ($mean[$i] == $k){
                    $flag = $key[$i];
                }
            }
            if (empty($flag)){
                continue;
            }
            if (!isset($arry[$flag])){
                $arry[$flag] = [];
            }
            $arry[$flag][] = $v['uid'];
        }

        $dx_score = \Yii::$app->db->createCommand("SELECT * FROM ".$table." where myear = '".$year."'")->queryAll();
        $mids = [];
        foreach ($dx_score as $v){
            if (!in_array($v['mid'],$mids)) {
                $mids[] = $v['mid'];
            }
        }
        $tablex = $_GET['type'] == 'dl' ? 'old_dl' : 'old_dx';
        $old =[];
        $old_s = \Yii::$app->db->createCommand("SELECT * FROM ".$tablex." limit 1")->query();
        foreach ($old_s as $k => $v){
            foreach ($v as $kk => $vv) {
                $old [$kk] = $vv;
            }
        }
        $result = [];
        foreach ($mids as $v){
            $result[$v] = [];
            $all = 0;
            foreach ($key as $vv){
                if (!in_array($vv,['mid','myear'])) {
                    $result[$v][$vv] = 0;
                }
                $sum = 0;
                if ($_GET['type'] == 'dl') {
                    $score = \Yii::$app->db->createCommand("SELECT ". implode(',',self::$key_dl) ." FROM ".$table . " where mid =" . $v . "")->queryAll();
                    foreach ($score as $s){
                        if (in_array($vv,['mid','myear'])){
                            continue;
                        }
                        $sum += (intval($s[$vv]) * $old[$vv]);
                    }
                }else {
                    if (!isset($arry[$vv])|| in_array($vv, ['eid'])) {
                        continue;
                    }
                    $list = implode(',', $arry[$vv]);
                    $score = \Yii::$app->db->createCommand("SELECT " . $vv . " FROM " . $table . " where mid = ".$v." and eid in (" . $list . ")")->queryAll();
                    if (empty($score)) {
                        continue;
                    }
                    foreach ($score as $s) {
                        $sum += (intval($s[$vv]) / $old[$vv]) * 100;
                    }
                    $sum = $sum / count($score) * self::$percent[$vv];
                }

                $result[$v][$vv] = $sum;
                $all += $sum;
            }
            $result[$v]['mid'] = $v;
            $result[$v]['myear'] = $year;
//            $result[$v]['sum'] = $all;
        }
        $db = new \yii\db\Query();
        $table = $_GET['type'] == 'dl' ? 'resultdl' : 'resultdx';
        $start = $_GET['type'] == 'dl' ? 4 : 2;
        $table_2 = $_GET['type'] == 'dl' ? $table.'_2' : $table.'_1';
        $temp_2 = [];
        foreach ($result as $v){
            unset($v['eid']);
            $result_1 = [
                'mid' => $v['mid'],
                'myear' => $year,
            ];
            foreach ($v as $kk => $vv){
                $key = substr($kk,$start,1);
                if (intval($key) == 0){
                    continue;
                }
                $key = $_GET['type'] == 'dl' ? substr($kk,0,4) . $key  : 'ep' . $key;

                if (!isset($result_1[$key])){
                    $result_1[$key] = intval($vv);
                }else{
                    $result_1[$key] = $result_1[$key] + intval($vv);
                }
            }

            $temp_2[$v['mid']] = $result_1;
            try{
                //插入一级指标
                $db->createCommand()->insert($table_2, $result_1)->execute();
            }catch (Exception $e){
//                var_dump($e->getMessage());
            }
            try{
                //插入二级指标
                $db->createCommand()->insert($table, $v)->execute();
            }catch (Exception $e){
//                var_dump($e->getMessage());
            }
        }
        if ($_GET['type'] == 'dx'){
            return true;
        }
        $max = [];
        $avg = [];

        foreach ($temp_2 as $v){
            foreach ($v as $kk => $vv){
                if (in_array($kk,['mid','myear'])) {
                    continue;
                }
                if (!isset($max[$kk])){
                    $max[$kk] = $vv;
                }elseif ($max[$kk] < $vv){
                    $max[$kk] = $vv;
                }

                if (!isset($avg[$kk])){
                    $avg[$kk] = $vv;
                }else{
//                    echo 1;
                    $avg[$kk] += $vv;
                }
            }
        }
        $all = count($temp_2);
        $cn = [];
        foreach ($temp_2 as $k => $v) {
            $cn[$k] = [];
            foreach ($v as $kk => $vv){
                if (in_array($kk,['mid','myear'])) {
                    $cn[$k][$kk] = $vv;
                    continue;
                }
                if ($max[$kk] - $avg[$kk]/$all == 0){
                    $max[$kk] = 2000;
                    $vv = 2500;
                }
                $cn[$k][$kk] = 100 * (0.6 + 0.4 * ($vv - $avg[$kk]/$all)/($max[$kk] - $avg[$kk]/$all)) * 0.1;
            }
        }
        $start = 3;
        $table_2 = 'resultdl_1';
        foreach ($cn as $k => $v){
            $result_1 = [
                'mid' => $v['mid'],
                'myear' => $year,
            ];
            foreach ($v as $kk => $vv){
                $key = substr($kk,$start,1);
                if (intval($key) == 0){
                    continue;
                }
                if (empty($result_1[$key])){
                    $key = substr($kk,0,3) . $key ;
                    $result_1[$key] = intval($vv);
                }else{
                    var_dump(111);
                    $key = substr($kk,0,3) . $key ;
                    $result_1[$key] = $result_1[$key] + intval($vv);
                }
            }
            $temp_2[$v['mid']] = $result_1;
            //插入一级指标
            $db->createCommand()->insert($table_2, $result_1)->execute();
        }





        return true;


    }

}
