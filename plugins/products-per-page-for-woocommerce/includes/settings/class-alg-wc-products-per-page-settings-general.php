<?php
/**
 * Products per Page for WooCommerce - General Section Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Products_Per_Page_Settings_General' ) ) :

class Alg_WC_Products_Per_Page_Settings_General extends Alg_WC_Products_Per_Page_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'products-per-page-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_positions.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_positions() {
		return array(
			'woocommerce_before_main_content'              => __( 'Before main content', 'products-per-page-for-woocommerce' ),
			'woocommerce_archive_description'              => __( 'In archive description', 'products-per-page-for-woocommerce' ),
			'woocommerce_before_shop_loop'                 => __( 'Before shop loop', 'products-per-page-for-woocommerce' ),
			'woocommerce_after_shop_loop'                  => __( 'After shop loop', 'products-per-page-for-woocommerce' ),
			'woocommerce_after_main_content'               => __( 'After main content', 'products-per-page-for-woocommerce' ),
			'alg_wc_products_per_page_before_pagination'   => __( 'Before pagination', 'products-per-page-for-woocommerce' ),
			'alg_wc_products_per_page_after_pagination'    => __( 'After pagination', 'products-per-page-for-woocommerce' ),
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (desc) split this into separate sections, e.g., "General", "Template", etc.?
	 * @todo    (desc) Position priority: list "known priorities"?
	 */
	function get_settings() {

		$positions = $this->get_positions();

		$settings = array(
			array(
				'title'    => __( 'Products per Page Options', 'products-per-page-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_products_per_page_plugin_options',
			),
			array(
				'title'    => __( 'Products per Page', 'products-per-page-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'products-per-page-for-woocommerce' ) . '</strong>',
				'id'       => 'alg_wc_products_per_page_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_products_per_page_plugin_options',
			),
			array(
				'title'    => __( 'Position Options', 'products-per-page-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_products_per_page_position_options',
			),
			array(
				'title'    => __( 'Position(s)', 'products-per-page-for-woocommerce' ),
				'id'       => 'alg_products_per_page_position',
				'default'  => array( 'woocommerce_before_shop_loop' ),
				'type'     => 'multiselect',
				'class'    => 'chosen_select',
				'options'  => $positions,
			),
		);
		$selected_positions = get_option( 'alg_products_per_page_position', array( 'woocommerce_before_shop_loop' ) );
		foreach ( $selected_positions as $position ) {
			$settings = array_merge( $settings, array(
				array(
					'desc'     => sprintf( __( 'Position priority: %s', 'products-per-page-for-woocommerce' ),
						'<em>' . ( isset( $positions[ $position ] ) ? $positions[ $position ] : $position ) . '</em>' ),
					'desc_tip' => __( 'Used to fine-tune the Position.', 'products-per-page-for-woocommerce' ),
					'id'       => "alg_wc_products_per_page_position_priorities[{$position}]",
					'default'  => get_option( 'alg_products_per_page_position_priority', 40 ),
					'type'     => 'number',
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'title'    => __( 'Custom position(s)', 'products-per-page-for-woocommerce' ),
				'desc_tip' => __( 'You can list custom WordPress actions here.', 'products-per-page-for-woocommerce' ) . ' ' .
					__( 'One per line.', 'products-per-page-for-woocommerce' ) . ' ' .
					sprintf( __( 'If you want to set the priority for the action, use vertical bar symbol, e.g.: %s', 'products-per-page-for-woocommerce' ),
						'your_custom_action|40' ),
				'id'       => 'alg_wc_products_per_page_position_custom',
				'type'     => 'textarea',
				'default'  => '',
				'css'      => 'height:100px;',
			),
			array(
				'title'    => __( 'Widget', 'products-per-page-for-woocommerce' ),
				'desc'     => __( 'Enable', 'products-per-page-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Will add "%s" widget to %s.', 'products-per-page-for-woocommerce' ),
						__( 'Products per Page', 'products-per-page-for-woocommerce' ),
						'<a href="' . admin_url( 'widgets.php' ) . '">' . __( 'Appearance > Widgets', 'products-per-page-for-woocommerce' ) . '</a>'
					) . apply_filters( 'alg_wc_products_per_page_settings', '<br>' .
						'You will need <a href="https://wpfactory.com/item/products-per-page-woocommerce/" target="_blank">Products per Page for WooCommerce Pro</a> plugin to enable this option.' ),
				'id'       => 'alg_wc_products_per_page_widget_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_products_per_page_settings', array ( 'disabled' => 'disabled' ) ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_products_per_page_position_options',
			),
			array(
				'title'    => __( 'Template Options', 'products-per-page-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_products_per_page_template_options',
			),
			array(
				'title'    => __( 'Template', 'products-per-page-for-woocommerce' ),
				'desc'     => sprintf( __( 'Available placeholders: %s.', 'products-per-page-for-woocommerce' ), '<code>' . implode( '</code>, <code>', array(
					'%from%', '%to%', '%total%', '%dropdown%', '%radio%' ) ) . '</code>' ),
				'id'       => 'alg_products_per_page_text',
				'default'  => __( 'Products <strong>%from% - %to%</strong> from <strong>%total%</strong>. Products on page %dropdown%', 'products-per-page-for-woocommerce' ),
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Selector class', 'products-per-page-for-woocommerce' ),
				'desc'     => sprintf( __( 'In case of %s it is applied to each %s tag. In case of %s it is applied to the %s tag.', 'products-per-page-for-woocommerce' ),
					'<code>%radio%</code>', '<code>' . esc_html( '<input>' ) . '</code>', '<code>%dropdown%</code>', '<code>' . esc_html( '<select>' ) . '</code>' ),
				'id'       => 'alg_wc_products_per_page_select_class',
				'default'  => 'sortby rounded_corners_class',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Selector style', 'products-per-page-for-woocommerce' ),
				'desc'     => sprintf( __( 'In case of %s it is applied to each %s tag. In case of %s it is applied to the %s tag.', 'products-per-page-for-woocommerce' ),
					'<code>%radio%</code>', '<code>' . esc_html( '<input>' ) . '</code>', '<code>%dropdown%</code>', '<code>' . esc_html( '<select>' ) . '</code>' ),
				'id'       => 'alg_wc_products_per_page_select_style',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Before HTML', 'products-per-page-for-woocommerce' ),
				'id'       => 'alg_wc_products_per_page_before_html',
				'default'  => '<div class="clearfix"></div><div>',
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'After HTML', 'products-per-page-for-woocommerce' ),
				'id'       => 'alg_wc_products_per_page_after_html',
				'default'  => '</div>',
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Radio glue', 'products-per-page-for-woocommerce' ),
				'desc'     => sprintf( __( 'This is used for the %s placeholder - to "glue" the radio buttons.', 'products-per-page-for-woocommerce' ), '<code>%radio%</code>' ),
				'id'       => 'alg_wc_products_per_page_radio_glue',
				'default'  => ' ',
				'type'     => 'text',
				'css'      => 'width:100%;',
				'alg_wc_ppp_sanitize' => 'wp_kses_post',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_products_per_page_template_options',
			),
			array(
				'title'    => __( 'Select Options', 'products-per-page-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_products_per_page_select_options',
			),
			array(
				'title'    => __( 'Select options', 'products-per-page-for-woocommerce' ),
				'desc'     => sprintf( __( 'Enter one option per line in %s format. Use %s for "all products".', 'products-per-page-for-woocommerce' ),
						'<code>' . __( 'Title', 'products-per-page-for-woocommerce' ) . '|' . __( 'Number', 'products-per-page-for-woocommerce' ) . '</code>', '<code>-1</code>' ) .
					apply_filters( 'alg_wc_products_per_page_settings', '<br>' .
						'You will need <a href="https://wpfactory.com/item/products-per-page-woocommerce/" target="_blank">Products per Page for WooCommerce Pro</a> plugin to change this option.' ),
				'id'       => 'alg_products_per_page_select_options',
				'default'  => implode( PHP_EOL, array( '10|10', '25|25', '50|50', '100|100', 'All|-1' ) ),
				'type'     => 'textarea',
				'css'      => 'height:200px;',
				'custom_attributes' => apply_filters( 'alg_wc_products_per_page_settings', array ( 'readonly' => 'readonly' ) ),
			),
			array(
				'title'    => __( 'Default option', 'products-per-page-for-woocommerce' ),
				'id'       => 'alg_products_per_page_default',
				'default'  => 10,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => -1 ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_products_per_page_select_options',
			),
		) );

		return $settings;
	}

}

endif;

return new Alg_WC_Products_Per_Page_Settings_General();
