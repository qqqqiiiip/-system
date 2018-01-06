<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">

        </div>
        <form action="/basic/web/index.php?r=site/expertpoint" method="post" enctype="multipart/form-data">
            格式（a,b,c,d）（txt）
            <input type="file" class="form-control" name="file">
            <hr />
            <input class="form-control"   type="submit" value="导入" >
        </form>

    </div>
</div>
