<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Function which is shared by Gutenberg Blocks and Shortcodes to generate output for a Tithe Form.
 *
 * @since    1.0.0
 * @param    array $tithe_form_vars Any values in this array will override the default values in church_tithe_wp_tithe_form_vars().
 * @return   string
 */
function church_tithe_wp_generate_output_for_tithe_form( $tithe_form_vars ) {

	global $church_tithe_wp_forms_on_page;
	$form_number = is_array( $church_tithe_wp_forms_on_page ) ? count( $church_tithe_wp_forms_on_page ) + 1 : 1;

	$dynamic_tithe_form_vars = church_tithe_wp_dynamic_tithe_form_vars();

	// Loop through each tithe form variable and remove any values that are dynamic.
	foreach ( $dynamic_tithe_form_vars as $dynamic_tithe_form_var_key => $dynamic_tithe_form_var_value ) {
		unset( $tithe_form_vars[ $dynamic_tithe_form_var_key ] );
	}

	// Add this Form's JSON to the global array so we can output it into the footer.
	$church_tithe_wp_forms_on_page[ $form_number ] = array(
		'unique_vars'  => $tithe_form_vars,
		'dynamic_vars' => $dynamic_tithe_form_vars,
	);

	// If this tithe form is set to open "in_modal", output the actual React Component in the footer, and only the button/link to open it here.
	if (
		! empty( $tithe_form_vars['mode'] ) &&
		'form' !== $tithe_form_vars['mode'] &&
		'in_modal' === $tithe_form_vars['open_style']
	) {
		if ( 'text_link' === $tithe_form_vars['mode'] ) {
			return '<a class="church-tithe-wp-a-tag church-tithe-wp-modal-link" onclick="church_tithe_wp_set_modal_to_open( ' . esc_attr( $form_number ) . ' )">' . $tithe_form_vars['strings']['link_text'] . '</a>';
		}

		if ( 'button' === $tithe_form_vars['mode'] ) {
				return '<button class="button church-tithe-wp-button" onclick="church_tithe_wp_set_modal_to_open( ' . esc_attr( $form_number ) . ' )">' . $tithe_form_vars['strings']['link_text'] . '</button>';
		}
	}

	// Otherwise only output the element without an ID.
	return '<span id="church-tithe-wp-element-' . esc_attr( $form_number ) . '" class="church-tithe-wp-element" church-tithe-wp-form-number="' . esc_attr( $form_number ) . '"></span>';

}

/**
 * Output Church Tithe WP JSON for a Tithe Form into the footer in a hidden div
 *
 * @since    1.0.0
 * @global   array $church_tithe_wp_forms_on_page The JSON for each Tithe form on the page.
 * @return   void
 */
function church_tithe_wp_json_in_footer() {

	global $church_tithe_wp_forms_on_page;

	if ( empty( $church_tithe_wp_forms_on_page ) ) {
		return;
	}

	$allowed_tags                                  = wp_kses_allowed_html( 'post' );
	$allowed_tags['div']['hidden']                 = true;
	$allowed_tags['div']['church-tithe-wp-form-number'] = true;

	foreach ( $church_tithe_wp_forms_on_page as $form_number => $form_json ) {
		echo wp_kses( '<div hidden style="display:none;" id="church-tithe-wp-element-unique-vars-json-' . esc_attr( $form_number ) . '" class="church-tithe-wp-element-json" church-tithe-wp-form-number="' . esc_attr( $form_number ) . '">' . esc_textarea( wp_json_encode( $form_json['unique_vars'] ) ) . '</div>', $allowed_tags );
		echo wp_kses( '<div hidden style="display:none;" id="church-tithe-wp-element-dynamic-vars-json-' . esc_attr( $form_number ) . '" class="church-tithe-wp-element-json" church-tithe-wp-form-number="' . esc_attr( $form_number ) . '">' . esc_textarea( wp_json_encode( $form_json['dynamic_vars'] ) ) . '</div>', $allowed_tags );

		// If this tithe form is set to open "in_modal", output the actual React Component here in the footer, where it sits silently until opened.
		if (
			! empty( $form_json['unique_vars']['mode'] ) &&
			'form' !== $form_json['unique_vars']['mode'] &&
			'in_modal' === $form_json['unique_vars']['open_style']
		) {
			echo '<span id="church-tithe-wp-element-' . esc_attr( $form_number ) . '" class="church-tithe-wp-element" church-tithe-wp-form-number="' . esc_attr( $form_number ) . '"></span>';
		}
	}

}
add_action( 'wp_footer', 'church_tithe_wp_json_in_footer' );
