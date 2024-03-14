<?php 
/* Taken from v2 to allow custom values to be added in theme.json */
if ( !function_exists( 'areoi2_get_theme_json' ) ) {
	function areoi2_get_theme_json()
	{
		$json 	= null;
    	$theme_json_path 	= get_stylesheet_directory() . '/theme.json';

    	if ( file_exists( $theme_json_path ) ) $json = json_decode( file_get_contents( $theme_json_path ), true );
    	
    	if ( !$json || !isset( $json['settings']['custom']['all-bootstrap-blocks'] ) ) return false;

    	return $json['settings']['custom']['all-bootstrap-blocks'];
	}
}

if ( !function_exists( 'areoi2_has_theme_json' ) ) {
	function areoi2_has_theme_json()
	{
		return areoi2_get_theme_json();
	}
}

if ( !function_exists( 'areoi2_get_theme_json_value' ) ) {
	function areoi2_get_theme_json_value( $key )
	{
		$json = areoi2_get_theme_json();

		if ( isset( $json[$key] ) ) return $json[$key];

    	return 'not-exist';
	}
}

if ( !function_exists( 'areoi2_has_theme_json_value' ) ) {
	function areoi2_has_theme_json_value( $key )
	{
    	if ( areoi2_get_theme_json_value( $key ) !== 'not-exist' ) return true;

    	return false;
	}
}

if ( !function_exists( 'areoi2_get_option' ) ) {
	function areoi2_get_option( $key, $default = null )
	{		
		if ( areoi2_has_theme_json() && areoi2_has_theme_json_value( $key ) ) {
			return areoi2_get_theme_json_value( $key );
		}

		return get_option( $key, $default );
	}
}

if ( !function_exists( 'areoi2_get_btn_styles' ) ) {
	function areoi2_get_btn_styles()
	{		
		$styles = array(
            array( 'label' => 'Default', 'value' => 'btn-primary' ),
            array( 'label' => 'Primary', 'value' => 'btn-primary' ),
            array( 'label' => 'Primary (Outline)', 'value' => 'btn-outline-primary' ),
            array( 'label' => 'Secondary', 'value' => 'btn-secondary' ),
            array( 'label' => 'Secondary (Outline)', 'value' => 'btn-outline-secondary' ),
            array( 'label' => 'Success', 'value' => 'btn-success' ),
            array( 'label' => 'Success (Outline)', 'value' => 'btn-outline-success' ),
            array( 'label' => 'Danger', 'value' => 'btn-danger' ),
            array( 'label' => 'Danger (Outline)', 'value' => 'btn-outline-danger' ),
            array( 'label' => 'Warning', 'value' => 'btn-warning' ),
            array( 'label' => 'Warning (Outline)', 'value' => 'btn-outline-warning' ),
            array( 'label' => 'Info', 'value' => 'btn-info' ),
            array( 'label' => 'Info (Outline)', 'value' => 'btn-outline-info' ),
            array( 'label' => 'Light', 'value' => 'btn-light' ),
            array( 'label' => 'Light (Outline)', 'value' => 'btn-outline-light' ),
            array( 'label' => 'Dark', 'value' => 'btn-dark' ),
            array( 'label' => 'Dark (Outline)', 'value' => 'btn-outline-dark' ),
            array( 'label' => 'Link', 'value' => 'btn-link' ),
		);

		$styles = apply_filters( 'areoi_btn_styles', $styles );

		return $styles;
	}
}