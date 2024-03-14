jQuery(function ($) {
    // Append the heading to payment option
    if (typeof wsqb_translation != 'undefined') {
        $.each(wsqb_translation.settings_heading, function (index, value) {
            let heading = '<tr><th scope="row"><h3 style="margin:0; ">' + value + '</h3></th></tr>'
            $('#' + index).parents('tr').before(heading);
        })
    }

    // handle the image uploader
    let settingName = $('.swiss_qr_bill_shop_logo');
    let customMediaButton = '<span class="set-shop-logo custom-media-button button">' + wsqb_translation.set_image_text + '</span>';
    let removeMediaButton = '<span class="button wpo_remove_image_button" data-input_id="header_logo">' + wsqb_translation.remove_image_text + '</span>'

    // insert custom media upload button
    settingName.after(customMediaButton);

    if (settingName.val()) {
        let image_url = '', imageLogo = '';
        wp.media.attachment(settingName.val()).fetch().then(function (data) {
            // preloading finished
            image_url = wp.media.attachment(settingName.val()).get('url');

            imageLogo = '<img src=" ' + image_url + ' " id="img-header_logo"  alt =""/>';
            settingName.after(removeMediaButton);
            settingName.after(imageLogo);

        });
    }

    // Run the script only if there is custom added button to upload media
    let customLogoUpload = $('.custom-media-button.set-shop-logo');
    if (customLogoUpload.length > 0) {
        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            customLogoUpload.on('click', function (e) {
                e.preventDefault();

                // Save the value to the settingName
                wp.media.editor.send.attachment = function (props, attachment) {

                    let image_url = wp.media.attachment(attachment.id).get("url");

                    settingName.val(attachment.id);

                    // display image
                    if (settingName.next().is('img')) {
                        settingName.next().attr('src', image_url);
                    } else {
                        let imageLogo = '<img src=" ' + image_url + ' " id="img-header_logo" />';
                        settingName.after(removeMediaButton);
                        settingName.after(imageLogo);
                    }
                };

                // Open the media editor
                wp.media.editor.open($(this));
                return false;
            });
        }
    }

    /**
     * Handle the remove image
     */
    $('body').on('click', '.button.wpo_remove_image_button', function (e) {
        e.preventDefault();

        $(this).prev().detach();
        settingName.val('');
        $(this).detach();
    })

    // mutual exclusive handling of two payment gateways
    // in the payment list views
    var paymentStatusSwitchLink = $('table.wc_gateways td.status a.wc-payment-gateway-method-toggle-enabled, table.wc_gateways td.action a');
    paymentStatusSwitchLink.on('click', function (e) {
        var trParent = $(this).closest('tr');
        var trRelatedSibling = null;
        var gatewayId = trParent.data('gateway_id');

        if (gatewayId === 'wc_swiss_qr_bill' || gatewayId === 'wc_swiss_qr_bill_classic') {
            var isAlreadyDisabled = trParent.find('span.woocommerce-input-toggle').hasClass('woocommerce-input-toggle--disabled');
            if (isAlreadyDisabled) {
                if (gatewayId === 'wc_swiss_qr_bill') {
                    trRelatedSibling = trParent.siblings('[data-gateway_id="wc_swiss_qr_bill_classic"]');
                } else {
                    trRelatedSibling = trParent.siblings('[data-gateway_id="wc_swiss_qr_bill"]');
                }

                if (!(trRelatedSibling !== null && trRelatedSibling.find('a.wc-payment-gateway-method-toggle-enabled span').hasClass('woocommerce-input-toggle--disabled'))) {
                    e.preventDefault();

                    return false;
                }
            }
        }
    })

    // in the payment setting page
    // making sure of mutual exclusiveness
    var woocommerce_wc_swiss_qr_bill_enabled = $('#woocommerce_wc_swiss_qr_bill_enabled');
    var woocommerce_wc_swiss_qr_bill_classic_enabled = $('#woocommerce_wc_swiss_qr_bill_classic_enabled');

    if(!woocommerce_wc_swiss_qr_bill_enabled.is(':checked')){
        if(wsqb_data.enabled_gateways.indexOf('wc_swiss_qr_bill_classic') > -1){
            woocommerce_wc_swiss_qr_bill_enabled.attr('disabled', 'disabled')
        }
    }

    if(!woocommerce_wc_swiss_qr_bill_classic_enabled.is(':checked')){
        if(wsqb_data.enabled_gateways.indexOf('wc_swiss_qr_bill') > -1){
            woocommerce_wc_swiss_qr_bill_classic_enabled.prop('disabled', 'disabled')
        }
    }
});