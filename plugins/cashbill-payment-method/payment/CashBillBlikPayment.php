<?php

class CashBillBlikPayment extends CashBillPaymentAbstract
{
    public function __construct()
    {
        $this->id = 'cashbill_blik_payment';
        $this->icon = $this->get_option('icon') === "yes" ? plugins_url('cashbill-payment-method/img/payment/logo_blik_200x24.png') : null;
        $this->has_fields = $this->get_option('extended') === "yes";
        $this->method_title = 'CashBill (BLIK)';
        $this->method_description = 'Wyświetl płatność BLIK na równi z innymi metodami płatności, zmniejszając tym samym liczbę potrzebnych kliknięć do wyboru jednej z najpopularniejszych form płatności w Polsce. Dodatkowo jednym kliknięcieć możesz umożliwić wpisanie kodu BLIK bezpośrednio na stronie swojego sklepu ';
        $this->title = $this->get_option('title');

        $this->init_form_fields();
        $this->init_settings();

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Włączony / Wyłączony', 'cashbill_payment'),
                'label' => __('Włącz metodę płatności', 'cashbill_payment'),
                'type' => 'checkbox',
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Tytuł', 'cashbill_payment'),
                'type' => 'text',
                'default' => __('Płatność BLIK', 'cashbill_payment'),
                'desc_tip' => __('Tytuł płatności widoczny dla użytkownika w momencie wybierania metody płatności', 'cashbill_payment'),
            ),
            'icon' => array(
                'title' => __('Wyświetl / Ukryj', 'cashbill_payment'),
                'label' => __('Wyświetl ikonę płatności', 'cashbill_payment'),
                'type' => 'checkbox',
                'default' => 'yes',
                'desc_tip' => __('Aktywując tą opcję wyświetlisz ikonę związaną z tą formą płątności obok jej nazwy.', 'cashbill_payment'),
            ),
            'extended' => array(
                'title' => __('Kod BLIK na stronie sklepu', 'cashbill_payment'),
                'label' => __('Wyświetl formularz wpisania kodu BLIK na stronie sklepu', 'cashbill_payment'),
                'type' => 'checkbox',
                'default' => 'no',
                'desc_tip' => __('Aktywując tą opcję wyświetlisz formularz wpisania kodu BLIK bezpośrednio na stronie sklepu.', 'cashbill_payment'),
            ),
        );
    }

    public function payment_fields()
    {
        wp_enqueue_style('cashbill_checkout_styles', plugins_url('cashbill-payment-method/css/checkout.css'));
        wp_enqueue_script('cashbill_checkout_script', plugins_url('cashbill-payment-method/js/checkout.js'));

        echo '
        <div id="cashbill__blik">
                <img src="' . plugins_url('cashbill-payment-method/img/payment/logo_blik_big.png') . '" />
                <h4>Wprowadź kod BLIK</h4>
                <div class="cashbill__blik__input">
                    <input name="cashbill__blikcode[]"  maxlength="1" size="1" onkeyup="doNext(this);"/>
                    <input name="cashbill__blikcode[]"  maxlength="1" size="1" onkeyup="doNext(this);"/>
                    <input name="cashbill__blikcode[]"  maxlength="1" size="1" onkeyup="doNext(this);"/>

                    <input name="cashbill__blikcode[]"  maxlength="1" size="1" onkeyup="doNext(this);"/>
                    <input name="cashbill__blikcode[]"  maxlength="1" size="1" onkeyup="doNext(this);"/>
                    <input name="cashbill__blikcode[]"  maxlength="1" size="1" onkeyup="doNext(this);"/>
                </div>
                <p>Po złożeniu zamówienia potwierdź transakcję PINem w aplikacji banku.</p>
        </div>
        ';
    }

    public function getUserIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }

    public function process_payment($order_id)
    {
        $blikCode = $this->get_option('extended') === "yes" && isset($_POST['cashbill__blikcode']) ? (is_array($_POST['cashbill__blikcode']) ? implode("", $_POST['cashbill__blikcode']) : $_POST['cashbill__blikcode']) : null;
        if ($blikCode !== null && !preg_match('/^[0-9]{6}$/', $blikCode)) {
            wc_add_notice(__('Wpisz poprawny kod BLIK. Kod BLIK powinien składać się z 6 cyfr.', 'cashbill_payment'), 'error');
            return;
        }

        $options = null;

        if ($blikCode !== null) {
            $options = new CashBill\Payments\Model\Options();
            $options->addOption('ip', $this->getUserIP());
            $options->addOption('blikCode', $blikCode);
        }

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
                $blikCode !== null ? null : $urls['returnUrl'],
                $blikCode !== null ? null : $urls['negativeReturnUrl'],
                "blik",
                null,
                $this->getReferer(),
                $options
            );

            $order->add_order_note(__('Rozpoczęcie płatności CashBill (BLIK' . ($blikCode !== null ? ' kod blik na stronie' : '') . ')', 'cashbill_payment') . " ($paymentData->id)");
            WC()->cart->empty_cart();

            return array(
                'result' => 'success',
                'redirect' => $blikCode !== null ? $urls['returnUrl'] : $paymentData->redirectUrl,
            );
        } catch (Exception $e) {
            wc_add_notice(__('Wystąpił problem przy rozpoczęciu płatności. Spróbuj ponownie.', 'cashbill_payment'), 'error');
            return;
        }
    }

    public function admin_options()
    {
        include_once(__DIR__ . '/../view/admin/option/basic.php');
    }
}
