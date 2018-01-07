<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">


        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <br><h2>历史数据</h2>
        <form action=""  method="post">
            评审年份:<select name="myear"  id="myYear"">
            <option value=""></option>
            </select>
            <input type="hidden" name="key_list" value="myear">
            <input class=""   type="submit" value="筛选" >
        </form>

        <table>
            <tr>
                <?php foreach ($th as $v): ?>
                    <th><?= Html::encode("{$v}") ?></th>
                <?php endforeach;  ?>
            </tr>
            <?php foreach ($data as $value): ?>
                <tr>
                    <?php foreach ($keys as $v): ?>
                    <td><?= Html::encode("{$value[$v]}") ?></td>
                    <?php endforeach;  ?>
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
</script>
