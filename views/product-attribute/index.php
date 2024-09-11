<?php

use app\models\ProductAttribute;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use himiklab\sortablegrid\SortableGridView;
use app\models\ProductAttributeCategory;
use app\models\Supplier;

/** @var yii\web\View $this */
/** @var app\models\ProductAttributeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'image_fields',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->mainImageHtml;
                }
            ],
            'name',
            'alias',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->category) {
                        return Html::a($data->category->name, ['product-attribute-category/update', 'id' => $data->category->id]);
                    }
                },
                'filter' => ProductAttributeCategory::getList(),
            ],
            [
                'attribute' => 'supplier_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->category) {
                        return Html::a($data->supplier->name, ['supplier/view', 'id' => $data->supplier->id]);
                    }
                },
                'filter' => Supplier::getList(),
            ],
            'begin_qty',
            'price',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ProductAttribute $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
