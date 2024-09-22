<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\widgets\Select2;
use app\models\Product;
use app\models\ProductSize;

/** @var yii\web\View $this */
/** @var app\models\Order $model */

$this->title = 'Закупка №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="order-view order-purchase-o" data-purchase="<?= $model->id ?>">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container-view">
        <div class="purchase-header-o">
            <?= $this->render('_purchase_header', [
                'model' => $model,
            ]) ?>
        </div>


        <div class="card">
            <div class="card-header">
                Состав закупки
            </div>
            <div class="card-body">
                <div class="table-pa-purchases-o">
                    <?= $this->render('_table_purchases', [
                        'model' => $model,
                    ]) ?>
                </div>

                <div>
                    <?= Html::a('Добавить', ['#'], ['class' => 'btn btn-sm btn-success btn-pa-purchase-add-o']) ?>
                </div>
            </div>
        </div>

    </div>


</div>
