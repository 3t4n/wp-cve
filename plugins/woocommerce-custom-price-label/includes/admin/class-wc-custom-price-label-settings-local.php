<?php
/**
 * WooCommerce Custom Price Label - Local Custom Price Labels Section Settings
 *
 * @version 2.5.12
 * @since   2.3.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label_Settings_Local' ) ) :

class WC_Custom_Price_Label_Settings_Local extends Alg_WC_Custom_Price_Labels_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function __construct() {
		$this->id   = 'local_price_labels';
		$this->desc = __( 'Per Product Price Labels', 'woocommerce-custom-price-label' );
		parent::__construct();
	}

	/**
	 * get_section_settings.
	 *
	 * @version 2.5.7
	 * @since   2.3.0
	 * @todo    add "Disable Sections" (same as "Disable Options") with possible values `instead`, `before`, `between`, `after`
	 */
	public static function get_section_settings() {
		$settings = array(
			array(
				'title'     => __( 'Per Product Custom Price Labels Options', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'desc'      => __( 'When enabled, this will add "Custom Price Labels" metabox to each product\'s edit page.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_local_price_labels_options',
			),
			array(
				'title'     => __( 'Per Product Custom Price Labels', 'woocommerce-custom-price-label' ),
				'desc'      => '<strong>' . __( 'Enable section', 'woocommerce-custom-price-label' ) . '</strong>',
				'desc_tip'  => __( 'To set labels on per product basis, start editing product, then fill in "Custom Price Labels" metabox.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_local_price_labels_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Disable options', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_custom_price_labels_disabled_options',
				'default'   => '',
				'type'      => 'multiselect',
				'options'   => alg_get_options_section_variations_visibility(),
				'class'     => 'chosen_select',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'woocommerce_local_price_labels_options',
			),
			array(
				'title'     => __( 'Wrap Per Product Custom Price Labels', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'id'        => 'woocommerce_local_price_labels_wrap_options',
			),
			array(
				'title'     => __( 'Enable/Disable', 'woocommerce-custom-price-label' ),
				'desc'      => __( 'Enable', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_local_price_labels_wrap_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
		);
		$options_sections     = alg_get_options_sections();
		$options_sections_ids = alg_get_options_sections_ids();
		foreach ( $options_sections as $section_id => $options_section_desc ) {
			$section_id = $options_sections_ids[ $section_id ];
			$settings = array_merge( $settings, array(
				array(
					'title'     => $options_section_desc,
					'desc'      => __( 'Prepend', 'woocommerce-custom-price-label' ),
					'id'        => 'woocommerce_local_price_labels_wrap_' . $section_id . '_prepend',
					'default'   => '<span class="alg-price-label-' . $section_id . '">',
					'type'      => 'alg_wc_custom_price_label_textarea',
					'css'       => 'width:30%;min-width:300px;',
				),
				array(
					'desc'      => __( 'Append', 'woocommerce-custom-price-label' ),
					'id'        => 'woocommerce_local_price_labels_wrap_' . $section_id . '_append',
					'default'   => '</span>',
					'type'      => 'alg_wc_custom_price_label_textarea',
					'css'       => 'width:30%;min-width:300px;',
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'      => 'sectionend',
				'id'        => 'woocommerce_local_price_labels_wrap_options',
			),
		) );
		return $settings;
	}

}

endif;

return new WC_Custom_Price_Label_Settings_Local();
