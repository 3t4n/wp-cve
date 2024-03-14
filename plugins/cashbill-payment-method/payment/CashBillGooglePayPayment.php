<?php

class CashBillGooglePayPayment extends CashBillChannelPayment
{

    public function __construct()
    {
        parent::__construct("googlepay", "Google Pay");
        $this->method_title = 'CashBill (Google Pay)';
        $this->method_description = 'Wyświetl płatność Google Pay na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru metody płatności.';
        $this->init();
    }
}
