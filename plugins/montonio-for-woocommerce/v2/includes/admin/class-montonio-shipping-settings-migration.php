<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_Shipping_Settings_Migration {

    public function __construct() {
        $this->shipping_access_key = get_option( 'montonio_shipping_accessKey' );
        $this->shipping_secret_key = get_option( 'montonio_shipping_secretKey' );

        $this->api_settings = get_option( 'woocommerce_wc_montonio_api_settings' );

        $this->migrate();
    }


    /**
     * Run various migrations for database changes after module updates. 
     */
    public function migrate() {

        if ( empty( $this->api_settings['access_key'] ) && $this->shipping_access_key ) {
            $this->api_settings['access_key'] = $this->shipping_access_key;
        }

        if ( empty( $this->api_settings['secret_key'] ) && $this->shipping_secret_key ) {
            $this->api_settings['secret_key'] = $this->shipping_secret_key;
        }

        update_option( 'woocommerce_wc_montonio_api_settings', $this->api_settings );
    }
}
new WC_Montonio_Shipping_Settings_Migration();