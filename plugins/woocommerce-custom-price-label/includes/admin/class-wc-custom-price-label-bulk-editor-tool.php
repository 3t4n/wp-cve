<?php
/**
 * WooCommerce Custom Price Label Bulk Editor Tool
 *
 * The WooCommerce Custom Price Label Bulk Editor Tool class.
 *
 * @version 2.4.0
 * @since   2.1.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label_Bulk_Editor' ) ) :

class WC_Custom_Price_Label_Bulk_Editor {

	/**
	 * Constructor.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_tool' ), PHP_INT_MAX );
		}
	}

	/**
	 * add_tool.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function add_tool() {
		add_submenu_page(
			'woocommerce',
			__( 'WooCommerce Custom Price Label Bulk Editor Tool', 'woocommerce-custom-price-label' ),
			__( 'Custom Price Label Bulk Editor Tool', 'woocommerce-custom-price-label' ),
			'manage_options',
			'wc-custom-price-label-bulk-editor-tool',
			array( $this, 'create_tool' )
		);
	}

	/**
	 * get_products.
	 *
	 * @version 2.3.0
	 * @since   2.1.0
	 */
	function get_products( $products = array() ) {
		$offset = 0;
		$block_size = 256;
		while( true ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $product_id ) {
				$products[ $product_id ] = get_the_title( $product_id );
			}
			$offset += $block_size;
		}
		return $products;
	}

	/**
	 * create_tool.
	 *
	 * @version 2.4.0
	 * @since   2.1.0
	 */
	function create_tool() {
		$products = $this->get_products();

		// Action
		if ( isset( $_POST['alg_custom_price_label_bulk_editor_submit'] ) ) {
			foreach ( $products as $product_id => $product_title ) {
				foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
					foreach ( alg_get_options_section_variations_main() as $options_section_variation => $options_section_variation_title ) {
						$option_name = alg_get_options_group_name() . $options_section . $options_section_variation;
						if ( '_text' === $options_section_variation ) {
							update_post_meta( $product_id, '_' . $option_name,  $_POST[ $option_name . '_' . $product_id ] );
						} else {
							if ( isset( $_POST[ $option_name . '_' . $product_id ] ) ) {
								update_post_meta( $product_id, '_' . $option_name,  $_POST[ $option_name . '_' . $product_id ] );
							} else {
								update_post_meta( $product_id, '_' . $option_name,  'off' );
							}
						}
					}
				}
			}
		}

		// Tool
		$html = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . __( 'Custom Price Label Bulk Editor Tool', 'woocommerce-custom-price-label' ) . '</h1>';
		$html .= '<form method="post" action="' . $_SERVER['REQUEST_URI'] . '">';
		$table_data = array();
		$table_header = array( __( 'ID', 'woocommerce-custom-price-label' ), __( 'Title', 'woocommerce-custom-price-label' ) );
		foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
			$table_header[] = '';
			$options_section_title .= apply_filters( 'alg_wc_custom_price_labels', ( '' != $options_section && '_before' != $options_section ? '<br><span style="font-size:x-small;">' . wccpl_get_pro_message() . '</span>' : '' ), 'settings' );
			$table_header[] = $options_section_title;
		}
		$table_data[] = $table_header;
		foreach ( $products as $product_id => $product_title ) {
			$table_row = array( $product_id, $product_title );
			foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
				$input_text = '';
				$input_checkbox = '';
				$readonly_if_no_plus = apply_filters( 'alg_wc_custom_price_labels', ( '' != $options_section && '_before' != $options_section ? 'readonly' : '' ), 'settings' );
				$disabled_if_no_plus = apply_filters( 'alg_wc_custom_price_labels', ( '' != $options_section && '_before' != $options_section ? 'disabled' : '' ), 'settings' );
				foreach ( alg_get_options_section_variations_main() as $options_section_variation => $options_section_variation_title ) {
					$option_name = alg_get_options_group_name() . $options_section . $options_section_variation;
					if ( '_text' === $options_section_variation ) {
						$input_text = '<textarea name="' . $option_name . '_' . $product_id . '" ' . $readonly_if_no_plus . '>' . get_post_meta( $product_id, '_' . $option_name, true ) . '</textarea>';
					} else {
						$value = get_post_meta( $product_id, '_' . $option_name, true );
						$value = ( 'on' === $value || 'yes' === $value ) ? 1 : 0;
						$input_checkbox = '<input type="checkbox" name="' . $option_name . '_' . $product_id . '" ' . checked( $value, 1, false ) . ' ' . $disabled_if_no_plus . '>';
					}
				}
				$table_row[] = $input_checkbox;
				$table_row[] = $input_text;
			}
			$table_data[] = $table_row;
		}
		$html .= $products_table = alg_get_table_html( $table_data, array( 'table_class' => 'widefat striped', 'columns_styles' => array( 'width:5%;', 'width:15%;', 'width:5%;text-align:right;', 'width:15%;', 'width:5%;text-align:right;', 'width:15%;', 'width:5%;text-align:right;', 'width:15%;', 'width:5%;text-align:right;', 'width:15%;', ) ) );
		$html .= '<p><input type="submit" class="button button-primary button-large" name="alg_custom_price_label_bulk_editor_submit" value="' . __( 'Save Price Labels', 'woocommerce-custom-price-label' ) . '"></p>';
		$html .= '</form>';
		$global_settings_url = admin_url( 'admin.php?page=wc-settings&tab=custom_price_label' );
		$html .= '<p>' . __( 'Global price labels can be set in', 'woocommerce-custom-price-label' ) . ' ' . '<a href="' . $global_settings_url . '">' . __( 'WooCommerce > Settings > Custom Price Labels', 'woocommerce-custom-price-label' ) . '</a>' . '</p>';
		$html .= '</div>';
		echo $html;
	}
}

endif;

return new WC_Custom_Price_Label_Bulk_Editor();
