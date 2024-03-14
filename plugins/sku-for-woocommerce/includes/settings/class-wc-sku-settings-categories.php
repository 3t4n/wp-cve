<?php
/**
 * SKU for WooCommerce - Categories Section Settings
 *
 * @version 1.6.1
 * @since   1.2.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU_Settings_Categories' ) ) :

class Alg_WC_SKU_Settings_Categories extends Alg_WC_SKU_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 1.2.2
	 * @since   1.2.0
	 */
	function __construct() {
		$this->id   = 'categories';
		$this->desc = __( 'Categories', 'sku-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.6.0
	 * @since   1.2.0
	 */
	public static function get_settings() {
		$settings = array();
		$settings = array_merge( $settings, array(
			array(
				'title'     => __( 'Categories Options', 'sku-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_sku_categories_options',
			),
			array(
				'title'     => __( 'Enable/Disable', 'sku-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable section', 'sku-for-woocommerce' ) . '</strong>',
				'id'        => 'alg_sku_categories_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Sequential numbering on per category basis', 'sku-for-woocommerce' ),
				'desc'      => __( 'Enable', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_categories_sequential_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'desc_tip'  => apply_filters( 'alg_wc_sku_generator_option', sprintf(
						__( 'Get <a target="_blank" href="%s">SKU Generator for WooCommerce Pro</a> plugin to enable this option.', 'sku-for-woocommerce' ),
						'https://wpwham.com/products/sku-generator-for-woocommerce/?utm_source=settings_categories_sequential_numbering&utm_campaign=free&utm_medium=sku_generator'
					), 'settings'
				),
				'custom_attributes' => apply_filters( 'alg_wc_sku_generator_option', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'     => __( 'Use Yoast primary categories', 'sku-for-woocommerce' ),
				'desc'      => __( 'Enable', 'sku-for-woocommerce' ),
				'id'        => 'wpw_sku_generator_use_yoast_primary_category',
				'default'   => 'no',
				'type'      => 'checkbox',
				'desc_tip'  => __( 'Yoast SEO adds the ability to mark a category as "primary". Normally when a product has multiple categories we use only the first one. With this option enabled, we will instead use the one identified by Yoast as "primary", if set.', 'sku-for-woocommerce' ) .
					apply_filters( 'alg_wc_sku_generator_option', sprintf(
						'<br />' . __( 'Get <a target="_blank" href="%s">SKU Generator for WooCommerce Pro</a> plugin to enable this option.', 'sku-for-woocommerce' ),
						'https://wpwham.com/products/sku-generator-for-woocommerce/?utm_source=settings_categories_yoast&utm_campaign=free&utm_medium=sku_generator'
					), 'settings'
				),
				'custom_attributes' => apply_filters( 'alg_wc_sku_generator_option', array( 'disabled' => 'disabled' ), 'settings' ),
			),
		) );
		$products_terms = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		if ( ! empty( $products_terms ) && ! is_wp_error( $products_terms ) ){
			foreach ( $products_terms as $products_term ) {
				$settings = array_merge( $settings, array(
					array(
						'title'     => $products_term->name,
						'desc'      => __( 'Prefix', 'sku-for-woocommerce' ),
						'id'        => 'alg_sku_prefix_cat_' . $products_term->term_id,
						'default'   => '',
						'type'      => 'text',
						'desc_tip'  => apply_filters( 'alg_wc_sku_generator_option',
							__( 'Get SKU Generator for WooCommerce Pro plugin to change value.', 'sku-for-woocommerce' ), 'settings' ),
						'custom_attributes' => apply_filters( 'alg_wc_sku_generator_option', array( 'readonly' => 'readonly' ), 'settings' ),
					),
					array(
						'desc'      => __( 'Suffix', 'sku-for-woocommerce' ),
						'id'        => 'alg_sku_suffix_cat_' . $products_term->term_id,
						'default'   => '',
						'type'      => 'text',
					),
					array(
						'desc'      => __( 'Counter', 'sku-for-woocommerce' ),
						'id'        => 'alg_sku_sequential_cat_' . $products_term->term_id,
						'default'   => 1,
						'type'      => 'number',
						'desc_tip'  => apply_filters( 'alg_wc_sku_generator_option',
							__( 'Get SKU Generator for WooCommerce Pro plugin to change value.', 'sku-for-woocommerce' ), 'settings' ),
						'custom_attributes' => apply_filters( 'alg_wc_sku_generator_option', array( 'readonly' => 'readonly' ), 'settings' ),
					),
				) );
			}
		}
		$settings = array_merge( $settings, array(
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_sku_categories_options',
			),
		) );
		return $settings;
	}

}

endif;

return new Alg_WC_SKU_Settings_Categories();
