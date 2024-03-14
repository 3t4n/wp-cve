<?php
/**
 * Product Price by Formula for WooCommerce - Admin Class
 *
 * @version 2.3.0
 * @since   2.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_PPBF_Admin' ) ) :

class ProWC_PPBF_Admin {
	public $products_list_columns;
	public $chosen_products_list_columns;

	/**
	 * Constructor.
	 *
	 * @version 2.1.1
	 * @since   2.0.0
	 * @todo    [feature] add "price by formula" options to quick and bulk edit
	 * @todo    [feature] add filtering for "price by formula" enabled products in admin products list
	 */
	function __construct() {
		// Admin final price preview
		add_action( 'prowc_ppbf_after_meta_box_settings', array( $this, 'add_final_price_preview_to_meta_box' ) );
		// Dashboard widget
		if ( 'yes' === get_option( 'prowc_ppbf_dashboard_widget_enabled', 'no' ) ) {
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
			add_action( 'admin_init',         array( $this, 'save_dashboard_widget_settings' ) );
		}
		// Products list column
		$this->products_list_columns = array(
			'prowc_ppbf_enabled'   => __( 'Formula Enabled', PPBF_TEXTDOMAIN ),
			'prowc_ppbf_formula'   => __( 'Formula', PPBF_TEXTDOMAIN ),
			'prowc_ppbf_params'    => __( 'Params', PPBF_TEXTDOMAIN ),
			'prowc_ppbf_price'     => __( 'Price', PPBF_TEXTDOMAIN ),
		);
		$this->chosen_products_list_columns = get_option( 'prowc_ppbf_products_list_columns', array() );
		if ( ! empty( $this->chosen_products_list_columns ) ) {
			add_filter( 'manage_edit-product_columns',        array( $this, 'add_products_list_column' ),    PHP_INT_MAX );
			add_action( 'manage_product_posts_custom_column', array( $this, 'render_products_list_column' ), PHP_INT_MAX );
		}
	}

	/**
	 * add_products_list_column.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function add_products_list_column( $columns ) {
		foreach ( $this->products_list_columns as $column_id => $column_title ) {
			if ( in_array( $column_id, $this->chosen_products_list_columns ) ) {
				$columns[ $column_id ] = $column_title;
			}
		}
		return $columns;
	}

	/**
	 * render_products_list_column.
	 *
	 * @version 2.3.0
	 * @since   2.0.0
	 */
	function render_products_list_column( $column ) {
		if ( in_array( $column, $this->chosen_products_list_columns ) ) {
			$_product = wc_get_product();
			if ( prowc_ppbf()->core->is_price_by_formula_product( $_product ) ) {
				switch ( $column ) {
					case 'prowc_ppbf_enabled':
						echo ( prowc_ppbf()->core->is_price_by_formula_product( $_product ) ? '&#9745;' : '' );
						break;
					case 'prowc_ppbf_formula':
						echo prowc_ppbf()->core->get_product_formula( $_product );
						break;
					case 'prowc_ppbf_params':
						$params = array();
						prowc_ppbf()->core->get_current_product_data( $_product->get_price(), $_product );
						foreach ( prowc_ppbf()->core->get_product_params( $_product ) as $param_id => $param_value ) {
							$params[] = $param_id . '=' . ( '' === $param_value ? '<em>N/A</em>' : $param_value );
						}
						if ( ! empty( $params ) ) {
							echo implode( '<br>', $params );
						}
						break;
					case 'prowc_ppbf_price':
						if ( $_product->is_type( 'variable' ) ) {
							$prices = array();
							foreach ( $_product->get_available_variations() as $variation ) {
								$variation_product = wc_get_product( $variation['variation_id'] );
								$prices[] = prowc_ppbf()->core->price_by_formula( $variation_product->get_price(), $variation_product, true );
							}
							if ( ! empty( $prices ) ) {
								$from = min( $prices );
								$to   = max( $prices );
								echo ( $from !== $to ? wc_format_price_range( $from, $to ) : wc_price( $from ) );
							}
						} else {
							echo wc_price( prowc_ppbf()->core->price_by_formula( $_product->get_price(), $_product, true ) );
						}
						break;
				}
			}
		}
	}

	/**
	 * save_dashboard_widget_settings.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function save_dashboard_widget_settings() {
		if ( isset( $_POST['prowc_ppbf_save_dashboard_widget_settings'] ) ) {
			foreach ( $this->get_dashboard_widget_settings() as $field ) {
				update_option( $field['id'], stripslashes( $_POST[ $field['id'] ] ) );
			}
		}
	}

	/**
	 * get_dashboard_widget_settings.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function get_dashboard_widget_settings() {
		$settings = array(
			array(
				'title'    => __( 'Formula', PPBF_TEXTDOMAIN ),
				'type'     => 'textarea',
				'id'       => 'prowc_ppbf_eval',
				'default'  => '',
			),
			array(
				'title'    => __( 'Number of parameters', PPBF_TEXTDOMAIN ),
				'id'       => 'prowc_ppbf_total_params',
				'default'  => 1,
				'type'     => 'number',
				'custom_attributes' => 'min="0"',
			),
		);
		for ( $i = 1; $i <= get_option( 'prowc_ppbf_total_params', 1 ); $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => 'p' . $i . ( '' != ( $admin_note = get_option( 'prowc_ppbf_param_note_' . $i, '' ) ) ? ' (' . $admin_note . ')' : '' ),
					'id'       => 'prowc_ppbf_param_' . $i,
					'default'  => '',
					'type'     => 'textarea',
				),
			) );
		}
		return $settings;
	}

	/**
	 * add_dashboard_widget.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function add_dashboard_widget() {
		wp_add_dashboard_widget(
			'prowc_ppbf_dashboard_widget',
			__( 'Product Price by Formula', PPBF_TEXTDOMAIN ),
			array( $this, 'output_dashboard_widget' )
		);
	}

	/**
	 * output_dashboard_widget.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function output_dashboard_widget() {
		$html = '';
		$html .= '<form method="post" action="">';
		$html .= '<table class="widefat striped">';
		foreach ( $this->get_dashboard_widget_settings() as $field ) {
			$html .= '<tr>';
			$html .= '<th>';
			$html .= '<label for="' . $field['id'] . '">' . $field['title'] . '</label>';
			$html .= '</th>';
			$html .= '<td>';
			if ( 'textarea' === $field['type'] ) {
				$html .= '<textarea style="width:100%;" id="' . $field['id'] . '" name="' . $field['id'] . '"' .
					( isset( $field['custom_attributes'] ) ? ' ' . $field['custom_attributes'] : '' ) . '>' . get_option( $field['id'], $field['default'] ) . '</textarea>';
			} else {
				$html .= '<input style="width:100%;" type="' . $field['type'] . '" value="' . get_option( $field['id'], $field['default'] ). '" id="' . $field['id'] . '" name="' . $field['id'] . '"' .
					( isset( $field['custom_attributes'] ) ? ' ' . $field['custom_attributes'] : '' ) . '>';
			}
			$html .= '</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		$html .= '<p>';
		$html .= '<input type="submit" name="prowc_ppbf_save_dashboard_widget_settings" class="button button-primary" value="' .
			__( 'Save', PPBF_TEXTDOMAIN ) . '">';
		$html .= '</p>';
		$html .= '</form>';
		echo $html;
	}

	/**
	 * add_final_price_preview_to_meta_box.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function add_final_price_preview_to_meta_box() {
		$_product = wc_get_product();
		if ( prowc_ppbf()->core->is_price_by_formula_product( $_product ) ) {
			$_price = $_product->get_price();
			$_price = prowc_ppbf()->core->price_by_formula( $_price, $_product, true );
			echo '<p style="border: 1px solid gray; padding: 10px;">' . sprintf( __( '<strong>Final price preview:</strong> %s', PPBF_TEXTDOMAIN ), wc_price( $_price ) ) . '</p>';
		}
	}

}

endif;

return new ProWC_PPBF_Admin();
