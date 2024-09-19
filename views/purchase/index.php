<?php

use app\models\Purchase;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use himiklab\sortablegrid\SortableGridView;
use app\models\Supplier;

/** @var yii\web\View $this */
/** @var app\models\PurchaseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'date_purchase',
            'date_delivery',
            'supplier_id',
            [
                'attribute' => 'supplier_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->supplier) {
                        return Html::a($data->supplier->name, ['supplier/update', 'id' => $data->supplier->id]);
                    }
                },
                'filter' => Supplier::getList(),
            ],
            'phone',
            [
                'attribute' => 'status_id',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->statusName;
                },
            ],
            'is_active:boolean',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Purchase $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
