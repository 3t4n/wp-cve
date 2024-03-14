<?php
/**
 * Register customizer repeater.
 */
function amigo_customizer_repeater_register() {
	require_once( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-repeater-control/class/class-customizer-repeater.php' );
}
add_action( 'customize_register', 'amigo_customizer_repeater_register' );

/**
 * Sanitization function.
 *
 * @param string $input Control input.
 *
 * @return string
 */
function amigo_repeater_sanitize( $input ) {
	$input_decoded = json_decode($input,true);
	if(!empty($input_decoded)) {
		foreach ($input_decoded as $content => $unit ){
			foreach ($unit as $key => $value){
				switch ( $key ) {
					case 'icon_value':
					$input_decoded[$content][$key] = $value;
					break;					
					case 'link':
					$input_decoded[$content][$key] = esc_url_raw( $value );
					break;
					default:
					$input_decoded[$content][$key] = wp_kses_post( force_balance_tags( $value ) );
				}
			}
		}
		return json_encode($input_decoded);
	}
	return $input;
}
