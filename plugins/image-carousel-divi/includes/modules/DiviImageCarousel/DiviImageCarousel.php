<?php

class LWP_ImageCarousel extends ET_Builder_Module {

	public $slug       = 'lwp_image_carousel';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://www.learnhowwp.com/divi-image-carousel-plugin',
		'author'     => 'learnhowwp.com',
		'author_uri' => 'https://www.learnhowwp.com/',
	);

	public function init() {
		$this->name = esc_html__( 'Image Carousel', 'lwp-image-carousel' );
		$this->main_css_element = '%%order_class%%';
		$this->icon = '\'';
	}

	public function get_fields() {
		return array(
			'gallery_ids' => array(
				'label'            => esc_html__( 'Images', 'lwp-image-carousel' ),
				'description'      => esc_html__( 'Choose the images that you would like to appear in the image carousel.', 'lwp-image-carousel' ),
				'type'             => 'upload-gallery',
				'computed_affects' => array(
					'__gallery',
				),
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'images_settings',
			),
			'slides_show' => array(
				'label'           => esc_html__( 'Slides Count', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the number of images to show in the carousel', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'1' => esc_html__( '1', 'lwp-image-carousel' ),
					'2' => esc_html__( '2', 'lwp-image-carousel' ),
					'3' => esc_html__( '3', 'lwp-image-carousel' ),
					'4' => esc_html__( '4', 'lwp-image-carousel' ),
					'5' => esc_html__( '5', 'lwp-image-carousel' ),
					'6' => esc_html__( '6', 'lwp-image-carousel' ),
				),
				'mobile_options'  => true,
				'default'=> '3',
				'default_tablet'=>'2',
				'default_phone'=>'1',
				'toggle_slug'     => 'element_settings',					
			),
			'slides_scroll' => array(
				'label'           => esc_html__( 'Slides Scroll', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the number of images to scroll on the press of a button or on automatic animation', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'1' => esc_html__( '1', 'lwp-image-carousel' ),
					'2' => esc_html__( '2', 'lwp-image-carousel' ),
					'3' => esc_html__( '3', 'lwp-image-carousel' ),
					'4' => esc_html__( '4', 'lwp-image-carousel' ),
					'5' => esc_html__( '5', 'lwp-image-carousel' ),
					'6' => esc_html__( '6', 'lwp-image-carousel' ),
				),
				'default'=> '1',
				'mobile_options'  => true,
				'toggle_slug'     => 'element_settings',					
			),
			'show_dots' => array(
				'label'           => esc_html__( 'Show Dots', 'lwp-image-carousel' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'description' => esc_html__('Select if you want to show dots under the carousel', 'lwp-image-carousel'),
				'toggle_slug'     => 'element_settings',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-image-carousel' ),
					'on'  => esc_html__( 'Yes', 'lwp-image-carousel' ),
				),
				'default' => 'on',
				'mobile_options'  => true,	
			),
			'show_arrows' => array(
				'label'           => esc_html__( 'Show Arrows', 'lwp-image-carousel'),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'description' => esc_html__('Select if you want to show arrows on the carousel', 'lwp-image-carousel'),
				'toggle_slug'     => 'element_settings',				
				'options' => array(
					'on'  => esc_html__( 'Yes', 'lwp-image-carousel'),
					'off' => esc_html__( 'No', 'lwp-image-carousel'),
				  ),
				'default' => 'on',
				'mobile_options'  => true,
			),			
			'autoplay_animation' => array(
				'label'           => esc_html__( 'Autoplay', 'lwp-image-carousel' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'description' => esc_html__('Select if you want the carousel to keep moving to next slides automatically', 'lwp-image-carousel'),
				'toggle_slug'     => 'animation_settings',
				'tab_slug'          => 'advanced',				
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-image-carousel' ),
					'on'  => esc_html__( 'Yes', 'lwp-image-carousel' ),
				),
				'show_if_not'     => array(
					'layout' => 'sync',
				),				
			),
			'pause_on_hover' => array(
				'label'           => esc_html__( 'Pause On Hover', 'lwp-image-carousel'),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'tab_slug'          => 'advanced',				
				'description' => esc_html__('Select if you want to stop the autoplay animation on hover', 'lwp-image-carousel'),
				'toggle_slug'     => 'animation_settings',				
				'options' => array(
					'on'  => esc_html__( 'Yes', 'lwp-image-carousel'),
					'off' => esc_html__( 'No', 'lwp-image-carousel'),
				  ),
				'default' => 'on',
				'show_if'         => array(
					'autoplay_animation' => 'on',
				),				
			),			
			'autoplay_animation_speed' => array(
				'label'           => esc_html__( 'Automatic Animation Speed (in ms)', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Set the animation speed for the autoplay animation.', 'lwp-image-carousel' ),
				'type'              => 'range',
				'toggle_slug'     => 'animation_settings',
				'tab_slug'          => 'advanced',				
				'option_category'   => 'basic_option',
				'range_settings'    => array(
					'min'  => 0,
					'max'  => 5000,
					'step' => 50,
				),
				'default'             => '2000ms',
				'description'         => esc_html__( 'Speed up or slow down your animation by adjusting the animation duration. Units are in milliseconds and the default animation duration is one second.', 'lwp-image-carousel' ),
				'validate_unit'       => true,
				'fixed_unit'          => 'ms',
				'fixed_range'         => true,
				'show_if'         => array(
					'autoplay_animation' => 'on',
				),									
			),
			'slide_animation_speed' => array(
				'label'           => esc_html__( 'Slide Animation Speed (in ms)', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Set the animation speed for transition between slides', 'lwp-image-carousel' ),
				'type'              => 'range',
				'toggle_slug'     => 'animation_settings',
				'tab_slug'          => 'advanced',				
				'option_category'   => 'basic_option',
				'range_settings'    => array(
					'min'  => 0,
					'max'  => 5000,
					'step' => 50,
				),
				'default'             => '300ms',
				'validate_unit'       => true,
				'fixed_unit'          => 'ms',
				'fixed_range'         => true,								
			),						
			'infinite_animation' => array(
				'label'           => esc_html__( 'Infinite Animation', 'lwp-image-carousel' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'description' => esc_html__('Select if you want enable infinite animation. The carousel will start from the first image once it reaches the end.', 'lwp-image-carousel'),
				'toggle_slug'     => 'animation_settings',
				'tab_slug'          => 'advanced',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-image-carousel' ),
					'on'  => esc_html__( 'Yes', 'lwp-image-carousel' ),
				),
				'show_if'     => array(
					'layout' => 'default',
				),				
			),
			'arrow_color' => array(
				'label'           	=> esc_html__( 'Arrow Color', 'lwp-image-carousel' ),
				'type'              => 'color-alpha',
				'option_category' 	=> 'basic_option',
				'description' 		=> esc_html__('Select the colors of the arrows in the carousel.', 'lwp-image-carousel'),
				'toggle_slug'     	=> 'element_design_settings',
				'tab_slug'          => 'advanced',
				'mobile_options'  	=> true,
				'hover'             => 'tabs',							
			),
			'arrow_bg' => array(
				'label'           	=> esc_html__( 'Arrow Background', 'lwp-image-carousel' ),
				'type'            	=> 'color-alpha',
				'description' 		=> esc_html__('Select the background color for the arrows in the carousel.', 'lwp-image-carousel'),
				'tab_slug'        	=> 'advanced',
				'toggle_slug'     	=> 'element_design_settings',
				'mobile_options'  	=> true,
				'hover'             => 'tabs',
			),									
			'dots_color' => array(
				'label'           	=> esc_html__( 'Dots Color', 'lwp-image-carousel' ),
				'type'              => 'color-alpha',
				'option_category' 	=> 'basic_option',
				'description' 		=> esc_html__('Select color for dots in the carousel', 'lwp-image-carousel'),
				'toggle_slug'     	=> 'element_design_settings',
				'tab_slug'          => 'advanced',
				'mobile_options'  => true,								
			),
			'layout' => array(
				'label'           => esc_html__( 'Layout', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the layout for the slider.', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'default' 		=> esc_html__( 'Default', 'lwp-image-carousel' ),
					'center' 	=> esc_html__( 'Center Mode', 'lwp-image-carousel' ),
					'sync' 	=> esc_html__( 'Synced Slider', 'lwp-image-carousel' ),
				),
				'default'=> 'default',
				'toggle_slug'     => 'layout',
				'tab_slug'          => 'advanced',					
			),
			'center_padding' => array(
				'label'           => esc_html__( 'Center Padding', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Set the center padding for the center mode slider.', 'lwp-image-carousel' ),
				'type'              => 'range',
				'toggle_slug'     => 'layout',
				'tab_slug'          => 'advanced',				
				'option_category'   => 'basic_option',
				'range_settings'    => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'default'             => '60px',
				'description'         => esc_html__( 'Speed up or slow down your animation by adjusting the animation duration. Units are in milliseconds and the default animation duration is one second.', 'lwp-image-carousel' ),
				'validate_unit'       => true,
				'fixed_unit'          => 'px',
				'fixed_range'         => true,
				'show_if'         => array(
					'layout' => 'center',
				),									
			),			
			'vertical_layout' => array(
				'label'           => esc_html__( 'Vertical Layout', 'lwp-image-carousel' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'description' => esc_html__('Select if you want enable sliding for this slider.', 'lwp-image-carousel'),
				'toggle_slug'     => 'layout',
				'tab_slug'          => 'advanced',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-image-carousel' ),
					'on'  => esc_html__( 'Yes', 'lwp-image-carousel' ),
				),
				'show_if'     => array(
					'layout' => array( 'default', 'center' ),
				),				
			),			
			'adaptive_height' => array(
				'label'           => esc_html__( 'Adaptive Height', 'lwp-image-carousel' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'description' => esc_html__('Select if you want enable adaptive height.', 'lwp-image-carousel'),
				'toggle_slug'     => 'layout',
				'tab_slug'          => 'advanced',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-image-carousel' ),
					'on'  => esc_html__( 'Yes', 'lwp-image-carousel' ),
				),
				'default' => 'on',				
			),			
			'arrow_location' => array(
				'label'           => esc_html__( 'Arrow Location', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the location of the arrows.', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'side' 	  		=> esc_html__( 'Side', 'lwp-image-carousel' ),
					'top'			=> esc_html__( 'Top', 'lwp-image-carousel' ),
					'bottom' 		=> esc_html__( 'Bottom', 'lwp-image-carousel' ),
				),
				'default'			=> 'side',
				'toggle_slug'     	=> 'layout',
				'tab_slug'          => 'advanced',
				'show_if_not'     => array(
					'layout' => 'sync',
				),									
			),
			'arrow_location_sync' => array(
				'label'           => esc_html__( 'Arrow Location', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the location of the arrows.', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'side' 	  		=> esc_html__( 'Side', 'lwp-image-carousel' ),
					'bottom' 		=> esc_html__( 'Bottom', 'lwp-image-carousel' ),
				),
				'default'			=> 'side',
				'toggle_slug'     	=> 'layout',
				'tab_slug'          => 'advanced',
				'show_if'     => array(
					'layout' => 'sync',
				),									
			),			
			'arrow_alignment' => array(
				'label'           => esc_html__( 'Arrow Alignment', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'left' 	  		=> esc_html__( 'Left', 'lwp-image-carousel' ),
					'right'			=> esc_html__( 'Right', 'lwp-image-carousel' ),
					'center' 		=> esc_html__( 'Center', 'lwp-image-carousel' ),
				),
				'default'			=> 'left',
				'toggle_slug'     	=> 'layout',
				'tab_slug'          => 'advanced',
				'show_if_not'     => array(
					'arrow_location' 		=> 'side',
					'layout' => 'sync',					
				),
			),
			'arrow_alignment_sync' => array(
				'label'           => esc_html__( 'Arrow Alignment', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'left' 	  		=> esc_html__( 'Left', 'lwp-image-carousel' ),
					'right'			=> esc_html__( 'Right', 'lwp-image-carousel' ),
					'center' 		=> esc_html__( 'Center', 'lwp-image-carousel' ),
				),
				'default'			=> 'left',
				'toggle_slug'     	=> 'layout',
				'tab_slug'          => 'advanced',
				'show_if_not'     => array(
					'arrow_location_sync' 		=> 'side',
				),
				'show_if'     => array(
					'layout'					=> 'sync',
				),														
			),			
			'arrow_alignment_side' => array(
				'label'           => esc_html__( 'Arrow Alignment', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'top' 	  		=> esc_html__( 'Top', 'lwp-image-carousel' ),
					'center' 		=> esc_html__( 'Center', 'lwp-image-carousel' ),
					'bottom'			=> esc_html__( 'Bottom', 'lwp-image-carousel' ),
				),
				'default'			=> 'center',
				'toggle_slug'     	=> 'layout',
				'tab_slug'          => 'advanced',
				'show_if'     => array(
					'arrow_location' 		=> 'side',
					'layout'				=> array('default','center'),
				),										
			),
			'arrow_alignment_side_sync' => array(
				'label'           => esc_html__( 'Arrow Alignment', 'lwp-image-carousel' ),
				'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-image-carousel' ),
				'type'            => 'select',
				'option_category' => 'basic_option',				
				'options'         => array(
					'top' 	  		=> esc_html__( 'Top', 'lwp-image-carousel' ),
					'center' 		=> esc_html__( 'Center', 'lwp-image-carousel' ),
					'bottom'			=> esc_html__( 'Bottom', 'lwp-image-carousel' ),
				),
				'default'			=> 'center',
				'toggle_slug'     	=> 'layout',
				'tab_slug'          => 'advanced',
				'show_if'     => array(
					'arrow_location_sync' 		=> 'side',
					'layout'					=> 'sync',
				),										
			),																																												
		);
	}

	public function get_settings_modal_toggles() {
		return array(
		  'advanced' => array(
			'toggles' => array(
			  'images_settings' => esc_html__( 'Images', 'lwp-image-carousel' ),
			  'element_settings' => esc_html__( 'Elements', 'lwp-image-carousel' ),
			  'element_design_settings' =>array(
				  'title'		=> esc_html__( 'Carousel Elements', 'lwp-image-carousel' ),
				  'priority' 	=>20,
			  ),
			  'animation_settings' => array( 
				  'title'		=>	esc_html__( 'Carousel Animation', 'lwp-image-carousel' ),
				  'priority' 	=>21,				  
			  ),
			  'image_styles' => array(
				  'title'	=>	esc_html__( 'Image', 'lwp-image-carousel' ),
				  'priority' 	=>22,
			  ),
			  'main_image_styles' => array(
				'title'	=>	esc_html__( 'Main Image', 'lwp-image-carousel' ),
				'priority' 	=>23,
				),			  			  			  				  
			  'center_image_styles' => array(
				'title'	=>	esc_html__( 'Center Image', 'lwp-image-carousel' ),
				'priority' 	=>23,
				),			  			  			  				  
			),
		  ),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'borders'        => array(
				'default' => array(),
				'image'   => array(
					'css'             => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .small-slider .slick-slide img",
							'border_styles' => "{$this->main_css_element} .small-slider .slick-slide img",
						),
					),
					'label_prefix'    => esc_html__( 'Image', 'lwp-image-carousel' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_styles',
				),
				'main_image'   => array(
					'css'             => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .big-slider .slick-slide img",
							'border_styles' => "{$this->main_css_element} .big-slider .slick-slide img",
						),
					),
					'label_prefix'    => esc_html__( 'Main Image', 'lwp-image-carousel' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'main_image_styles',
					'depends_show_if'  => 'sync',
					'depends_on'       => array(
						'layout',
					),
				),
				'center_image'   => array(
					'css'             => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img, {$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide[aria-hidden=\"true\"]:not([tabindex=\"-1\"]) + .slick-cloned[aria-hidden=\"true\"] img",
							'border_styles' => "{$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img, {$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide[aria-hidden=\"true\"]:not([tabindex=\"-1\"]) + .slick-cloned[aria-hidden=\"true\"] img",
						),
					),
					'label_prefix'    => esc_html__( 'Center Image', 'lwp-image-carousel' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'center_image_styles',
					'depends_show_if'  => 'center',
					'depends_on'       => array(
						'layout',
					),
				),												
			),
			'box_shadow'        => array(
				'default' => array(),
				'image'   => array(
					'css'             => array(
						'main' => "{$this->main_css_element} .small-slider .slick-slide img",
						'overlay' => 'inset',
					),
					'label_prefix'    => esc_html__( 'Image', 'lwp-image-carousel' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_styles',
				),
				'main_image'   => array(
					'css'             => array(
						'main' => "{$this->main_css_element} .big-slider .slick-slide img",
						'overlay' => 'inset',
					),
					'label_prefix'    => esc_html__( 'Main Image', 'lwp-image-carousel' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'main_image_styles',
					'depends_show_if'  => 'sync',
					'depends_on'       => array(
						'layout',
					),					
				),				
				'center_image'   => array(
					'css'             => array(
						'main' => "{$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img, {$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide[aria-hidden=\"true\"]:not([tabindex=\"-1\"]) + .slick-cloned[aria-hidden=\"true\"] img",
						'overlay' => 'inset',
					),
					'label_prefix'    => esc_html__( 'Main Image', 'lwp-image-carousel' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'center_image_styles',
					'depends_show_if'  => 'center',
					'depends_on'       => array(
						'layout',
					),					
				),				
			),
			'margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'fonts' 				=> false,													
		);
	}
	
	public function get_custom_css_fields_config() {
		return array(
			'arrow' => array(
				'label'    => esc_html__( 'Arrow', 'lwp-image-carousel' ),
				'selector' => '%%order_class%% .slick-next,%%order_class%% .slick-prev',
			),
			'dots' => array(
				'label'    => esc_html__( 'Dots', 'lwp-image-carousel' ),
				'selector' => '%%order_class%% .slick-dots li button',
			),
			'image' => array(
				'label'    => esc_html__( 'Images', 'lwp-image-carousel' ),
				'selector' => '%%order_class%% .lwp-slick-slider .slick-slide img',
			),
			'center_image' => array(
				'label'    => esc_html__( 'Center Image', 'lwp-image-carousel' ),
				'selector' => '%%order_class%% .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img',
			),
			'main_image' => array(
				'label'    => esc_html__( 'Main Image', 'lwp-image-carousel' ),
				'selector' => '%%order_class%% .big-slider .slick-slide img',
			),
			'slider_container' => array(
				'label'    => esc_html__( 'Slider Container', 'lwp-image-carousel' ),
				'selector' => '%%order_class%% .slick-slider',
			),
			'slide_container' => array(
				'label'    => esc_html__( 'Slide Container', 'lwp-image-carousel' ),
				'selector' => '%%order_class%% .lwp-slick-slider .slick-slide',
			),
		);
	}	

	private function on_off_map($setting){
		if($setting=='off' || $setting=='')
			return 'false';
		else 
			return 'true';
	}

	public function render( $attrs, $content = null, $render_slug ) {

		$gallery_ids					= $this->props['gallery_ids'];

		$show_arrows					= $this->props['show_arrows'];
		$show_arrows_tablet				= $this->props['show_arrows_tablet'];
		$show_arrows_phone				= $this->props['show_arrows_phone'];
		$show_arrows_last_edited		= $this->props['show_arrows_last_edited'];

		$show_dots						= $this->props['show_dots'];
		$show_dots_tablet				= $this->props['show_dots_tablet'];
		$show_dots_phone				= $this->props['show_dots_phone'];
		$show_dots_last_edited			= $this->props['show_dots_last_edited'];
		
		$slides_scroll					= $this->props['slides_scroll'];
		$slides_scroll_tablet			= $this->props['slides_scroll_tablet'];
		$slides_scroll_phone			= $this->props['slides_scroll_phone'];
		$slides_scroll_last_edited		= $this->props['slides_scroll_last_edited'];
		
		$slides_show					= $this->props['slides_show'];
		$slides_show_tablet				= $this->props['slides_show_tablet'];
		$slides_show_phone				= $this->props['slides_show_phone'];
		$slides_show_last_edited		= $this->props['slides_show_last_edited'];
		
		$infinite_animation				= $this->props['infinite_animation'];
		$autoplay_animation				= $this->props['autoplay_animation'];
		$autoplay_animation_speed		= $this->props['autoplay_animation_speed'];
		$slide_animation_speed			= $this->props['slide_animation_speed'];
		$pause_on_hover					= $this->props['pause_on_hover'];
		
		$arrow_color					= $this->props['arrow_color'];
		$arrow_color_tablet				= $this->props['arrow_color_tablet'];
		$arrow_color_phone				= $this->props['arrow_color_phone'];
		$arrow_color_last_edited		= $this->props['arrow_color_last_edited'];
		$arrow_color_hover				= $this->get_hover_value( 'arrow_color');

		$arrow_bg						= $this->props['arrow_bg'];
		$arrow_bg_tablet				= $this->props['arrow_bg_tablet'];
		$arrow_bg_phone					= $this->props['arrow_bg_phone'];
		$arrow_bg_last_edited			= $this->props['arrow_bg_last_edited'];
		$arrow_bg_hover					= $this->get_hover_value( 'arrow_bg');
		
		$arrow_location					= $this->props['arrow_location'];
		$arrow_location_sync			= $this->props['arrow_location_sync'];		
		$arrow_alignment				= $this->props['arrow_alignment'];		
		$arrow_alignment_sync			= $this->props['arrow_alignment_sync'];		
		$arrow_alignment_side			= $this->props['arrow_alignment_side'];		
		$arrow_alignment_side_sync		= $this->props['arrow_alignment_side_sync'];		
		
		$dots_color						= $this->props['dots_color'];
		$dots_color_tablet				= $this->props['dots_color_tablet'];
		$dots_color_phone				= $this->props['dots_color_phone'];
		$dots_color_last_edited			= $this->props['dots_color_last_edited'];

		$layout							= $this->props['layout'];
		$vertical_layout				= $this->props['vertical_layout'];
		$adaptive_height				= $this->props['adaptive_height'];
		$center_padding					= $this->props['center_padding'];

		$helper_class ='lwp-'.$layout.'-slider';

		if($layout==='sync'){
			if($arrow_location_sync=='side')
				$helper_class =$helper_class.' '.'lwp-'.$arrow_location_sync.'-'.$arrow_alignment_side_sync;
			else
				$helper_class =$helper_class.' '.'lwp-'.$arrow_location_sync.'-'.$arrow_alignment_sync;			
		}

		else{
			if($arrow_location=='side')
				$helper_class =$helper_class.' '.'lwp-'.$arrow_location.'-'.$arrow_alignment_side;
			else
				$helper_class =$helper_class.' '.'lwp-'.$arrow_location.'-'.$arrow_alignment;
		}

		$slides_show_responsive_active = et_pb_get_responsive_status( $slides_show_last_edited );

		if($slides_show_responsive_active==false){
			$slides_show_tablet=$slides_show;
			$slides_show_phone='1';	//By default the carousel shows 1 slide for phones

			if($layout=='sync')	//If layout is synced slider it should default to 3
				$slides_show_phone='3';
		}
		else{
			$slides_show_tablet = ($slides_show_tablet=='') ? $slides_show : $slides_show_tablet;
			$slides_show_phone = ($slides_show_phone=='') ? $slides_show_tablet : $slides_show_phone;			
		}

		$slides_scroll_responsive_active = et_pb_get_responsive_status( $slides_scroll_last_edited );

		if($slides_scroll_responsive_active==false){
			$slides_scroll_tablet=$slides_scroll;
			$slides_scroll_phone='1';
		}
		else{
			$slides_scroll_tablet = ($slides_scroll_tablet=='') ? $slides_scroll : $slides_scroll_tablet;
			$slides_scroll_phone = ($slides_scroll_phone=='') ? $slides_scroll_tablet : $slides_scroll_phone;			
		}		
		
		$show_arrows_resposnive_active = et_pb_get_responsive_status($show_arrows_last_edited);

		if($show_arrows_resposnive_active==false)
			$show_arrows_phone=$show_arrows_tablet=$show_arrows;
		else{
			if($show_arrows_tablet=='')
				$show_arrows_tablet=$show_arrows;
			if($show_arrows_phone=='')
				$show_arrows_phone=$show_arrows_tablet;
		}

		$show_dots_resposnive_active = et_pb_get_responsive_status($show_dots_last_edited);
		
		if($show_dots_resposnive_active==false)
			$show_dots_phone=$show_dots_tablet=$show_dots;
		else{
			if($show_dots_tablet=='')
				$show_dots_tablet=$show_dots;
			if($show_dots_phone=='')
				$show_dots_phone=$show_dots_tablet;
		}			
			

		$attachments = array();
		$attachments_args = array(
			'numberposts'    => 5,
			'include'        => $gallery_ids,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'post__in',
		);
		
		$_attachments = get_posts( $attachments_args );
	
		foreach ( $_attachments as $key => $val ) {
			$attachments[$key] = $_attachments[$key];
			$attachments[$key]->image_alt_text  	= get_post_meta( $val->ID, '_wp_attachment_image_alt', true);
			$attachments[$key]->image_title_text  	= get_the_title( $val->ID );
			$attachments[$key]->image_src_full  	= wp_get_attachment_image_src( $val->ID, 'full' );
		}

		$images_output='';	//stores html output for the images

		foreach ( $attachments as $key => $val ) {

			$images_output=$images_output.'<div><img src="'.$attachments[$key]->image_src_full[0].'" alt="'.$attachments[$key]->image_alt_text.'" title="'.$attachments[$key]->image_title_text.'"></div>';
			
			$attachments[$key];
			$attachments[$key]->image_alt_text;
			$attachments[$key]->image_src_full;
		}

		$show_arrows 		= self::on_off_map($show_arrows);
		$show_arrows_tablet	= self::on_off_map($show_arrows_tablet);
		$show_arrows_phone	= self::on_off_map($show_arrows_phone);

		$show_dots	 		= self::on_off_map($show_dots);
		$show_dots_tablet	= self::on_off_map($show_dots_tablet);
		$show_dots_phone	= self::on_off_map($show_dots_phone);

		$vertical_layout	= self::on_off_map($vertical_layout);
		$adaptive_height	= self::on_off_map($adaptive_height);

		$pause_on_hover 		= self::on_off_map($pause_on_hover);
		$infinite_animation 	= self::on_off_map($infinite_animation);
		$autoplay_animation 	= self::on_off_map($autoplay_animation);

		$autoplay_animation_speed = chop($autoplay_animation_speed,"ms");
		$slide_animation_speed = chop($slide_animation_speed,"ms");

		$data_slick='';	//variable for data-slick attributes.
		$sync_slider_html='';//variable to store html for the sync slider

		if($layout=='default' || $layout=='')	//Slick attribute if layout is default
			$data_slick = sprintf('data-slick=\'{ "vertical":%17$s, "slidesToShow": %1$s, "slidesToScroll": %2$s, "dots":%3$s, "arrows":%4$s, "infinite":%5$s, "autoplay":%6$s, "autoplaySpeed":%7$s, "pauseOnHover":%16$s, "adaptiveHeight":%18$s, "speed":%19$s, "responsive": [ { "breakpoint": 980, "settings": { "slidesToShow": %8$s, "slidesToScroll": %10$s, "arrows":%12$s,"dots":%14$s } } ,{ "breakpoint": 767, "settings": { "slidesToShow": %9$s, "slidesToScroll": %11$s, "arrows":%13$s,"dots":%15$s } } ] }\''
		,$slides_show,$slides_scroll,$show_dots,$show_arrows,$infinite_animation,$autoplay_animation,$autoplay_animation_speed,$slides_show_tablet,$slides_show_phone,$slides_scroll_tablet,$slides_scroll_phone,$show_arrows_tablet,$show_arrows_phone,$show_dots_tablet,$show_dots_phone,$pause_on_hover,$vertical_layout,$adaptive_height,$slide_animation_speed);
		
		else if($layout=='center')	//Slick attribute if layout is set to center mode
			$data_slick = sprintf('data-slick=\'{ "vertical":%17$s, "centerMode": true, "centerPadding": "%18$s",  "slidesToShow": %1$s, "dots":%3$s, "arrows":%4$s, "infinite":true, "autoplay":%6$s, "autoplaySpeed":%7$s, "pauseOnHover":%16$s, "adaptiveHeight":%19$s, "speed":%20$s, "responsive": [ { "breakpoint": 980, "settings": { "centerMode": true, "slidesToShow": %8$s, "arrows":%12$s,"dots":%14$s } } ,{ "breakpoint": 767, "settings": {"centerMode": true, "slidesToShow": %9$s, "arrows":%13$s,"dots":%15$s } } ] }\''
		,$slides_show,$slides_scroll,$show_dots,$show_arrows,$infinite_animation,$autoplay_animation,$autoplay_animation_speed,$slides_show_tablet,$slides_show_phone,$slides_scroll_tablet,$slides_scroll_phone,$show_arrows_tablet,$show_arrows_phone,$show_dots_tablet,$show_dots_phone,$pause_on_hover,$vertical_layout,$center_padding,$adaptive_height,$slide_animation_speed);
		
		else if($layout=='sync'){	//Slick attribute and html if layout is set to synced slider
			
			$data_slick = sprintf('data-slick=\'{"asNavFor": ".big-slider","slidesToShow": %1$s, "slidesToScroll": %2$s, "dots":%3$s, "arrows":%4$s, "focusOnSelect": true, "adaptiveHeight":%17$s, "speed":%18$s, "responsive": [ { "breakpoint": 980, "settings": { "slidesToShow": %8$s, "slidesToScroll": %10$s, "arrows":%12$s,"dots":%14$s } } ,{ "breakpoint": 767, "settings": { "slidesToShow": %9$s, "slidesToScroll": %11$s, "arrows":%13$s,"dots":%15$s } } ] }\''
			,$slides_show,$slides_scroll,$show_dots,$show_arrows,$infinite_animation,$autoplay_animation,$autoplay_animation_speed,$slides_show_tablet,$slides_show_phone,$slides_scroll_tablet,$slides_scroll_phone,$show_arrows_tablet,$show_arrows_phone,$show_dots_tablet,$show_dots_phone,$pause_on_hover,$adaptive_height,$slide_animation_speed);

			$sync_slider_html = sprintf(
				'<section class="lwp-slick-slider slider big-slider" data-slick=\'{"slidesToShow": 1, "slidesToScroll": 1, "arrows":false, "fade":true, "asNavFor":".small-slider", "adaptiveHeight":true}\'>
					%1$s
		 		</section>',$images_output);
			
		}
		
		if(isset($arrow_color)){

			$arrow_color_responsive_active = et_pb_get_responsive_status( $arrow_color_last_edited );

			$arrow_color_values = array(
				'desktop' => $arrow_color,
				'tablet'  => $arrow_color_responsive_active ? $arrow_color_tablet : '',
				'phone'   => $arrow_color_responsive_active ? $arrow_color_phone : '',
			);

			et_pb_responsive_options()->generate_responsive_css( $arrow_color_values, '%%order_class%% .slick-next:before,%%order_class%% .slick-prev:before', 'color', $render_slug,'','color');
		}

		if(isset($arrow_color_hover) && $arrow_color_hover!=''){
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .slick-next:hover:before,%%order_class%% .slick-prev:hover:before',
				'declaration' => sprintf('color:%1$s;',$arrow_color_hover),
			) );			
		}

		if(isset($arrow_bg)){

			$arrow_bg_responsive_active = et_pb_get_responsive_status( $arrow_bg_last_edited );

			$arrow_bg_values = array(
				'desktop' => $arrow_bg,
				'tablet'  => $arrow_bg_responsive_active ? $arrow_bg_tablet : '',
				'phone'   => $arrow_bg_responsive_active ? $arrow_bg_phone : '',
			);

			et_pb_responsive_options()->generate_responsive_css( $arrow_bg_values, '%%order_class%% .slick-next,%%order_class%% .slick-prev', 'background-color', $render_slug,'','color');
		}
		
		if(isset($arrow_bg_hover) && $arrow_bg_hover!=''){
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .slick-next:hover,%%order_class%% .slick-prev:hover',
				'declaration' => sprintf('background-color:%1$s;',$arrow_bg_hover),
			) );			
		}		
		
		if(isset($dots_color)){

			$dots_color_responsive_active = et_pb_get_responsive_status( $dots_color_last_edited );

			$dots_color_values = array(
				'desktop' => $dots_color,
				'tablet'  => $dots_color_responsive_active ? $dots_color_tablet : '',
				'phone'   => $dots_color_responsive_active ? $dots_color_phone : '',
			);

			et_pb_responsive_options()->generate_responsive_css( $dots_color_values, '%%order_class%% .slick-dots li button', 'background-color', $render_slug,'','color');
		}

		  return sprintf( 
			'%3$s
			<section class="lwp-slick-slider slider small-slider %4$s" %2$s>
				%1$s
			</section>',$images_output,$data_slick,$sync_slider_html,$helper_class);
	
	}
}

new LWP_ImageCarousel;
