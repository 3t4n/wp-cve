//GA Enhanced Ecommerce
jQuery(document).ready(function ($) {
    jQuery(document.body).on('updated_cart_totals wc_cart_emptied removed_from_cart added_to_cart', function () {
        $.ajax({
            method: 'GET',
            url: siteseoAjaxAnalytics.siteseo_analytics,
            data: {
                action: 'siteseo_after_update_cart',
                _ajax_nonce: siteseoAjaxAnalytics.siteseo_nonce,
            },
            success: function (data) {
                jQuery('body').append(data.data);
            },
        });
    });
});
