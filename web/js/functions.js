
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
        toastr.success(message)
    }
    function displayErrorMessage(message) {
        toastr.error(message)
    }



    function initPlugins() {
        $('.chosen').chosen()
        $(".select-time").inputmask({"mask": "99:99"});
        $(".phone-mask").inputmask({"mask": "+9 (999) 999-99-99"});
        $('.date-picker').datepicker({
            todayHighlight: true,
            clearBtn: true,
            format: 'dd.mm.yyyy',
            language: 'ru',
        })
    }










