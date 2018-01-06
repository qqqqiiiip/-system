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
    $self = $self[0];
}


if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username == '管理员'){
    $data = \Yii::$app->db->createCommand('SELECT * FROM `sysuser`')->queryAll();
    foreach ($data as $v){
        $content[] = array(
            'id' => $v['utype'] == '管理员' ? 2 : ($v['utype'] == '专家' ? 1 : 0),
            'username' => $v['uname'],
            'password' => $v['upassword'],
            'content' => $v['content'],
            'type' => $v['utype'],
        );
    }

}
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <table>
        <tr>
            <th>username</th>
            <th>password</th>
            <th>content</th>
            <th>operator</th>
        </tr>
        <tr>
            <td><input type="text" value="<?= Html::encode("{$self['uname']}") ?>" readonly></td>
            <td><input type="text" value="<?= Html::encode("{$self['upassword']}") ?>"></td>
            <td><input type="text" value="<?= Html::encode("{$self['content']}") ?>"></td>
            <td><button class="btn btn-sm btn-primary">更新</button></td>
        </tr>
    </table>
    <br>
    <?php if (!empty($content)): ?>
    <table>
        <tr>
            <th>username</th>
            <th>content</th>
            <th>type</th>
            <th>operator</th>
        </tr>

        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username == '管理员'):?>
            <tr>
                <td><input type="text" value=""></td>
                <td><input type="text" value=""></td>
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


        <?php foreach ($content as $value): ?>
        <tr>
            <td><input type="text" value="<?= Html::encode("{$value['username']}") ?>" readonly></td>
            <td><input type="text" value="<?= Html::encode("{$value['content']}") ?>"></td>
            <td>
                <select id="selectID">
                    <option value="博物馆" <?php if ($value['type'] == '博物馆'):?>selected="selected" <?php endif?> >博物馆</option>
                    <option value="专家" <?php if ($value['type'] == '专家'):?>selected="selected" <?php endif?>  >专家</option>
                    <option value="管理员"<?php if ($value['type'] == '管理员'):?>selected="selected" <?php endif?> >管理员</option>
                </select>
            </td>
            <td><button class="btn btn-sm btn-danger">删除</button> <button class="btn btn-sm btn-primary">保存</button></td>
        </tr>
    <?php endforeach;  ?>
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
        ucontent = base.eq(1).find('input').val()
        utype = base.eq(2).find('select').val()
        if($(this).text() === '更新'){
            utype = base.eq(2).find('input').val()
            data = {uname:uname,upassword:ucontent,ucontent:utype,}
        }else {
            data = {uname:uname,ucontent:ucontent,utype:utype}
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
        ucontent = base.eq(1).find('input').val()
        utype = base.eq(2).find('select').val()

        $.post({
            url: "/basic/web/index.php?r=site/add",
            data: {uname:uname,ucontent:ucontent,utype:utype},
            dataType: "json"
        }).done(function (ret) {
            alert('success');
            window.location.reload()
        }).fail(function () {
            alert('failed');
        });
    })
</script>
