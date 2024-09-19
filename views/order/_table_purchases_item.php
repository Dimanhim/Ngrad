<?php

use app\models\Product;
use app\models\ProductSize;
use yii\helpers\Html;

?>

<tr class="order-edit-o">
    <td class="chosen-md">
        <?= Html::activeDropDownList($purchase, 'product_id', [0 => '[Не выбрано]'] + Product::getList(), ['value' => $purchase->product_id ?? null, 'class' => 'form-control chosen', 'data-field' => 'product_id', 'data-purchase-id' => $purchase->id ?? null]) ?>
    </td>
    <td class="chosen-sm">
        <?= Html::activeDropDownList($purchase, 'size_id', [0 => '[Не выбрано]'] + ProductSize::getList(), ['value' => $purchase->size_id ?? null, 'class' => 'form-control chosen', 'data-field' => 'size_id', 'data-purchase-id' => $purchase->id ?? null]) ?>
    </td>
    <td class="chosen-sm">
        <?= Html::activeDropDownList($purchase, 'qty', [0 => '[Не выбрано]'] + Product::getPurchasesCount(), ['value' => $purchase->qty ?? null, 'class' => 'form-control chosen', 'data-field' => 'qty', 'data-purchase-id' => $purchase->id ?? null]) ?>
    </td>
    <td><?= Html::a('<i class="bi bi-trash"></i>', ['/#'], ['class' => 'btn btn-success btn-order-purchase-delete-o', 'data-id' => $purchase->id ?? null]) ?></td>
</tr>
