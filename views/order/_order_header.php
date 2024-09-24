<div class="card">
    <div class="card-header">
        Информация по заказу
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Дата заказа
                    </div>
                    <div class="card-view-body">
                        <?= $model->date_order ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Дата сдачи
                    </div>
                    <div class="card-view-body">
                        <input type="text" class="card-view-date card-view-date-o date-picker" data-order="<?= $model->id ?>" value="<?= $model->date_shipping ?? '' ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-view">
                    <div class="card-view-header">
                        Заказчик
                    </div>
                    <div class="card-view-body">
                        <?= $model->client->name ?>
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
