<?php

class CashBillPaymentTypeAbstract extends Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType
{
    public $channel_name;
    public $name;
    public $iconUrl;

    public function __construct($channel_name, $icon)
    {
        $this->channel_name = $channel_name;
        $this->iconUrl = plugins_url('cashbill-payment-method/img/payment/icons/' . $icon);
        $this->name = "cashbill_{$this->channel_name}_payment";
    }

    public function initialize()
    {
        $this->settings = get_option('woocommerce_' . $this->name . '_settings', []);
    }

    public function is_active()
    {
        return filter_var($this->get_setting('enabled', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function get_payment_method_script_handles()
    {
        $script_name = 'cashbill_payment_gateway';
        $script_asset_path = WP_PLUGIN_DIR . '/cashbill-payment-method/build/' . $script_name . '.asset.php';
        $script_asset = require $script_asset_path;
        $script_url = plugins_url('cashbill-payment-method/build/' . $script_name . '.js');
        $style_url = plugins_url('cashbill-payment-method/build/' . $script_name . '.css');

        wp_register_script(
            $script_name,
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );
        wp_enqueue_style($script_name, $style_url, [], $script_asset['version']);

        return
            [
                $script_name
            ];
    }

    public function get_payment_method_data()
    {
        return [
            'title' => $this->get_setting('title'),
            'icon' => $this->get_setting('icon') == "yes",
            'iconUrl' => $this->iconUrl,
            'extended' => $this->get_setting('extended') == "yes",
            'description' => $this->get_setting('description'),
            'supports' => $this->get_supported_features(),
        ];
    }
}
