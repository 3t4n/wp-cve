<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( !isset($_GET['post']) || !(int) $_GET['post'] ) return array();

$defaultTheme = 0;

if( 
	isset($_GET['post']) && 
	get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 ) == (int) $_GET['post']
){
	$defaultTheme = 1;
}


function wpape_gallery_fields_themes_default_assets(){
	wp_register_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-themes-default', WPAPE_GALLERY_FIELDS_URL.'asset/metabox/themes_default/script.js', array('jquery'), WPAPE_GALLERY_VERSION, true);
	$translation_array = array(
		'messageOk' 	=> 	__( 'Saved!', 'gallery-images-ape'),
		'messageError' 	=> 	__( 'Save error. Please try again later.', 'gallery-images-ape')
	);
	wp_localize_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-themes-default', 'twojGalleryThemesDefaultTr', $translation_array );
	wp_enqueue_script( WPAPE_GALLERY_ASSETS_PREFIX.'-field-type-themes-default' );
}

add_action( 'in_admin_header',  'wpape_gallery_fields_themes_default_assets' );

return array(
	'active' => true,
	'order' => 8,
	'settings' => array(
		'id' => 'wpape_gallery_theme_default',
		'title' => ( $defaultTheme ? 'Default Theme' : 'Default Theme'),
		'screen' => array(WPAPE_GALLERY_THEME_POST),
		'context' => 'side',
		'priority' => 'default',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'content' => 'template::content/themes_default/'.( $defaultTheme ? 'current' : 'content'),
);
