<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <?php
        $model = new \app\models\GetInfo();
        if ($model->validate()) {
            // 验证成功！
        } else {
            // 失败！
            // 使用 $model->getErrors() 获取错误详情
        }?>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'begin') ?>

        <?= $form->field($model, 'end') ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="body-content">

        <div class="row">

        </div>

    </div>
</div>
