<?php

class CashBillCCPayment extends CashBillChannelPayment
{

    public function __construct()
    {
        parent::__construct("paymentocc", "Płatność Kartami Płatniczymi");
        $this->method_title = 'CashBill (Karty Płatnicze)';
        $this->method_description = 'Wyświetl płatność kartami płatniczym na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru metody płatności.';
        $this->init();
    }
}