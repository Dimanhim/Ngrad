$(document).ready(function() {
    /**
     * Добавляет строчку в таблицу
     * */
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

    /**
     * Удаляет строчку
     * */
    $(document).on('click', '.btn-remove-product-category-o', function(e) {
        e.preventDefault();
        let parent = $(this).closest('.product-attribute-container');
        parent.remove();
    });

    /**
     Добавляет коллекцию к созданию
     * */
    $(document).on('change', '.select-collection-o', function(e) {
        e.preventDefault();
        let btn = $('.btn-collection-o');
        let collection_id = $(this).val();
        btn.attr('href', '/product/create?collection_id=' + collection_id);
    });

    /**
     Считает стоимость заказа
     * */
    $(document).on('click', '.btn-total-cost-o', function(e) {
        e.preventDefault();
        calculateAttributes();
    });

    /**
     * Выбор размера
     * */
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
     * Выбор количества
     * */
    $(document).on('change', '.product-count-o select', function(e) {
        e.preventDefault();
        setOrderData()
    });

    /**
     *  расчитать заказ
     * */
    $(document).on('click', '.calculate-order-btn-o', function(e) {
        e.preventDefault();
        calculateOrderPrice();
    });

    /**
     * Изменение количества атрибута на складе
     * */
    $(document).on('change', '.change-stock-input-o', function(e) {
        e.preventDefault();

        let attributeId = $(this).attr('data-attribute');
        let value = $(this).val();

        if(!confirm('Вы действительно хотите изменить количество на складе?')) {
            setAttributeFromStock($(this), attributeId);
            return false;
        }

        changeAttributeInStock(attributeId, value)
    });









    /**
     * устанавливает количество со склада
     * */
    function setAttributeFromStock(inputObj, attribute_id) {
        $.ajax({
            url: '/ajax/set-attributes-stock',
            type: 'POST',
            data: {attribute_id: attribute_id},
            success: function (res) {
                if(res.error == 0 && res.data) {
                    inputObj.val(res.data);
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    }

    /**
     * устанавливает пользовательское количество на склад
     * */
    function changeAttributeInStock(attribute_id, value) {
        $.ajax({
            url: '/ajax/change-attributes-stock',
            type: 'POST',
            data: {attribute_id: attribute_id, value: value},
            success: function (res) {
                if(res.error == 0) {
                    displaySuccessMessage('Количество сохранено успешно')
                }
            },
            error: function () {
                alert('Error!');
            }
        });
    }

    /**
     * собирает массив данных из таблицы при расчете заказа
     * */
    function getAttributesQtyData() {
        let data = [];
        $('.product-attribute-container-o').each(function(index, element) {
            let el = $(element);
            let attribute_id = el.find('.product-attribute-id-o').val();
            let qty = el.find('.product-attribute-qty-o').val();

            if(attribute_id.length && qty.length) {
                data.push({
                    attribute_id: attribute_id,
                    qty: qty
                })
            }
        })
        return data;
    }

    /**
     * считает стоимость заказа
     * */
    function calculateAttributes() {
        let input = $('.total-cost-o');
        let data = getAttributesQtyData();

        if(!data.length) return false;

        $.ajax({
            url: '/ajax/get-attributes-cost',
            type: 'POST',
            data: {data: JSON.stringify(data)},
            success: function (res) {
                if(res.error == 0 && res.data) {
                    input.val(res.data);
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
    function setOrderData() {
        // надо сюда поставщика приплести как то
        let data = [];
        let product_row = $('.product-id-o');
        product_row.each(function(index_p, element_p) {
            let el_p = $(element_p);
            let product_id = el_p.attr('data-id')
            let selected_sizes = el_p.find('.product-size-o.active');
            selected_sizes.each(function(index_a, element_a) {
                let el_a = $(element_a);
                let size = el_a.text();
                let count = el_a.closest('tr').find('.product-count-o select').val();

                data.push({
                    product_id: product_id,
                    size: size,
                    count: count,
                })
            });
        });
        $('.total-data-o').val(JSON.stringify(data))
        return data;
    }

    /**
     * AJAX
     * считает стоимость заказа
     * */
    function calculateOrderPrice() {
        $.ajax({
            url: '/ajax/calculate-order',
            type: 'POST',
            data: {data: setOrderData()},
            success: function (res) {
                if(res.data) {
                    $('.calculate-order-o').val(res.data)
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    }

})
