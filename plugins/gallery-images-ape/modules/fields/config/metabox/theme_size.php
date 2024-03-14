<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

return array(
	'active' => true,
	'order' => 10,
	'settings' => array(
		'id' => 'wpape-theme-size',
		'title' => __('Ape Gallery Theme Settings', 'gallery-images-ape'),
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
			'type' => 'composite',
			'view' => 'default',
			'name' => 'width-size',
			'default' => null,
			'label' => __('Gallery Width', 'gallery-images-ape'),
			'description' => __( 'in our gallery we use smart algorithm for the size calculation. In Max Width option you define maximum allowed size of the gallery box', 'gallery-images-ape') ,

			'fields' => array(

				array(
					'type' => 'text',
					'view' => 'default/llc4',
					'name' => 'width',
					'default' => 100,
					'attributes' => array(),
					'label' => null,
				),
				array(
					'type' => 'checkbox',
					'view' => 'switch/c2',
					'name' => 'widthType',
					'default' => null,
					'attributes' => array(),
					'options' => array(
						'columnWrap' => 6,
						'size' => 'large',
						'onLabel' => 'px',
						'offLabel' => '%',
					),
				),
			)
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'paddingCustom',
			'label' => __('Padding', 'gallery-images-ape'),

			'fields' => array(
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'left',
					'default' => 0,
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
					'name' => 'right',
					'default' => 0,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Right', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'top',
					'default' => 0,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Top', 'gallery-images-ape' )
					),
				),
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'bottom',
					'default' => 0,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Bottom', 'gallery-images-ape' )
					),
				),
			)
		),

		//array( 'type' => 'skip' ),
		apeGalleryFieldsHelper::addDependField( 'layout.php', 'ext.layout.php' ),

		array(
			'type' => 'select',
			'view' => 'default',
			'name' => 'orderby',
			'default' => 'categoryD',
			'label' =>  __('Images order by ', 	'gallery-images-ape' ),
			//'description' => '',
			'options' => array(
				'column' => 8,
				'values' => array(					
					'categoryD' => __( 'Category &darr;', 	'gallery-images-ape' ),
					'categoryU' => __( 'Category &uarr;', 	'gallery-images-ape' ),

					'titleD' 	=> __( 'Title &darr;', 		'gallery-images-ape' ),
					'titleU' 	=> __( 'Title &uarr;', 		'gallery-images-ape' ),

					'dateD' 	=> __( 'Date &darr;', 		'gallery-images-ape' ),
					'dateU' 	=> __( 'Date &uarr;', 		'gallery-images-ape' ),

					'random' 	=> __( 'Random', 			'gallery-images-ape' ),
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',

			'name' => 'source',
			'default' => 'medium',

			'label' => __('Gallery Thumbnails Quality', 'gallery-images-ape'),
			'description' => sprintf(
								' %s <a href="%s" target="_blank">%s</a>', 
								__('here you can customize thumbnails quality, depend of this value you will have different thumbnails resolution. Please check values for the thumbnails resolutions', 'gallery-images-ape'),
								admin_url( 'options-media.php' ),
								__('here', 'gallery-images-ape')
							),
			'options' => array(
				'values' => array(
					array(
						'value' => 'thumbnail',
						'label' => __('Small', 'gallery-images-ape'),
					),
					array(
						'value' => 'medium',
						'label' => __('Medium', 'gallery-images-ape'),
					),
					array(
						'value' => 'medium_large',
						'label' => __('Large', 'gallery-images-ape'),
					),
					array(
						'value' => 'original',
						'label' => __('Full', 'gallery-images-ape'),
					)
				),
			),
		),
				
		array(
			'type' => 'checkbox',
			'view' => 'switch',
			'name' => 'sizeType',
			'label' => __('Images custom ration', 'gallery-images-ape'),
			'default' => 0,
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
			"dependents" => array(
				1 => array(
					'show' => array('#wrap-field-custom-ration'),
				),
				0 => array(
					'hide' => array('#wrap-field-custom-ration'),
				),
			)
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'id'	=> 'custom-ration',
			'name' => 'thumb-size-options',
			'label' => __('Image Resolution', 'gallery-images-ape'),
			'is_hide' => 1,
			'fields' => array(
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'width',
					'default' => 240,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Width', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'hight',
					'default' => 140,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Height', 'gallery-images-ape' )
					),
				),
			),
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'thumb-options',
			'label' => __('Thumbs', 'gallery-images-ape'),

			'fields' => array(
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'xspace',
					'default' => 15,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('X space', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'yspace',
					'default' => 15,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Y space', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'radius',
					'default' => 5,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('radius', 'gallery-images-ape' )
					),
				),
				
				
				
			)
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'align',
			'default' => '',
			'label' => __('Gallery Align', 'gallery-images-ape'),
			'description' => 'here you can align whole gallery block depend of your need or disable alignment option if you do not need it',
			'options' => array(
				'values' => array(
					array(
						'value' => 'left',
						'label' => 'Left',
					),
					array(
						'value' => 'center',
						'label' => 'Center',
					),
					array(
						'value' => 'right',
						'label' => 'Right',
					),
					array(
						'value' => '',
						'label' => 'Disabled',
					)
				),
			),
		),


		/*  =======================   */

		array(
			'type' => 'radio',
			'view' => 'buttons-group',	
			'name' => 'shadow',
			'label' => __('Thumbs shadow', 'gallery-images-ape'),
			'default' => '',
			'options' => array(
				'values' => array(
					array(
						'value' => '',
						'label' => __('Disabled', 'gallery-images-ape'),
					),
					array(
						'value' => '1',
						'label' => __('Shadow', 'gallery-images-ape'),
					),
					array(
						'value' => '2',
						'label' => __('Shadow + hover', 'gallery-images-ape'),
					),
				),
			),
			"dependents" => array(
				2 => array(
					'show' => array('#wrap-field-shadow-options', '#wrap-field-hover-shadow-options'),
				),
				1 => array(
					'show' => array('#wrap-field-shadow-options'),
					'hide' => array('#wrap-field-hover-shadow-options'),

				),
				'' => array(
					'hide' => array('#wrap-field-shadow-options', '#wrap-field-hover-shadow-options'),
				),
			)
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'id'	=> 'shadow-options',
			'name' => 'shadow-options',
			'label' => __('Shadow Options', 'gallery-images-ape'),
			'is_hide' => 1,
			'fields' => array(
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'hshadow',
					'default' => 0,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('X Shadow', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'vshadow',
					'default' => 5,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Y Shadow', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'bshadow',
					'default' => 7,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Blur', 'gallery-images-ape' )
					),
				),

				array(
					'type' => 'text',
					'view' => 'color',
					'name' => 'color',
					'default' => 'rgba(70, 36, 36, 0.65)',
					'attributes' => array(
						'readonly' => 'readonly',
					),
					'options' => array(
						'leftLabel' => __('Color', 'gallery-images-ape'),
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'alpha' => true,
					),
				),
			),
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'id'	=> 'hover-shadow-options',
			'name' => 'hover-shadow-options',
			'label' => __('Hover Shadow Options', 'gallery-images-ape'),
			'is_hide' => 1,
			'fields' => array(
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'hshadow',
					'default' => 1,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('X Shadow', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'vshadow',
					'default' => 3,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Y Shadow', 'gallery-images-ape' )
					),
				),
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'bshadow',
					'default' => 3,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'leftLabel'		=> __('Blur', 'gallery-images-ape' )
					),
				),

				array(
					'type' => 'text',
					'view' => 'color',
					'name' => 'color',
					'default' => 'rgba(34, 25, 25, 0.4)',
					'attributes' => array(
						'readonly' => 'readonly',
					),
					'options' => array(
						'leftLabel' => 'Color',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
						'alpha' => true,
					),
				),
			),
		),

		/*  border field  */

		/*  =======================   */

		array(
			'type' => 'radio',
			'view' => 'buttons-group',	
			'name' => 'thumbBorder',
			'label' => __('Thumbs border', 'gallery-images-ape'),
			'default' => '',
			'options' => array(
				'values' => array(
					array(
						'value' => '',
						'label' => 'Disabled',
					),
					array(
						'value' => '1',
						'label' => 'Border',
					),
					array(
						'value' => '2',
						'label' => 'Border with hover',
					),
				),
			),
			"dependents" => array(
				2 => array(
					'show' => array('#wrap-field-border-options', '#field-div-color-border-options-hover-color'),
				),
				1 => array(
					'show' => array('#wrap-field-border-options'),
					'hide' => array('#field-div-color-border-options-hover-color'),

				),
				'' => array(
					'hide' => array('#wrap-field-border-options', '#field-div-color-border-options-hover-color'),
				),
			)
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'id'	=> 'border-options',
			'name' => 'border-options',
			'is_hide' => 1,
			'fields' => array(
				
				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'width',
					'default' => 3,
					'label' => __('Border Width', 'gallery-images-ape'),
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'px',
						'column'		=> '12  medium-6',
						'columnWrap'	=> '12',
						
					),
				),
				array(
					'type' => 'select',
					'view' => 'default',
					'name' => 'style',
					'default' => 'solid',
					'label' => __('Border Style', 'gallery-images-ape'),
					'options' => array(
						'column'		=> '12  medium-6',
						'columnWrap'	=> '12',
						'values' => array(					
							'none' 		=> __( 'none', 'gallery-images-ape' ),
							'dotted' 	=> __( 'dotted', 'gallery-images-ape' ),
							'dashed' 	=> __( 'dashed', 'gallery-images-ape' ),
							'solid' 	=> __( 'solid', 'gallery-images-ape' ),
							'double' 	=> __( 'double', 'gallery-images-ape' ),
							'groove' 	=> __( 'groove', 'gallery-images-ape' ),
							'ridge' 	=> __( 'ridge', 'gallery-images-ape' ),
							'inset' 	=> __( 'inset', 'gallery-images-ape' ),
							'outset' 	=> __( 'outset', 'gallery-images-ape' ),
							'hidden' 	=> __( 'hidden', 'gallery-images-ape' ),
						),
					),
				),

				array(
					'type' => 'text',
					'view' => 'color',
					'name' => 'color',
					'label' => __('Border Color', 'gallery-images-ape'),
					'default' => 'rgba(70, 36, 36, 0.65)',
					'attributes' => array(
						'readonly' => 'readonly',
					),
					'options' => array(
						'column'		=> '12  medium-6',
						'columnWrap'	=> '12',
						'alpha' => true,
					),
				),
				array( //field-div-color-border-options-hover-color
					'type' => 'text',
					'view' => 'color',
					'name' => 'hover-color',
					'id'	=> 'border-options-hover-color',
					'label' => __('Hover Border Color', 'gallery-images-ape'),
					'default' => 'rgba(70, 36, 36, 0.65)',
					'attributes' => array(
						'readonly' => 'readonly',
					),
					'options' => array(
						'column'		=> '12  medium-6',
						'columnWrap'	=> '12',
						'alpha' => true,
					),
				),
			),
		),
		
	),
);
