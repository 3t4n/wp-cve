<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Suffix_Api
{
    const AWC_SUFFIX_CUSTOMER = "customer/";

    const AWC_SUFFIX_ORDER = "order/";

    const AWC_SUFFIX_PAYMENT_BILLET = "payment/boleto/";

    const AWC_SUFFIX_PAYMENT_CREDIT_CARD = "payment/credit-card/";
    
    const AWC_SUFFIX_PAYMENT_PIX = "payment/pix/";

    const AWC_SUFFIX_PAYMENT_INSTALLMENTS = "payment/installments/";

    const AWS_SUFFIX_TRACKING_CODE = "order/delivery-tracking-code";
}