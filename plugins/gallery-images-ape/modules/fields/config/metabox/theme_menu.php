<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( apeGalleryHelper::compareVersion('2.1') ){
	return include WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/theme_menu.ext.php';
}

return array(
	'active' => true,
	'order' => 20,
	'settings' => array(
		'id' => 'wpape-theme-menu',
		'title' => __('Ape Gallery Menu Settings', 'gallery-images-ape'),
		'screen' => array(  WPAPE_GALLERY_THEME_POST ),
		'context' => 'normal',
		'priority' => 'default',
		'for' => array( 'type' => 'grid' ),
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'fields' => array(
		
		array(
			'type' => 'checkbox',
			'view' => 'switch/c2',
			'name' => 'menu',
			'label' => __('Menu', 'gallery-images-ape'),
			'default' => true,
			'attributes' => array(),
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
			"dependents" => array(
				1 => array(
					'show' => array('#div-menu-options-block'),
				),
				0 => array(
					'hide' => array('#div-menu-options-block'),
				),
			),
			'contentAfterBlock' => '<div id="div-menu-options-block">',
		),

		array(
			'type' => 'html',
			'view' => 'raw',
			'content' => apeGalleryHelper::getAddonButton( __('Add Extended Menu Add-on', 'gallery-images-ape')),
		),

		/* start menu options */
		
		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'menuSelfImages',
			'default' => '1',
			'label' => __('Output mode', 'gallery-images-ape'),
			'description' => __('when you enable this output mode gallery do not show images from current category', 'gallery-images-ape'),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
				'values' => array(					
					'0' => __( 'subcategory', 'gallery-images-ape' ),
					'1' => __( 'images + subcategory', 'gallery-images-ape' ),
				),
			),
		),

		array(
			'type' => 'html',
			'view' => 'default',
			'content' => '<hr />',
		),
		
		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'menuHome',
			'default' => 'label',
			'label' => __('Home button', 'gallery-images-ape'),
			'description' => __('when you enable this output mode gallery do not show images from current category', 'gallery-images-ape'),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
				'values' => array(					
					'hide' => __( 'Hide', 'gallery-images-ape' ),
					'label' => __( 'Label', 'gallery-images-ape' ),
					'icon' => __( 'Icon', 'gallery-images-ape' ),
					'iconlabel' => __( 'Icon & Label', 'gallery-images-ape' ),
				),
			),
			"dependents" => array(
				'hide' => array(
					'show' => array('#wrap-field-menu-options-block'),
					'hide' => array('#wrap-field-menu-option-root-icon-block', '#wrap-field-menu-option-root-label-block'),
				),
				'label' => array(
					'show' => array('#wrap-field-menu-option-root-label-block'),
					'hide' => array('#wrap-field-menu-option-root-icon-block'),
				),
				'icon' => array(
					'show' => array('#wrap-field-menu-option-root-icon-block'),
					'hide' => array('#wrap-field-menu-option-root-label-block'),
				),
				'iconlabel' => array(

					'show' => array('#wrap-field-menu-option-root-icon-block', '#wrap-field-menu-option-root-label-block'),
				),
			),
		),

		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'menuRootIcon',
			'id'   => 'menu-option-root-icon-block',
			'label'=> __( 'Home icon', 'gallery-images-ape' ),
			'default' => 'fa-home',
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
			),
		),

		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'menuRootLabel',
			'id'   => 'menu-option-root-label-block',
			'label'=> __( 'Home label', 'gallery-images-ape' ),
			'default' => __( 'Home', 'gallery-images-ape' ),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
			),
		),

		array(
			'type' => 'html',
			'view' => 'default',
			'content' => '<hr />',
		),

		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'buttonColor',
			'default' => 'blue',
			'label' => __('Color', 'gallery-images-ape'),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap'	=> '12',
				'values' => array(					
					'black' 	=> __( 'Black' , 'gallery-images-ape' ),
					'dark' 		=> __( 'Dark' , 'gallery-images-ape' ),
					'gray' 		=> __( 'Gray' , 'gallery-images-ape' ),
					'blue' 		=> __( 'Blue' , 'gallery-images-ape' ),
					'green' 	=> __( 'Green' , 'gallery-images-ape' ),
					'orange' 	=> __( 'Orange' , 'gallery-images-ape' ),
					'red' 		=> __( 'Red' , 'gallery-images-ape' ),
				),
			),
		),

		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'buttonType',
			'default' => 'normal',
			'label' => __('Rounds', 'gallery-images-ape'),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap' => '12',
				'values' => array(					
					'normal' 	=> __( 'Normal' , 	'gallery-images-ape' ),
					'rounded' 	=> __( 'Rounded' , 	'gallery-images-ape' ),
					'pill' 		=> __( 'Pill' , 	'gallery-images-ape' ),
					'circle' 	=> __( 'Circle ' , 	'gallery-images-ape' ),
					'box' 		=> __( 'Box ' , 	'gallery-images-ape' ),
					'square' 	=> __( 'Square ' , 	'gallery-images-ape' ),
				),
			),
		),

		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'buttonSize',
			'default' => 'normal',
			'label' => __('Size', 'gallery-images-ape'),
			'options' => array(
				'column' => '12 medium-6',
				'columnWrap' => '12',
				'values' => array(					
					'giant' 	=> __( 'Giant' , 	'gallery-images-ape' ),
					'jumbo' 	=> __( 'Jumbo' , 	'gallery-images-ape' ),
					'large' 	=> __( 'Large' , 	'gallery-images-ape' ),
					'normal' 	=> __( 'Normal' , 	'gallery-images-ape' ),
					'small' 	=> __( 'Small' , 	'gallery-images-ape' ),
					'tiny' 		=> __( 'Tiny ' , 	'gallery-images-ape' ),
				),
			),
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'buttonAlign',
			'default' => 'left',
			'label' => __('Align', 'gallery-images-ape'),
			'options' => array(
				'values' => array(
					array(
						'value' => 'left',
						'label' => __('Left', 'gallery-images-ape'),
					),
					array(
						'value' => 'center',
						'label' => __('Center', 'gallery-images-ape'),
					),
					array(
						'value' => 'right',
						'label' => __('Right', 'gallery-images-ape'),
					)
				),
			),
		),


		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'paddingMenu',
			'label' => __('Spacing', 'gallery-images-ape'),
			'fields' => array(
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'left',
					'default' => 5,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Left', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'bottom',
					'default' => 10,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Bottom', 'gallery-images-ape' )
					),
				),
			),
			'contentAfterBlock' => '</div>',
		),


		array(
			'type' 		=> 'hidden',
			'view' 		=> 'default',
			'name' 		=> 'buttonFill',
			'default' 	=> 'flat',
		),
		array(
			'type' 		=> 'hidden',
			'view' 		=> 'default',
			'name' 		=> 'buttonEffect',
			'default' 	=> '',
		),
		array(
			'type' 		=> 'hidden',
			'view' 		=> 'default',
			'name' 		=> 'buttonShadow',
			'default' 	=> '',
		),
			
	),
);
