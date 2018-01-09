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
          <select>
        <option value ="volvo">Volvo</option>
        <option value ="saab">Saab</option>
        <option value="opel">Opel</option>
        <option value="audi">Audi</option>
      </select>


        </div>

        <?php ActiveForm::end(); ?>

        <br>
            评审年份:
            <select name="myear"  id="myYear"">
            </select>
            <select name="type"  id="type"">
            <option value="dl">定量分</option>
            <option value="dx">定性分</option>
            </select>
            <input class=""   type="submit" id="submit" value="生成" >

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

    $('#submit').click(function () {
        year = $('#myYear').val();
        type = $('#type').val();
        $.get({
            url: "/basic/web/index.php?r=site/cal",
            data: {year:year,type:type},
            dataType: "json"
        }).done(function (ret) {
            alert('success');
            window.location.reload()
        }).fail(function () {
            alert('failed');
        });
    });
</script>
