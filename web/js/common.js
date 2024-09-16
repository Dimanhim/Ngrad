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


    initPlugins()
})
