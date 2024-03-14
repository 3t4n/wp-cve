<?php

class CashBillTwistoPayment extends CashBillChannelPayment
{

    public function __construct()
    {
        parent::__construct("twisto", "Płatność Odroczona Twisto");
        $this->method_title = 'CashBill (Twisto)';
        $this->method_description = 'Wyświetl płatność odroczoną płatniczym na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru metody płatności.';
        $this->init();
    }
}
