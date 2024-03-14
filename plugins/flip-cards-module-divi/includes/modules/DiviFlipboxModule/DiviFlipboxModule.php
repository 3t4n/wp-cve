<?php

class LWP_DiviFlipboxModule extends ET_Builder_Module {

	public $slug       = 'lwp_divi_flipbox';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'http://www.learnhowwp.com/divi-flip-cards-plugin',
		'author'     => 'learnhowwp.com',
		'author_uri' => 'http://www.learnhowwp.com/',
	);

	public function init() {
		$this->name = esc_html__( 'Flip Cards', 'lwp-divi-flipbox' );
		$this->main_css_element = '%%order_class%%';
		$this->icon ='g';		
	}

	public function get_fields() {
		$et_accent_color = et_builder_accent_color();

		return array(
		  'front_title' => array(
			'label'           => esc_html__( 'Front Title', 'lwp-divi-flipbox' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'The text before the Breadcrumbs', 'lwp-divi-flipbox' ),
			'toggle_slug'     => 'flipcard',
			'sub_toggle'  => 'front_card',			
			),
			'front_body' => array(
				'label'           => esc_html__( 'Content', 'lwp-divi-flipbox' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear on the front card inside the module.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'front_card',	
			),
			'use_front_icon' => array(
				'label'           => esc_html__( 'Use Front Icon', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'toggle_slug'         => 'icon',					
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'front_card',				
			),
			'front_src'                    => array(
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload a Front image', 'lwp-divi-flipbox' ),
				'choose_text'        => esc_attr__( 'Choose a Front Image', 'lwp-divi-flipbox' ),
				'update_text'        => esc_attr__( 'Set As Front Image', 'lwp-divi-flipbox' ),
				'hide_metadata'      => true,
				'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display on the front card.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'front_card',
				'show_if_not'     => array(
					'use_front_icon' => 'on',
				),				
			),									
			'front_icon' => array(
				'label'               => esc_html__( 'Front Icon', 'lwp-divi-flipbox' ),
				'type'                => 'et_font_icon_select',
				'renderer'            => 'et_pb_get_font_icon_list',
				'option_category'     => 'basic_option',
				'class'               => array( 'et-pb-font-icon' ),
				'description'         => esc_html__( 'Choose the icon for the front card.', 'lwp-divi-flipbox' ),
				'show_if'         => array(
					'use_front_icon' => 'on',
				),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'front_card',									
			),
			'front_background_color' => array(
				'label'             => esc_html__( 'Front Background Color', 'lwp-divi-flipbox' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Here you can define a custom color for the backgroun of the front card.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'front_card',
			),				
			'front_icon_color' => array(
				'default'           => $et_accent_color,
				'label'             => esc_html__( 'Icon Color', 'lwp-divi-flipbox' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'lwp-divi-flipbox' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'front_icon_settings',
				'show_if'         => array(
					'use_front_icon' => 'on',
				),				
			),			
			'front_use_circle' => array(
				'label'           => esc_html__( 'Circle Icon', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'front_icon_settings',
				'description'      => esc_html__( 'Here you can choose whether icon set above should display within a circle.', 'lwp-divi-flipbox' ),
				'default_on_front'=> 'off',
				'show_if'         => array(
					'use_front_icon' => 'on',
				),				
			),
			'front_circle_color' => array(
				'default'         => $et_accent_color,
				'label'           => esc_html__( 'Circle Color', 'lwp-divi-flipbox' ),
				'type'            => 'color-alpha',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle.', 'lwp-divi-flipbox' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'front_icon_settings',
				'show_if'         => array(
					'front_use_circle' => 'on',
				),				
			),
			'front_use_circle_border' => array(
				'label'           => esc_html__( 'Show Circle Border', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'description' => esc_html__( 'Here you can choose whether if the icon circle border should display.', 'lwp-divi-flipbox' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'front_icon_settings',
				'default_on_front'  => 'off',
				'show_if'         => array(
					'front_use_circle' => 'on',
				),				
			),
			'front_circle_border_color' => array(
				'default'         => $et_accent_color,
				'label'           => esc_html__( 'Circle Border Color', 'lwp-divi-flipbox' ),
				'type'            => 'color-alpha',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle border.', 'lwp-divi-flipbox' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'front_icon_settings',
				'show_if'         => array(
					'front_use_circle_border' => 'on',
				),				
			),
			'front_icon_alignment' => array(
				'label'           => esc_html__( 'Image/Icon Alignment', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Align image/icon to the left, right or center.', 'lwp-divi-flipbox' ),
				'type'            => 'align',
				'option_category' => 'layout',
				'options'         => et_builder_get_text_orientation_options( array( 'justified' ) ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'front_icon_settings',
				'default'         => 'center',
			),
			'front_use_icon_font_size' => array(
				'label'           => esc_html__( 'Use Icon Font Size', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'If you would like to control the size of the icon, you must first enable this option.', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'font_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'front_icon_settings',
				'default_on_front' => 'off',
				'show_if'         => array(
					'use_front_icon' => 'on',
				),				
			),
			'front_icon_font_size' => array(
				'label'           => esc_html__( 'Icon Front Size', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'lwp-divi-flipbox' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'front_icon_settings',
				'default'         => '96px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings' => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'show_if'         => array(
					'front_use_icon_font_size' => 'on',
				),
			),														  
			'back_title' => array(
				'label'           => esc_html__( 'Back Title', 'lwp-divi-flipbox' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the Title for the back card here.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'back_card',			
			),
			'back_body' => array(
				'label'           => esc_html__( 'Content', 'lwp-divi-flipbox' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear on the back card.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'back_card',	
			),
			'button_text'    => array(
				'label'           => esc_html__( 'Button Text', 'lwp-divi-flipbox'  ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your desired button text, or leave blank for no button.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'main_content',
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'back_card',				
			),			
			'button_url'     => array(
				'label'           => esc_html__( 'Button Link URL', 'lwp-divi-flipbox' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input the destination URL for your button.', 'lwp-divi-flipbox' ),
				'toggle_slug'      => 'link_options',
			),
			'button_url_new_window' => array(
				'label'            => esc_html__( 'Button Link Target', 'lwp-divi-flipbox' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'In The Same Window', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'In The New Tab', 'lwp-divi-flipbox' ),
				),
				'toggle_slug'      => 'link_options',
				'description'      => esc_html__( 'Here you can choose whether or not your link opens in a new window', 'lwp-divi-flipbox' ),
				'default_on_front' => 'off',
			),									
			'use_back_icon' => array(
				'label'           => esc_html__( 'Use Back Icon', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'toggle_slug'         => 'icon',					
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'back_card',				
			),
			'back_src'                    => array(
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload a Back image', 'lwp-divi-flipbox' ),
				'choose_text'        => esc_attr__( 'Choose a Back Image', 'lwp-divi-flipbox' ),
				'update_text'        => esc_attr__( 'Set As Back Image', 'lwp-divi-flipbox' ),
				'hide_metadata'      => true,
				'description'        => esc_html__( 'Upload your desired back image, or type in the URL to the image you would like to display.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'back_card',
				'show_if_not'     => array(
					'use_back_icon' => 'on',
				),				
			),												
			'back_icon' => array(
				'label'               => esc_html__( 'Front Icon', 'lwp-divi-flipbox' ),
				'type'                => 'et_font_icon_select',
				'renderer'            => 'et_pb_get_font_icon_list',
				'option_category'     => 'basic_option',
				'class'               => array( 'et-pb-font-icon' ),
				'description'         => esc_html__( 'Choose the icon for the back card.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'back_card',
				'show_if'         => array(
					'use_back_icon' => 'on',
				),					
			),
			'back_background_color' => array(
				'label'             => esc_html__( 'Back Background Color', 'lwp-divi-flipbox' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Here you can define a custom color for the backgroun of the back card.', 'lwp-divi-flipbox' ),
				'toggle_slug'     => 'flipcard',
				'sub_toggle'  => 'back_card',
			),			
			'back_icon_color' => array(
				'default'           => $et_accent_color,
				'label'             => esc_html__( 'Icon Color', 'lwp-divi-flipbox' ),
				'type'              => 'color-alpha',
				'description'       => esc_html__( 'Here you can define a custom color for your icon.', 'lwp-divi-flipbox' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'back_icon_settings',
				'show_if'         => array(
					'use_back_icon' => 'on',
				),				
			),
			'back_use_circle' => array(
				'label'           => esc_html__( 'Circle Icon', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'back_icon_settings',
				'description'      => esc_html__( 'Here you can choose whether icon set above should display within a circle.', 'lwp-divi-flipbox' ),
				'default_on_front'=> 'off',
				'show_if'         => array(
					'use_back_icon' => 'on',
				),				
			),
			'back_circle_color' => array(
				'default'         => $et_accent_color,
				'label'           => esc_html__( 'Circle Color', 'lwp-divi-flipbox' ),
				'type'            => 'color-alpha',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle.', 'lwp-divi-flipbox' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'back_icon_settings',
				'show_if'         => array(
					'back_use_circle' => 'on',
				),				
			),
			'back_use_circle_border' => array(
				'label'           => esc_html__( 'Show Circle Border', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'description' => esc_html__( 'Here you can choose whether if the icon circle border should display.', 'lwp-divi-flipbox' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'back_icon_settings',
				'default_on_front'  => 'off',
				'show_if'         => array(
					'back_use_circle' => 'on',
				),				
			),
			'back_circle_border_color' => array(
				'default'         => $et_accent_color,
				'label'           => esc_html__( 'Circle Border Color', 'lwp-divi-flipbox' ),
				'type'            => 'color-alpha',
				'description'     => esc_html__( 'Here you can define a custom color for the icon circle border.', 'lwp-divi-flipbox' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'back_icon_settings',
				'show_if'         => array(
					'back_use_circle_border' => 'on',
				),				
			),
			'back_icon_alignment' => array(
				'label'           => esc_html__( 'Image/Icon Alignment', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Align image/icon to the left, right or center.', 'lwp-divi-flipbox' ),
				'type'            => 'align',
				'option_category' => 'layout',
				'options'         => et_builder_get_text_orientation_options( array( 'justified' ) ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'back_icon_settings',
				'default'         => 'center',
			),
			'back_use_icon_font_size' => array(
				'label'           => esc_html__( 'Use Icon Font Size', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'If you would like to control the size of the icon, you must first enable this option.', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'font_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'back_icon_settings',
				'default_on_front' => 'off',
				'show_if'         => array(
					'use_back_icon' => 'on',
				),				
			),
			'back_icon_font_size' => array(
				'label'           => esc_html__( 'Icon Font Size', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'lwp-divi-flipbox' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'back_icon_settings',
				'default'         => '96px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'range_settings' => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'show_if'         => array(
					'back_use_icon_font_size' => 'on',
				),
			),
			'content_max_width' => array(
				'label'           => esc_html__( 'Content Width', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Adjust the width of the cards in the Flip Box.', 'lwp-divi-flipbox' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'width',
				'validate_unit'   => true,
				'default'         => '550px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'allow_empty'     => true,
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '1100',
					'step' => '1',
				),
			),
			'box_height' => array(
				'label'           => esc_html__( 'Flip Box Height', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Set a height for the cards in the Flip Card', 'lwp-divi-flipbox' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'width',
				'validate_unit'   => true,
				'default'         => '450px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'allow_empty'     => true,
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '1100',
					'step' => '1',
				),				
			),
			'flip_card_animation' => array(
				'label'           => esc_html__( 'Flip Animation', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Choose the animation for the flip card', 'lwp-divi-flipbox' ),
				'type'            => 'select',
				'options'         => array(
					'flip_up' => esc_html__( 'Up', 'lwp-overlay-images' ),
					'flip_down'  => esc_html__( 'Down', 'lwp-overlay-images' ),
					'flip_left' => esc_html__( 'Left', 'lwp-overlay-images' ),
					'flip_right'  => esc_html__( 'Right', 'lwp-overlay-images' ),
				),
				'default'=> 'flip_right',
				'tab_slug'        => 'advanced',				
				'toggle_slug'     => 'flip_animation_settings',					
			),
			'flip_card_animation_duration' => array(
				'label'           => esc_html__( 'Animation Duration', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Choose the animation duration for the flip card', 'lwp-divi-flipbox' ),
				'type'              => 'range',
				'option_category'   => 'configuration',
				'range_settings'    => array(
					'min'  => 0,
					'max'  => 2000,
					'step' => 50,
				),
				'default'             => '800ms',
				'description'         => esc_html__( 'Speed up or slow down your animation by adjusting the animation duration. Units are in milliseconds and the default animation duration is one second.', 'lwp-divi-flipbox' ),
				'validate_unit'       => true,
				'fixed_unit'          => 'ms',
				'fixed_range'         => true,	
				'tab_slug'        => 'advanced',				
				'toggle_slug'     => 'flip_animation_settings',					
			),
			'flip_card_animation_timing' => array(
				'label'           => esc_html__( 'Animation Speed Curve', 'lwp-divi-flipbox' ),
				'description'     => esc_html__( 'Here you can adjust the easing method of your animation. Easing your animation in and out will create a smoother effect when compared to a linear speed curve.', 'lwp-divi-flipbox' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'default'           => 'ease-in-out',
				'description'       => esc_html__( 'Here you can adjust the easing method of your animation. Easing your animation in and out will create a smoother effect when compared to a linear speed curve.', 'lwp-divi-flipbox' ),
				'options'         => array(
					'ease-in-out' => esc_html__( 'Ease-In-Out', 'lwp-divi-flipbox' ),
					'ease'        => esc_html__( 'Ease', 'lwp-divi-flipbox' ),
					'ease-in'     => esc_html__( 'Ease-In', 'lwp-divi-flipbox' ),
					'ease-out'    => esc_html__( 'Ease-Out', 'lwp-divi-flipbox' ),
					'linear'      => esc_html__( 'Linear', 'lwp-divi-flipbox' ),
				),		
				'tab_slug'        => 'advanced',				
				'toggle_slug'     => 'flip_animation_settings',					
			),
			'flip_card_3d_effect' => array(
				'label'           => esc_html__( '3D Effect', 'lwp-divi-flipbox' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'lwp-divi-flipbox' ),
					'on'  => esc_html__( 'Yes', 'lwp-divi-flipbox' ),
				),
				'tab_slug'        => 'advanced',				
				'toggle_slug'     => 'flip_3d_settings',					
			),																								
		);
	  }

	  public function get_settings_modal_toggles() {
		return array(
		  'advanced' => array(
			'toggles' => array(
			  'flipcard' => array(
				'priority' => 24,
				'sub_toggles' => array(
				  'front_card' => array(
					'name' => 'Front',
				  ),
				  'back_card' => array(
					'name' => 'Back',
				  ),
				),
				'tabbed_subtoggles' => true,
				'title' => 'Content',
			  ),
			  'front_icon_settings' => esc_html__( 'Front Icon', 'lwp-divi-flipbox' ),
			  'back_icon_settings' => esc_html__( 'Back Icon', 'lwp-divi-flipbox' ),			  
			  'flip_animation_settings' => esc_html__( 'Flip Animation', 'lwp-divi-flipbox' ),			  
			  'flip_3d_settings' => esc_html__( '3D Effect', 'lwp-divi-flipbox' ),			  
			),
		  ),
		);
	  }

	public function get_advanced_fields_config() {
		return array(						
			'fonts' => array(
				'header-front' => array(
					'css'          => array(
						'main'      => "{$this->main_css_element} h2.front_title, {$this->main_css_element} h1.front_title, {$this->main_css_element} h3.front_title, {$this->main_css_element} h4.front_title, {$this->main_css_element} h5.front_title, {$this->main_css_element} h6.front_title",
						'important' => 'all',
					),
					'header_level' => array(
						'default' => 'h4',
					),
					'label'        => esc_html__( 'Front Title', 'lwp-divi-flipbox' ),
				),
				'header-back' => array(
					'css'          => array(
						'main'      => "{$this->main_css_element} h2.back_title, {$this->main_css_element} h1.back_title, {$this->main_css_element} h3.back_title, {$this->main_css_element} h4.back_title, {$this->main_css_element} h5.back_title, {$this->main_css_element} h6.back_title",
						'important' => 'all',
					),
					'header_level' => array(
						'default' => 'h4',
					),
					'label'        => esc_html__( 'Back Title', 'lwp-divi-flipbox' ),
				),
				'front-body'   => array(
					'css'   => array(
						'main' => "{$this->main_css_element} .front_body",
					),
					'label' => esc_html__( 'Front Body', 'lwp-divi-flipbox' ),
				),
				'back-body'   => array(
					'css'   => array(
						'main' => "{$this->main_css_element} .back_body",
					),
					'label' => esc_html__( 'Back Body', 'lwp-divi-flipbox' ),
				),			
			),
			'front_icon_settings'=> array(
				'css' => array(
					'main' => '%%order_class%% .front_icon',
				),
			),
			'back_icon_settings'=> array(
				'css' => array(
					'main' => '%%order_class%% .back_icon',
				),
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main' => "{$this->main_css_element} .flip_card",
					),
				),
			),
			'borders' => array(
				'default' => array(
					'css'      => array(
						'main' => array(
							'border_styles' => "{$this->main_css_element} .flip_card",
							'border_radii' => "{$this->main_css_element} .flip_card",
						),
					),
				),
			),
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Button', 'lwp-divi-flipbox' ),
					'css'            => array(
						'main'         => "{$this->main_css_element} .et_pb_button",
						'limited_main' => "{$this->main_css_element} .et_pb_button",
						'alignment'    => "{$this->main_css_element} .et_pb_button_wrapper",
					),					
					'box_shadow'     => array(
						'css' => array(
							'main' => '%%order_class%% .et_pb_button',
						),
					),
					'margin_padding' => array(
						'css' => array(
							'main'      => "%%order_class%% .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'  => true,
				),
			),						
			'background' => false										
		);
	}
	
	public function get_custom_css_fields_config() {
		return array(
			'front_card' => array(
				'label'    => esc_html__( 'Front Card', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box .flip_box_front',
			),
			'back_card' => array(
				'label'    => esc_html__( 'Back Card', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box .flip_box_back',
			),
			'flip_box' => array(
				'label'    => esc_html__( 'Flip Box', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box',
			),			
			'flip_box_inner' => array(
				'label'    => esc_html__( 'Box Inner', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_inner',
			),			
			'front_title' => array(
				'label'    => esc_html__( 'Front Title', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_front .front_title',
			),			
			'back_title' => array(
				'label'    => esc_html__( 'Back Title', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_back .back_title',
			),			
			'front_body' => array(
				'label'    => esc_html__( 'Front Body', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_front .front_body',
			),			
			'back_body' => array(
				'label'    => esc_html__( 'Back Body', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_back .back_body',
			),			
			'font_image' => array(
				'label'    => esc_html__( 'Front Image', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_front .image_wrap',
			),			
			'back_image' => array(
				'label'    => esc_html__( 'Back Image', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_back .image_wrap',
			),			
			'back_button' => array(
				'label'    => esc_html__( 'Back Button', 'lwp-divi-flipbox' ),
				'selector' => '%%order_class%% .flip_box_back .et_pb_button',
			),			
		);
	}	

	public function render( $attrs, $content = null, $render_slug ) {

		$front_title					= esc_html($this->props['front_title']);		
		$front_body						= $this->props['front_body'];	
		$front_background_color			= esc_attr($this->props['front_background_color']);
		$front_img_src					= $this->props['front_src'];
			
		
		$back_title						= esc_html($this->props['back_title']);		
		$back_body						= $this->props['back_body'];
		$back_background_color			= esc_attr($this->props['back_background_color']);	
		$back_img_src					= $this->props['back_src'];
		
		$use_front_icon					= $this->props['use_front_icon'];
		$front_icon						= $this->props['front_icon'];
		$front_icon_color 				= esc_attr($this->props['front_icon_color']);
		$front_use_circle 				= $this->props['front_use_circle'];
		$front_circle_color 			= esc_attr($this->props['front_circle_color']);
		$front_use_circle_border 		= $this->props['front_use_circle_border'];
		$front_circle_border_color 		= esc_attr($this->props['front_circle_border_color']);
		$front_icon_alignment 			= esc_attr($this->props['front_icon_alignment']);
		$front_use_icon_font_size 		= $this->props['front_use_icon_font_size'];
		$front_icon_font_size 			= esc_attr($this->props['front_icon_font_size']);

		$use_back_icon					= $this->props['use_back_icon'];
		$back_icon						= $this->props['back_icon'];
		$back_icon_color 				= esc_attr($this->props['back_icon_color']);
		$back_use_circle 				= $this->props['back_use_circle'];
		$back_circle_color 				= esc_attr($this->props['back_circle_color']);
		$back_use_circle_border 		= $this->props['back_use_circle_border'];
		$back_circle_border_color 		= esc_attr($this->props['back_circle_border_color']);
		$back_icon_alignment 			= esc_attr($this->props['back_icon_alignment']);
		$back_use_icon_font_size 		= $this->props['back_use_icon_font_size'];
		$back_icon_font_size 			= esc_attr($this->props['back_icon_font_size']);


		$flip_card_animation			= esc_attr($this->props['flip_card_animation']);
		$flip_card_animation_duration 	= $this->props['flip_card_animation_duration'];
		$flip_card_animation_timing 	= $this->props['flip_card_animation_timing'];
		$content_max_width				= $this->props['content_max_width'];
		$box_height						= $this->props['box_height'];
		$flip_card_3d_effect			= $this->props['flip_card_3d_effect'];

		$header_front_level ='h4';
		$header_back_level ='h4';

		$button_text					= $this->props['button_text']; 
		$button_url						= $this->props['button_url'];
		$button_url_new_window 			= $this->props['button_url_new_window']; 

		//Props generated by advanced options
		$button_custom         = $this->props['custom_button'];
		$button_rel            = $this->props['button_rel'];
		$button_icon           = $this->props['button_icon'];		

		if(isset($this->attrs_unprocessed['header-front_level']))
			$header_front_level				= $this->attrs_unprocessed['header-front_level'];

		if(isset($this->attrs_unprocessed['header-back_level']))	
			$header_back_level				= $this->attrs_unprocessed['header-back_level'];

		$front_icon_html='';
		$back_icon_html='';
		$front_title_html='';
		$back_title_html='';
		$front_body_html='';
		$back_body_html='';
		
		if($front_title!='')
			$front_title_html=sprintf('<%2$s class="front_title">%1$s</%2$s>',$front_title,$header_front_level);

		if($back_title!='')
			$back_title_html=sprintf('<%2$s class="back_title">%1$s</%2$s>',$back_title,$header_back_level);
		
		if($front_body!='')
			$front_body_html = sprintf('<div class="front_body">%1$s</div>',do_shortcode(html_entity_decode($front_body)));

		if($back_body!='')
			$back_body_html = sprintf('<div class="back_body">%1$s</div>',do_shortcode(html_entity_decode($back_body)));

		if($front_background_color!=''){
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .flip_box_front',
				'declaration' => sprintf('background-color:%1$s;',$front_background_color),
			) );			
		}

		if($back_background_color!=''){
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .flip_box_back',
				'declaration' => sprintf('background-color:%1$s;',$back_background_color),
			) );			
		}			

		//Front Icon
		if($use_front_icon==='on'){		
			$front_icon=esc_attr( et_pb_process_font_icon($front_icon));	//Processing the Front Icon

			$circle_class='';
			if($front_use_circle==='on')
				$circle_class='icon_circle';			

			$front_icon_html=sprintf('<span class="image_wrap"><span class="front_icon et-pb-icon %2$s">%1$s</span></span>',$front_icon,$circle_class);			

			$front_icon_style='';

			$front_icon_style.=sprintf('color:%1$s;',$front_icon_color);

			if($front_use_circle==='on')
				$front_icon_style.=sprintf('background-color:%1$s;',$front_circle_color);

			if($front_use_circle_border==='on')
				$front_icon_style.=sprintf('border:3px solid; border-color:%1$s;',$front_circle_border_color);

			if($front_use_icon_font_size==='on')
				$front_icon_style.=sprintf('font-size:%1$s;',$front_icon_font_size);

			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .front_icon',
				'declaration' => $front_icon_style,
			) );

			$icon_selector_front = '%%order_class%% .front_icon';
			// Font Icon Styles.
			$this->generate_styles(
				array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'front_icon',
					'important'      => true,
					'selector'       => $icon_selector_front,
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				)
			);			
		}
		else{
			$front_icon_html=sprintf('<span class="image_wrap"><img src="%1$s"/></span>',$front_img_src);						
		}
		
		//Back Icon
		if($use_back_icon==='on'){		
			$back_icon=esc_attr( et_pb_process_font_icon($back_icon));	//Processing the Back Icon

			$circle_class='';
			if($back_use_circle==='on')
				$circle_class='icon_circle';			

			$back_icon_html=sprintf('<span class="image_wrap"><span class="back_icon et-pb-icon %2$s">%1$s</span></span>',$back_icon,$circle_class);			

			$back_icon_style='';

			$back_icon_style.=sprintf('color:%1$s;',$back_icon_color);

			if($back_use_circle==='on')
				$back_icon_style.=sprintf('background-color:%1$s;',$back_circle_color);

			if($back_use_circle_border==='on')
				$back_icon_style.=sprintf('border:3px solid; border-color:%1$s;',$back_circle_border_color);

			if($back_use_icon_font_size==='on')
				$back_icon_style.=sprintf('font-size:%1$s;',$back_icon_font_size);

			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .back_icon',
				'declaration' => $back_icon_style,
			) );

			$icon_selector_front = '%%order_class%% .back_icon';
			// Font Icon Styles.
			$this->generate_styles(
				array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'back_icon',
					'important'      => true,
					'selector'       => $icon_selector_front,
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				)
			);

		}
		else{
			$back_icon_html=sprintf('<span class="image_wrap"><img src="%1$s"/></span>',$back_img_src);						
		}

		ET_Builder_Element::set_style( $render_slug, array(
			'selector'    => '%%order_class%% .flip_box_front .image_wrap',
			'declaration' => sprintf('text-align:%1$s;',$front_icon_alignment),
		) );

		ET_Builder_Element::set_style( $render_slug, array(
			'selector'    => '%%order_class%% .flip_box_back .image_wrap',
			'declaration' => sprintf('text-align:%1$s;',$back_icon_alignment),
		) );


		if(isset($flip_card_animation_duration)){
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .flip_box_inner',
				'declaration' => sprintf('transition-duration:%1$s;',$flip_card_animation_duration),
			) );
		}

		if(isset($flip_card_animation_timing)){
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .flip_box_inner',
				'declaration' => sprintf('transition-timing-function:%1$s;',$flip_card_animation_timing),
			) );
		}		

		if($box_height!=''){
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .flip_box',
				'declaration' => sprintf('height:%1$s!important;',$box_height),
			) );
		}
		
		// Render button
		$button = $this->render_button( array(
			'button_text'      => $button_text,
			'button_url'       => $button_url,
			'url_new_window'   => $button_url_new_window,			
			'button_custom'    => $button_custom,
			'button_rel'       => $button_rel,
			'custom_icon'      => $button_icon,
		) );
		
		if($flip_card_3d_effect=='on')
			$flip_card_3d_effect='lwp_flip_box_3d';
		else
			$flip_card_3d_effect='';

		//<pre style="background:#eee;"><pre>var_export($this, true)</pre>

		return sprintf( 
		'<div class="flip_box %7$s %9$s">
			<div class="flip_box_inner">
				<div class="flip_box_front flip_card">
					%1$s
					%3$s
					%5$s
				</div>
				<div class="flip_box_back flip_card">
					%2$s
					%4$s
					%6$s
					%8$s
				</div>
			</div>
		</div>'
		, $front_icon_html,$back_icon_html,$front_title_html,$back_title_html,$front_body_html,$back_body_html,$flip_card_animation,$button, $flip_card_3d_effect);
	}
}

new LWP_DiviFlipboxModule;