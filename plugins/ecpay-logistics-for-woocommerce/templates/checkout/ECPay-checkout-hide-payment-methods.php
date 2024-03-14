<?php
/**
 * 前台 - 隱藏ECPay/O'Pay付款方式
 */

defined('ECPAY_PLUGIN_PATH') || exit;

?>
<script>
    var product_total = document.getElementsByClassName('wc_payment_method');
    var disabled_payment_method_ecpay = [
        'wc_payment_method payment_method_ecpay_shipping_pay',
        'wc_payment_method payment_method_allpay',
        'wc_payment_method payment_method_allpay_dca',
        'wc_payment_method payment_method_ecpay',
        'wc_payment_method payment_method_ecpay_dca'
    ];
    for (var i = 0; i < product_total.length; i++) {
        if (disabled_payment_method_ecpay.indexOf(product_total[i].className) !== -1) {
            document.getElementsByClassName(product_total[i].className)[0].style.display = 'none';
        }
    }
</script>