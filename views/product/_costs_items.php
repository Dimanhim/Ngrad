<?php

use yii\helpers\Html;
use app\models\ProductAttributeCategory;
// productAttributeCategory
// productAttribute

// В example есть массив, который должен отображаться, здесь его нужно вывести. По ходу нужно не значения в массив добавлять, а объекты
// https://stackoverflow.com/questions/61043996/im-trying-to-place-the-placeholder-in-the-border-of-the-input-field

$attributeId = $attribute->id ?? null;
$relationQty = $relation->qty ?? null;

?>
<div class="product-attribute-container product-attribute-container-o">
    <div class="row">
        <div class="col-md-3">
            <?= $category->name ?>
        </div>
        <div class="col-md-3">
            <?= Html::activeDropDownList(
                    $model,
                    "_cat_fields[attributes][]",
                    $category->getAttributeItems(),
                    ['prompt' => '[Выбрать '.$category->name.']', 'class' => 'form-control form-control-sm product-attribute-id-o', 'value' =>  $attributeId, 'data-category-id' => $category->id]) ?>
        </div>
        <div class="col-md-3">
            <?= Html::activeInput('text', $model, "_cat_fields[values][]", ['class' => 'form-control  form-control-sm product-attribute-qty-o', 'placeholder' => $category->getShortTypeText(), 'value' => $relationQty]) ?>
        </div>
        <div class="col-md-3">
            <?= Html::a('<i class="bi bi-plus"></i>', ['#'], [
                'class' => 'btn btn-sm btn-success btn-add-product-category-o',
                'data-product-category-id' => $category->id,
            ]) ?>
            <?= $fixed ? null : Html::a('<i class="bi bi-trash"></i>', ['#'], ['class' => 'btn btn-sm btn-success btn-remove-product-category-o']) ?>
        </div>
    </div>
</div>
