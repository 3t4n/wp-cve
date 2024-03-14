<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

function wpape_gallery_fields_metabox_feedback_assets(){
	wp_enqueue_style ( WPAPE_GALLERY_ASSETS_PREFIX.'-metabox-type-feedback', WPAPE_GALLERY_FIELDS_URL.'asset/metabox/feedback/style.css', array( ), '' );
}
add_action( 'in_admin_header',  'wpape_gallery_fields_metabox_feedback_assets' );




return array(
	'active' => true,
	'order' => 8,
	'settings' => array(
		'id' => 'wape_gallery_feedback',
		'title' => __('Need Help?', 'gallery-images-ape'),
		'screen' => array(WPAPE_GALLERY_POST, WPAPE_GALLERY_THEME_POST),
		'context' => 'side',
		'priority' => 'low',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'content' => 'template::content/feedback/content',
);