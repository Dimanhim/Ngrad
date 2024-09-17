<?php

use app\models\Order;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use himiklab\sortablegrid\SortableGridView;

/** @var yii\web\View $this */
/** @var app\models\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать заказ', ['product/index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'date_order',
                'format' => 'raw',
                'value' => function($data) {
                    return date('d.m.Y', $data->created_at);
                },
                'headerOptions' => [
                    'class' => 'date-filter-range'
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => '_created_from',
                    'attribute2' => '_created_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd'],
                    'options' => ['autocomplete' => 'off'],
                    'options2' => ['autocomplete' => 'off'],
                ]),
            ],
            'date_shipping',
            [
                'attribute' => 'client_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->client) {
                        return $data->client->name;
                    }
                }
            ],
            [
                'attribute' => 'Всего товаров',
                'value' => function($data) {
                    return $data->getCountProducts();
                }
            ],
            [
                'attribute' => 'price',
                'value' => function($data) {
                    return $data->getFormatPrice();
                }
            ],
            'phone',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, Order $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
