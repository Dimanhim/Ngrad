<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\ProductAttributeCategory;

/** @var yii\web\View $this */
/** @var app\models\ProductAttributeCategory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-attribute-category-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type_id')->widget(Select2::className(), [
            'data' => ProductAttributeCategory::getTypes(),
            'options' => [
                'prompt' => '[Не выбрано]'
            ],
        ]) ?>

        <?= $form->field($model, 'is_active')->checkbox() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>






</div>
