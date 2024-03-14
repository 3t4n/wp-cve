<?php
/**
 * SKU for WooCommerce - General Section Settings
 *
 * @version 1.6.1
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU_Settings_General' ) ) :

class Alg_WC_SKU_Settings_General extends Alg_WC_SKU_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 1.2.2
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'sku-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.6.1
	 * @todo    [dev] (maybe) "SKU Format Options" etc. to separate section (and then also add "Dashboard" subsection)
	 */
	public static function get_settings() {
		$settings = array(
			array(
				'title'     => __( 'SKU Generator for WooCommerce Options', 'sku-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_sku_for_woocommerce_options',
			),
			array(
				'title'     => __( 'SKU Generator for WooCommerce', 'sku-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable plugin', 'sku-for-woocommerce' ) . '</strong>',
				'desc_tip'  => 
					__( 'SKU Generator for WooCommerce', 'sku-for-woocommerce' )
					. ' v' . WPWHAM_SKU_GENERATOR_VERSION . '.<br />'
					. '<a href="https://wpwham.com/documentation/sku-generator-for-woocommerce/?utm_source=documentation_link&utm_campaign=free&utm_medium=sku_generator" target="_blank" class="button">' .
					__( 'Documentation', 'sku-for-woocommerce' ) . '</a>',
				'id'        => 'alg_sku_for_woocommerce_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_sku_for_woocommerce_options',
			),
			array(
				'title'     => __( 'SKU Format Options', 'sku-for-woocommerce' ),
				'desc'      => __( 'This section lets you set format for SKUs.', 'sku-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_sku_for_woocommerce_format_options',
			),
			array(
				'title'     => __( 'Number generation', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_number_generation',
				'default'   => 'product_id',
				'type'      => 'select',
				'class'     => 'wc-enhanced-select',
				'options'   => array(
					'product_id' => __( 'From product ID', 'sku-for-woocommerce' ),
				),
				'desc_tip'  => __( 'Possible values: from product ID, sequential or pseudorandom.', 'sku-for-woocommerce' ),
				'desc'      => apply_filters( 'alg_wc_sku_generator_option', sprintf(
						__( 'Get <a target="_blank" href="%s">SKU Generator for WooCommerce Pro</a> to add even more options.', 'sku-for-woocommerce' ),
						'https://wpwham.com/products/sku-generator-for-woocommerce/?utm_source=settings_general_number_generation&utm_campaign=free&utm_medium=sku_generator'
					), 'settings'
				),
				'custom_attributes' => apply_filters( 'alg_wc_sku_generator_option', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'     => __( 'Sequential number generation counter', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_number_generation_sequential',
				'default'   => 1,
				'type'      => 'number',
				'desc_tip'  => __( 'Ignored if "Number Generation" is not "Sequential".', 'sku-for-woocommerce' ),
				'custom_attributes' => array( 'min' => 0 ),
			),
			array(
				'title'     => __( 'Prefix', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_prefix',
				'default'   => '',
				'type'      => 'text',
			),
			array(
				'title'     => __( 'Minimum number length', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_minimum_number_length',
				'default'   => 0,
				'type'      => 'number',
			),
			array(
				'title'     => __( 'Suffix', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_suffix',
				'default'   => '',
				'type'      => 'text',
			),
			array(
				'title'     => __( 'Variable products variations', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_variations_handling',
				'default'   => 'as_variable',
				'type'      => 'select',
				'class'     => 'wc-enhanced-select',
				'options'   => array(
					'as_variable'             => __( 'SKU same as parent\'s product', 'sku-for-woocommerce' ),
					'as_variation'            => __( 'Generate different SKU for each variation', 'sku-for-woocommerce' ),
				),
				'desc_tip'  => __( 'Possible values: SKU same as parent\'s product, generate different SKU for each variation or SKU same as parent\'s product + variation suffix.', 'sku-for-woocommerce' ),
				'desc'      => apply_filters( 'alg_wc_sku_generator_option', sprintf(
						__( 'Get <a target="_blank" href="%s">SKU Generator for WooCommerce Pro</a> to add even more options.', 'sku-for-woocommerce' ),
						'https://wpwham.com/products/sku-generator-for-woocommerce/?utm_source=settings_general_variations_handling&utm_campaign=free&utm_medium=sku_generator'
					), 'settings'
				),
			),
			array(
				'title'     => __( 'Template', 'sku-for-woocommerce' ),
				'desc'      => __( 'Replaced values:', 'sku-for-woocommerce' ) . ' ' .
					'<code>' . implode( '</code>, <code>',
						array(
							'{category_prefix}', '{category_suffix}', '{category_slug}', '{category_name}',
							'{tag_prefix}', '{tag_suffix}', '{tag_slug}', '{tag_name}',
							'{prefix}', '{suffix}', '{variation_suffix}', '{sku_number}' )
					) . '</code>.'
					. '<br />' . sprintf( __( 'To force uppercase letters, enter any tag in all uppercase.  For example: %s', 'sku-for-woocommerce' ), '<code>{SUFFIX}</code>' )
					. '<br>* ' . sprintf( __( 'To use any of %s, %s, or %s replaced values, corresponding section must be enabled.', 'sku-for-woocommerce' ),
						'<code>{variation_suffix}</code>',
						'<code>{category_...}</code>',
						'<code>{tag_...}</code>' ),
				'id'        => 'alg_sku_for_woocommerce_template',
				'default'   => '{category_prefix}{tag_prefix}{prefix}{sku_number}{suffix}{tag_suffix}{category_suffix}{variation_suffix}',
				'type'      => 'text',
				'css'       => 'width:100%;',
				'alg_wc_sku_raw' => true,
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_sku_for_woocommerce_format_options',
			),
			array(
				'title'     => __( 'More Options', 'sku-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_sku_for_woocommerce_more_options',
			),
			array(
				'title'     => __( 'Automatically generate SKU for new products', 'sku-for-woocommerce' ),
				'desc'      => __( 'Enable', 'sku-for-woocommerce' ),
				'desc_tip'  => sprintf(
					__( 'If enabled - all new products will automatically get SKU according to set format values. To change SKUs of existing products, use <a href="%s">Regenerator tool</a>.', 'sku-for-woocommerce' ),
					admin_url( 'admin.php?page=wc-settings&tab=alg_sku&section=regenerator' )
				),
				'id'        => 'alg_sku_new_products_generate_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'desc'      => __( 'Delay SKU generation till product is published', 'sku-for-woocommerce' ),
				'desc_tip'  => __( 'Check this if you are using category/tag prefix/suffix for products SKU or individual SKUs for variations.', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_new_products_generate_only_on_publish',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Generate SKUs only for products with empty SKU', 'sku-for-woocommerce' ),
				'desc'      => __( 'Enable', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_generate_only_for_empty_sku',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Allow duplicate SKUs', 'sku-for-woocommerce' ),
				'desc'      => __( 'Enable', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_allow_duplicates',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Search by SKU', 'sku-for-woocommerce' ),
				'desc'      => __( 'Add', 'sku-for-woocommerce' ),
				'desc_tip'  => __( 'Add product searching by SKU on frontend.', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_search_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'desc'      => __( 'Search by SKU: Algorithm', 'sku-for-woocommerce' ),
				'desc_tip'  => __( 'Change this if you are experiencing issues with search by SKU on frontend.', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_for_woocommerce_search_algorithm',
				'default'   => 'posts_search',
				'type'      => 'select',
				'options'   => array(
					'posts_search'  => 'posts_search',
					'pre_get_posts' => 'pre_get_posts',
				),
			),
			array(
				'title'     => __( 'Add SKU to customer emails', 'sku-for-woocommerce' ),
				'desc'      => __( 'Add', 'sku-for-woocommerce' ),
				'id'        => 'alg_sku_add_to_customer_emails',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_sku_for_woocommerce_more_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_SKU_Settings_General();
