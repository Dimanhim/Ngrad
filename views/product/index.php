<?php

use app\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\ProductCollection;

/** @var yii\web\View $this */
/** @var app\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1>Платья</h1>

    <div class="row">
        <div class="col-md-3">
            <div>
                <?= Html::dropDownList('collections', null, ProductCollection::getList(), ['prompt' => '[Выбрать коллекцию]', 'class' => 'form-control select-collection-o']) ?>
            </div>
        </div>
        <div class="col-md-2">
            <p>
                <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success btn-collection-o']) ?>
            </p>
        </div>
    </div>

    <?= GridView::widget([
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
                'attribute' => 'collection_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->collection) {
                        return $data->collection->name;
                    }
                }
            ],
            'is_active:boolean',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
