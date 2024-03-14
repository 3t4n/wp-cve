<?php
/**
 * Products per Page for WooCommerce - Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Settings_Products_Per_Page' ) ) :

class Alg_WC_Settings_Products_Per_Page extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_products_per_page';
		$this->label = __( 'Products per Page', 'products-per-page-for-woocommerce' );
		parent::__construct();
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'alg_wc_ppp_sanitize' ), PHP_INT_MAX, 3 );
		// Sections
		require_once( 'class-alg-wc-products-per-page-settings-section.php' );
		require_once( 'class-alg-wc-products-per-page-settings-general.php' );
		require_once( 'class-alg-wc-products-per-page-settings-advanced.php' );
	}

	/**
	 * alg_wc_ppp_sanitize.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function alg_wc_ppp_sanitize( $value, $option, $raw_value ) {
		if ( ! empty( $option['alg_wc_ppp_sanitize'] ) ) {
			switch ( $option['alg_wc_ppp_sanitize'] ) {
				case 'textarea':
					return wp_kses_post( trim( $raw_value ) );
				default:
					$func = $option['alg_wc_ppp_sanitize'];
					return ( function_exists( $func ) ? $func( $raw_value ) : $value );
			}
		}
		return $value;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'products-per-page-for-woocommerce' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'products-per-page-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'products-per-page-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'products-per-page-for-woocommerce' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.3.0
	 * @since   1.1.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( 'alg_wc_products_per_page_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			if ( method_exists( 'WC_Admin_Settings', 'add_message' ) ) {
				WC_Admin_Settings::add_message( __( 'Your settings have been reset.', 'products-per-page-for-woocommerce' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
			}
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'products-per-page-for-woocommerce' ) . '</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Settings_Products_Per_Page();
