$(document).ready(function() {


    /**
     * Устанавливает дату сдачи заказа
     * */
    $(document).on('change', '.card-view-date-o', function(e) {
        e.preventDefault();

        if(!confirm('Вы уверены, что хотите установить дату сдачи заказа?')) return false;

        let order_id = $(this).attr('data-order');
        let value = $(this).val();

        $.ajax({
            url: '/ajax/change-order-date-shipping',
            type: 'POST',
            data: {order_id: order_id, date: value},
            success: function (res) {
                if(res.error == 0) {
                    displaySuccessMessage('Дата отгрузки успешно обновлена');
                }
                else {
                    displayErrorMessage('Произошла ошибка обновления даты')
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    });

    /**
     * РЕДАКТИРОВАНИЕ - ТАБЛИЦА
     * Добавляет поле в заказ
     * */
    $(document).on('click', '.btn-order-purchase-add-o', function(e) {
        e.preventDefault();

        let order_id = $('.order-view-o').attr('data-order');

        $.ajax({
            url: '/ajax/add-order-purchase-field',
            type: 'POST',
            data: {order_id: order_id},
            success: function (res) {
                if(res.error == 0 && res.data) {
                    $('.table-order-purchases-o table tbody').append(res.data);
                    initPlugins();
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    });

    /**
     * РЕДАКТИРОВАНИЕ - ТАБЛИЦА
     * Удаляет поле
     * */
    $(document).on('click', '.btn-order-purchase-delete-o', function(e) {
        e.preventDefault();
        if(!confirm('Вы действительно хотите удалить товар в заказе?')) return false;

        let self = $(this);
        let row = self.closest('.order-edit-o');
        let pa_id = self.attr('data-pa-id');
        let purchase_id = self.attr('data-id');
        let order_id = $('.order-view-o').attr('data-order');

        if(typeof purchase_id === 'undefined') {
            row.remove();
            return false;
        }

        $.ajax({
            url: '/ajax/delete-order-purchase',
            type: 'POST',
            data: {purchase_id: purchase_id},
            success: function (res) {
                if(res.error == 0) {
                    displaySuccessMessage('Сохранено успешно');
                    updatePurchasesList(order_id);
                    updateOrderHeader(order_id);
                }
                else if(res.message.length) {
                    displayErrorMessage(res.message)
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    });

    /**
     * РЕДАКТИРОВАНИЕ - ТАБЛИЦА
     * Редактирование состава заказа
     * */
    $(document).on('change', '.order-edit-o select', function(e) {
        e.preventDefault();

        let self = $(this);
        let order_id = $('.order-view-o').attr('data-order');
        let val = self.val();
        let field = self.attr('data-field');
        let purchase_id = self.attr('data-purchase-id');
        let row = self.closest('.order-edit-o');

        let product_id = row.find('select[data-field="product_id"]').val();
        let qty = row.find('select[data-field="qty"]').val();

        let sizeIndex = row.find('select[data-field="size_id"]').val();
        let size = row.find('select[data-field="size_id"] option[value="' + sizeIndex + '"]').html()

        if(!product_id || !order_id) return false;

        $.ajax({
            url: '/ajax/change-order-field',
            type: 'POST',
            data: {order_id: order_id, purchase_id: purchase_id, product_id: product_id, size: size, qty: qty},
            success: function (res) {

                if(res.error == 0) {
                    displaySuccessMessage(res.data);
                    updateOrderHeader(order_id);
                    updatePurchasesList(order_id);
                    initPlugins();
                }
                else {
                    displayErrorMessage(res.message);
                }
            },
            error: function () {
                alert('Error!');
            }
        });

        // продолжаем здесь
    });







    /**
     * Обновляет таблицу информации по заказу
     * */
    function updateOrderHeader(order_id) {
        $.ajax({
            url: '/ajax/update-order-header',
            type: 'POST',
            data: {order_id: order_id},
            success: function (res) {
                if(res.error == 0) {
                    $('.order-header-o').html(res.data);
                    initPlugins();
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    }

    /**
     * Обновляет таблицу состава заказа
     * */
    function updatePurchasesList(order_id) {
        $.ajax({
            url: '/ajax/order-purchases-list',
            type: 'POST',
            data: {order_id: order_id},
            success: function (res) {
                if(res.error == 0) {
                    $('.table-order-purchases-o').html(res.data);
                    initPlugins();
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    }


})
