<?php

class CashBillPayPalPayment extends CashBillChannelPayment
{
    public function __construct()
    {
        parent::__construct("paypal", "Płatność PayPal");
        $this->method_title = 'CashBill (PayPal)';
        $this->method_description = 'Wyświetl płatność PayPal na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru metody płatności.';
        $this->init();
    }
}
