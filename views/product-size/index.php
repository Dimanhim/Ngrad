<?php

use app\models\ProductSize;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use himiklab\sortablegrid\SortableGridView;

/** @var yii\web\View $this */
/** @var app\models\ProductCollectionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = 'Справочник';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-collection-index">



    <div class="row">
        <div class="col-md-6 offset-md-3">
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

                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, ProductSize $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
