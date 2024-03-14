<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( apeGalleryHelper::compareVersion('2.1') ){
	return include WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/theme_polaroid.ext.php';
}


return array(
	'active' => true,
	'order' => 20,
	'settings' => array(
		'id' => 'wpape-theme-polaroid',
		'title' => __('Ape Gallery Polaroid Settings', 'gallery-images-ape'),
		'screen' => array(  WPAPE_GALLERY_THEME_POST ),
		'for' => array( 'type' => 'grid' ),
		'context' => 'normal',
		'priority' => 'default',
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'style' => null,
	'fields' => array(
		
		array(
			'type' => 'checkbox',
			'view' => 'switch/c2',
			'name' => 'polaroidOn',
			/*'is_lock' => true,*/
			'label' => __('Polaroid', 'gallery-images-ape'),
			'default' => false,
			'description' => null,
			'attributes' => array(),
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
			"dependents" => array(
				1 => array(
					'show' => array('#div-polaroid-options-block'),
				),
				0 => array(
					'hide' => array('#div-polaroid-options-block'),
				),
			),
			'contentAfterBlock' => '<div id="div-polaroid-options-block">',
		),

		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'polaroidBackground',
			'label'=> __('Background', 'gallery-images-ape'),
			'default' => '#ffffff',
			'options' => array(
				'alpha'			=> true,
				'column'		=> '12',
				'columnWrap'	=> '12  medium-6',
			),
		),


		
		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'polaroidAlign',
			'default' => 'center',
			'label' => __('Align', 'gallery-images-ape'),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
				'values' => array(					
					'left' 		=> __( 'Left', 'gallery-images-ape' ),
					'center' 	=> __( 'Center', 'gallery-images-ape' ),
					'right' 	=> __( 'Right', 'gallery-images-ape' ),
				),
			),
			'contentAfterBlock' => '</div>',
		),

		array(
			'type' => 'hidden',
			'view' => 'default',
			'name' => 'polaroidSource',
			'default' => 'title',
		),

		array(
			'type' => 'html',
			'view' => 'raw',
			'content' => apeGalleryHelper::getAddonButton( __('Add Extended Polaroid Add-on', 'gallery-images-ape')),
		),
	),
);
