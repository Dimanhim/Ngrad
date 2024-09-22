<div class="card">
    <div class="card-header">
        Информация по закупке
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Дата закупки
                    </div>
                    <div class="card-view-body">
                        <?= $model->date_purchase ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Дата поставки
                    </div>
                    <div class="card-view-body">
                        <input type="text" class="card-view-date card-view-date-o date-picker" data-purchase="<?= $model->id ?>" value="<?= $model->date_delilery ?? '01.01.2024' ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Поставщик
                    </div>
                    <div class="card-view-body">
                        <?= $model->supplier->name ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Всего товаров
                    </div>
                    <div class="card-view-body">
                        <?= $model->getCountProducts() ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Сумма оплаты
                    </div>
                    <div class="card-view-body">
                        <?= $model->getFormatPrice() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
