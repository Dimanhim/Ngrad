<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProductCollection $model */

$this->title = 'Добавление размера';
$this->params['breadcrumbs'][] = 'Справочник';
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-collection-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
