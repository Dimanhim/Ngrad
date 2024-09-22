<?php

use app\models\Product;
use app\models\ProductSize;
use yii\helpers\Html;
use app\models\ProductAttribute;

?>

<tr class="pa-edit-o">
    <td class="chosen-md">
        <?= Html::activeDropDownList($attribute, 'product_attribute_id', [0 => '[Не выбрано]'] + ProductAttribute::getList($attribute->productAttribute->category_id), ['value' => $attribute->product_attribute_id ?? null, 'class' => 'form-control chosen', 'data-field' => 'product_attribute_id', 'data-pa-id' => $attribute->productAttribute->id ?? null]) ?>
    </td>
    <td>
        <?= $attribute->productAttribute->alias ?>
    </td>
    <td>
        <?= $attribute->productAttribute->category->name ?>
    </td>
    <td>
        <?= Html::textInput('purchase-product-attribute-qty', $attribute->qty, ['class' => 'form-control form-control-sm', 'data-field' => 'qty', 'data-pa-id' => $attribute->id ?? null]) ?>
    </td>
    <td>
        <?= $attribute->purchase->date_purchase ?>
    </td>
    <td>
        <?php $fullCost = $attribute->productAttribute->price * $attribute->qty; echo number_format($fullCost, 0, null, ' ') ?>
    </td>
    <td><?= Html::a('<i class="bi bi-trash"></i>', ['/#'], ['class' => 'btn btn-sm btn-success btn-pa-purchase-delete-o', 'data-id' => $attribute->id ?? null, 'data-pa-id' => $attribute->id ?? null]) ?></td>
</tr>
