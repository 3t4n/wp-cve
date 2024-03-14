<?php

class CashBillPaymentNotification
{
    public function callback()
    {
        $shop = null;
        if (isset($_REQUEST['cashbill-mode']) && $_REQUEST['cashbill-mode'] === "psc") {
            $shop = new \CashBill\Payments\Shop(CashBillSettingsModel::getPSCId(), CashBillSettingsModel::getPSCSecret(), !CashBillSettingsModel::isTestMode());
        } else {
            $shop = new \CashBill\Payments\Shop(CashBillSettingsModel::getId(), CashBillSettingsModel::getSecret(), !CashBillSettingsModel::isTestMode());
        }

        $shop->notificationHandler("success", "error", $this);
    }

    public function success($paymentInfo)
    {
        if ($paymentInfo->status == 'PositiveFinish') {
            $order = new WC_Order($paymentInfo->additionalData);
            $order->add_order_note(__('Płatność na kwotę '.$paymentInfo->amount->value.' '.$paymentInfo->amount->currencyCode.' została przyjęta.', 'cashbill_payment'));
            $order->payment_complete();
        }
    }

    public function error($paymentInfo)
    {
        if ($paymentInfo->status == 'Abort' || $paymentInfo->status == 'Fraud' || $paymentInfo->status == 'NegativeFinish') {
            $order = new WC_Order($paymentInfo->additionalData);
            $order->add_order_note(__('Płatność nie została przyjęta system zwrócił status '.$paymentInfo->status, 'cashbill_payment'));
            $order->cancel_order('Płatność nie została przyjęta system zwrócił status '.$paymentInfo->status);
        }
    }
}
