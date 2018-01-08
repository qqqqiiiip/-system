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
        <form action=""  method="post">
            评审年份:<select name="myear"  id="myYear"">
                <option value=""></option>
            </select>
            <input type="hidden" name="key_list" value="myear">
            <input class=""   type="submit" value="生成" >
        </form>
        <table  class='table table-hover' >
              <tr>

                <td width='200'>序号</td>
                <td width='200'> 博物馆</td>
                <td width='200'> 定性总分</td>
                <td width='180'> 定量总分</td>
                <td width='180'> 总分</td>
                <td width='100'> 排名</td>

                </tr>"
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
