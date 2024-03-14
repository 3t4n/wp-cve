jQuery(function ($) {
    var currencySelector = $('select.premmerce-multicurrency');
    if (currencySelector.length === 0) {
        console.log('Premmerce multicurrency selector not found on this page');
        return;
    }

    currencySelector.change(function () {
        var currencyID = $(this).val();
        console.log(currencyID);
        var url = new URL(document.location.href);
        url.searchParams.delete('min_price');
        url.searchParams.delete('max_price');
        url.searchParams.set('currency_id', currencyID);
        //Force Woocommerce to refresh fragments and update cart widget
        sessionStorage.setItem(wc_cart_fragments_params.fragment_name, {});
        document.location = url.toString();

    });

    //Empty mini cart wrong currency symbol fix
    $(document.body).on('wc_fragments_loaded', function () {
        $('.widget_shopping_cart_content').empty();
        $(document.body).trigger('wc_fragment_refresh');
    });

});
