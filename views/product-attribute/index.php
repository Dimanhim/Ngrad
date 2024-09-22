<?php

use app\models\ProductAttribute;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use himiklab\sortablegrid\SortableGridView;
use app\models\ProductAttributeCategory;
use app\models\Supplier;
use yii\widgets\ListView;
use app\models\PurchaseForm;

/** @var yii\web\View $this */
/** @var app\models\ProductAttributeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_purchase_form', [
        'attribute' => new ProductAttribute(),
        'model' => new PurchaseForm(),
    ]) ?>

    <?= ListView::widget([
        'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        'emptyText' => false,
        'layout' => "{items}\n{pager}",
        'options' => ['class' => 'product-list'],
        'itemOptions' => ['tag' => false],
        'itemView' => '//product-attribute/_item_attribute',
    ]); ?>

</div>
