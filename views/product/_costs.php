<?php

use yii\helpers\Html;
use app\models\Product;
use app\models\ProductAttributeCategory;

//$productAttributeCategories = $model->getProductAttributeCategories();

?>

<?php
if($model->_categories) :
    foreach($model->_categories as $productAttributeCategoryId => $productAttributeCategoryValues) : ?>
        <?php if($productAttributeCategoryValues['product_attributes']) : ?>
            <?php $i = 0; foreach($productAttributeCategoryValues['product_attributes'] as $productAttributeId => $productAttributeValues) : ?>

                <?= $this->render('_costs_items', [
                    'model' => $model,
                    'category' => $productAttributeCategoryValues['product_attribute_category'],
                    'attribute' => $productAttributeValues['product_attribute'],
                    'relation' => $productAttributeValues['product_relation'],
                    'fixed' => $i == 0,
                ]) ?>

            <?php $i++; endforeach; ?>
        <?php else : ?>

            <?= $this->render('_costs_items', [
                'model' => $model,
                'category' => $productAttributeCategoryValues['product_attribute_category'],
                'attribute' => null,
                'relation' => null,
                'fixed' => true,
            ]) ?>

        <?php endif; ?>
<?php endforeach;?>

    <div class="product-attribute-total-cost">
        <div class="row">
            <div class="col-md-9">
                <label for="total-cost">Итоговая стоимость расходников</label>
                <?= Html::textInput('total-cost', $model->fullCost, ['class' => 'form-control total-cost-o', 'placeholder' => 'Формируется автоматически после расчета']) ?>
            </div>
            <div class="col-md-3">
                <?= Html::a('Рассчитать', ['#'], ['class' => 'btn btn-xs btn-success mgt31 btn-total-cost-o', 'data-product' => $model->id]) ?>
            </div>
        </div>
    </div>

<?php endif; ?>
