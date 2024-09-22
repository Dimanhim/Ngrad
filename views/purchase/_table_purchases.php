<?php

use app\models\Product;
use app\models\ProductSize;
use yii\helpers\Html;

?>
<table class="table table-bordered">
    <tr>
        <th>Название материала</th>
        <th>Артикул</th>
        <th>Тип</th>
        <th>Кол-во (м.п. и шт.)</th>
        <th>Дата закупки</th>
        <th>Стоимость</th>
        <th>Действия</th>
    </tr>
    <?php

    if($attributes = $model->productAttributes) : ?>
        <?php foreach($attributes as $attribute) : ?>
            <?= $this->render('_table_purchases_item', [
                'attribute' => $attribute,
                'purchase' => $model,
            ]) ?>
        <?php endforeach; ?>
    <?php else : ?>
        <tr class="order-edit-o">
            <td colspan="7">В закупке товары не найдены</td>
        </tr>
    <?php endif; ?>
</table>
