<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( '_WP_Editors' ) ) {
	require( ABSPATH . WPINC . '/class-wp-editor.php' );
}

function dropdowncontent_tinymce_plugin_translation() {
	$strings = array(
		'insertadropdown' => __( 'Insert a Dropdown', 'dropdowncontent' ),
		'insertacontentdropdown' => __( 'Insert a content dropdown', 'dropdowncontent' ),
		'selectelementname' => __( 'SELECT element name', 'dropdowncontent' ),
		'customcssclassnames' => __( 'Custom CSS class name(s)', 'dropdowncontent' ),
		'rememberdefault' => __( 'Remember that you can add default=true to a single<br>dropdown-option shortcode if you want something<br>other than the first option to be the default.', 'dropdowncontent' )
	);
	$locale = _WP_Editors::$mce_locale;
	$translated = 'tinyMCE.addI18n("' . $locale . '.dropdowncontent", ' . json_encode( $strings ) . ");\n";

	return $translated;
}

$strings = dropdowncontent_tinymce_plugin_translation();
