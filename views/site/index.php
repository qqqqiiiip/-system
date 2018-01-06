<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">


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
