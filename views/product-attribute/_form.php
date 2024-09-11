<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\Supplier;
use app\models\ProductAttributeCategory;

/** @var yii\web\View $this */
/** @var app\models\ProductAttribute $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-attribute-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Основная информация
                    </div>
                    <div class="card-body">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'category_id')->widget(Select2::className(), [
                            'data' => ProductAttributeCategory::getList(),
                            'options' => [
                                'prompt' => '[Не выбрано]',
                            ],
                        ]) ?>
                        <?= $form->field($model, 'supplier_id')->widget(Select2::className(), [
                            'data' => Supplier::getList(),
                            'options' => [
                                'prompt' => '[Не выбрано]',
                            ],
                        ]) ?>

                        <?= $form->field($model, 'begin_qty')->textInput() ?>

                        <?= $form->field($model, 'price')->textInput() ?>

                        <?= $form->field($model, 'is_active')->checkbox() ?>
                    </div>
                </div>
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
