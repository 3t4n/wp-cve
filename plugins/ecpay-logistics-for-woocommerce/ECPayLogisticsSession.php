<?php

// 前台 - 結帳頁電子地圖
class ECPayShippingCheckout
{
    public $ecpayInput = array();
    public $ecpayCheckout = array();

    public function setInput($post)
    {
        $this->ecpayInput = $post;
    }

    public function store()
    {
        foreach ($this->ecpayCheckout as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}

// 切換超商
class ECPay_ecpayShippingType extends ECPayShippingCheckout
{
    public function validate()
    {
        $checkoutInput = $this->ecpayInput['ecpayShippingType'];
        $checkout = array();
        $ecpayShippingType = array(
            'FAMI',
            'FAMI_Collection',
            'UNIMART' ,
            'UNIMART_Collection',
            'HILIFE',
            'HILIFE_Collection'
        );
        if (in_array($checkoutInput, $ecpayShippingType)) {
            $checkout['ecpayShippingType'] = $checkoutInput;
        }

        foreach ($checkout as $key => $value) {
            $checkout[$key] = sanitize_text_field($value);
        }

        $this->ecpayCheckout = $checkout;
    }
}

// 選擇門市
class ECPay_checkoutInput extends ECPayShippingCheckout
{
    public function validate()
    {
        $checkoutInput = $this->ecpayInput['checkoutInput'];
        $checkout = array();
        $validateInput = [
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'shipping_first_name',
            'shipping_last_name',
            'shipping_company',
            'shipping_to_different_address',
            'order_comments'
        ];
        $validateEmail = ['billing_email'];
        $validatePhone = ['billing_phone'];
        foreach ($checkoutInput as $key => $value) {
            if (in_array($key, $validatePhone)) {
                $result = preg_match('/^09\d{8}$/', $checkoutInput[$key]);
                if ($result === 0) {
                    $checkout[$key] = 'Must be a mobile';
                } else {
                    $checkout[$key] = $checkoutInput[$key];
                }
            } else {
                $checkout[$key] = $checkoutInput[$key];
            }
        }

        foreach ($checkout as $key => $value) {
            $checkout[$key] = sanitize_text_field($value);
        }

        $this->ecpayCheckout = $checkout;
    }
}