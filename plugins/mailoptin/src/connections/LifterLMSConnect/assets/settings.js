(function ($) {

    var llms = {};

    llms.save_settings = function (event) {
        event.preventDefault();
        $('.llms-save input[type="submit"]').click();
    }

    llms.init = function () {
        $("#llms_integration_mailoptin_mo_llms_integration, #llms_integration_mailoptin_mo_llms_list").on('change', llms.save_settings);
    }

    $(window).on('load', llms.init);

})(jQuery);