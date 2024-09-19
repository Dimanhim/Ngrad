$(document).ready(function() {

    /**
     * сортирует изображения
     * */
    $('.image-preview-container-o').sortable({
        stop(ev, ui) {
            let sort = [];
            $('.image-preview-container-o .image-preview-o').each(function(index, element){
                sort.push($(element).attr('data-id'));
            });
            $.ajax({
                url: '/images/save-sort',
                method: 'post',
                data: {ids: JSON.stringify(sort)},
                success(response) {
                    if(response.result) {
                        displaySuccessMessage(response.message)
                    }
                },
                error(e) {
                    console.log('error', e)
                }
            });
        }
    });



    /**
     * сворачивает/разворачивает карточку
     * */
    $('body').on('click', '.card-header-o', function(e) {
        //e.preventDefault();
        let target = e.target;
        if($(target).is('.card-header-o')) {
            let parent = $(this).closest('.card-img-o');
            let body = parent.find('.card-body-o');
            let icon = $(this).find('.bi')
            if(body.is(':visible')) {
                body.slideUp();
                icon.removeClass('bi-chevron-up').addClass('bi-chevron-down')
            }
            else {
                body.slideDown();
                icon.removeClass('bi-chevron-down').addClass('bi-chevron-up')
            }
        }

    });

    /**
     * в форме товара показывает форму атрибутов по их типу
     * */
    $('body').on('click', '.show-attributes-for-type-o', function(e) {
        e.preventDefault();
        let self = $(this);
    });

    /**
     * Открывает нужный таб по ссылке
     * */
    if($('#myTab').length) {
        let location = window.location;
        let hash = location.hash;
        if(hash.length) {
            let tab = $('.nav-item a[href="' + hash + '"]');
            if(tab.length) {
                tab.trigger('click');
            }
        }
    }


    // CUSTOM
    $(document).on('click', '.btn-add-product-category-o', function(e) {
        e.preventDefault();
        let self = $(this);
        let parent = self.closest('.product-attribute-container');
        let product_category_id = self.attr('data-product-category-id');
        $.ajax({
            url: '/ajax/get-product-category-field',
            type: 'POST',
            data: {product_category_id: product_category_id},
            success: function (res) {
                if(res.error == 0 && res.data.length) {
                    parent.after(res.data);
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    });

    $(document).on('click', '.btn-remove-product-category-o', function(e) {
        e.preventDefault();
        let parent = $(this).closest('.product-attribute-container');
        parent.remove();
    });

    $(document).on('change', '.select-collection-o', function(e) {
        e.preventDefault();
        let btn = $('.btn-collection-o');
        let collection_id = $(this).val();
        btn.attr('href', '/product/create?collection_id=' + collection_id);
    });

    $(document).on('click', '.btn-total-cost-o', function(e) {
        e.preventDefault();
        calculateAttributes();
    });

    $(document).on('click', '.product-size-o', function(e) {
        e.preventDefault();
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
        else {
            $(this).addClass('active');
        }
        setOrderData()
    });

    /**
     *  расчитать заказ
     * */
    $(document).on('click', '.calculate-order-btn-o', function(e) {
        e.preventDefault();
        calculateOrderPrice();
    });

    $(document).on('change', '.product-count-o select', function(e) {
        e.preventDefault();
        setOrderData()
    });

    $(document).on('change', '.card-view-date-o', function(e) {
        e.preventDefault();
        console.log('date change');
        let order_id = $(this).attr('data-order');
        let value = $(this).val();

        $.ajax({
            url: '/ajax/change-order-date-shipping',
            type: 'POST',
            data: {order_id: order_id, date: value},
            success: function (res) {
                console.log('res', res);
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
     * Добавляет поле в заказ
     * */
    $(document).on('click', '.btn-order-purchase-add-o', function(e) {
        e.preventDefault();

        let order_id = $('.order-view').attr('data-order');

        $.ajax({
            url: '/ajax/add-order-purchase-field',
            type: 'POST',
            data: {order_id: order_id},
            success: function (res) {
                console.log('res add', res);
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

    $(document).on('click', '.btn-order-purchase-delete-o', function(e) {
        e.preventDefault();
        if(!confirm('Вы действительно хотите удалить товар в заказе?')) return false;

        let self = $(this);
        let row = self.closest('.order-edit-o');
        let purchase_id = self.attr('data-id');
        let order_id = $('.order-view').attr('data-order');

        console.log('purchase_id', purchase_id);

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

    $(document).on('change', '.order-edit-o select', function(e) {
        e.preventDefault();

        let self = $(this);
        let order_id = $('.order-view').attr('data-order');
        let val = self.val();
        let field = self.attr('data-field');
        let purchase_id = self.attr('data-purchase-id');
        let row = self.closest('.order-edit-o');

        let product_id = row.find('select[data-field="product_id"]').val();
        let qty = row.find('select[data-field="qty"]').val();


        let sizeIndex = row.find('select[data-field="size_id"]').val();
        let size = row.find('select[data-field="size_id"] option[value="' + sizeIndex + '"]').html()

        if(!product_id || !order_id) return false;

        console.log('size', size)

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


    initPlugins()
})
