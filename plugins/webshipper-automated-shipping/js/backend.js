function webshipper_change_shipping_method(){
    var e = document.getElementById("ws_rate");
    var adrInfo = e.options[e.selectedIndex].value.split(/::/);

    var cur_url = document.URL.split("&webshipper_change_shipping_method=true")[0].split("&webshipper_change_droppoint=true")[0];

    window.location = cur_url + "&webshipper_change_shipping_method=true&ws_rate="+adrInfo[0]+"&name="+adrInfo[1];
}

function webshipper_change_droppoint(){
    var e = document.getElementById("ws_droppoint");
    var adrInfo = e.options[e.selectedIndex].value.split(/::/);

    var cur_url = document.URL.split("&webshipper_change_shipping_method=true")[0].split("&webshipper_change_droppoint=true")[0];

    window.location = cur_url + "&webshipper_change_droppoint=true&dp_id="+adrInfo[0]+
        "&dp_street="+adrInfo[1]+
        "&dp_zip="+adrInfo[2]+
        "&dp_city="+adrInfo[3]+
        "&dp_name="+adrInfo[4]+
        "&dp_country="+adrInfo[5];
}


jQuery(function() {
    if (jQuery("#woocommerce-order-data").length > 0 ) {
        jQuery("#webshipper_backend").insertAfter("#woocommerce-order-data");
    } else {
        jQuery("#webshipper_backend").insertAfter("#woocommerce-subscription-data");
    }
    jQuery("#webshipper_backend").show();
});
