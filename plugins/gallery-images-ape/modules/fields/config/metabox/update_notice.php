<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( WPAPE_GALLERY_PREMIUM == false ) return array();
if( apeGalleryHelper::compareVersion('2.1') ) return array();

return array(
	'active' => true,
	'order' => 1,
	'settings' => array(
		'id' => 'wpape_gallery_update_notice',
		'title' => __('Update license key file', 'gallery-images-ape'),
		'screen' => array( WPAPE_GALLERY_POST, WPAPE_GALLERY_THEME_POST ),
		'context' => 'normal',
		'priority' => 'high',
	),
	'view' => 'default',
	'state' => 'open',	
	'content' => sprintf( 
		'<br/><div class="label warning large-12 columns"><h6 style="margin-top: 9px;"><strong>%s</strong><br/>%s</h6></div>%s',
		__('Please update license key to the latest version.', 'gallery-images-ape'),
		__('With latest version of the license key you get access to the full list of the latest functionality of the plugin.', 'gallery-images-ape'),
		apeGalleryHelper::getUpdateButton( __('Add new version license key', 'gallery-images-ape') )
	)
);
