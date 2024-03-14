<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( apeGalleryHelper::compareVersion('2.1') ){
	return include WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/theme_slider.ext.php';
}


return array(
	'active' => true,
	'order' => 10,
	'settings' => array(
		'id' => 'wpape-theme-slider-size',
		'title' => __('Ape Gallery Slider Theme Settings', 'gallery-images-ape'),
		'screen' => array(  WPAPE_GALLERY_THEME_POST ),
		'context' => 'normal',
		'priority' => 'default',
		'for' => array( 'type' => 'slider' ),
		'callback_args' => null,
	),
	'view' => 'default',
	'state' => 'open',
	'fields' => array(

		array(
			'type' => 'checkbox',
			'view' => 'switch',
			'name' => 'autoplay',
			'label' => __('Slider autoplay', 'gallery-images-ape'),
			'default' => 0,
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
			"dependents" => array(
				0 => array(
					'hide' => array('#wrap-field-custom-delay'),
				),
				1 => array(
					'show' => array('#wrap-field-custom-delay'),
				),
			)
		),

		array(
			'type' => 'text',
			'view' => 'group',
			'name' => 'delay',
			'id'	=> 'custom-delay',
			'label' => __('Delay', 'gallery-images-ape'),
			'default' => 1500,
			'cb_sanitize' => 'intval',
			'options' => array(
				'rightLabel' 	=> 'ms',
				'column'		=> '12',
				'columnWrap'	=> '12  medium-6',
			),
			
		),


		array(
			'type' => 'checkbox',
			'view' => 'switch',
			'name' => 'autoWidth',
			'label' => __('Slider Auto Width', 'gallery-images-ape'),
			'default' => 1,
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
			"dependents" => array(
				0 => array(
					'show' => array('#wrap-field-width'),
				),
				1 => array(
					'hide' => array('#wrap-field-width'),
				),
			)
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'width',
			'id'	=> 'width',
			'label' => __('Slider Width ', 'gallery-images-ape'),
			'description' => __( '', 'gallery-images-ape') ,
			'fields' => array(
				array(
					'type' => 'text',
					'view' => 'default/llc4',
					'name' => 'value',
					'default' => 100,
				),

				array(
					'type' => 'select',
					'view' => 'default/c2',
					'name' => 'type',
					'default' => '%',
					'options' => array(
						'values' => array(				
							'px' => 'px',
							'%' => '%',
						),
					),
				),
			)
		),

		array(
			'type' => 'checkbox',
			'view' => 'switch',
			'name' => 'autoHeight',
			'label' => __('Slider Auto Height', 'gallery-images-ape'),
			'default' => 1,
			'options' => array(
				'size' => 'large',
				'onLabel' => 'On',
				'offLabel' => 'Off',
			),
			"dependents" => array(
				0 => array(
					'show' => array('#wrap-field-height'),
				),
				1 => array(
					'hide' => array('#wrap-field-height'),
				),
			)
		),

		array(
			'type' => 'composite',
			'view' => 'default',
			'name' => 'height',
			'id'	=> 'height',
			'label' => __('Slider Height', 'gallery-images-ape'),
			'description' => __( 'in our gallery we use smart algorithm for the size calculation. In Max Width option you define maximum allowed size of the gallery box', 'gallery-images-ape') ,
			'fields' => array(

				array(
					'type' => 'text',
					'view' => 'default/llc4',
					'name' => 'value',
					'default' => 100,
				),

				array(
					'type' => 'select',
					'view' => 'default/c2',
					'name' => 'type',
					'default' => 'vh',
					'options' => array(
						'values' => array(				
							'px' => 'px',
							'%' => '%',
							'vh' 	=> 'vh',
						),
					),
				),
			)
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'nav_buttons',
			'default' => 'show',
			'label' => __('Navigation buttons', 'gallery-images-ape'),
			'options' => array(
				'values' => array(
					array(
						'value' => '',
						'label' => 'Hide',
					),
					array(
						'value' => 'show',
						'label' => 'Show',
					),
				),
			),
		),

		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'nav_scrollbar',
			'default' => 'show',
			'label' => __('Scrollbar', 'gallery-images-ape'),
			'options' => array(
				'values' => array(
					array(
						'value' => '',
						'label' => 'Hide',
					),
					array(
						'value' => 'show',
						'label' => 'Show',
					),
				),
			),
		),



		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'direction',
			'default' => 'horizontal',
			'label' => __('Direction', 'gallery-images-ape'),
			'options' => array(
				'values' => array(
					array(
						'value' => 'vertical',
						'label' => 'Vertical',
					),
					array(
						'value' => 'horizontal',
						'label' => 'Horizontal',
					),
					
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',		
			'name' => 'preload',
			'default' => 'preload',
			'label' => __('Preload', 'gallery-images-ape'),
			'options' => array(
				'values' => array(
					array(
						'value' => 'off',
						'label' => 'Off',
					),
					array(
						'value' => 'preload',
						'label' => 'On',
					),
					array(
						'value' => 'lazy',
						'label' => 'LazyLoad Dark',
					),
					array(
						'value' => 'lazy_white',
						'label' => 'LazyLoad Light',
					),
				),
			),
		),

		
		array(
			'type' => 'select',
			'view' => 'default',
			'is_lock' => false,
			'name' => 'orderby',
			'default' => 'categoryD',
			'label' => 'Images order by ',
			'description' => 'Field type: select, view: default',
			'options' => array(
				'column' => 8,
				'values' => array(					
					'categoryD' => __( 'Category &darr;', 'gallery-images-ape' ),
					'categoryU' => __( 'Category &uarr;', 'gallery-images-ape' ),

					'titleD' 	=> __( 'Title &darr;', 'gallery-images-ape' ),
					'titleU' 	=> __( 'Title &uarr;', 'gallery-images-ape' ),

					'dateD' 	=> __( 'Date &darr;', 'gallery-images-ape' ),
					'dateU' 	=> __( 'Date &uarr;', 'gallery-images-ape' ),

					'random' 	=> __( 'Random', 'gallery-images-ape' ),
				),
			),
		),


		array(
			'type' => 'radio',
			'view' => 'buttons-group',

			'name' => 'source',
			'default' => 'original',

			'label' => __('Slider Images Quality', 'gallery-images-ape'),

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
						'label' => 'Small',
					),
					array(
						'value' => 'medium',
						'label' => 'Medium',
					),
					array(
						'value' => 'medium_large',
						'label' => 'Large',
					),
					array(
						'value' => 'original',
						'label' => 'Full',
					)
				),
			),
		),

		array(
			'type' => 'html',
			'view' => 'default',
			'content' => apeGalleryHelper::getAddonButton( __('Add Extended Slider Add-on', 'gallery-images-ape')),
		),

		array(
			'type' => 'hidden',
			'view' => 'default',
			'name' => 'effect',
			'default' => 'slide',
		),
		array(
			'type' => 'hidden',
			'view' => 'default',
			'name' => 'nav_pagination',
			'default' => 'bullets',
		),
		
	),
);
