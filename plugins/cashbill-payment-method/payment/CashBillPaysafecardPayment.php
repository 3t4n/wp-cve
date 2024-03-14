<?php

class CashBillPaysafecardPayment extends CashBillChannelPayment
{
    public function __construct()
    {
        parent::__construct("paysafecard", "Płatność PAYSAFECARD");
        $this->method_title = 'CashBill (PAYSAFECARD)';
        $this->method_description = 'Wyświetl płatność paysafecard na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru metody płatności.';
        $this->init();
    }

    public function getCashBillShop()
    {
        if ($this->shop === null) {
            if(CashBillSettingsModel::isPSCMode()){
                $this->shop = new CashBill\Payments\Shop(CashBillSettingsModel::getPSCId(), CashBillSettingsModel::getPSCSecret(), !CashBillSettingsModel::isTestMode());
            }else{
                $this->shop = new CashBill\Payments\Shop(CashBillSettingsModel::getId(), CashBillSettingsModel::getSecret(), !CashBillSettingsModel::isTestMode());
            }
        }

        return $this->shop;
    }
}