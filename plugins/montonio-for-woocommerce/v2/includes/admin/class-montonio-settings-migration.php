<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_Settings_Migration {

    public function __construct() {      
        $this->pis_v1_settings     = get_option( 'woocommerce_montonio_payments_settings' );
        $this->pis_v2_settings     = get_option( 'woocommerce_wc_montonio_payments_settings' );
        $this->cards_v1_settings   = get_option( 'woocommerce_montonio_card_payments_settings' );
        $this->blik_v1_settings    = get_option( 'woocommerce_montonio_blik_payments_settings' );
        $this->shipping_access_key = get_option( 'montonio_shipping_accessKey' );
        $this->shipping_secret_key = get_option( 'montonio_shipping_secretKey' );
        $this->api_settings        = get_option( 'woocommerce_wc_montonio_api_settings' );

        $this->migrate();
    }


    /**
     * Migrate settings after module update. 
     */
    public function migrate() {
        if ( empty( $this->api_settings['access_key'] ) || empty( $this->api_settings['secret_key'] ) ) {

            $access_key = null;
            $secret_key = null;

            if ( ! empty( $this->pis_v2_settings['access_key'] ) && ! empty( $this->pis_v2_settings['secret_key'] ) ) {

                $access_key = $this->pis_v2_settings['access_key'];
                $secret_key = $this->pis_v2_settings['secret_key'];

            } elseif ( $this->pis_v1_settings['montonioPaymentsEnvironment'] == 'production' && ! empty( $this->pis_v1_settings['montonioPaymentsAccessKey'] ) && ! empty( $this->pis_v1_settings['montonioPaymentsAccessKey'] ) ) {

                $access_key = $this->pis_v1_settings['montonioPaymentsAccessKey'];
                $secret_key = $this->pis_v1_settings['montonioPaymentsSecretKey'];
                
            } elseif ( $this->cards_v1_settings['montonioCardPaymentsEnvironment'] == 'production' && ! empty( $this->cards_v1_settings['montonioCardPaymentsAccessKey'] ) && ! empty( $this->cards_v1_settings['montonioCardPaymentsSecretKey'] ) ) {

                $access_key = $this->cards_v1_settings['montonioCardPaymentsAccessKey'];
                $secret_key = $this->cards_v1_settings['montonioCardPaymentsSecretKey'];

            } elseif ( $this->blik_v1_settings['montonioBlikPaymentsEnvironment'] == 'production' && ! empty( $this->blik_v1_settings['montonioBlikPaymentsAccessKey'] ) && ! empty( $this->blik_v1_settings['montonioBlikPaymentsSecretKey'] ) ) {

                $access_key = $this->blik_v1_settings['montonioBlikPaymentsAccessKey'];
                $secret_key = $this->blik_v1_settings['montonioBlikPaymentsSecretKey'];

            } elseif ( ! empty( $this->shipping_access_key ) && ! empty( $this->shipping_secret_key ) ) {

                $access_key = $this->shipping_access_key;
                $secret_key = $this->shipping_secret_key;

            }

            if ( $access_key != null && $secret_key != null ) {
                $this->api_settings['access_key'] = $access_key;
                $this->api_settings['secret_key'] = $secret_key;

                update_option( 'woocommerce_wc_montonio_api_settings', $this->api_settings );
            }
        }  
    }
}
new WC_Montonio_Settings_Migration();