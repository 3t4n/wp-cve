(function ($) {
    "use strict";

    var woo = {};

    woo.add_spinner = function (placement) {
        var spinner_html = $('<img class="mo-spinner fetch-email-list" src="' + mailoptin_globals.admin_url + 'images/spinner.gif">');
        $(placement).after(spinner_html);
    }

    woo.remove_spinner = function (parent) {
        $('.mo-spinner.fetch-email-list', parent).remove();
    }

    woo.connection_service_handler = function (_this, type = 'product') {
        var self = _this, data, result;

        $('.mailoptin_woocommerce_email_list').empty();
        $('.mailoptin_woocommerce_custom_fields_tags').empty();

        var connection = $(self).val();

        woo.add_spinner(self);

        if (connection === '') {
            woo.remove_spinner();
            return;
        }

        data = {
            action: 'mo_woocommerce_fetch_lists',
            nonce: moWooCommerce.nonce,
            connection: connection,
            type: type,
            product_id: mailoptin_globals.woo_product_id,
            product_cat_id: mailoptin_globals.woo_product_cat_id
        }

        $.post(moWooCommerce.ajax_url, data, function (response) {
            woo.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.lists;
                $('.mailoptin_woocommerce_email_list').html(result);
                woo.connection_email_list_handler($("select[name='mailoptinWooCommerceSelectList']"))
            }

        });
    };

    woo.connection_email_list_handler = function (_this) {
        var self = _this, data, result;
        var connection = $("select[name='mailoptinWooCommerceSelectIntegration']").val();
        var type = $("select[name='mailoptinWooCommerceSelectIntegration']").data('type');
        $('.mailoptin_woocommerce_custom_fields_tags').empty();

        var connection_email_list = $(self).val();
        woo.remove_spinner();
        woo.add_spinner(self);

        data = {
            action: 'mo_woocommerce_fetch_custom_fields',
            nonce: moWooCommerce.nonce,
            connection: connection,
            connection_email_list: connection_email_list,
            type: type,
            product_id: mailoptin_globals.woo_product_id,
            product_cat_id: mailoptin_globals.woo_product_cat_id
        }

        $.post(moWooCommerce.ajax_url, data, function (response) {
            woo.remove_spinner();
            if ('success' in response && response.success === true) {
                result = response.data.fields;
                $('.mailoptin_woocommerce_custom_fields_tags').html(result);
            }

        });
    };

    woo.product_connection_service_handler = function () {
        woo.connection_service_handler(this, 'product');
    };

    woo.product_category_connection_service_handler = function () {
        woo.connection_service_handler(this, 'product_category');
    };

    woo.product_tag_connection_service_handler = function () {
        woo.connection_service_handler(this, 'product_tag');
    };

    woo.init = function () {
        $("select[name='mailoptinWooCommerceSelectIntegration']").change(function () {
            woo.connection_service_handler(this, $(this).data('type'));
        }).change();

        $(document).on('change', "select[name='mailoptinWooCommerceSelectList']", function () {
            woo.connection_email_list_handler(this)
        });

        $(document).on('click', "input[name='mailoptinWooCommerceCloseBox']", function () {
            $.fancybox.close();
        });
    }

    $(window).on('load', woo.init);

})(jQuery);