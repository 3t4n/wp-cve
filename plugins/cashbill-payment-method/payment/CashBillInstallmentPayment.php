<?php

class CashBillInstallmentPayment extends CashBillChannelPayment
{

    public function __construct()
    {
        parent::__construct("aliorraty", "Płatność Ratalna");
        $this->method_title = 'CashBill (Raty)';
        $this->method_description = 'Wyświetl płatności ratalne na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru metody płatności.';
        $this->init();
    }

    public function validate_order($order){
        $amount = $this->getAmountForOrder($order);

        if($amout['value'] > 20000 || $amount['value'] < 300){
            wc_add_notice(__('Minimalna kwota płatności ratalnej to 300 zł, a maksymalna 20 000 zł', 'cashbill_payment'), 'error');
            return false;
        }

        return true;
    }
}