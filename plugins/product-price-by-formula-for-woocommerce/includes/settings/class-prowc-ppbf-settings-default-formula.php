<?php
/**
 * Product Price by Formula for WooCommerce - Default Formula Section Settings
 *
 * @version 2.2.0
 * @since   2.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_PPBF_Settings_Default_Formula' ) ) :

class ProWC_PPBF_Settings_Default_Formula extends ProWC_PPBF_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public $id;
	public $desc;
	public function __construct() {
		$this->id   = 'default_formula';
		$this->desc = __( 'Default Formula', PPBF_TEXTDOMAIN );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.2.0
	 * @since   2.0.0
	 * @todo    [dev] (now) add link to the plugin page ("... for examples, please check...")
	 */
	function get_settings() {
		$link_start = apply_filters( 'prowc_ppbf', '<a href="https://prowcplugins.com/downloads/product-price-by-formula-for-woocommerce/" target="_blank" title="' .
			'To use this shortcode you\'ll need Product Price by Formula for WooCommerce Pro plugin.' . '">', 'settings' );
		$link_end   = apply_filters( 'prowc_ppbf', '</a>', 'settings' );
		$default_settings = array(
			array(
				'title'    => __( 'Default Formula Settings', PPBF_TEXTDOMAIN ),
				'type'     => 'title',
				'desc'     => '<p>' . __( 'You can set default settings here. All settings can later be changed on individual product\'s edit page (in <strong>Product Price by Formula</strong> meta box).', PPBF_TEXTDOMAIN ) . '</p>' .
					'<p>' . sprintf( __( 'In <strong>formula</strong> use %s variable for product\'s base price. For example: %s. Please note that you can not use %s or %s <strong>inside other params</strong>.', PPBF_TEXTDOMAIN ),
						'<code>' . 'x' . '</code>', '<code>' . 'x+p1*p2' . '</code>', '<code>' . 'x' . '</code>', '<code>' . 'pN' . '</code>' ) . '</p>' .
					'<p>' . sprintf( __( 'In <strong>formula and/or params</strong> use can also use shortcodes: %s.', PPBF_TEXTDOMAIN ),
						'<code>[' . prowc_ppbf()->core->shortcodes->shortcodes_prefix .
							implode( ']</code>, <code>[' . prowc_ppbf()->core->shortcodes->shortcodes_prefix,
								prowc_ppbf()->core->shortcodes->shortcodes ) .
						']</code>, ' .
						'<code>['  . $link_start . prowc_ppbf()->core->shortcodes->shortcodes_prefix .
							implode( $link_end . ']</code>, <code>[' . $link_start . prowc_ppbf()->core->shortcodes->shortcodes_prefix,
								prowc_ppbf()->core->shortcodes->extra_shortcodes ) .
						$link_end . ']</code>' ) .
					'</p>' .
					'<p>' . sprintf( __( 'Please note that if you are using <strong>caching plugins</strong> and dynamic product pricing (e.g.: price changing with product stock (%s) or by customer\'s location (%s)), then caching needs to be disabled for products pages.', PPBF_TEXTDOMAIN ),
						'<code>[product_stock]</code>', '<code>[if_customer_location]</code>' ) . ' ' .
					sprintf( __( 'If you want to keep caching enabled, you will need to cache product pages for each condition: for example for %s you can set %s option to %s in %s.', PPBF_TEXTDOMAIN ),
						'<code>[if_customer_location]</code>',
						'<em>' . __( 'Default customer location', 'woocommerce' ) . '</em>',
						'<em>' . __( 'Geolocate (with page caching support)', 'woocommerce' ) . '</em>',
						'<em>' . __( 'WooCommerce > Settings > General', PPBF_TEXTDOMAIN ) . '</em>' ) . '</p>',
				'id'       => 'prowc_ppbf_default_options',
			),
			array(
				'title'    => __( 'Formula', PPBF_TEXTDOMAIN ),
				'type'     => 'textarea',
				'id'       => 'prowc_ppbf_eval',
				'default'  => '',
				'css'      => 'width:100%;height:150px;',
			),
			array(
				'title'    => __( 'Number of parameters', PPBF_TEXTDOMAIN ),
				'desc'     => '<button name="save" class="button-primary woocommerce-save-button" type="submit" value="' . esc_attr( __( 'Save changes', 'woocommerce' ) ) . '">' . esc_html( __( 'Save changes', 'woocommerce' ) ) . '</button>',
				'desc_tip' => __( 'Save settings after you change this number - new settings fields will appear.', PPBF_TEXTDOMAIN ),
				'id'       => 'prowc_ppbf_total_params',
				'default'  => 1,
				'type'     => 'number',
				'custom_attributes' => array( 'min' => 0 ),
			),
		);
		for ( $i = 1; $i <= get_option( 'prowc_ppbf_total_params', 1 ); $i++ ) {
			$default_settings = array_merge( $default_settings, array(
				array(
					'title'    => 'p' . $i . ( '' != ( $admin_note = get_option( 'prowc_ppbf_param_note_' . $i, '' ) ) ? ' (' . $admin_note . ')' : '' ),
					'desc'     => __( 'Value', PPBF_TEXTDOMAIN ),
					'id'       => 'prowc_ppbf_param_' . $i,
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
				),
				array(
					'desc'     => __( 'Admin note (optional)', PPBF_TEXTDOMAIN ),
					'id'       => 'prowc_ppbf_param_note_' . $i,
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
				),
			) );
		}
		$default_settings = array_merge( $default_settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'prowc_ppbf_default_options',
			),
		) );
		return $default_settings;
	}

}

endif;

return new ProWC_PPBF_Settings_Default_Formula();
