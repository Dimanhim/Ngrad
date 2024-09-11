<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProductCollection $model */

$this->title = 'Редактирование коллекции: ' . $model->name;
$this->params['breadcrumbs'][] = 'Справочник';
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="product-collection-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
