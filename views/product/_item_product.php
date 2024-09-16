<?php

use app\models\Product;
use app\models\ProductSize;
use yii\helpers\Html;


$colspan = 3;
$categories = $model->productAttributeCategories;
$attributes = $model->productRelations;
$sizes = ProductSize::getListKeys();
$productCount = Product::getPurchasesCount();


if(count($attributes) > count($sizes) and count($attributes) >= 3) {
    $rowspan = count($attributes);
}
elseif(count($attributes) <= count($sizes) and count($sizes) >= 3) {
    $rowspan = count($sizes);
}
else {
    $rowspan = 3;
}

$totalCost = [];


// отдельно заполняем 1-ю строчку
// остальные - циклом
// rowspan определяется как наибольшее между размером и атрибутами товаров
?>
<table class="table table-bordered product-id-o" data-id="<?= $model->id ?>">
    <tr>
        <th><?= $model->name ?></th>
        <th colspan="<?= $colspan ?>">Ткани</th>
        <th colspan="<?= $colspan ?>">Второй слой</th>
        <th colspan="<?= $colspan ?>">Фурнитура</th>
        <th colspan="<?= $colspan ?>">Глиттер</th>
        <th colspan="<?= $colspan ?>">Кружево</th>
        <th>Д</th>
        <th>Размер</th>
        <th>Кол-во</th>
    </tr>
    <tr>
        <td rowspan="<?= $rowspan ?>">
            <?= $model->mainImageHtml ?>
        </td>

        <?php foreach ($categories as $category) : ?>
            <?php
                if(
                    $relations = $category->getProductRelations($model) and
                    isset($relations[0]) and
                    ($relation = $relations[0]) and
                    ($attribute = $relation->productAttribute)
                )
                :
                    if(isset($totalCost[$category->id])) {
                        $totalCost[$category->id] += $relation->qty * $relation->productAttribute->price;
                    }
                    else {
                        $totalCost[$category->id] = $relation->qty * $relation->productAttribute->price;
                    }

            ?>
                <td><?= $relation->productAttribute->name ?></td>
                <td><?= $relation->qty ?></td>
                <td><?= $relation->productAttribute->price ?></td>
        <?php else : ?>
                <td></td><td></td><td></td>
        <?php endif; ?>
        <?php endforeach; ?>

        <td><?= $model->getActionButtons('view') ?></td>
        <td class="product-size product-size-o"><?= $sizes[0] ?? null ?></td>
        <td class="product-count-o">
            <?= Html::dropDownList('product-count', null, $productCount ,['prompt' => '', 'class' => 'form-control form-control-sm']) ?>
        </td>
    </tr>

    <?php for($i = 1; $i <= $rowspan - 1; $i++) : ?>
        <tr>
            <?php foreach ($categories as $category) : ?>
            <?php
                if(
                    $relations = $category->getProductRelations($model) and
                    isset($relations[0]) and
                    ($relation = $relations[$i]) and
                    ($attribute = $relation->productAttribute)
                )
                :
                    if(isset($totalCost[$category->id])) {
                        $totalCost[$category->id] += $relation->qty * $relation->productAttribute->price;
                    }
                    else {
                        $totalCost[$category->id] = $relation->qty * $relation->productAttribute->price;
                    }
            ?>
                <td><?= $relation->productAttribute->name ?></td>
                <td><?= $relation->qty ?></td>
                <td><?= $relation->productAttribute->price ?></td>
                <?php else : ?>
                    <td></td><td></td><td></td>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php
                if($i == 1) $action = 'update';
                elseif($i == 2) $action = 'delete';
                else $action = null;
            ?>
            <td><?= $model->getActionButtons($action) ?></td>
            <td class="product-size product-size-o"><?= $sizes[$i] ?? null ?></td>
            <td class="product-count-o">
                <?= Html::dropDownList('product-count', null, $productCount ,['prompt' => '', 'class' => 'form-control form-control-sm']) ?>
            </td>
        </tr>
    <?php endfor; ?>
    <tr>
        <th>ИТОГО</th>
        <?php foreach ($categories as $category) : ?>
            <th colspan="3"><p class="total-th"><?= round($totalCost[$category->id])  ?></p></th>
        <?php endforeach; ?>
        <th></th>
        <th></th>
        <th></th>
    </tr>
</table>
