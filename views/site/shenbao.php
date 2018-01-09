<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <?php if (Yii::$app->user->identity->username != '专家'):?>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <?php endif?>

        <br><h2>历史数据</h2>
        <form action=""  method="post">
            评审年份:<select name="myear"  id="myYear"">
            <option value=""></option>
            </select>
            博物馆id<input type="text" name="mid">
            <input type="hidden" name="key_list" value="myear,mid">
            <input class=""   type="submit" value="筛选" >
        </form>
        <button id="export" class="btn-success btn-sm btn"><a target="_blank" href="/basic/web/index.php?r=site/export&type=muse&condition=<?= Html::encode(json_encode($condition)) ?>">导出excel</a></button>

        <table>
            <tr>
                <?php foreach ($th as $v): ?>
                    <th><?= Html::encode("{$v}") ?></th>
                <?php endforeach;  ?>
                <?php if (Yii::$app->user->identity->username != '专家'):?>
                <th>操作</th>
                <?php endif?>
            </tr>
            <?php foreach ($data as $value): ?>
                <tr>
                    <?php foreach ($keys as $v): ?>
                    <td><?= Html::encode("{$value[$v]}") ?></td>
                    <?php endforeach;  ?>
                    <?php if (Yii::$app->user->identity->username != '专家'):?>
                    <td><button class="btn btn-sm btn-danger">删除</button></td>
                    <?php endif?>
                </tr>
            <?php endforeach;  ?>
        </table>

    </div>
</div>
<script>
    window.onload=function(){
//设置年份的选择
        var myDate= new Date();
        var startYear=myDate.getFullYear()-20;//起始年份
        var endYear=myDate.getFullYear();//结束年份
        var obj=document.getElementById('myYear')
        for (var i=endYear;i>=startYear;i--)
        {
            obj.options.add(new Option(i,i));
        }
    }

    $('#export').click(function (data) {
        if(!isArray(data)){
            return;
        }
        $.post({
            url: "/basic/web/index.php?r=site/export",
            data: data,
            dataType: "json"
        }).done(function (ret) {
            alert('success');
            window.location.reload()
        }).fail(function () {
            alert('failed');
        });
    });

    $('.btn-danger').click(function () {
        $(this).parent().parent().remove()
        base = $(this).parent().parent().find('td');
        mid = base.eq(0).text()
        myear = base.eq(1).text()
        $.post({
            url: "/basic/web/index.php?r=site/deletemus",
            data: {mid:mid,myear:myear},
            dataType: "json"
        }).done(function (ret) {
            alert('success');
        }).fail(function () {
            alert('failed');
        });
    })
</script>
