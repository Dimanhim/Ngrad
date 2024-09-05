<?php

use app\models\Supplier;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use himiklab\sortablegrid\SortableGridView;

/** @var yii\web\View $this */
/** @var backend\models\ClientSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

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
            'phone',
            'email:email',
            //'type',
            //'status_id',
            [
                'attribute' => 'is_active',
                'value' => function($data) {
                    return $data->active;
                },
                'filter' => [0 => 'Нет', 1 => 'Да'],
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Supplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
