
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
        console.log('data', data)
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










