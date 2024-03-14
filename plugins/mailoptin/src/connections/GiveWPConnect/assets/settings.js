(function ($) {
    "use strict";

    var givewp = {};

    givewp.save_settings = function (event) {
        event.preventDefault();
        $(this).parents("form").find('input[type="submit"]').click();
    }

    givewp.activate_select2 = function () {
        var cache = jQuery('.mo_gwp_select2 select');
        if (typeof cache.select2 !== 'undefined') {
            cache.select2()
        }
    }

    givewp.init = function () {
        $("select[name*='mailoptin_gwp_integration_connections'], select[name*='mailoptin_gwp_integration_lists']").change(givewp.save_settings);
        givewp.activate_select2();
    }

    $(window).on('load', givewp.init);

})(jQuery);