<?php

class CashBillChannelPayment extends CashBillPaymentAbstract
{
    public $channel_name;
    public $default_title;

    public function __construct($channel_name, $default_title)
    {
        $this->channel_name = $channel_name;
        $this->default_title = $default_title;
        $this->id = "cashbill_{$this->channel_name}_payment";
        $this->icon = $this->get_option('icon') === "yes" ? plugins_url("cashbill-payment-method/img/payment/logo_{$this->channel_name}_200x24.png") : null;
        $this->has_fields = false;
        $this->title = $this->get_option('title') ? $this->get_option('title') : $default_title;
    }

    public function init()
    {
        $this->init_form_fields();
        $this->init_settings();
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ));
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title'     => __('Włączony / Wyłączony', 'cashbill_payment'),
                'label'     => __('Włącz metodę płatności', 'cashbill_payment'),
                'type'      => 'checkbox',
                'default'   => 'no',
            ),
            'title' => array(
                'title'     => __('Tytuł', 'cashbill_payment'),
                'type'      => 'text',
                'default' => __($this->default_title, 'cashbill_payment'),
                'desc_tip'  => __('Tytuł płatności widoczny dla użytkownika w momencie wybierania metody płatności', 'cashbill_payment'),
            ),
            'icon' => array(
                'title'     => __('Wyświetl / Ukryj', 'cashbill_payment'),
                'label'     => __('Wyświetl ikonę płatności', 'cashbill_payment'),
                'type'      => 'checkbox',
                'default'   => 'yes',
                'desc_tip'  => __('Wybierając tą opcję wyświetlisz ikonę związaną z tą formą płątności obok jej nazwy.', 'cashbill_payment'),
            ),
        );
    }

    public function process_payment($order_id)
    {
        try {
            $order = wc_get_order($order_id);
            if (!$this->validate_order($order)) {
                return;
            }
            $shop = $this->getCashBillShop();
            $urls = $this->getReturnUrlsForOrder($order);

            $paymentData = $shop->createPayment(
                $this->getTitleForOrder($order),
                $this->getAmountForOrder($order),
                $this->getDescriptionForOrder($order),
                $order_id,
                $this->getPersonalDataForOrder($order),
                $urls['returnUrl'],
                $urls['negativeReturnUrl'],
                $this->channel_name,
                null,
                $this->getReferer()
            );

            $order->add_order_note(__("Rozpoczęcie płatności CashBill ({$this->method_title})", 'cashbill_payment')." ($paymentData->id)");
            WC()->cart->empty_cart();

            return array(
                    'result'   => 'success',
                    'redirect' =>  $paymentData->redirectUrl,
            );
        } catch (Exception $e) {
            wc_add_notice(__('Wystąpił problem przy rozpoczęciu płatności. Spróbuj ponownie.', 'cashbill_payment'), 'error');
            return;
        }
    }

    public function validate_order($order)
    {
        return true;
    }
    
    public function admin_options()
    {
        include_once(__DIR__.'/../view/admin/option/basic.php');
    }
}
