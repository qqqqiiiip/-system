<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">


        <?php if (Yii::$app->user->identity->username != '博物馆'):?>
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
            专家id:<input type="text" name="eid" value="">
            博物馆id:<input type="text" name="mid" value="">
            <input type="hidden" name="key_list" value="myear,mid,eid">
            <input class=""   type="submit" value="筛选" >
        </form>
        <button id="export" class="btn-success btn-sm btn"><a target="_blank" href="/basic/web/index.php?r=site/export&type=expertdx&condition=<?= Html::encode(json_encode($condition)) ?>">导出excel</a></button>
        <table>
            <tr>
                <?php foreach ($th as $v): ?>
                    <th><?= Html::encode("{$v}") ?></th>
                <?php endforeach;  ?>
                <th>操作</th>
            </tr>
            <?php foreach ($data as $value): ?>
                <tr>
                    <?php foreach ($keys as $v): ?>
                        <?php if (!empty($value[$v])):?>
                            <td><?= Html::encode("{$value[$v]}") ?></td>
                        <?php endif?>
                        <?php if (empty($value[$v])):?>
                            <td></td>
                        <?php endif?>
                    <?php endforeach;  ?>
                    <?php if (Yii::$app->user->identity->username != '博物馆'):?>
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

    $('.btn-danger').click(function () {
        $(this).parent().parent().remove()
        base = $(this).parent().parent().find('td');
        eid = base.eq(0).text()
        mid = base.eq(1).text()
        myear = base.eq(2).text()
        $.post({
            url: "/basic/web/index.php?r=site/deleteexp",
            data: {eid:eid,mid:mid,myear:myear},
            dataType: "json"
        }).done(function (ret) {
            alert('success');
        }).fail(function () {
            alert('failed');
        });
    })
</script>
