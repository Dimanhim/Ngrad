<?php

use yii\helpers\Html;

?>

<div>
    <h3><?= $model->name ?></h3>
    <table class="table table-bordered purchase-id-o" data-id="<?= $model->id ?>">
        <tr>
            <th>Поставщик</th>
            <th>Название</th>
            <th>Артикул</th>
            <th>Стоимость <?= $model->getShortTypeText(true) ?></th>
            <th>Наличие на складе <?= $model->getShortTypeText(true) ?></th>
            <th>В ожидании <?= $model->getShortTypeText(true) ?></th>
            <th>Действия</th>
            <th>Добавить в закупку <?= $model->getShortTypeText(true) ?></th>
        </tr>
        <?php if($attributes = $model->productAttributes) : ?>
            <?php foreach($attributes as $attribute) : ?>
                <tr class="attributes-list-o">
                    <td><?= $attribute->supplier ? Html::a($attribute->supplier->name, ['supplier/view', 'id' => $attribute->supplier->id]): null ?></td>
                    <td><?= $attribute->name ?></td>
                    <td><?= $attribute->alias ?></td>
                    <td><?= $attribute->price ?></td>
                    <td><?= $attribute->stockValue ?></td>
                    <td><?= $attribute->waitValue ?></td>
                    <td class="attribute-actions">
                        <?= $model->getActionButtonsList(['view', 'update'], 'product-attribute', $attribute, true) ?>
                    </td>
                    <td>
                        <?= Html::textInput('attribute-name', null, ['class' => 'form-control form-control-sm add-purchase-input-o', 'data-attribute' => $attribute->id]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

    </table>
</div>
