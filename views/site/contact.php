<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">
        <form action="/basic/web/index.php?r=site/contact&type=muse"  method="post">

            <select style ="height : 30px" name="type">
                <option value ="">总分排名 </option>
                <option value ="dl">定量排名</option>
                <option value="dx">定性排名</option>
            </select>
            博物馆名称搜索:<input type="text" name="muse" value="">


            评审年份:<select name="myear"  id="myYear"">
            <option value=""></option>
            </select>
            <input type="hidden" name="key_list" value="myear">
            <input class=""   type="submit" value="筛选" >
        </form>

        <table  class='table table-hover' id='zfpm'>
              <tr>

                <td width='100'>序号</td>
                <td width='100'> 博物馆</td>
                <td width='180'> 博物馆类型</td>
                <td width='180'> 博物馆级别</td>
                <td width='180'> 年份</td>
                <td width='180'> 总分</td>
                <td width='200'> 定性总分</td>
                <td width='200'> 定量总分</td>

                </tr>
            <?php foreach ($data as $value): ?>
                <tr>
                    <td><?= Html::encode("{$value[0]}") ?></td>
                    <td><?= Html::encode("{$value['uname']}") ?></td>
                    <td><?= Html::encode("{$value['fenlei']}") ?></td>
                    <td><?= Html::encode("{$value['level']}") ?></td>
                    <td><?= Html::encode("{$value['myear']}") ?></td>
                    <td><?= Html::encode("{$value['score']}") ?></td>
                    <td><?= Html::encode("{$value['dx']}") ?></td>
                    <td><?= Html::encode("{$value['dl']}") ?></td>

                </tr>
            <?php endforeach;  ?>


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
