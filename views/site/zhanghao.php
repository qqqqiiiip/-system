<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = '账号管理';
$this->params['breadcrumbs'][] = $this->title;

$content = [];
$cookies1 = Yii::$app->request->cookies;
$self = '';
if (!Yii::$app->user->isGuest && $cookies1->has('username')) {
    $username = $cookies1->getValue('username');
    $self = \Yii::$app->db->createCommand("SELECT * FROM `sysuser` where uname = '".$username."'")->queryAll();
    $self[0]['content'] = rtrim($self[0]['content'],',');
    $arr = explode(',',$self[0]['content']);
    array_pop($arr);
    $self[0]['content_a'] = '';
    foreach ($arr as $v){
        $self[0]['content_a'] .= $v . ',';
    }
    $self[0]['content_a'] = trim($self[0]['content_a'],',');
    $self[0]['content'] = explode(',',$self[0]['content'])[count($self[0]['content'])];
    $self = $self[0];
}


if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username == '管理员'){
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
            $muse[] = array(
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
    $exp = [];
    foreach ($content as $v){
        if ($v['type'] == '专家'){
            $exp[] = array(
                'uid' => $v['uid'],
                'username' => $v['username'],
                'password' => $v['password'],
                'content' => explode(',',$v['content'])[1],
                'fenlei' => explode(',',$v['content'])[0],
                'type' => $v['type'],
            );
        }
    }
    $opt = [];
    foreach ($content as $v){
        if ($v['type'] == '管理员'){
            $opt[] = array(
                'uid' => $v['uid'],
                'username' => $v['username'],
                'password' => $v['password'],
                'content' => explode(',',$v['content'])[0],
                'type' => $v['type'],
            );
        }
    }

}
?>
<div class="site-about">
    <button class="btn btn-sm btn-primary" id="person">个人信息管理</button>
    <?php if (Yii::$app->user->identity->username == '管理员'):?>

    <button class="btn btn-sm btn-primary" id="muse">博物馆</button>
    <button class="btn btn-sm btn-primary" id="expt">专家管理</button>
    <button class="btn btn-sm btn-primary" id="add">增加账号</button>
    <?php endif?>
    <table id="person-table">
        <tr>
            <th>用户名</th>
            <th>密码</th>
            <th>简介</th>
            <th>操作</th>
        </tr>
        <tr>
            <td ><input type="text" value="<?= Html::encode("{$self['uname']}") ?>" readonly></td>
            <td class="label-primary"><input type="text" value="<?= Html::encode("{$self['upassword']}") ?>"></td>
            <td class="label-primary"><input type="text" value="<?= Html::encode("{$self['content']}") ?>"></td>
            <td style="display: none"><input type="text" value="<?= Html::encode("{$self['content_a']}") ?>"></td>
            <td><button class="btn btn-sm btn-primary">更新</button></td>
        </tr>
    </table>
    <br>
    <?php if (!empty($content)): ?>
    <table id="add-table" >
        <tr>
            <th>用户名</th>
            <th>类型</th>
            <th>操作</th>
        </tr>

        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username == '管理员'):?>
            <tr>
                <td class="label-primary"><input type="text" value=""></td>
                <td>
                    <select id="selectID">
                        <option value="博物馆" >博物馆</option>
                        <option value="专家"  >专家</option>
                        <option value="管理员">管理员</option>
                    </select>
                </td>
                <td><button class="btn btn-sm btn-success">添加</button></td>
            </tr>
        <?php endif?>
    </table>
    <table id="opt-table">
        <tr>
            <th>用户名</th>
            <th>简介</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
    <?php foreach ($opt as $value): ?>
        <tr>
            <td><input type="text" value="<?= Html::encode("{$value['username']}") ?>" readonly></td>
            <td><input type="text" value="<?= Html::encode("{$value['content']}") ?>"></td>
            <td>
                <input type="text" name="type" id="type" value="<?= Html::encode("{$value['type']}") ?>" readonly>
            </td>
            <td><button class="btn btn-sm btn-danger">删除</button> <button class="btn btn-sm btn-primary">保存</button></td>
        </tr>
    <?php endforeach;  ?>
    </table>
    <table id="expt-table">
        <tr>
            <th>用户名</th>
            <th>简介</th>
            <th>方向</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
        <?php foreach ($exp as $value): ?>
            <tr>
                <td><input type="text" value="<?= Html::encode("{$value['username']}") ?>" readonly></td>
                <td class="label-primary"><input type="text" value="<?= Html::encode("{$value['content']}") ?>"></td>
                <td class="label-primary"><input type="text" value="<?= Html::encode("{$value['fenlei']}") ?>"></td>
                <td><input type="text" name="type" id="type" value="<?= Html::encode("{$value['type']}") ?>" readonly></td>
                <td><button class="btn btn-sm btn-danger">删除</button> <button class="btn btn-sm btn-primary">保存</button></td>
            </tr>
        <?php endforeach;  ?>
    </table>
    <table id="muse-table">
        <tr>
            <th>用户名</th>
            <th>简介</th>
            <th>等级</th>
            <th>分类</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
    <?php foreach ($muse as $value): ?>
        <tr>
            <td><input type="text" value="<?= Html::encode("{$value['username']}") ?>" readonly></td>
            <td class="label-primary"><input type="text" value="<?= Html::encode("{$value['content']}") ?>"></td>
            <td class="label-primary"><input type="text" value="<?= Html::encode("{$value['level']}") ?>"></td>
            <td class="label-primary"><input type="text" value="<?= Html::encode("{$value['fenlei']}") ?>"></td>
            <td><input type="text" name="type" id="type" value="<?= Html::encode("{$value['type']}") ?>" readonly></td>
            <td><button class="btn btn-sm btn-danger">删除</button> <button class="btn btn-sm btn-primary">保存</button></td>
        </tr>
    <?php endforeach;  ?>

    </table>


        </table>
    <?php endif?>
<!--    <code>--><?//= __FILE__ ?><!--</code>-->


</div>
<script>
    $('.btn-danger').click(function () {
        $(this).parent().parent().remove()
        base = $(this).parent().parent().find('td');
        uname = base.eq(0).find('input').val()
        $.post({
            url: "/basic/web/index.php?r=site/delete",
            data: {uname:uname},
            dataType: "json"
        }).done(function (ret) {
            alert('success');
        }).fail(function () {
            alert('failed');
        });
    })
    $('.btn-primary').click(function () {
        base = $(this).parent().parent().find('td');
        uname = base.eq(0).find('input').val()
        if($(this).text() === '更新'){
            upassword = base.eq(1).find('input').val()
            ucontent_1 = base.eq(2).find('input').val()
            ucontent_2 = base.eq(3).find('input').val()
            ucontent = ucontent_2 + ',' + ucontent_1;
            data = {uname:uname,upassword:upassword,ucontent:ucontent}
        }else if($(this).text() === '保存'){
            ucontent_1 = base.eq(1).find('input').val()
            ucontent_2 = base.eq(2).find('input').val()
            ucontent_3 = base.eq(3).find('input').val()
            ucontent =  ucontent_2 + ',' + ucontent_1;
            if (ucontent_3 != '专家'){
                ucontent = ucontent_3 +',' + ucontent ;
            }
            data = {uname:uname,ucontent:ucontent}
        }else {
            return;
        }
        $.post({
            url: "/basic/web/index.php?r=site/change",
            data: data,
            dataType: "json"
        }).done(function (ret) {
            alert('success');
            window.location.reload()
        }).fail(function () {
            alert('failed');
        });
    })
    $('.btn-success').click(function () {
        base = $(this).parent().parent().find('td');
        uname = base.eq(0).find('input').val()
        utype = base.eq(1).find('select').val()

        $.post({
            url: "/basic/web/index.php?r=site/add",
            data: {uname:uname,content:', ,',utype:utype,upassword:123456},
            dataType: "json"
        }).done(function (ret) {
            alert('success');
            window.location.reload()
        }).fail(function () {
            alert('failed');
        });
    })

    $('#person').click(function () {
        $('table').css('display',"none");
        $('#person-table').css('display',"block");
    })
    $('#muse').click(function () {
        $('table').css('display',"none");
        $('#muse-table').css('display',"block");
    })
    $('#expt').click(function () {
        $('table').css('display',"none");
        $('#expt-table').css('display',"block");
    })
    $('#opt').click(function () {
        $('table').css('display',"none");
        $('#opt-table').css('display',"block");
    })
    $('#add').click(function () {
        $('table').css('display',"none");
        $('#add-table').css('display',"block");
    })
    $('table').css('display',"none");
    $('#person-table').css('display',"block");


</script>
