<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\models\ProductCollection;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Основная информация
                        </div>
                        <div class="card-body">
                            <div class="added-block">
                                <div class="row">
                                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                                    <?php
                                        $classNumber = $model->isNewRecord ? 4 : 12;
                                    ?>
                                    <div class="col-md-<?= $classNumber ?>">
                                        <?= $form->field($model, 'collection_id')->widget(Select2::className(), [
                                            'data' => ProductCollection::getList(),
                                            'options' => [
                                                'prompt' =>'[Не выбрано]',
                                            ],
                                        ]) ?>
                                    </div>
                                    <?php if($model->isNewRecord) : ?>
                                    <div class="col-md-1">
                                        <p class="added-range" style="margin-top: 36px;">
                                            или
                                        </p>
                                    </div>
                                    <div class="col-md-7">
                                        <?= $form->field($model, '_free_field')->textInput(['class' => 'form-control added-field', 'placeholder' => 'Введите название новой коллекции'])->label(false) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?= $form->field($model, 'model_name')->textInput(['maxlength' => true])->label('Модель') ?>
                            <?= $form->field($model, 'is_active')->checkbox() ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Расходы на пошив
                        </div>
                        <div class="card-body">
                            <?= $this->render('_costs', [
                                'model' => $model,
                                'form' => $form,
                            ]) ?>
                        </div>
                    </div>
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
