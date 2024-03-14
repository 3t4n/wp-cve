<?php

class CashBillBasicPayment extends CashBillPaymentAbstract
{
    public function __construct()
    {
        $this->id = 'cashbill_basic_payment';
        $this->icon = $this->get_option('icon') === "yes" ? plugins_url('cashbill-payment-method/img/payment/logo_black_200x24.png') : null;
        $this->has_fields = $this->get_option('extended') === "yes";
        $this->method_title = 'CashBill';
        $this->method_description = 'Metoda płatności z przekierowaniem umożliwia przyjmowanie wpłat od klientów przekierowując ich na stronę wyboru formy płatności lub wyświetla wszystkie formy płatności na stronie sklepu (white-label). Przekierowanie następuje do formularza wyboru wszystkich dostępnych form płatności.';
        $this->title = $this->get_option('title');

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
                'default' => __('Płatność Natychmiastowa', 'cashbill_payment'),
                'desc_tip'  => __('Tytuł płatności widoczny dla użytkownika w momencie wybierania metody płatności', 'cashbill_payment'),
            ),
            'icon' => array(
                'title'     => __('Wyświetl / Ukryj', 'cashbill_payment'),
                'label'     => __('Wyświetl ikonę płatności', 'cashbill_payment'),
                'type'      => 'checkbox',
                'default'   => 'yes',
                'desc_tip'  => __('Aktywując tą opcję wyświetlisz ikonę związaną z tą formą płątności obok jej nazwy.', 'cashbill_payment'),
            ),
            'extended' => array(
                'title'     => __('Metody płatności na stronie sklepu', 'cashbill_payment'),
                'label'     => __('Wyświetl metody płatności bezpośrednio na stronie sklepu', 'cashbill_payment'),
                'type'      => 'checkbox',
                'default'   => 'no',
                'desc_tip'  => __('Aktywując tą opcję wyświetlisz wszystkie dostępne kanały płatności bezpośrednio na stronie sklepu, a klient zostanie przekierowany bezpośrednio do płatności np. banku.', 'cashbill_payment'),
            ),
        );
    }

    public function payment_fields()
    {
        wp_enqueue_style('cashbill_checkout_styles', plugins_url('cashbill-payment-method/css/checkout.css'));
        wp_enqueue_script('cashbill_checkout_script', plugins_url('cashbill-payment-method/js/checkout.js'));

        $shop = $this->getCashBillShop();
        $paymetChannels = $shop->getPaymentChannels();
        echo '<div id="cashbill__payments">';
        foreach ($paymetChannels as $singleChannel) {
            echo '
            <div class="cashbill__payments__channel" onClick="selectCashBillChannel(this, \''.$singleChannel->id.'\')">
                <img src="'.$singleChannel->logoUrl.'" />
                <h4>'.$singleChannel->name.'</h4>
            </div>
            ';
        }
        echo '<input id="cashbill__channel" name="cashbill__channel" type="hidden" value="" />';
        echo "</div>";
    }

    public function process_payment($order_id)
    {
        $paymentChannel = isset($_POST['cashbill__channel']) ? $_POST['cashbill__channel'] : null;

        try {
            $order = wc_get_order($order_id);
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
                $paymentChannel,
                null,
                $this->getReferer()
            );

            $order->add_order_note(__('Rozpoczęcie płatności CashBill ('. ($paymentChannel  === null ? "Przekierowanie" : $paymentChannel ).')', 'cashbill_payment')." ($paymentData->id)");
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
    
    public function admin_options()
    {
        include_once(__DIR__.'/../view/admin/option/basic.php');
    }
}
