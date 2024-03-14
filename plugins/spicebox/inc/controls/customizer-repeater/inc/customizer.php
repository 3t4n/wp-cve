<?php
function Spicebox_Repeater_register( $wp_customize ) {

	$Spicebox_Repeater_path = SPICEB_PLUGIN_DIR . '/inc/controls/customizer-repeater/class/customizer-repeater-control.php';
	if( file_exists( $Spicebox_Repeater_path ) ){
		require_once( $Spicebox_Repeater_path );
	}

	$Spicebox_limit_Repeater_path = SPICEB_PLUGIN_DIR . '/inc/controls/customizer-repeater/class/customizer-limit-repeater-control.php';
	if( file_exists( $Spicebox_limit_Repeater_path ) ){
		require_once( $Spicebox_limit_Repeater_path );
	}
}
add_action( 'customize_register', 'Spicebox_Repeater_register' );

function Spicebox_Repeater_sanitize($input){
	$spicepress_input_decoded = json_decode($input,true);

	if(!empty($spicepress_input_decoded)) {
		foreach ($spicepress_input_decoded as $boxk => $box ){
			foreach ($box as $key => $value){

					$spicepress_input_decoded[$boxk][$key] = wp_kses_post( force_balance_tags( $value ) );

			}
		}
		return json_encode($spicepress_input_decoded);
	}
	return $input;
}