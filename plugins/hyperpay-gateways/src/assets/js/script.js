
/**
 * 
 * to check if current browser support ApplePay 
 * @param {boolean} 
 * if @returns {false} attach error message Your Device Dose Not Support ApplePay
 */
if (!window.ApplePaySession && dataObj.payment_brands.includes('APPLEPAY')) {
    jQuery('.woocommerce-notices-wrapper')
        .append(`<ul class="woocommerce-error" role="alert"><li> ${dataObj.is_arabic ? 'جهازك او متصفحك لا يدعم الدفع عن طريق ابل' : 'Your Device Or Browser Dose Not Support ApplePay'} </li></ul>`)
}


/**
 * initiate wpwlOptions 
 */
var wpwlOptions = {

    onReady: function () {

        jQuery('.wpwl-form-virtualAccount-STC_PAY .wpwl-wrapper-radio-qrcode').hide();
        jQuery('.wpwl-form-virtualAccount-STC_PAY .wpwl-wrapper-radio-mobile').hide();
        jQuery('.wpwl-form-virtualAccount-STC_PAY .wpwl-group-paymentMode').hide();
        jQuery('.wpwl-form-virtualAccount-STC_PAY .wpwl-group-mobilePhone').show();
        jQuery('.wpwl-form-virtualAccount-STC_PAY .wpwl-wrapper-radio-mobile .wpwl-control-radio-mobile').attr('checked', true);
        jQuery('.wpwl-form-virtualAccount-STC_PAY .wpwl-wrapper-radio-mobile .wpwl-control-radio-mobile').trigger('click');

    },
    style: dataObj.style, // <== this style comes from settings page of gateways
    paymentTarget: "_top",
    locale: dataObj.is_arabic ? 'ar' : 'en',
    registrations: {
        hideInitialPaymentForms: true,
        requireCvv: "true"
    },
    browser: {
        threeDChallengeWindow: 5
    }


}


if (dataObj.supported_network) {
    wpwlOptions.applePay = {
        supportedNetworks: dataObj.supported_network
    }
}
