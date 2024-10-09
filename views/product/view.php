<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->modelName, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$totalPrice = 0;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card-view">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <?= $model->getMainImageHtml(300, 300) ?>
                        </div>
                        <!--
                        <div class="form-group">
                            <b>Коллекция</b><br>
                            <p><?//= $model->collection->name ?? '---' ?></p>
                        </div>
                        <div class="form-group">
                            <b>Модель</b><br>
                            <p><?//= $model->model_name ?? '---' ?></p>
                        </div>
                        -->
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        Расходы на пошив
                    </div>
                    <div class="card-body">
                        <?php if($categories = $model->productAttributeCategories) : ?>
                        <?php $i = 1; ?>
                        <table class="table table-bordered">
                            <?php foreach($categories as $category) : ?>
                                <?php if($relations = $category->getProductRelations($model)) : ?>
                                    <?php foreach($relations as $relation) : ?>
                                        <?php
                                            if($i == count($relations)) {
                                                $trClass = 'bottom-border';
                                                $i = 1;
                                            }
                                            else {
                                                $trClass = '';
                                                $i++;
                                            }
                                        ?>
                                        <tr class="<?= $trClass ?>">
                                            <td><?= $relation->productAttribute->name ?></td>
                                            <td><?= $relation->qty . ' ' . $category->getShortTypeText(true) ?></td>
                                            <td>
                                                <?= $relation->productAttribute->price * $relation->qty .' р.' ?>
                                                <?php
                                                    $totalPrice += $relation->productAttribute->price * $relation->qty;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <tr>
                                <th colspan="2">Итоговая стоимость</th>
                                <th><?= $totalPrice .' р.' ?></th>
                            </tr>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <p>
                    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удлить товар?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            </div>
        </div>
    </div>



</div>
