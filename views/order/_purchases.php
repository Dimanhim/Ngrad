<?php

use yii\helpers\Html;

?>
<?php if($purchases = $model->purchases) : ?>
    <table class="table table-sm table-attributes-list">
        <tbody>
            <tr>
                <th>Товар</th>
                <th>Размер</th>
                <th>Кол-во, шт.</th>
            </tr>
        <?php foreach($purchases as $purchase) : ?>
            <tr>
                <td><?= Html::a($purchase->product->name, ['product/view', 'id' => $purchase->product->id]) ?></td>
                <td><?= Html::a($purchase->size->name, ['size/view', 'id' => $purchase->size->id]) ?></td>
                <td><?= $purchase->qty ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
