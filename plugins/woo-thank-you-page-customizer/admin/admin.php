<?php
/*
Class Name: VI_WOO_THANK_YOU_PAGE_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_THANK_YOU_PAGE_Admin_Admin {
	protected $settings;
	protected $active_components;

	public function __construct() {
		$this->settings          = new VI_WOO_THANK_YOU_PAGE_DATA();
		$this->active_components = array();
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-thank-you-page-customizer' );
		load_textdomain( 'woo-thank-you-page-customizer', VI_WOO_THANK_YOU_PAGE_LANGUAGES . "woo-thank-you-page-customizer-$locale.mo" );
		load_plugin_textdomain( 'woo-thank-you-page-customizer', false, VI_WOO_THANK_YOU_PAGE_LANGUAGES );
	}

	public function init() {
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'    => 'https://wordpress.org/support/plugin/woo-thank-you-page-customizer/',
					'docs'       => 'http://docs.villatheme.com/?item=woo-thank-you-page-customizer',
					'review'     => 'https://wordpress.org/support/plugin/woo-thank-you-page-customizer/reviews/?rate=5#rate-response',
					'pro_url'    => 'https://1.envato.market/Q3Weo',
					'css'        => VI_WOO_THANK_YOU_PAGE_CSS,
					'image'      => VI_WOO_THANK_YOU_PAGE_IMAGES,
					'slug'       => 'woo-thank-you-page-customizer',
					'menu_slug'  => 'woo_thank_you_page_customizer',
					'version'    => VI_WOO_THANK_YOU_PAGE_VERSION,
					'survey_url' => 'https://script.google.com/macros/s/AKfycbxq1yQW09kljn32kI0MYjWSewBiwB81cBy3vRxGH681l36k8dsf0TGM8pd3Igd1Zm31rw/exec'
				)
			);
		}
	}

	public function admin_notices() {
		if ( ! get_option( 'woocommerce_checkout_page_id' ) ) {
			?>
            <div id="message" class="error">
                <p><?php esc_html_e( 'Checkout page is not set yet, Thank You Page Customizer for WooCommerce is not working. Please set it <a target="_blank" href="' . admin_url( 'admin.php' ) . '?page=wc-settings&tab=advanced">here</a>.', 'woo-thank-you-page-customizer' ); ?></p>
            </div>
			<?php
		}
		if ( isset( $_REQUEST['woocommerce_thank_you_page_customizer_items_removed_notice_hide'] ) && $_REQUEST['woocommerce_thank_you_page_customizer_items_removed_notice_hide'] ) {
			set_transient( 'woocommerce_thank_you_page_customizer_items_removed_notice', 'hide' );
		}
	}

	public function get_active_components( $value, $key ) {
		if ( ! in_array( $value, $this->active_components ) ) {
			$this->active_components[] = $value;
		}
	}

}