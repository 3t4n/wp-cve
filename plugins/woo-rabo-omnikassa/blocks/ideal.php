<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class Icwoorok2_Ideal_Blocks extends AbstractPaymentMethodType {

    private $gateway;
    protected $name = 'icwoorok2_ideal'; // your payment gateway name

    public function initialize() {
        $this->settings = get_option( 'woocommerce_icwoorok2_ideal_settings', [] );
        $this->gateway = new icwoorok2_ideal();
    }

    public function is_active() {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles() {

        wp_register_script(
            'icwoorok2_ideal-blocks-integration',
            ICWOOROK_ROOT_URL . 'assets/js/ideal.js',
            [
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
                'wp-html-entities',
                'wp-i18n',
            ],
            null,
            true
        );
        
        if( function_exists( 'wp_set_script_translations' ) ) {            
            wp_set_script_translations( 'icwoorok2_ideal-blocks-integration');
            
        }
        return [ 'icwoorok2_ideal-blocks-integration' ];
    }

    public function get_payment_method_data() {
        return [
            'title' => $this->gateway->method_title,
            'supports' => $this->gateway->supports,
            'icon' => $this->gateway->icon,
            'description' => $this->gateway->description,
        ];
    }

}
?>