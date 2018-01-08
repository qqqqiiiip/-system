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
        <select style ="height : 30px">
        <option id ="volvo">总分排名 </option>
        <option id ="saab">定量总排名</option>
        <option id="opel">定量一级排名</option>
        <option id="audi">定量一级类别排名</option>
        <option id="di">定量一级级别排名</option>
        <option id="opel1">定量二级排名</option>
        <option id="audi1">定量二级类别排名</option>
        <option id="di1">定量二级级别排名</option>

      </select>


        </div>

        <?php ActiveForm::end(); ?>

        <br>
        博物馆名称搜索:<input type="text" name="ep11" value="">

        <form action=""  method="post">
<div style ="margin:0 auto;">
            评审年份 :<select style ="margin-left : 38px"   name="myear"  id="myYear">
                <option value=""></option>
            </select>
            <input type="hidden" name="key_list" value="myear">

            <input class=""   type="submit" value="搜索">
</div>
        </form>
        <table  class='table table-hover'  style ="display:none"; id='zfpm'>
              <tr>

                <td width='100'>序号</td>
                <td width='100'> 博物馆</td>
                <td width='180'> 博物馆类型</td>
                <td width='180'> 博物馆级别</td>
                <td width='180'> 年份</td>
                <td width='180'> 总分排名</td>
                <td width='200'> 定性总分排名</td>
                <td width='200'> 定量总分排名</td>

                </tr>
              </table>
              <table  class='table table-hover'  style ="display:none"; id='dlzpm'>
                    <tr>

                      <td width='100'>序号</td>
                      <td width='100'> 博物馆</td>
                      <td width='180'> 博物馆类型</td>
                      <td width='180'> 博物馆级别</td>
                      <td width='180'> 年份</td>
                      <td width='180'> 定量排名</td>
                      <td width='200'> 定量类型排名</td>
                      <td width='200'> 定量级别排名</td>

                      </tr>
                    </table>
              <table  class='table table-hover' style ="display:none" id ='dlyjpm'>
                    <tr>

                      <td width='100'>序号</td>
                      <td width='100'> 博物馆</td>
                      <td width='180'> 年份</td>
                      <td width='180'> 藏品排名</td>
                      <td width='180'> 科学研究排名</td>
                      <td width='180'> 展览与教育排名</td>
                      <td width='200'> 人才培养排名</td>

                      </tr>
                    </table>
                    <table  class='table table-hover' style ="display:none" id ='dlyjlbpm'>
                          <tr>

                            <td width='100'>序号</td>
                            <td width='100'> 博物馆</td>
                            <td width='180'> 年份</td>
                            <td width='180'> 类型</td>
                            <td width='180'> 藏品排名</td>
                            <td width='180'> 科学研究排名</td>
                            <td width='180'> 展览与教育排名</td>
                            <td width='200'> 人才培养排名</td>

                            </tr>
                          </table>
                    <table  class='table table-hover' style ="display:none" id ='dlyjjbpm'>
                                <tr>

                                  <td width='100'>序号</td>
                                  <td width='100'> 博物馆</td>
                                  <td width='180'> 年份</td>
                                  <td width='180'> 级别</td>
                                  <td width='180'> 藏品排名</td>
                                  <td width='180'> 科学研究排名</td>
                                  <td width='180'> 展览与教育排名</td>
                                  <td width='200'> 人才培养排名</td>
                                  </tr>
                    </table>



                    <table  class='table table-hover'  style ="display:none"; id='dlejpm'>
                          <tr>

                            <td width='100'>序号</td>
                            <td width='100'> 博物馆</td>
                            <td width='200'> 年份</td>
                            <td width='180'> 藏品收集排名</td>
                            <td width='200'> 藏品修复排名</td>
                            <td width='200'> 承担项目</td>
                            <td width='200'> 研究成果排名</td>
                            <td width='200'> 学术会议排名</td>
                            <td width='200'> 展览排名</td>
                            <td width='200'> 教育项目排名</td>
                            <td width='200'> 中青年人才引进培养排名</td>
                            <td width='200'> 员工进修与培训排名</td>


                            </tr>
                          </table>
                          <table  class='table table-hover'  style ="display:none"; id='dlejpm'>
                                <tr>

                                  <td width='100'>序号</td>
                                  <td width='100'> 博物馆</td>
                                  <td width='100'> 年份</td>
                                  <td width='100'> 类型</td>
                                  <td width='180'> 藏品收集排名</td>
                                  <td width='200'> 藏品修复排名</td>
                                  <td width='200'> 承担项目</td>
                                  <td width='200'> 研究成果排名</td>
                                  <td width='200'> 学术会议排名</td>
                                  <td width='200'> 展览排名</td>
                                  <td width='200'> 教育项目排名</td>
                                  <td width='200'> 中青年人才引进培养排名</td>
                                  <td width='200'> 员工进修与培训排名</td>


                                  </tr>
                                </table>
                                <table  class='table table-hover'  style ="display:none" id='dlejjbpm'>
                                      <tr>

                                        <td width='100'>序号</td>
                                        <td width='100'> 博物馆</td>
                                        <td width='100'> 年份</td>
                                        <td width='100'> 级别</td>
                                        <td width='180'> 藏品收集排名</td>
                                        <td width='200'> 藏品修复排名</td>
                                        <td width='200'> 承担项目</td>
                                        <td width='200'> 研究成果排名</td>
                                        <td width='200'> 学术会议排名</td>
                                        <td width='200'> 展览排名</td>
                                        <td width='200'> 教育项目排名</td>
                                        <td width='200'> 中青年人才引进培养排名</td>
                                        <td width='200'> 员工进修与培训排名</td>


                                        </tr>
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
    $('#volvo').click(function () {
        $('#zfpm').css('display',"block");
    })
    $('#saab').click(function () {
        $('#dlzpm').css('display',"block");
    })
</script>
