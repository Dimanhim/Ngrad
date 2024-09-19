<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use app\models\Supplier;
use app\models\Purchase;

/** @var yii\web\View $this */
/** @var app\models\Purchase $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="purchase-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'date_purchase')->widget(DatePicker::className(), []) ?>
                <?= $form->field($model, 'date_delivery')->widget(DatePicker::className(), []) ?>
                <?= $form->field($model, 'supplier_id')->dropDownList(Supplier::getList(), ['prompt' => '[Не выбрано]']) ?>
                <?= $form->field($model, 'phone')->textInput(['class' => 'form-control phone-mask']) ?>
                <?= $form->field($model, 'status_id')->dropDownList(Purchase::getStatusesList(), ['prompt' => '[Не выбрано]']) ?>
                <?= $form->field($model, 'is_active')->checkbox() ?>
            </div>
            <div class="col-md-6">
                <?= $model->getImagesField($form) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
