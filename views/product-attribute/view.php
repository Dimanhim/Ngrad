<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ProductAttribute $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-attribute-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить расходник?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'alias',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->category) {
                        return Html::a($data->category->name, ['product-attribute-category/update', 'id' => $data->category->id]);
                    }
                }
            ],
            [
                'attribute' => 'supplier_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->category) {
                        return Html::a($data->supplier->name, ['supplier/view', 'id' => $data->supplier->id]);
                    }
                }
            ],
            'begin_qty',
            'price',
            'is_active:boolean',
            'created_at:datetime',
        ],
    ]) ?>

</div>
