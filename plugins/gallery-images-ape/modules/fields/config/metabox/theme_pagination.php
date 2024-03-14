<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( apeGalleryHelper::compareVersion('2.1') ){
	return include WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/theme_pagination.ext.php';
}

return array(
	'active' => true,
	'order' => 20,
	'settings' => array(
		'id' => 'wpape-theme-pagination',
		'title' => __('Ape Gallery Pagination Settings', 'gallery-images-ape'),
		'screen' => array(  WPAPE_GALLERY_THEME_POST ),
		'context' => 'normal',
		'for' => array( 'type' => 'grid' ),
		'priority' => 'default',
	),
	'view' => 'default',
	'state' => 'open',
	'fields' => array(
		
		array(
			'type' => 'checkbox',
			'view' => 'switch/c2',
			'name' => 'lazyLoad',
			'label' => __('Pre-load', 'gallery-images-ape'),
			'default' => true,
		),

		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'boxesToLoadStart',
			'label'=> __( 'Start Images', 'gallery-images-ape' ),
			'default' => '12',
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
			),
		),

		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'loadingBgColor',
			'default' => 'rgba(255,255,255,1)',
			'label' => __('Background Color', 'gallery-images-ape'),
			'description' => __( 'this is background color for the image. You\'ll see images filled by this color during images pre-load', 'gallery-images-ape'),
			'options' => array(
				'alpha'			=> true,
				'column'		=> '12',
				'columnWrap'	=> '12  medium-6',
			),
		),

		array(
			'type' 		=> 'hidden',
			'view' 		=> 'default',
			'name' 		=> 'boxesToLoad',
			'default' 	=> '8',
		),
		array(
			'type' 		=> 'hidden',
			'view' 		=> 'default',
			'name' 		=> 'LoadingWord',
			'default' 	=> __( 'Gallery images loading', 'gallery-images-ape' ),
		),
		array(
			'type' 		=> 'hidden',
			'view' 		=> 'default',
			'name' 		=> 'loadMoreWord',
			'default' 	=> __( 'More images', 'gallery-images-ape' ),
		),
		array(
			'type' 		=> 'hidden',
			'view' 		=> 'default',
			'name' 		=> 'noMoreEntriesWord',
			'default' 	=> __( 'No images', 'gallery-images-ape' ),
		),

		array(
			'type' => 'html',
			'view' => 'raw',
			'content' => apeGalleryHelper::getAddonButton( __('Add Extended Pagination Add-on', 'gallery-images-ape')),
		),
		
	),
);
