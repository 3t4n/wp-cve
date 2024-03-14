<?php

class CashBillApplePayPayment extends CashBillChannelPayment
{

    public function __construct()
    {
        parent::__construct("applepay", "Apple Pay");
        $this->method_title = 'CashBill (Apple Pay)';
        $this->method_description = 'Wyświetl płatność Apple Pay na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru metody płatności.';
        $this->init();
    }
}
