
    function calculateAttributes(product_id) {
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

    function calculateOrderPrice() {
        $.ajax({
            url: '/ajax/calculate-order',
            type: 'POST',
            data: {data: setOrderData()},
            success: function (res) {
                console.log('res', res)
                if(res.data) {
                    $('.calculate-order-o').val(res.data)
                }
            },
            error: function (e) {
                console.log('Error!', e);
            }
        });
    }

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




    function addPreloader() {
        setTimeout(function() {
            $('.loader-block').addClass('loader');
        }, 500);
    }
    function removePreloader() {
        setTimeout(function() {
            $('.loader-block').removeClass('loader');
        }, 500)
    }

    function displaySuccessMessage(message) {
        $('.info-message').text(message).fadeIn();
        setTimeout(function() {
            $('.info-message').text('').fadeOut();
        }, 5000)
    }
    function displayErrorMessage(message) {
        $('.info-message').addClass('error').text(message).fadeIn();
        setTimeout(function() {
            $('.info-message').text('').fadeOut();
        }, 5000)
    }



    function initPlugins() {
        $('.chosen').chosen()
        $(".select-time").inputmask({"mask": "99:99"});
        $(".phone-mask").inputmask({"mask": "+7 (999) 999-99-99"});
    }










