<?php
/**
 * used to create translations for tinymce
 * see: https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_external_languages
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '_WP_Editors' ) ) {
	require( ABSPATH . WPINC . '/class-wp-editor.php' );
}

function rve_tinymce_plugin_translation() {
	$strings    = array(
		'windowTitle' => __( 'Video details', 'rve' ),
		'buttonText'  => __( 'Embed video', 'rve' ),
		'embedUrl'    => __( 'Embed URL', 'rve' ),
		'aspectRatio' => __( 'Aspect ratio', 'rve' ),
	);
	$locale     = _WP_Editors::$mce_locale;
	$translated = 'tinyMCE.addI18n("' . $locale . '.rve_tinymce_plugin", ' . json_encode( $strings ) . ");\n";

	return $translated;
}

$strings = rve_tinymce_plugin_translation();