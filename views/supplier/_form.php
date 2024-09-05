<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Client $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="client-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-6">
                <?php
                    $attributes = [
                        $form->field($model, 'name')->textInput(['maxlength' => true]),
                        $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'form-control phone-mask']),
                        $form->field($model, 'email')->textInput(['maxlength' => true]),
                        $form->field($model, 'comment')->textarea(),
                        $form->field($model, 'is_active')->checkbox()
                    ];
                    echo $model->getFormCard($attributes, 'Основная информация');
                ?>
            </div>
            <div class="col-6">
                <?= $model->getImagesField($form) ?>
            </div>
            <div class="col-12">
                <div class="form-group mt10">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
