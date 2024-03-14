<?php

/* --------------------------------------------------------- */
/* !Register the translated settings - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_register_translate_settings') ) {
function mtphr_members_register_translate_settings( $settings ) {

	// Reverse the array, strictly for visual arrangement
	$settings = array_reverse( $settings );
	
	// Make sure wpml is active
	if( function_exists('icl_register_string') ) {

		foreach( $settings as $a=>$setting ) {
			if( is_array($setting) ) {
				foreach( $setting as $e=>$set ) {
					icl_register_string( 'mtphr-members', $a.'_'.$e, $set );
				}
			} else {
				icl_register_string( 'mtphr-members', $a, $setting );
			}
		}
	}
}
}

/* --------------------------------------------------------- */
/* !Render translated settings - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_members_translate_settings') ) {
function mtphr_members_translate_settings( $settings ) {
	
	// Make sure wpml is active
	if( function_exists('icl_t') ) {

		$translated = array();
		if( is_array($settings) && count($settings) > 0 ) {
			foreach( $settings as $a=>$setting ) {
				if( is_array($setting) ) {
					$trans = array();
					foreach( $setting as $e=>$set ) {
						$trans[$e] = icl_t( 'mtphr-members', $a.'_'.$e, $set );
					}
					$translated[$a] = $trans;
				} else {
					$translated[$a] = icl_t( 'mtphr-members', $a, $setting );
				}
			}
		}
		
		return $translated;
	}
	
	return $settings;	
}
}