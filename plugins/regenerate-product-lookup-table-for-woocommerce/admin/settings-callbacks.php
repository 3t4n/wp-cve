<?php

// No direct access to file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Register admin section
 */
function smnwcrpl_callback_section_admin() {
	echo '<p>' . esc_html__( 'These settings enables you to customize Cron settings and change cron frequency.',
			'smnwcrpl' ) . '</p>';
}


/**
 * Generate select field in admin
 *
 * @param $args
 */
function smnwcrpl_callback_field_select( $args ) {
	$options = get_option( 'smnwcrpl_options', smnwcrpl_options_default() );

	$id    = isset( $args['id'] ) ? $args['id'] : '';
	$label = isset( $args['label'] ) ? $args['label'] : '';

	$selected_option = isset( $options[ $id ] ) ? sanitize_text_field( $options[ $id ] ) : '';

	$select_options = [
		'hourly'     => esc_html__( 'Hourly', 'smnwcrpl' ),
		'twicedaily' => esc_html__( 'Twice Daily', 'smnwcrpl' ),
		'daily'      => esc_html__( 'Daily', 'smnwcrpl' ),
		'weekly'     => esc_html__( 'Weekly', 'smnwcrpl' ),
	];

	echo '<select id="smnwcrpl_options_' . $id . '" name="smnwcrpl_options[' . $id . ']">';

	foreach ( $select_options as $value => $option ) {
		$selected = selected( $selected_option === $value, true, false );
		echo '<option value="' . $value . '"' . $selected . '>' . __( $option, 'smnwcrpl' ) . '</option>';
	}

	echo '</select><label for="smnwcrpl_options_' . $id . '">' . __( $label, 'smnwcrpl' ) . '</label>';
}