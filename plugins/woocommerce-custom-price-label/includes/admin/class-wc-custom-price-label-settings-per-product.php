<?php
/**
 * WooCommerce Custom Price Label - Per Product Settings
 *
 * @version 2.4.3
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label_Settings_Per_Product' ) ) :

class WC_Custom_Price_Label_Settings_Per_Product {

	/**
	 * Constructor.
	 *
	 * @version 2.3.0
	 */
	function __construct() {
		if ( 'yes' === get_option( 'woocommerce_local_price_labels_enabled', 'yes') ) {
			add_action( 'add_meta_boxes',    array( $this, 'add_price_label_meta_box' ) );
			add_action( 'save_post_product', array( $this, 'save_custom_price_labels' ), PHP_INT_MAX, 2 );
		}
	}

	/**
	 * save_custom_price_labels.
	 *
	 * @version 2.3.0
	 */
	function save_custom_price_labels( $post_id, $post ) {
		if ( ! isset( $_POST['woocommerce_price_labels_save_post'] ) ) {
			return;
		}
		$disabled_options = get_option( 'woocommerce_custom_price_labels_disabled_options', '' );
		foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
			foreach ( alg_get_options_section_variations() as $options_section_variation => $options_section_variation_title ) {
				if ( ! empty( $disabled_options ) && in_array( $options_section_variation, $disabled_options ) ) {
					continue;
				}
				$option_name = alg_get_options_group_name() . $options_section . $options_section_variation;
				if ( isset( $_POST[ $option_name ] ) ) {
					update_post_meta( $post_id, '_' . $option_name, $_POST[ $option_name ] );
				} elseif ( in_array( $options_section_variation, array( '_roles_to_hide', '_roles_to_show', '_show_on', '_hide_on' ) ) ) {
					update_post_meta( $post_id, '_' . $option_name, '' );
				} elseif ( '_text' != $options_section_variation ) {
					update_post_meta( $post_id, '_' . $option_name, 'off' );
				}
			}
		}
		update_post_meta( $post_id, '_' . 'woocommerce_custom_price_labels_version', WCCPL()->version );
	}

	/*
	 * add_price_label_meta_box.
	 */
	function add_price_label_meta_box() {
		add_meta_box(
			'wc-custom-price-labels',
			__( 'Custom Price Labels', 'woocommerce-custom-price-label' ),
			array( $this, 'price_label_meta_box' ),
			'product',
			'normal',
			'high'
		);
	}

	/*
	 * price_label_meta_box.
	 *
	 * @version 2.4.3
	 * @todo    restyle output
	 */
	function price_label_meta_box() {

		$current_post_id = get_the_ID();

		$version = get_post_meta( $current_post_id, '_' . 'woocommerce_custom_price_labels_version', true );

		if ( version_compare( $version, '2.3.0', '<' ) ) {
			// Handle deprecated
			update_post_meta( $current_post_id, '_' . 'woocommerce_custom_price_labels_version', WCCPL()->version );
			$version = WCCPL()->version;
			foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
				$deprecated_hide_on_enabled_options = array();
				foreach ( alg_get_options_section_variations_deprecated() as $options_section_variation => $options_section_variation_title ) {
					$option_name = alg_get_options_group_name() . $options_section . $options_section_variation;
					if ( 'on' === get_post_meta( $current_post_id, '_' . $option_name, true ) ) {
						$deprecated_hide_on_enabled_options[] = str_replace( '_', '', $options_section_variation );
					}
					delete_post_meta( $current_post_id, '_' . $option_name );
				}
				if ( ! empty( $deprecated_hide_on_enabled_options ) ) {
					$option_name = alg_get_options_group_name() . $options_section . '_hide_on';
					update_post_meta( $current_post_id, '_' . $option_name, $deprecated_hide_on_enabled_options );
					echo $option_name;
				}
			}
		}

		$html = '';
		$html .= '<table class="widefat striped">';

		$html .= '<tr>';
		foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
			$html .= '<td style="width:25%;"><h4>' . $options_section_title . '</h4></td>';
		}
		$html .= '</tr>';

		$disabled_options = get_option( 'woocommerce_custom_price_labels_disabled_options', '' );

		$html .= '<tr>';
		foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
			$html .= '<td style="width:25%;">';
			$html .= '<ul>';
			foreach ( alg_get_options_section_variations() as $options_section_variation => $options_section_variation_title ) {
				if ( ! empty( $disabled_options ) && in_array( $options_section_variation, $disabled_options ) ) {
					continue;
				}
				$option_name = alg_get_options_group_name() . $options_section . $options_section_variation;
				if ( $options_section_variation == '_text' ) {
					$disabled_if_no_plus = apply_filters( 'alg_wc_custom_price_labels', ( '' != $options_section && '_before' != $options_section ? 'readonly' : '' ), 'settings' );
					$label_text = get_post_meta( $current_post_id, '_' . $option_name, true );
					$label_text = str_replace ( '"', '&quot;', $label_text );
					$html .= '<li>' . $options_section_variation_title
						. '<textarea style="width:95%;min-width:100px;height:100px;" ' . $disabled_if_no_plus . ' name="' . $option_name . '">'
						. $label_text . '</textarea></li>';
				} elseif ( in_array( $options_section_variation, array( '_roles_to_hide', '_roles_to_show', '_show_on', '_hide_on' ) ) ) {
					$disabled_if_no_plus = apply_filters( 'alg_wc_custom_price_labels', ( '' != $options_section && '_before' != $options_section ? 'disabled' : '' ), 'settings' );
					$select_options_html = '';
					if ( '_hide_on' === $options_section_variation || '_show_on' === $options_section_variation ) {
						$select_options = alg_get_options_section_variations_visibility_options();
						$tooltip = ( '_hide_on' === $options_section_variation ) ?
							__( 'If set - will hide price label for selected options.', 'woocommerce-custom-price-label' ) :
							__( 'If set - will show price label only for selected options.', 'woocommerce-custom-price-label' );
						$tooltip .= ' ' . __( 'Leave empty to show for all options.', 'woocommerce-custom-price-label' );
						$tooltip .= ' ' . __( 'Hold Control key to select multiple options.', 'woocommerce-custom-price-label' );
					} else { // '_roles_to_hide' || '_roles_to_show'
						$select_options = alg_get_user_roles_options();
						$tooltip = ( '_roles_to_hide' === $options_section_variation ) ?
							__( 'If set - will hide price label for selected user roles.', 'woocommerce-custom-price-label' ) :
							__( 'If set - will show price label only for selected user roles.', 'woocommerce-custom-price-label' );
						$tooltip .= ' ' . __( 'Leave empty to show to all users.', 'woocommerce-custom-price-label' );
						$tooltip .= ' ' . __( 'Hold Control key to select multiple roles.', 'woocommerce-custom-price-label' );
					}
					foreach ( $select_options as $select_option_id => $select_option_name ) {
						$selected = '';
						$option_value = get_post_meta( $current_post_id, '_' . $option_name, true );
						if ( is_array( $option_value ) ) {
							foreach ( $option_value as $single_option_value ) {
								if ( '' != ( $selected = selected( $single_option_value, $select_option_id, false ) ) ) {
									break;
								}
							}
						}
						$select_options_html .= '<option value="' . $select_option_id . '" ' . $selected . '>' . $select_option_name . '</option>';
					}
					$tooltip = wc_help_tip( $tooltip, true );
					$html .= '<li><h4>' . $options_section_variation_title . $tooltip . '</h4><select style="width:100%;height:100px;" multiple '
						. $disabled_if_no_plus . ' name="' . $option_name . '[]" id="' . $option_name . '">'
						. $select_options_html . '</select></li>';
				} else { // checkboxes
					$disabled_if_no_plus = apply_filters( 'alg_wc_custom_price_labels', ( '' != $options_section && '_before' != $options_section ? 'disabled' : '' ), 'settings' );
					$html .= '<li><input class="checkbox" type="checkbox" '
						. $disabled_if_no_plus . ' name="' . $option_name . '" id="' . $option_name . '" '
						. checked( get_post_meta( $current_post_id, '_' . $option_name, true ), 'on', false ) . ' /> '
						. $options_section_variation_title . '</li>';
				}
			}
			$html .= '</ul>';
			$html .= '</td>';
		}
		$html .= '</tr>';

		if ( 'custom_price_labels' === apply_filters( 'alg_wc_custom_price_labels', 'custom_price_labels', 'settings' ) ) {
			$html .= '<tr>';
			foreach ( alg_get_options_sections() as $options_section => $options_section_title ) {
				$disabled_if_no_plus = ( '' != $options_section && '_before' != $options_section ) ? '<em>' . wccpl_get_pro_message() . '</em>' : '';
				$html .= '<td style="width:25%;">' . $disabled_if_no_plus . '</td>';
			}
			$html .= '</tr>';
		}

		$html .= '</table>';
		$html .= '<input type="hidden" name="woocommerce_price_labels_save_post" value="woocommerce_price_labels_save_post">';
		if ( isset( $_GET['alg_debug'] ) && '' != $version ) {
			$html .= '<p style="font-size:x-small;font-style:italic;color:gray;">v.' . $version . '</p>';
		}
		echo $html;
	}

}

endif;

return new WC_Custom_Price_Label_Settings_Per_Product();
