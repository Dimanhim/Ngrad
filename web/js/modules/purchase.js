$(document).ready(function() {
    /**
     Добавляет категорию к созданию
     * */
    $(document).on('change', '.select-category-o', function(e) {
        e.preventDefault();
        let btn = $('.btn-category-o');
        let category_id = $(this).val();
        btn.attr('href', '/product-attribute/create?category_id=' + category_id);
    });

    /**
     * Расчитывает стоимость закупки
     * */
    $(document).on('click', '.calculate-purchase-btn-o', function(e) {
        e.preventDefault();
        calculatePurchasePrice();
    });

    /**
     * Устанавливает hiddenInput со параметрами закупки в JSON
     * */
    $(document).on('change', '.add-purchase-input-o', function(e) {
        e.preventDefault();
        setPurchaseData();
    });



    /**
     * РЕДАКТИРОВАНИЕ - ТАБЛИЦА
     * Добавляет поле в закупку
     * */
    $(document).on('click', '.btn-pa-purchase-add-o', function(e) {
        e.preventDefault();

        let purchase_id = $('.order-purchase-o').attr('data-purchase');

        $.ajax({
            url: '/ajax/add-pa-purchase-field',
            type: 'POST',
            data: {purchase_id: purchase_id},
            success: function (res) {
                if(res.error == 0 && res.data) {
                    $('.table-pa-purchases-o table tbody').append(res.data);
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
    $(document).on('click', '.btn-pa-purchase-delete-o', function(e) {
        e.preventDefault();
        if(!confirm('Вы действительно хотите удалить материал в закупке?')) return false;

        let self = $(this);
        let row = self.closest('.purchase-edit-o');
        let pa_id = self.attr('data-id');
        let purchase_id = $('.order-purchase-o').attr('data-purchase');

        if(typeof pa_id === 'undefined') {
            row.remove();
            return false;
        }

        $.ajax({
            url: '/ajax/delete-pa-purchase',
            type: 'POST',
            data: {pa_id: pa_id},
            success: function (res) {
                if(res.error == 0) {
                    displaySuccessMessage('Сохранено успешно');
                    updatePurchaseHeader(purchase_id);
                    updatePaList(purchase_id);
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
    $(document).on('change', '.pa-edit-o select, .pa-edit-o input', function(e) {
        e.preventDefault();

        let self = $(this);
        let purchase_id = $('.order-purchase-o').attr('data-purchase');
        let val = self.val();
        let field = self.attr('data-field');
        let pa_id = self.attr('data-pa-id');
        let row = self.closest('.pa-edit-o');

        let product_attribute_id = row.find('select[data-field="product_attribute_id"]').val();
        let qty = row.find('input[data-field="qty"]').val();

        console.log('pa_id', pa_id)
        console.log('purchase_id', purchase_id)
        if(!purchase_id) return false;

        $.ajax({
            url: '/ajax/change-pa-field',
            type: 'POST',
            data: {pa_id: pa_id, purchase_id: purchase_id, product_attribute_id: product_attribute_id, qty: qty},
            success: function (res) {
                console.log('res', res)
                if(res.error == 0) {
                    displaySuccessMessage(res.data);
                    updatePurchaseHeader(purchase_id);
                    updatePaList(purchase_id);
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
    function updatePurchaseHeader(purchase_id) {
        $.ajax({
            url: '/ajax/update-pa-header',
            type: 'POST',
            data: {purchase_id: purchase_id},
            success: function (res) {
                console.log('update pa header', res)
                if(res.error == 0) {
                    $('.purchase-header-o').html(res.data);
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
    function updatePaList(purchase_id) {
        $.ajax({
            url: '/ajax/pa-purchases-list',
            type: 'POST',
            data: {purchase_id: purchase_id},
            success: function (res) {
                console.log('update pa list', res)
                if(res.error == 0) {
                    $('.table-pa-purchases-o').html(res.data);
                    initPlugins();
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    }














    /**
    * AJAX
    * считает стоимость закупки
    * */
    function calculatePurchasePrice() {
        $.ajax({
            url: '/ajax/calculate-purchase',
            type: 'POST',
            data: {data: setPurchaseData()},
            success: function (res) {
                console.log('res', res)
                if(res.data) {
                    $('.calculate-purchase-o').val(res.data)
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    }

    /**
     * Устанавливает hiddenInput с JSON данных таблицы
     * */
    function setPurchaseData() {

        let data = [];
        let purchase_row = $('.purchase-id-o');
        purchase_row.each(function(index_p, element_p) {
            let el_p = $(element_p);
            let category_id = el_p.attr('data-id')
            el_p.find('.attributes-list-o').each(function(index_a, element_a) {
                let el_a = $(element_a);
                let attribute_id = el_a.find('.add-purchase-input-o').attr('data-attribute');
                let val = el_a.find('.add-purchase-input-o').val();
                if(val) {
                    data.push({
                        category_id: category_id,
                        attribute_id: attribute_id,
                        val: val,
                    });
                }

            });
        });
        $('.total-purchase-data-o').val(JSON.stringify(data))
        return data;
    }



})
