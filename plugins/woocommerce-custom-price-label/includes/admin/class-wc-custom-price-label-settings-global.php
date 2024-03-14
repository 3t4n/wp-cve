<?php
/**
 * WooCommerce Custom Price Label - Global Custom Price Labels Section Settings
 *
 * @version 2.5.12
 * @since   2.3.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label_Settings_Global' ) ) :

class WC_Custom_Price_Label_Settings_Global extends Alg_WC_Custom_Price_Labels_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function __construct() {
		$this->id   = 'global_price_labels';
		$this->desc = __( 'Global Price Labels', 'woocommerce-custom-price-label' );
		parent::__construct();
	}

	/**
	 * get_section_settings.
	 *
	 * @version 2.5.7
	 * @since   2.3.0
	 * @todo    all products - instead of the price
	 * @todo    "show/hide options" and "user roles options" for each label separately (as in per product)
	 */
	public static function get_section_settings() {
		$desc              = apply_filters( 'alg_wc_custom_price_labels', wccpl_get_pro_message(), 'settings' );
		$custom_attributes = apply_filters( 'alg_wc_custom_price_labels', array( 'readonly' => 'readonly' ), 'settings' );
		$settings = array(
			array(
				'title'     => __( 'Global Custom Price Labels Options', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'desc'      => __( 'This section lets you set price labels for all products globally.', 'woocommerce-custom-price-label' )
					. ' ' . __( 'You can use HTML here.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_options',
			),
			array(
				'title'     => __( 'Global Custom Price Labels', 'woocommerce-custom-price-label' ),
				'desc'      => '<strong>' . __( 'Enable section', 'woocommerce-custom-price-label' ) . '</strong>',
				'id'        => 'woocommerce_global_price_labels_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Add before the price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to add before all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_add_before_text',
				'default'   => '',
				'type'      => 'alg_wc_custom_price_label_textarea',
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Add after the price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to add after all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_add_after_text',
				'default'   => '',
				'type'      => 'alg_wc_custom_price_label_textarea',
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Add between regular and sale prices', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to add between regular and sale prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_between_regular_and_sale_text',
				'default'   => '',
				'type'      => 'alg_wc_custom_price_label_textarea',
				'desc'      => $desc,
				'custom_attributes' => $custom_attributes,
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Remove from price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to remove from all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_remove_text',
				'default'   => '',
				'type'      => 'alg_wc_custom_price_label_textarea',
				'desc'      => $desc,
				'custom_attributes' => $custom_attributes,
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => __( 'Replace in price', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Enter text to replace in all products prices. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_replace_text',
				'default'   => '',
				'type'      => 'alg_wc_custom_price_label_textarea',
				'desc'      => $desc,
				'custom_attributes' => $custom_attributes,
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'title'     => '',
				'desc_tip'  => __( 'Enter text to replace with. Leave blank to disable.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_global_price_labels_replace_with_text',
				'default'   => '',
				'type'      => 'alg_wc_custom_price_label_textarea',
				'desc'      => $desc,
				'custom_attributes' => $custom_attributes,
				'css'       => 'width:30%;min-width:300px;',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'woocommerce_global_price_labels_options',
			),
			array(
				'title'     => __( 'Visibility Options', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'id'        => 'woocommerce_global_price_labels_visibility_options',
			),
			array(
				'title'     => __( 'Hide on', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'If set - will hide global price labels for selected options. Leave empty to show on all site.', 'woocommerce-custom-price-label' ),
				'id'        => 'alg_woocommerce_global_price_labels_hide_on',
				'default'   => '',
				'type'      => 'multiselect',
				'options'   => alg_get_options_section_variations_visibility_options(),
				'class'     => 'chosen_select',
			),
			array(
				'title'     => __( 'Show on', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'If set - will show global price labels only for selected options. Leave empty to show on all site.', 'woocommerce-custom-price-label' ),
				'id'        => 'alg_woocommerce_global_price_labels_show_on',
				'default'   => '',
				'type'      => 'multiselect',
				'options'   => alg_get_options_section_variations_visibility_options(),
				'class'     => 'chosen_select',
			),
			array(
				'title'     => __( 'User roles to hide', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'If set - will hide global price labels for selected user roles. Leave empty to show to all users.', 'woocommerce-custom-price-label' ),
				'id'        => 'alg_woocommerce_global_price_labels_roles_to_hide',
				'default'   => '',
				'type'      => 'multiselect',
				'options'   => alg_get_user_roles_options(),
				'class'     => 'chosen_select',
			),
			array(
				'title'     => __( 'User roles to show', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'If set - will show global price labels only for selected user roles. Leave empty to show to all users.', 'woocommerce-custom-price-label' ),
				'id'        => 'alg_woocommerce_global_price_labels_roles_to_show',
				'default'   => '',
				'type'      => 'multiselect',
				'options'   => alg_get_user_roles_options(),
				'class'     => 'chosen_select',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'woocommerce_global_price_labels_visibility_options',
			),
		);
		return $settings;
	}

}

endif;

return new WC_Custom_Price_Label_Settings_Global();
