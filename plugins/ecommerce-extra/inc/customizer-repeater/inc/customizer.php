<?php
function ecommerce_extra_customizer_repeater_register( $wp_customize ) {
	require( ecommerce_extra_dir_path .'inc/customizer-repeater/class/class-customizer-repeater.php' );

}
add_action( 'customize_register', 'ecommerce_extra_customizer_repeater_register' );

function ecommerce_extra_customizer_repeater_sanitize($input){
	$input_decoded = json_decode($input,true);   
	if(!empty($input_decoded)) {
		foreach ($input_decoded as $boxk => $box ){ 
	
			foreach ($box as $key => $value){

					$input_decoded[$boxk][$key] = wp_kses_post( force_balance_tags( $value ) );

			}
		}
		return json_encode($input_decoded);
	}
	return $input;
}
