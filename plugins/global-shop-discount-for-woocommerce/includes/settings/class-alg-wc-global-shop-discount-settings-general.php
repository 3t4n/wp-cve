<?php
/**
 * Global Shop Discount for WooCommerce - General Section Settings
 *
 * @version 1.9.5
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Global_Shop_Discount_Settings_General' ) ) :

class Alg_WC_Global_Shop_Discount_Settings_General extends Alg_WC_Global_Shop_Discount_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'global-shop-discount-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.5
	 * @since   1.0.0
	 *
	 * @todo    (dev) `alg_wc_global_shop_discount_taxonomies`: per group?
	 * @todo    (desc) `alg_wc_global_shop_discount_taxonomies`: better desc?
	 * @todo    (desc) `alg_wc_global_shop_discount_taxonomies`: move to the "General" subsection?
	 * @todo    (desc) `alg_wc_global_shop_discount_total_groups`: better desc?
	 */
	function get_settings() {

		$plugin_settings = array(
			array(
				'title'    => __( 'Global Shop Discount Options', 'global-shop-discount-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_global_shop_discount_plugin_options',
			),
			array(
				'title'    => __( 'Global Shop Discount', 'global-shop-discount-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'global-shop-discount-for-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'Add global shop discount to all WooCommerce products. Beautifully.', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_plugin_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_global_shop_discount_plugin_options',
			),
		);

		$general_settings = array(
			array(
				'title'    => __( 'General Options', 'global-shop-discount-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_global_shop_discount_general_options',
			),
			array(
				'title'    => __( 'Total groups', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_total_groups',
				'default'  => 1,
				'type'     => 'number',
				'desc_tip' => __( 'Click "Save changes" after you change this number, and new settings sections will be displayed.', 'global-shop-discount-for-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_global_shop_discount_settings', sprintf( 'You will need %s plugin to add more than one discount group.',
					'<a target="_blank" href="https://wpfactory.com/item/global-shop-discount-for-woocommerce/">' . 'Global Shop Discount for WooCommerce Pro' . '</a>' ) ),
				'custom_attributes' => apply_filters( 'alg_wc_global_shop_discount_settings', array( 'readonly' => 'readonly' ), 'array' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_global_shop_discount_general_options',
			),
		);

		$advanced_settings = array(
			array(
				'title'    => __( 'Advanced Options', 'global-shop-discount-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_global_shop_discount_advanced_options',
			),
			array(
				'title'    => __( 'Apply discounts in backend', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'This will show the discounted prices in the admin area as well. E.g., in the "Products" list.', 'global-shop-discount-for-woocommerce' ),
				'desc'     => __( 'Enable', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_load_in_admin',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Stop on first matching discount group', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'Enable this if you want to stop applying discounts for product, when first matching discount group is found.', 'global-shop-discount-for-woocommerce' ),
				'desc'     => __( 'Enable', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_stop_on_first_discount_group',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Taxonomies', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'Taxonomies for the "Include/Exclude" options.', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_taxonomies',
				'default'  => array( 'product_cat', 'product_tag' ),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => array_combine( get_object_taxonomies( 'product', 'names' ), wp_list_pluck( get_object_taxonomies( 'product', 'objects' ), 'label' ) ),
			),
			array(
				'desc'     => __( 'Taxonomies sorting in admin settings', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_taxonomies_orderby',
				'default'  => 'name',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'name'    => __( 'Name', 'global-shop-discount-for-woocommerce' ),
					'term_id' => __( 'ID', 'global-shop-discount-for-woocommerce' ),
					'parent'  => __( 'Parent', 'global-shop-discount-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Cache product prices', 'global-shop-discount-for-woocommerce' ),
				'desc_tip' => __( 'Enable this if you have a compatibility (e.g., with a currency switcher plugin) issue, e.g., discount is applied twice.', 'global-shop-discount-for-woocommerce' ),
				'desc'     => __( 'Enable', 'global-shop-discount-for-woocommerce' ),
				'id'       => 'alg_wc_global_shop_discount_cache_product_prices',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_global_shop_discount_advanced_options',
			),
		);

		return array_merge( $plugin_settings, $general_settings, $advanced_settings );
	}

}

endif;

return new Alg_WC_Global_Shop_Discount_Settings_General();
