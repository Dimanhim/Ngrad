<?php

use app\models\Product;
use app\models\ProductSize;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\models\ProductCollection;
use yii\widgets\ListView;
use app\models\Supplier;
use app\models\OrderForm;

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
                <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success btn-collection-o']) ?>
            </p>
        </div>
    </div>

    <?= ListView::widget([
        'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        'emptyText' => false,
        'layout' => "{items}\n{pager}",
        'options' => ['class' => 'product-list'],
        'itemOptions' => ['tag' => false],
        'itemView' => '//product/_item_product',
    ]); ?>

    <?= $this->render('_order_form', [
        'model' => new OrderForm(),
    ]) ?>


</div>
