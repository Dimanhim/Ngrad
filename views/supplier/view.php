<?php

use common\models\Client;
use common\models\Order;
use frontend\modules\profile\Profile;
use himiklab\sortablegrid\SortableGridView;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Client $model */

$this->title = 'Поставщик: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="client-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Информация по поставщику
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'name',
                            'phone',
                            'email:email',
                            'comment',


                            [
                                'attribute' => 'image_fields',
                                'format' => 'raw',
                                'value' => function($data) {
                                    return $data->imagesHtml;
                                }
                            ],
                            [
                                'attribute' => 'is_active',
                                'value' => function($data) {
                                    return $data->active;
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => function($data) {
                                    return $data->createdAt;
                                }
                            ],
                            [
                                'attribute' => 'updated_at',
                                'value' => function($data) {
                                    return $data->updatedAt;
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
