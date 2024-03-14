<?php

abstract class CashBillPaymentAbstract extends WC_Payment_Gateway
{
    private $shop;
    
    public function getCashBillShop()
    {
        if ($this->shop === null) {
            $this->shop = new CashBill\Payments\Shop(CashBillSettingsModel::getId(), CashBillSettingsModel::getSecret(), !CashBillSettingsModel::isTestMode());
        }

        return $this->shop;
    }
    
    public function getPersonalDataForOrder($order)
    {
        return array(
            'firstName'=>$order->get_billing_first_name(),
            'surname'=>$order->get_billing_last_name(),
            'email'=>$order->get_billing_email(),
            'country'=>$order->get_billing_country(),
            'city'=>$order->get_billing_city(),
            'postcode'=>$order->get_billing_postcode(),
            'street'=>$order->get_billing_address_1(),
        );
    }

    public function getAmountForOrder($order)
    {
        return array(
            "value"=>$order->get_total(),
            "currencyCode"=>$order->get_currency()
        );
    }

    public function getReferer()
    {
        return "WooCommerce#3";
    }

    public function getReturnUrlsForOrder($order)
    {
        return array(
            'returnUrl' =>  $this->get_return_url($order),
            'negativeReturnUrl' => $order->get_cancel_order_url()
        );
    }

    public function getTitleForOrder($order)
    {
        return "Zamówienie {$order->get_order_number()}";
    }

    public function getDescriptionForOrder($order)
    {
        return "Płatność za zamówienie {$order->get_order_number()}";
    }
}
