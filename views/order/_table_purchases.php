<?php

use app\models\Product;
use app\models\ProductSize;
use yii\helpers\Html;

?>
<table class="table table-bordered">
    <tr>
        <th>Название товара</th>
        <th>Размер</th>
        <th>Количество</th>
        <th>Действия</th>
    </tr>
    <?php

    if($purchases = $model->purchases) : ?>
        <?php foreach($purchases as $purchase) : ?>
            <?= $this->render('_table_purchases_item', [
                'purchase' => $purchase,
            ]) ?>
        <?php endforeach; ?>
    <?php else : ?>
        <tr class="order-edit-o">
            <td colspan="4">В заказе товары не найдены</td>
        </tr>
    <?php endif; ?>
</table>
