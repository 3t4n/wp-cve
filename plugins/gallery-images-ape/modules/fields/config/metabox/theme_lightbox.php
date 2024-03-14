<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( apeGalleryHelper::compareVersion('2.1') ){
	return include WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/theme_lightbox.ext.php';
}

return array(
	'active' => true,
	'order' => 11,
	'settings' => array(
		'id' => 'wpape-theme-lightbox',
		'title' => __('Ape Gallery Lightbox Settings', 'gallery-images-ape'),
		'screen' => array(  WPAPE_GALLERY_THEME_POST ),
		'context' => 'normal',
		'priority' => 'default',
		'for' => array( 'type' => 'grid' ),
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,	
	'fields' => array(
		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'lightboxColor',
			'default' => '#f3f3f3',
			'label' => __('Text Color', 'gallery-images-ape'),
			'options' => array(
				'column'		=> '12',
				'columnWrap'	=> '12  medium-6',
			),
		),

		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'lightboxBackground',
			'default' => 'rgba(11, 11, 11, 0.8)',
			'label' => __('Background Color', 'gallery-images-ape'),
			'contentAfter' => apeGalleryHelper::getAddonButton( __('Add Extended Lightbox ( Social, Swipe, Arrows ) Add-on', 'gallery-images-ape')),
			'options' => array(
				'alpha'		=> true,
				'column'	=> '12',
				'columnWrap'=> '12  medium-6',
			),
		),

		array(
			'type' => 'hidden',
			'view' => 'default',
			'name' => 'lightboxSwipe',
			'default' => '1',
		),

		array(
			'type' => 'hidden',
			'view' => 'default',
			'name' => 'arrows',
			'default' => '0',
		),

	),
);
