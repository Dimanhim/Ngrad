<?php

use yii\widgets\ActiveForm;
use app\models\Client;
use yii\helpers\Html;
use kartik\widgets\Select2;
//$model->addError('supplier_id', 'Ошибка')
?>
<div class="create-order">
    <?php $form = ActiveForm::begin([
        'id' => 'form-create-order',
        'action' => '/product/create-order',
        'options' => ['class' => 'form-create-order-o'],
    ]) ?>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'price', ['template' => "{input}"])->textInput(['class' => 'form-control form-control-sm calculate-order-o', 'placeholder' => 'Посчитать цену заказа']) ?>
            </div>
            <div class="col-md-2">
                <?= Html::button('Рассчитать заказ', ['class' => 'btn btn-sm btn-success calculate-order-btn-o']) ?>
            </div>
            <div class="col-md-2">
                <?/*= $form->field($model, 'supplier_id', ['template' => "{input} {error}"])->widget(Select2::className(), [
                    'data' => Supplier::getList(),
                    'options' => [
                        'prompt' => '[Выбрать заказчика]',
                        'class' => 'form-control form-control-sm calculate-order-supplier-o',
                    ],
                ])*/ ?>
                <?= $form->field($model, 'supplier_id', ['template' => "{input} {error}"])->dropDownList(Client::getList(), ['prompt' => 'Выбрать заказчика', 'class' => 'form-control form-control-sm calculate-order-supplier-o']) ?>
            </div>
            <div class="col-md-1">
                или
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'supplier_name', ['template' => "{input} {error}"])->textInput(['class' => 'form-control form-control-sm calculate-supplier-o', 'placeholder' => 'Впишите нового заказчика']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'data', ['template' => "{input}"])->hiddenInput(['class' => 'total-data-o']) ?>
                <?= Html::submitButton('Создать заказ', ['class' => 'btn btn-sm btn-success calculate-create-order-btn-o']) ?>
            </div>
        </div>
    <?php ActiveForm::end() ?>
</div>
