<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProductAttribute $model */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>