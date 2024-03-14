<?php
/**
 * SKU for WooCommerce - Tags Section Settings
 *
 * @version 1.6.1
 * @since   1.2.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU_Settings_Tags' ) ) :

class Alg_WC_SKU_Settings_Tags extends Alg_WC_SKU_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 1.2.2
	 * @since   1.2.0
	 */
	function __construct() {
		$this->id   = 'tags';
		$this->desc = __( 'Tags', 'sku-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.4.1
	 * @since   1.2.0
	 */
	public static function get_settings() {
		$settings = array();
		$settings = array_merge( $settings, array(
			array(
				'title'     => __( 'Tags Options', 'sku-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_sku_tags_options',
			),
			array(
				'title'     => __( 'Enable/Disable', 'sku-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable section', 'sku-for-woocommerce' ) . '</strong>',
				'id'        => 'alg_sku_tags_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
		) );
		$products_terms = get_terms( 'product_tag', 'orderby=name&hide_empty=0' );
		if ( ! empty( $products_terms ) && ! is_wp_error( $products_terms ) ){
			foreach ( $products_terms as $products_term ) {
				$settings = array_merge( $settings, array(
					array(
						'title'     => $products_term->name,
						'desc'      => __( 'Prefix', 'sku-for-woocommerce' ),
						'id'        => 'alg_sku_prefix_tag_' . $products_term->term_id,
						'default'   => '',
						'type'      => 'text',
						'desc_tip'  => apply_filters( 'alg_wc_sku_generator_option',
							__( 'Get SKU Generator for WooCommerce Pro plugin to change value.', 'sku-for-woocommerce' ), 'settings' ),
						'custom_attributes' => apply_filters( 'alg_wc_sku_generator_option', array( 'readonly' => 'readonly' ), 'settings' ),
					),
					array(
						'desc'      => __( 'Suffix', 'sku-for-woocommerce' ),
						'id'        => 'alg_sku_suffix_tag_' . $products_term->term_id,
						'default'   => '',
						'type'      => 'text',
					),
				) );
			}
		}
		$settings = array_merge( $settings, array(
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_sku_tags_options',
			),
		) );
		return $settings;
	}

}

endif;

return new Alg_WC_SKU_Settings_Tags();
