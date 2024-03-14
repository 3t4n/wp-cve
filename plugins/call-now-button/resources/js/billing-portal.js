function cnb_goto_billing_portal() {
    const data = {
        'action': 'cnb_get_billing_portal'
    };

    jQuery.get(ajaxurl, data, function (response) {
        window.open(response.url, '_blank');
    });
    return false
}
