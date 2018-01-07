<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">



        </div>

        <?php ActiveForm::end(); ?>

        <br><h2>按年份进行计算</h2>
        <form action=""  method="post">
            评审年份:<select name="myear"  id="myYear"">
                <option value=""></option>
            </select>
            <input type="hidden" name="key_list" value="myear">
            <input class=""   type="submit" value="生成" >
        </form>


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
