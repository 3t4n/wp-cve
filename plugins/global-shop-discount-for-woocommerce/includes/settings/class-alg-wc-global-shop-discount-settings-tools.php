<?php
/**
 * Global Shop Discount for WooCommerce - Tools Section Settings
 *
 * @version 1.9.0
 * @since   1.9.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Global_Shop_Discount_Settings_Tools' ) ) :

class Alg_WC_Global_Shop_Discount_Settings_Tools extends Alg_WC_Global_Shop_Discount_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function __construct() {
		$this->id   = 'tools';
		$this->desc = __( 'Tools', 'global-shop-discount-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function get_settings() {
		$icon = '<span class="dashicons dashicons-admin-generic"></span>';
		return array(
			array(
				'title'    => __( 'Tools', 'global-shop-discount-for-woocommerce' ),
				'desc'     => sprintf( __( 'Check the %s box and "Save changes" to run the tool.', 'global-shop-discount-for-woocommerce' ), $icon ) . ' ' .
					'<strong>' . __( 'Please note that there is no undo option.', 'global-shop-discount-for-woocommerce' ) . '</strong>',
				'type'     => 'title',
				'id'       => 'alg_wc_global_shop_discount_tools',
			),
			array(
				'title'    => __( 'Save prices in DB for all products', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'The tool will apply and save plugin\'s prices for all products in DB.', 'global-shop-discount-for-woocommerce' ) . ' ' .
					__( 'You\'ll probably want to disable all plugin\'s discounts after running this tool.', 'global-shop-discount-for-woocommerce' ),
				'desc'     => $icon . ' ' . __( 'Save', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_tool_save_all_products',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_global_shop_discount_tools',
			),
		);
	}

}

endif;

return new Alg_WC_Global_Shop_Discount_Settings_Tools();
