<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

return array(
	'active' => true,
	'order' => 20,
	'settings' => array(
		'id' => 'wpape-theme-hover',
		'title' => __('Ape Gallery Hover Settings', 'gallery-images-ape'),
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
			'name' => 'thumbClick',
			'label' => __('Click event', 'gallery-images-ape'),
			'default' => true,
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
		),

		array(
			'type' => 'checkbox',
			'view' => 'switch/c2',
			'name' => 'hover',
			'label' => __('Hover mode', 'gallery-images-ape'),
			'default' => true,
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
			"dependents" => array(
				1 => array(
					'show' => array('#div-hover-options-block'),
				),
				0 => array(
					'hide' => array('#div-hover-options-block'),
				),
			),
			'contentAfterBlock' => '<div id="div-hover-options-block">',
		),

		/* start hover options */

		array(
			'type' => 'text',
			'view' => 'color',
			'name' => 'background',
			'default' => 'rgba(7, 7, 7, 0.5)',
			'label' => __('Fill Color', 'gallery-images-ape'),
			'options' => array(
				'alpha'			=> true,
				'column'		=> '12',
				'columnWrap'	=> '12  medium-6',
			),
		),
		
		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'overlayEffect',
			'default' => 'direction-aware-fade',
			'label' => __('Animation', 'gallery-images-ape'),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
				'values' => array(					
					 'push-up' 				=> __( 'push-up', 'gallery-images-ape' ),
					 'push-down'	 		=> __( 'push-down', 'gallery-images-ape' ),
					 'push-up-100%' 		=> __( 'push-up-100%', 'gallery-images-ape' ),
					 'push-down-100%' 		=> __( 'push-down-100%', 'gallery-images-ape' ),
					 'reveal-top'			=> __( 'reveal-top', 'gallery-images-ape' ),
					 'reveal-bottom' 		=> __( 'reveal-bottom', 'gallery-images-ape' ),
					 'reveal-top-100%' 		=> __( 'reveal-top-100%', 'gallery-images-ape' ),
					 'reveal-bottom-100%' 	=> __( 'reveal-bottom-100%', 'gallery-images-ape' ),
					 'direction-aware' 		=> __( 'direction-aware', 'gallery-images-ape' ),
					 'direction-aware-fade' => __( 'direction-aware-fade', 'gallery-images-ape' ),
					 'direction-right' 		=> __( 'direction-right', 'gallery-images-ape' ),
					 'direction-left' 		=> __( 'direction-left', 'gallery-images-ape' ),
					 'direction-top' 		=> __( 'direction-top', 'gallery-images-ape' ),
					 'direction-bottom' 	=> __( 'direction-bottom', 'gallery-images-ape' ),
					 'fade' 				=> __( 'fade', 'gallery-images-ape' )
				),
			),
		),

		array(
			'type' => 'html',
			'view' => 'default',
			'content' => '<hr />',
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'showTitle',
			'label' => __('Show Title', 'gallery-images-ape'),
			'fields' => include( WPAPE_GALLERY_FIELDS_SUB_FIELDS.'font-title.php' ),
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'showDesc',
			'label' => __('Show Description', 'gallery-images-ape'),
			'fields' => include( WPAPE_GALLERY_FIELDS_SUB_FIELDS.'font-desc.php' ),
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'linkIcon',
			'label' => __('Show Link Button', 'gallery-images-ape'),
			'fields' => include( WPAPE_GALLERY_FIELDS_SUB_FIELDS.'link-icon.php' ),
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'zoomIcon',
			'label' => __('Show Zoom Button', 'gallery-images-ape'),
			'fields' => include( WPAPE_GALLERY_FIELDS_SUB_FIELDS.'zoom-icon.php' ),
		),

		array(
			'type' 		=> 'html',
			'view' 		=> 'raw',
			'options'   => array(
				'content' 	=> '</div>',
			),
		),

	),
);
