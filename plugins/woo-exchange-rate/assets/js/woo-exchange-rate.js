jQuery(function($) {
    var wooer_widget_id = '#wooer_currency_code';
    var currency_code = $(wooer_widget_id).val();
    var data = {
        'action': 'change_currency_action',
        'currency_code': currency_code
    };
    
    //global wc_cart_fragments_params
    var storage_key = (typeof wc_cart_fragments_params != 'undefined') ? wc_cart_fragments_params.fragment_name : null;

    $(wooer_widget_id).change(function (e) {
        currencyRedirectCallback(e.target.value);
    });
    
    currencyRedirectCallback = function (currency_code) {
        if (!currency_code) {
            return false;
        }
        data.currency_code = currency_code;
        // woo_exchange_rate - global plugin object, defined in frontend header 
        jQuery.post(woo_exchange_rate.ajax_url, data, function (response) {
//            console.log('WOOER plugin log: ' + response);
            //refresh WC mini cart
            if (storage_key) {
                window.sessionStorage.removeItem(storage_key);
            }
            //page reload, not good...
            location.reload(true);
        });
        return true;
    };
});
