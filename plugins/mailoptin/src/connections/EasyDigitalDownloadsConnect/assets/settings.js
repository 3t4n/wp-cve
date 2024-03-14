(function ($) {
    "use strict";

    var easydigitaldownloads = {};

    easydigitaldownloads.save_settings = function (event) {
        event.preventDefault();
        $(this).parents("form").find('input[type="submit"]').click();
    }

    easydigitaldownloads.activate_select2 = function () {
        var cache = jQuery('.mo_edd_select2 select');
        if (typeof cache.select2 !== 'undefined') {
            cache.select2()
        }
    }


    easydigitaldownloads.init = function () {
        $("select[name*='mailoptin_edd_integration_connections'], select[name*='mailoptin_edd_integration_lists']").change(easydigitaldownloads.save_settings);
        easydigitaldownloads.activate_select2();
    }

    $(window).on('load', easydigitaldownloads.init);

})(jQuery);