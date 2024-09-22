<?php

use yii\widgets\ActiveForm;
use app\models\Client;
use yii\helpers\Html;
use kartik\widgets\Select2;
use app\models\ProductAttributeCategory;
use app\models\Supplier;

?>
<div class="create-order">
    <?php $form = ActiveForm::begin([
        'id' => 'form-create-order',
        'action' => '/product-attribute/create-purchase',
        'options' => ['class' => 'form-create-order-o'],
    ]) ?>
        <div class="row">
            <div class="col-md-2">
                <?= Html::dropDownList('categories', null, ProductAttributeCategory::getList(), ['prompt' => '[Выбрать категорию]', 'class' => 'form-control form-control-sm select-category-o']) ?>
            </div>
            <div class="col-md-2">
                <?= Html::a('Создать расходник', ['create'], ['class' => 'btn btn-sm btn-primary btn-category-o']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'supplier_id', ['template' => "{input} {error}"])->dropDownList(Supplier::getList(), ['prompt' => 'Выбрать поставщика', 'class' => 'form-control form-control-sm purchase-supplier-o']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'price', ['template' => "{input}"])->textInput(['class' => 'form-control form-control-sm calculate-purchase-o', 'placeholder' => 'Посчитать стоимость закупки']) ?>
            </div>
            <div class="col-md-2">
                <?= Html::button('Рассчитать закупку', ['class' => 'btn btn-sm btn-success calculate-purchase-btn-o']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'data', ['template' => "{input}"])->hiddenInput(['class' => 'total-purchase-data-o']) ?>
                <?= Html::submitButton('Создать закупку', ['class' => 'btn btn-sm btn-success create-purchase-btn-o']) ?>
            </div>


        </div>
    <?php ActiveForm::end() ?>
</div>
