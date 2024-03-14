<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Montonio_API_Settings extends WC_Settings_API {

    public function __construct() {
		$this->id = 'wc_montonio_api';

		add_action( 'woocommerce_update_options_checkout_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_montonio_settings_checkout_' . $this->id, array( $this, 'admin_options' ) );

        $this->init_form_fields();
	}


    public function init_form_fields() {
        $this->form_fields = array(
            'title'           => array(
                'type'        => 'title',
                'title'       => __( 'Add API Keys', 'montonio-for-woocommerce' ),
                'description' => __('Live and Sandbox API keys can be obtained at <a target="_blank" href="https://partner.montonio.com">Montonio Partner System</a>', 'montonio-for-woocommerce'),
            ),
            'live_title'    => array(
                'type'  => 'title',
                'title' => __( 'Live keys', 'montonio-for-woocommerce' ),
                'description' => __('Use live keys to receive real payments from your customers.', 'montonio-for-woocommerce'),
                'class' => 'sdas'
            ),
            'access_key'      => array(
                'title'       => __('Access Key', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'description' => '',
                'desc_tip'    => true,
            ),
            'secret_key'      => array(
                'title'       => __('Secret Key', 'montonio-for-woocommerce'),
                'type'        => 'password',
                'description' => '',
                'desc_tip'    => true,
            ),
            'sanbox_title'    => array(
                'type'  => 'title',
                'title' => __( 'Sandbox keys for testing', 'montonio-for-woocommerce' ),
                'description' => __('Use sandbox keys to test our services.', 'montonio-for-woocommerce'),
            ),
            'sandbox_access_key' => array(
                'title'       => __('Access Key', 'montonio-for-woocommerce'),
                'type'        => 'text',
                'description' => '',
                'desc_tip'    => true,
            ),
            'sandbox_secret_key' => array(
                'title'       => __('Secret Key', 'montonio-for-woocommerce'),
                'type'        => 'password',
                'description' => '',
                'desc_tip'    => true,
            )
        );
    }

    public function admin_options() {
        WC_Montonio_Display_Admin_Options::display_options( 
            __( 'API Settings', 'montonio-for-woocommerce' ), 
            $this->generate_settings_html( array(), false ),
            $this->id
        );
    }

}
new WC_Montonio_API_Settings();