<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\widgets\Select2;
use app\models\Product;
use app\models\ProductSize;

/** @var yii\web\View $this */
/** @var app\models\Order $model */

$this->title = 'Заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view order-view-o" data-order="<?= $model->id ?>">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container-view">
        <div class="order-header-o">
            <?= $this->render('_order_header', [
                'model' => $model,
            ]) ?>
        </div>


        <div class="card">
            <div class="card-header">
                Состав заказа
            </div>
            <div class="card-body">
                <div class="table-order-purchases-o">
                    <?= $this->render('_table_purchases', [
                        'model' => $model,
                    ]) ?>
                </div>

                <div>
                    <?= Html::a('Добавить', ['#'], ['class' => 'btn btn-sm btn-success btn-order-purchase-add-o']) ?>
                </div>
            </div>
        </div>

    </div>


</div>
