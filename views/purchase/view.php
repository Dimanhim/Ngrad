<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Purchase $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить закупку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',

            'date_purchase',
            'date_delivery',
            [
                'attribute' => 'supplier_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->supplier) {
                        return Html::a($data->supplier->name, ['supplier/update', 'id' => $data->supplier->id]);
                    }
                },
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
                'attribute' => 'image_fields',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->imagesHtml;
                }
            ],
            'created_at:datetime',
        ],
    ]) ?>

</div>
