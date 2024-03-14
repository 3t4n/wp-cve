<?php

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class PL_Theme_Bizstrait_Customizer {


	private $defaults;
	private $sections;

	public function __construct() {
		 /**
		 * Customizer defaults
		 */
		$this->defaults = $this->generate_defaults();
		/*
		 * Array of upgrade notice classes
		 */
		$this->sections = array(
			'slider_upgrade_notice'      => 'slider_section',
			'service_upgrade_notice'     => 'service_section',
			'testimonial_upgrade_notice' => 'testimonial_section',
			'contactus_upgrade_notice'   => 'contactus_section',
		);
		add_action( 'customize_register', array( $this, 'pluglab_customizer_panel' ) );
		add_action( 'customize_register', array( $this, 'pluglab_customizer_section' ) );
		add_action( 'customize_register', array( $this, 'pluglab_customizer_control' ) );
	}

	public function pluglab_customizer_panel( $wp_customize ) {
		$wp_customize->add_panel(
			'homepage_template_settings',
			array(
				'title'       => __( 'Homepage Template' ),
				'description' => 'Template sections setting to manage like hide/show, etc.', // Include html tags such as <p>.
				// 'priority'    => 1, // Mixed with top-level-section hierarchy.
			)
		);
		$wp_customize->add_panel(
			'bizstrait_template_settings',
			array(
				'title' => __( 'Other Templates', 'bizstrait' ),
				// 'description' => 'Template sections setting to manage like hide/show, etc.', // Include html tags such as <p>.
				// 'priority'    => 50,
			)
		);
	}

	public function pluglab_customizer_section( $wp_customize ) {
		// $wp_customize->add_section(
		// 'top_header',
		// array(
		// 'priority' => 2,
		// 'title'    => __( 'Top Header', 'pluglab' ),
		// 'panel'    => 'bizstrait_header_setting',
		// )
		// );

		$wp_customize->add_section(
			'slider_section',
			array(
				'title'       => __( 'Slider', 'pluglab' ),
				'description' => esc_html__( 'Manage slider banner', 'pluglab' ),
				'panel'       => 'homepage_template_settings',
			)
		);

		$wp_customize->add_section(
			'callout_section',
			array(
				'title'       => __( 'Callout', 'pluglab' ),
				'description' => esc_html__( 'Manage callout section', 'pluglab' ),
				'panel'       => 'homepage_template_settings',
			)
		);

		$wp_customize->add_section(
			'service_section',
			array(
				'title'       => __( 'Service', 'pluglab' ),
				'description' => esc_html__( 'Manage service section', 'pluglab' ),
				'panel'       => 'homepage_template_settings',
			)
		);

		$wp_customize->add_section(
			'portfolio_section',
			array(
				'title'       => __( 'portfolio', 'pluglab' ),
				'description' => esc_html__( 'Manage all projects', 'pluglab' ),
				'panel'       => 'homepage_template_settings',
			)
		);

		$wp_customize->add_section(
			'cta_section',
			array(
				'title'       => __( 'Call to action', 'pluglab' ),
				'description' => esc_html__( 'Manage CTA section', 'pluglab' ),
				'panel'       => 'homepage_template_settings',
			)
		);

		$wp_customize->add_section(
			'testimonial_section',
			array(
				'title'       => __( 'Testimonial', 'pluglab' ),
				'description' => esc_html__( 'Manage Testimonial section', 'pluglab' ),
				'panel'       => 'homepage_template_settings',
			)
		);
		$wp_customize->add_section(
			'blog_section',
			array(
				'title'       => __( 'Blog', 'pluglab' ),
				'description' => esc_html__( 'Manage Blog section', 'pluglab' ),
				'panel'       => 'homepage_template_settings',
			)
		);

		/**
		 * Add Contact section for Template Panel
		 */
		$wp_customize->add_section(
			'contactus_section',
			array(
				'title' => __( 'Contact Template', 'bizstrait' ),
				'panel' => 'bizstrait_template_settings',
			)
		);
		$wp_customize->add_section(
			'aboutus_section',
			array(
				'title' => __( 'About Template', 'bizstrait' ),
				'panel' => 'bizstrait_template_settings',
			)
		);

		$wp_customize->add_section(
			'top_header',
			array(
				// 'priority' => 12,
				'title' => __( 'Top Header', 'bizstrait' ),
				'panel' => 'bizstrait_header_setting',
			)
		);

		/* Menu */
		$wp_customize->add_section(
			'menu_part',
			array(
				// 'priority' => 12,
				'title' => __( 'Menu', 'bizstrait' ),
				'panel' => 'bizstrait_header_setting',
			)
		);

	}

	public function pluglab_customizer_control( $wp_customize ) {
		/**
		 * slider start
		 */
		$this->slider( $wp_customize );

		$this->callout( $wp_customize );

		$this->portfolio( $wp_customize );

		$this->aboutus( $wp_customize );

		/**
		 * Service start
		 */
		$this->service( $wp_customize );

		/**
		 * Testimonial
		 */
		$this->testimonial( $wp_customize );

		/**
		 * Blog
		 */
		$this->blog( $wp_customize );

		$this->upgradeNotice( $wp_customize );

		$this->header( $wp_customize );
		// $this->copyright($wp_customize);
		$this->scrollbar_btn( $wp_customize );
		$this->template_settings( $wp_customize );
	}

	function header( $wp_customize ) {
		$wp_customize->add_setting( 'separator_topbar_nfo' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_topbar_nfo',
				array(
					'settings'      => 'separator_topbar_nfo',
					'priority'      => 0,
					'section'       => 'top_header',
					'separator_txt' => 'Topbar Info',
				)
			)
		);

		$wp_customize->add_setting(
			'hide_show_top_details',
			array(
				'default'    => '1',
				'capability' => 'edit_theme_options',
				// 'sanitize_callback' => 'pluglab_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'hide_show_top_details',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'top_header',
				)
			)
		);

		// icon //
		$wp_customize->add_setting(
			'top_mail_icon',
			array(
				'default'           => 'fas fa-envelope',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Icon_Picker_New(
				$wp_customize,
				'top_mail_icon',
				array(
					'label'    => __( 'Icon', 'pluglab' ),
					'section'  => 'top_header',
					'priority' => 2,
					'iconset'  => 'fa',
				)
			)
		);

		// Top Text //
		$wp_customize->add_setting(
			'top_header_mail_text',
			array(
				'default'           => 'youremail@gmail.com',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'top_header_mail_text',
			array(
				'label'       => __( 'Mail Text', 'pluglab' ),
				'section'     => 'top_header',
				'type'        => 'text',
				'priority'    => 3,
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
				),
			)
		);
		// phone

		// icon //
		$wp_customize->add_setting(
			'top_phone_icon',
			array(
				'default'           => 'fas fa-phone-alt',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		// $wp_customize->add_setting(
		// 'top_phone_icon',
		// array(
		// 'default'           => 'fa-map-marker',
		// 'sanitize_callback' => 'sanitize_text_field',
		// 'capability'        => 'edit_theme_options',
		// )
		// );

		$wp_customize->add_control(
			new PL_customizer_Control_Icon_Picker_New(
				$wp_customize,
				'top_phone_icon',
				array(
					'label'    => __( 'Icon', 'pluglab' ),
					'priority' => 4,
					'section'  => 'top_header',
					'iconset'  => 'fa',
				)
			)
		);

		// Top Text //
		$wp_customize->add_setting(
			'top_header_phone_text',
			array(
				'default' => '134-566-7680',
			)
		);
		$wp_customize->add_control(
			'top_header_phone_text',
			array(
				'label'       => __( 'Phone Text', 'pluglab' ),
				'section'     => 'top_header',
				'type'        => 'text',
				'priority'    => 5,
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
				),
			)
		);

		$wp_customize->add_setting( 'separator_topbar_social_icons' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_topbar_social_icons',
				array(
					'settings'      => 'separator_topbar_social_icons',
					'priority'      => 6,
					'section'       => 'top_header',
					'separator_txt' => 'Topbar Social Links',
				)
			)
		);

		$wp_customize->add_setting(
			'social_icon_enable_disable',
			array(
				'default'    => '1',
				'capability' => 'edit_theme_options',
				// 'sanitize_callback' => 'pluglab_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'social_icon_enable_disable',
				array(
					'priority' => 7,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'top_header',
				)
			)
		);

		/**
		 * Customizer Repeater
		 */
		$wp_customize->add_setting(
			'bizstrait_social_icons',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
				'default'           => pluglab_get_social_icon_default(),
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Repeater_New(
				$wp_customize,
				'bizstrait_social_icons',
				array(
					'label'                            => esc_html__( 'Social Icons', 'pluglab' ),
					'priority'                         => 8,
					'section'                          => 'top_header',
					'add_field_label'                  => esc_html__( 'Add New Social', 'pluglab' ),
					'item_name'                        => esc_html__( 'Social', 'pluglab' ),
					'customizer_repeater_icon_control' => true,
					'customizer_repeater_link_control' => true,
				)
			)
		);

		$wp_customize->add_setting( 'separator_topbar_searchicon' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_topbar_searchicon',
				array(
					'settings'      => 'separator_topbar_searchicon',
					'section'       => 'menu_part',
					'priority'      => 11,
					'separator_txt' => 'Search icon',
				)
			)
		);

		/**
	 * Search
	 */
		$wp_customize->add_setting(
			'topbar_search_icon_display',
			array(
				'transport'         => 'refresh',
				'default'           => 1,
				'sanitize_callback' => 'bizstrait_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new Bizstrait_Toggle_Switch_Custom_Control(
				$wp_customize,
				'topbar_search_icon_display',
				array(
					'priority' => 12,
					'label'    => __( 'Search icon', 'bizstrait' ),
					'section'  => 'menu_part',
				)
			)
		);

		$wp_customize->add_setting( 'separator_topbar_button' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_topbar_button',
				array(
					'settings'      => 'separator_topbar_button',
					'section'       => 'menu_part',
					'priority'      => 13,
					'separator_txt' => 'Action Button',
				)
			)
		);

		$wp_customize->add_setting(
			'menu_inline_btn_enable_disable',
			array(
				'default'           => '1',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'bizstrait_switch_sanitization',
			)
		);

		$wp_customize->add_control(
			new Bizstrait_Toggle_Switch_Custom_Control(
				$wp_customize,
				'menu_inline_btn_enable_disable',
				array(
					'label'    => __( 'Menu Button', 'bizstrait' ),
					'priority' => 14,
					'section'  => 'menu_part',
				)
			)
		);

		$wp_customize->add_setting(
			'menu_inline_btn_text',
			array(
				'default'           => __( 'Lorem', 'bizstrait' ),
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);
		$wp_customize->add_control(
			'menu_inline_btn_text',
			array(
				'label'       => __( 'Button Text', 'bizstrait' ),
				'section'     => 'menu_part',
				'type'        => 'text',
				'priority'    => 15,
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
				),
			)
		);

		$wp_customize->add_setting(
			'menu_inline_btn_link',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'menu_inline_btn_link',
			array(
				'label'    => __( 'Link', 'bizstrait' ),
				'priority' => 15,
				'section'  => 'menu_part',
				'type'     => 'text',
			)
		);

		// $wp_customize->add_setting(
		// 'topbar_search_icon_display',
		// array(
		// 'transport'         => 'refresh',
		// 'default'           => 1,
		// 'sanitize_callback' => 'customizer_switch_sanitization',//todo
		// )
		// );
		// $wp_customize->add_control(
		// new PL_Customizer_Control_Toggle_Switch_Custom(
		// $wp_customize,
		// 'topbar_search_icon_display',
		// array(
		// 'priority' => 12,
		// 'label'   => __( 'Display', 'pluglab' ),
		// 'section' => 'top_header',
		// )
		// )
		// );
	}

	function slider( $wp_customize ) {
		$wp_customize->add_setting(
			'slider_display',
			array(
				'default'   => $this->defaults['slider_display'],
				'transport' => 'refresh',
				// 'sanitize_callback' => 'pluglab_customizer_switch_sanitization',//todo
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'slider_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'slider_section',
				)
			)
		);

		$wp_customize->add_setting(
			'slider_repeater',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
			)
		);
		$wp_customize->add_control(
			new PL_customizer_Control_Repeater_New(
				$wp_customize,
				'slider_repeater',
				array(
					'label'                                => esc_html__( 'Slider', 'customizer-repeater' ),
					'section'                              => 'slider_section',
					'priority'                             => 4,
					'add_field_label'                      => esc_html__( 'Add New Slider', 'pluglab' ),
					'active_callback'                      => 'plugLab_slider_fnback',
					'item_name'                            => esc_html__( 'Slide Content', 'pluglab' ),
					'customizer_repeater_image_control'    => true,
					'customizer_repeater_icon_control'     => false,
					'customizer_repeater_image_icon_choice_control' => false,
					'customizer_repeater_title_control'    => true,
					'customizer_repeater_subtitle_control' => true,
					'customizer_repeater_text_control'     => true,
					'customizer_repeater_link_control'     => true,
					'customizer_repeater_checkbox_control' => true,
					'customizer_repeater_text2_control'    => true,
					'customizer_repeater_link2_control'    => true,
					'customizer_repeater_shortcode_control' => false,
					'customizer_repeater_repeater_control' => false,
					'customizer_repeater_color_control'    => false,
					'customizer_repeater_color2_control'   => false,
				)
			)
		);
	}

	function portfolio( $wp_customize ) {
		/**
		 * Separator1
		 */
		$wp_customize->add_setting( 'separator_portfolio_sep_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_portfolio_sep_setting',
				array(
					'settings'        => 'separator_portfolio_sep_setting',
					'active_callback' => 'plugLab_portfolio_fnback',
					'section'         => 'portfolio_section',
					'separator_txt'   => 'Section Heading',
				)
			)
		);

		$wp_customize->add_setting(
			'portfolio_display',
			array(
				'default'           => 1,
				'transport'         => 'refresh',
				'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'portfolio_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'portfolio_section',
				)
			)
		);

		$wp_customize->add_setting(
			'portfolio_title',
			array(
				'default'           => __( 'We recent projects', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'portfolio_title',
			array(
				'label'           => __( 'Portfolio Title', 'bizstrait' ),
				'section'         => 'portfolio_section',
				'active_callback' => 'plugLab_portfolio_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Portfolio title...', 'bizstrait' ),
				),
			)
		);

		$wp_customize->add_setting(
			'portfolio_sub_title',
			array(
				'default'           => __( 'Our Portfolio', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'portfolio_sub_title',
			array(
				'label'           => __( 'Portfolio Sub-title', 'bizstrait' ),
				'section'         => 'portfolio_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_portfolio_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Portfolio sub-title...', 'bizstrait' ),
				),
			)
		);

		/*
		  $wp_customize->add_setting(
			'portfolio_description',
			array(
				'default'           => __( 'Business we operate in is like an intricate', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'portfolio_description',
			array(
				'label'           => __( 'Portfolio Description', 'bizstrait' ),
				'section'         => 'portfolio_section',
				'active_callback' => 'plugLab_portfolio_fnback',
				'type'            => 'textarea',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Portfolio description...', 'bizstrait' ),
				),
			)
		); */

		/**
		 * Separator1
		 */
		$wp_customize->add_setting( 'separator_portfolio1_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_portfolio1_setting',
				array(
					'settings'        => 'separator_portfolio1_setting',
					'active_callback' => 'plugLab_portfolio_fnback',
					'section'         => 'portfolio_section',
					'separator_txt'   => 'Project 1',
				)
			)
		);

		// portfolio1

		$wp_customize->add_setting(
			'portfolio1_title',
			array(
				'default'           => __( 'Project 1 title', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$wp_customize->add_control(
			'portfolio1_title',
			array(
				'label'           => __( 'Title', 'pluglab' ),
				'section'         => 'portfolio_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_portfolio_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'portfolio1_desc',
			array(
				'default'           => __( 'Project 1 description', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'portfolio1_desc',
			array(
				'label'           => __( 'Description', 'pluglab' ),
				'section'         => 'portfolio_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_portfolio_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'portfolio1_image',
			array(
				'default'           => PL_PLUGIN_URL . 'assets/images/about-img.jpg', // Add Default Image URL
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'portfolio1_image',
				array(
					'label'           => 'Portfolio Image',
					'description'     => __( 'Recommended image size shoud be 608*458', 'bizstrait' ),
					'section'         => 'portfolio_section',
					'settings'        => 'portfolio1_image',
					'active_callback' => 'plugLab_portfolio_fnback',
					'button_labels'   => array(
						'select' => 'Select Image',
						'remove' => 'Remove Image',
						'change' => 'Change Image',
					),
				)
			)
		);

		/**
		 * Separator2
		 */
		$wp_customize->add_setting( 'separator_portfolio2_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_portfolio2_setting',
				array(
					'settings'        => 'separator_portfolio2_setting',
					'active_callback' => 'plugLab_portfolio_fnback',
					'section'         => 'portfolio_section',
					'separator_txt'   => 'Project 2',
				)
			)
		);

		// portfolio2

		$wp_customize->add_setting(
			'portfolio2_title',
			array(
				'default'           => __( 'Project 2 title', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'portfolio2_title',
			array(
				'label'           => __( 'Title', 'pluglab' ),
				'section'         => 'portfolio_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_portfolio_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'portfolio2_desc',
			array(
				'default'           => __( 'Project 2 description', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'portfolio2_desc',
			array(
				'label'           => __( 'Description', 'pluglab' ),
				'section'         => 'portfolio_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_portfolio_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'portfolio2_image',
			array(
				'default'           => PL_PLUGIN_URL . 'assets/images/about-img.jpg', // Add Default Image URL
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'portfolio2_image',
				array(
					'label'           => 'Portfolio Image',
					'description'     => __( 'Recommended image size shoud be 608*458', 'bizstrait' ),
					'section'         => 'portfolio_section',
					'active_callback' => 'plugLab_portfolio_fnback',
					'settings'        => 'portfolio2_image',
					'button_labels'   => array(
						'select' => 'Select Image',
						'remove' => 'Remove Image',
						'change' => 'Change Image',
					),
				)
			)
		);

		/**
		 * Separator3
		 */
		$wp_customize->add_setting( 'separator_portfolio3_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_portfolio3_setting',
				array(
					'settings'        => 'separator_portfolio3_setting',
					'active_callback' => 'plugLab_portfolio_fnback',
					'section'         => 'portfolio_section',
					'separator_txt'   => 'Project 3',
				)
			)
		);

		$wp_customize->add_setting(
			'portfolio3_title',
			array(
				'default'           => __( 'Porject 3 title', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'portfolio3_title',
			array(
				'label'           => __( 'Title', 'pluglab' ),
				'section'         => 'portfolio_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_portfolio_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'portfolio3_desc',
			array(
				'default'           => __( 'Project 3 description', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'portfolio3_desc',
			array(
				'label'           => __( 'Description', 'pluglab' ),
				'section'         => 'portfolio_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_portfolio_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'portfolio3_image',
			array(
				'default'           => PL_PLUGIN_URL . 'assets/images/about-img.jpg', // Add Default Image URL
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'portfolio3_image',
				array(
					'label'           => 'Portfolio Image',
					'description'     => __( 'Recommended image size shoud be 608*458', 'bizstrait' ),
					'active_callback' => 'plugLab_portfolio_fnback',
					'section'         => 'portfolio_section',
					'settings'        => 'portfolio3_image',
					'button_labels'   => array(
						'select' => 'Select Image',
						'remove' => 'Remove Image',
						'change' => 'Change Image',
					),
				)
			)
		);
	}

	function callout( $wp_customize ) {
		$wp_customize->add_setting(
			'callout_display',
			array(
				'default'           => $this->defaults['callout_display'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'callout_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'callout_section',
				)
			)
		);

		/**
		 * Separator1
		 */
		$wp_customize->add_setting( 'separator_callout1_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_callout1_setting',
				array(
					'settings'        => 'separator_callout1_setting',
					'active_callback' => 'plugLab_callout_fnback',
					'section'         => 'callout_section',
					'separator_txt'   => 'Callout 1',
				)
			)
		);

		// callout1
		$wp_customize->add_setting(
			'callout1_icon',
			array(
				'default'           => $this->defaults['callout1_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'callout1_icon',
			array(
				'label'           => __( 'Icon class', 'pluglab' ),
				'section'         => 'callout_section',
				'active_callback' => 'plugLab_callout_fnback',
				'type'            => 'text',
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Icon_Picker_New(
				$wp_customize,
				'callout1_icon',
				array(
					'label'           => __( 'Icon class', 'pluglab' ),
					'active_callback' => 'plugLab_callout_fnback',
					'section'         => 'callout_section',
					'iconset'         => 'fa',
				)
			)
		);

		$wp_customize->add_setting(
			'callout1_title',
			array(
				'default'           => $this->defaults['callout1_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'callout1_title',
			array(
				'label'           => __( 'Title', 'pluglab' ),
				'section'         => 'callout_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_callout_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'callout1_desc',
			array(
				'default'           => $this->defaults['callout1_description'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'callout1_desc',
			array(
				'label'           => __( 'Description', 'pluglab' ),
				'section'         => 'callout_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_callout_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		/**
		 * Separator2
		 */
		$wp_customize->add_setting( 'separator_callout2_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_callout2_setting',
				array(
					'settings'        => 'separator_callout2_setting',
					'active_callback' => 'plugLab_callout_fnback',
					'section'         => 'callout_section',
					'separator_txt'   => 'Callout 2',
				)
			)
		);

		// callout2
		$wp_customize->add_setting(
			'callout2_icon',
			array(
				'default'           => $this->defaults['callout2_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		// $wp_customize->add_control('callout2_icon',
		// array(
		// 'label' => __('Icon class', 'pluglab'),
		// 'section' => 'callout_section',
		// 'active_callback' => 'plugLab_callout_fnback',
		// 'type' => 'text'
		// )
		// );

		$wp_customize->add_control(
			new PL_customizer_Control_Icon_Picker_New(
				$wp_customize,
				'callout2_icon',
				array(
					'label'           => __( 'Icon class', 'pluglab' ),
					'active_callback' => 'plugLab_callout_fnback',
					'section'         => 'callout_section',
					'iconset'         => 'fa',
				)
			)
		);

		$wp_customize->add_setting(
			'callout2_title',
			array(
				'default'           => $this->defaults['callout2_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'callout2_title',
			array(
				'label'           => __( 'Title', 'pluglab' ),
				'section'         => 'callout_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_callout_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'callout2_desc',
			array(
				'default'           => $this->defaults['callout2_description'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'callout2_desc',
			array(
				'label'           => __( 'Description', 'pluglab' ),
				'section'         => 'callout_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_callout_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		/**
		 * Separator3
		 */
		$wp_customize->add_setting( 'separator_callout3_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_callout3_setting',
				array(
					'settings'        => 'separator_callout3_setting',
					'section'         => 'callout_section',
					'active_callback' => 'plugLab_callout_fnback',
					'separator_txt'   => 'Callout 3',
				)
			)
		);

		// callout3
		$wp_customize->add_setting(
			'callout3_icon',
			array(
				'default'           => $this->defaults['callout3_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Icon_Picker_New(
				$wp_customize,
				'callout3_icon',
				array(
					'label'           => __( 'Icon class', 'pluglab' ),
					'active_callback' => 'plugLab_callout_fnback',
					'section'         => 'callout_section',
					'iconset'         => 'fa',
				)
			)
		);

		$wp_customize->add_setting(
			'callout3_title',
			array(
				'default'           => $this->defaults['callout3_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'callout3_title',
			array(
				'label'           => __( 'Title', 'pluglab' ),
				'section'         => 'callout_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_callout_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'callout3_desc',
			array(
				'default'           => $this->defaults['callout3_description'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'callout3_desc',
			array(
				'label'           => __( 'Description', 'pluglab' ),
				'section'         => 'callout_section',
				'type'            => 'text',
				'active_callback' => 'plugLab_callout_fnback',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'pluglab' ),
				),
			)
		);
	}

	public function aboutus( $wp_customize ) {
		$wp_customize->add_setting(
			'about_display',
			array(
				'default'           => $this->defaults['about_display'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'about_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'bizstrait' ),
					'section'  => 'bizstrait_about_section',
				)
			)
		);

		$wp_customize->add_setting(
			'about_title',
			array(
				'default'           => $this->defaults['about_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'about_title',
			array(
				'label'       => __( 'Title', 'bizstrait' ),
				'section'     => 'bizstrait_about_section',
				'type'        => 'text',
				'input_attrs' => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Title...', 'bizstrait' ),
				),
			)
		);

		$wp_customize->add_setting(
			'about_sub_title',
			array(
				'default'           => $this->defaults['about_sub_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'about_sub_title',
			array(
				'label'       => __( 'Sub-title', 'bizstrait' ),
				'section'     => 'bizstrait_about_section',
				'type'        => 'text',
				'input_attrs' => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Sub-title...', 'bizstrait' ),
				),
			)
		);

		$wp_customize->add_setting(
			'bizstrait_about_section_content',
			array(
				'default' => __(
					'<p>Distinctively exploit optimal alignments for intuitive. Quickly coordinate business applications through revolutionary catalysts for chang the Seamlessly optimal testing procedures</p>
				<p>Distinctively exploit optimal alignments for intuitive. Quickly coordinate business applications through revolutionary catalysts for chang.</p>
				<blockquote>
				<p>We work all the time with our customers and together we are able to create
				  beautifull and amazing things that surely brings positive results and complete
				  satisfaction.</p>
			  </blockquote> 
				<p> whereas processes. Synerg stically evolve 2.0 technologies rather than just in web & apps development optimal alignments for intuitive
				</p>',
					'bizstrait'
				),
				// 'transport'         => 'postMessage',
				// 'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Page_Editor(
				$wp_customize,
				'bizstrait_about_section_content',
				array(
					'label'   => esc_html__( 'Content', 'bizstrait' ),
					'section' => 'bizstrait_about_section',
				)
			)
		);

		/**
		 * Enable/Disable Read More button
		 */
		$wp_customize->add_setting(
			'about_button_display',
			array(
				'default' => $this->defaults['about_button_display'],
				// 'sanitize_callback' => 'customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'about_button_display',
				array(
					'label'   => __( 'Add button after content', 'bizstrait' ),
					'section' => 'bizstrait_about_section',
				)
			)
		);

		/**
		 * Read More Button
		 */
		$wp_customize->add_setting(
			'about_button',
			array(
				'default'           => $this->defaults['about_button'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'about_button',
			array(
				'label'   => __( 'Button text:-', 'bizstrait' ),
				'section' => 'bizstrait_about_section',
				// 'active_callback' => 'about_button_display_callback',
				'type'    => 'text',
			)
		);

		/**
		 * Read More Button LINK
		 */
		$wp_customize->add_setting(
			'about_button_link',
			array(
				'default'           => $this->defaults['about_button_link'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'about_button_link',
			array(
				'label'       => __( 'Link over button:-', 'bizstrait' ),
				'description' => __( 'This link would be attatched over button', 'bizstrait' ),
				'section'     => 'bizstrait_about_section',
				// 'active_callback' => 'about_button_display_callback',
				'type'        => 'text',
			)
		);

		/**
		 * Read More Button LINK _target attribute
		 */
		$wp_customize->add_setting(
			'about_button_link_target',
			array(
				'default'           => $this->defaults['about_button_link_target'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'about_button_link_target',
			array(
				'label'   => __( 'Open the link to a new tab', 'bizstrait' ),
				'section' => 'bizstrait_about_section',
				// 'active_callback' => 'about_button_display_callback',
				'type'    => 'checkbox',
			)
		);

		/**
		 * Separator
		 */
		$wp_customize->add_setting( 'separator_about' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_about',
				array(
					'settings'      => 'separator_about',
					'section'       => 'bizstrait_about_section',
					'separator_txt' => 'Side Image',
				)
			)
		);

		/**
		 * Image 1
		 */
		$wp_customize->add_setting(
			'about_image1',
			array(
				'default'           => PL_PLUGIN_URL . 'assets/images/about-img.jpg', // Add Default Image URL
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'about_image1',
				array(
					'label'         => 'Business Image',
					'description'   => __( 'Recommended image size shoud be 608*458', 'bizstrait' ),
					'section'       => 'bizstrait_about_section',
					'settings'      => 'about_image1',
					'button_labels' => array(
						'select' => 'Select Image',
						'remove' => 'Remove Image',
						'change' => 'Change Image',
					),
				)
			)
		);

		/**
		 * Count number over image
		 */

		$wp_customize->add_setting(
			'about_count',
			array(
				'default'           => '10',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'about_count',
				array(
					'label'       => __( 'Count Number', 'bizstrait' ),
					'section'     => 'bizstrait_about_section',
					'settings'    => 'about_count',
					'type'        => 'number',
					'input_attrs' => array(
						'min' => 0,
						'max' => 100,
					),
				)
			)
		);

		/**
		 * Tag over Image
		 */
		$wp_customize->add_setting(
			'about_tagline',
			array(
				'default'           => $this->defaults['about_tagline'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'about_tagline',
			array(
				'label'   => __( 'Tag Line', 'bizstrait' ),
				'section' => 'bizstrait_about_section',
				'type'    => 'text',
			)
		);
	}

	function service( $wp_customize ) {
		$wp_customize->add_setting(
			'service_display',
			array(
				'default'           => $this->defaults['service_display'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'service_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'service_section',
				)
			)
		);
		// $wp_customize->selective_refresh->add_partial(
		// 'service_display',
		// array(
		// 'selector'            => '.social',
		// 'container_inclusive' => false,
		// 'render_callback'     => function () {
		// echo pluglab_get_social_media();
		// },
		// 'fallback_refresh'    => true,
		// )
		// );

		$wp_customize->add_setting(
			'service_title',
			array(
				'default'           => $this->defaults['service_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'service_title',
			array(
				'label'           => __( 'Service Title', 'pluglab' ),
				'section'         => 'service_section',
				'priority'        => 2,
				'active_callback' => 'plugLab_service_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Service title...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'service_sub_title',
			array(
				'default'           => $this->defaults['service_sub_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'service_sub_title',
			array(
				'label'           => __( 'Service Sub-title', 'pluglab' ),
				'section'         => 'service_section',
				'priority'        => 3,
				'active_callback' => 'plugLab_service_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Service sub-title...', 'pluglab' ),
				),
			)
		);
		$wp_customize->add_setting(
			'service_description',
			array(
				'default'           => $this->defaults['service_description'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'service_description',
			array(
				'label'           => __( 'Service description', 'pluglab' ),
				'section'         => 'service_section',
				'priority'        => 4,
				'active_callback' => 'plugLab_service_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Service brief-description...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'service_repeater',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
			)
		);
		$wp_customize->add_control(
			new PL_customizer_Control_Repeater_New(
				$wp_customize,
				'service_repeater',
				array(
					'label'                                => esc_html__( 'Service Content', 'customizer-repeater' ),
					'section'                              => 'service_section',
					'priority'                             => 5,
					'active_callback'                      => 'plugLab_service_fnback',
					'add_field_label'                      => esc_html__( 'Add New Service', 'pluglab' ),
					'item_name'                            => esc_html__( 'Service', 'pluglab' ),
					'customizer_repeater_image_control'    => true,
					'customizer_repeater_icon_control'     => true,
					'customizer_repeater_image_icon_choice_control' => false,
					'customizer_repeater_title_control'    => true,
					'customizer_repeater_subtitle_control' => true,
					// 'customizer_repeater_text_control'     => true,
					'customizer_repeater_text2_control'    => false,
					'customizer_repeater_link_control'     => true,
					'customizer_repeater_link2_control'    => false,
					'customizer_repeater_shortcode_control' => false,
					'customizer_repeater_repeater_control' => false,
					'customizer_repeater_color_control'    => false,
					'customizer_repeater_color2_control'   => false,
				)
			)
		);
		/*
		$wp_customize->add_setting('service_bg', array(
			'transport'         => 'refresh',
			'height'         => 325,
			'default'=> PL_PLUGIN_URL . 'assets/images/bizstrait/bg.jpg'
		));

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'service_bg', array(
			'label'             => __('Background Image', 'bizstrait'),
			'section'           => 'service_section',
			'settings'          => 'service_bg',
		)));     */
	}

	function testimonial( $wp_customize ) {
		 // $wp_customize->add_setting(
		// 'pluglab_scrolltop_tabs',
		// array(
		// 'default'           => '',
		// 'sanitize_callback' => 'esc_attr'
		// )
		// );
		// $wp_customize->add_control(
		// new PL_Customizer_Control_Tab (
		// $wp_customize,
		// 'pluglab_scrolltop_tabs',
		// array(
		// 'priority' => 0,
		// 'label'                 => '',
		// 'section'               => 'testimonial_section',
		// 'controls_general'      => json_encode( array( '#customize-control-testimonial_display','#customize-control-testimonial_description',   ) ),
		// 'controls_design'       => json_encode( array( '#customize-control-testimonial_title','#customize-control-testimonial_sub_title', ) ),
		// )
		// )
		// );

		$wp_customize->add_setting(
			'testimonial_display',
			array(
				'default'           => $this->defaults['testimonial_display'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'testimonial_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'testimonial_section',
				)
			)
		);
		/*
		$wp_customize->add_setting(
			'testimonial_title',
			array(
				'default'           => $this->defaults['testimonial_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'testimonial_title',
			array(
				'label'           => __( 'Testimonial Title', 'pluglab' ),
				'section'         => 'testimonial_section',
				'priority'        => 2,
				'active_callback' => 'plugLab_testimonial_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Testimonial title...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'testimonial_sub_title',
			array(
				'default'           => $this->defaults['testimonial_sub_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'testimonial_sub_title',
			array(
				'label'           => __( 'Testimonial Sub-title', 'pluglab' ),
				'section'         => 'testimonial_section',
				'priority'        => 3,
				'active_callback' => 'plugLab_testimonial_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Testimonial sub-title...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'testimonial_description',
			array(
				'default'           => $this->defaults['testimonial_description'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'testimonial_description',
			array(
				'label'           => __( 'Testimonial Description', 'pluglab' ),
				'section'         => 'testimonial_section',
				'priority'        => 4,
				'active_callback' => 'plugLab_testimonial_fnback',
				'type'            => 'textarea',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Testimonial description...', 'pluglab' ),
				),
			)
		); */

		/**
		 * Count number over image
		 */

		$wp_customize->add_setting(
			'testimonial_count',
			array(
				'default'           => '12k',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'testimonial_count',
			array(
				'label'           => __( 'Count text', 'pluglab' ),
				'active_callback' => 'plugLab_testimonial_fnback',
				'section'         => 'testimonial_section',
				'type'            => 'text',
			)
		);

		/**
		 * Tag over Image
		 */
		$wp_customize->add_setting(
			'testimonial_tagline',
			array(
				'default'           => __( 'Happy Customers', 'pluglab' ),
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'testimonial_tagline',
			array(
				'label'           => __( 'Tag Line', 'pluglab' ),
				'active_callback' => 'plugLab_testimonial_fnback',
				'section'         => 'testimonial_section',
				'type'            => 'text',
			)
		);

		$wp_customize->add_setting(
			'testimonial_repeater',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
			)
		);
		$wp_customize->add_control(
			new PL_customizer_Control_Repeater_New(
				$wp_customize,
				'testimonial_repeater',
				array(
					'label'                                => esc_html__( 'Testimonial Content', 'customizer-repeater' ),
					'section'                              => 'testimonial_section',
					// 'priority'                             => 5,
					'active_callback'                      => 'plugLab_testimonial_fnback',
					'add_field_label'                      => esc_html__( 'Add New Testinonial', 'pluglab' ),
					'item_name'                            => esc_html__( 'Testimonial', 'pluglab' ),
					'customizer_repeater_image_control'    => true,
					'customizer_repeater_image_icon_choice_control' => false,
					'customizer_repeater_title_control'    => false,
					'customizer_repeater_subtitle_control' => true,
					'customizer_repeater_text_control'     => true,
					'customizer_repeater_text2_control'    => true,
					'customizer_repeater_link_control'     => false,
					'customizer_repeater_link2_control'    => false,
					'customizer_repeater_shortcode_control' => false,
					'customizer_repeater_repeater_control' => false,
					'customizer_repeater_color_control'    => false,
					'customizer_repeater_color2_control'   => false,
				)
			)
		);

		/**
		 * Image 1
		 */
		$wp_customize->add_setting(
			'testimonial_banner_img',
			array(
				'default'           => PL_PLUGIN_URL . 'assets/images/testimonial.jpg', // Add Default Image URL
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'testimonial_banner_img',
				array(
					'label'           => 'Background Image',
					'priority'        => 999,
					'active_callback' => 'plugLab_testimonial_fnback',
					'description'     => __( 'Recommended image size shoud be 450*450', 'bizstrait' ),
					'section'         => 'testimonial_section',
					'settings'        => 'testimonial_banner_img',
					'button_labels'   => array(
						'select' => 'Select Image',
						'remove' => 'Remove Image',
						'change' => 'Change Image',
					),
				)
			)
		);
	}

	function blog( $wp_customize ) {
		$wp_customize->add_setting(
			'blog_display',
			array(
				'default'           => $this->defaults['blog_display'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'blog_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'blog_section',
				)
			)
		);

		$wp_customize->add_setting(
			'blog_title',
			array(
				'default'           => $this->defaults['blog_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			'blog_title',
			array(
				'label'           => __( 'Blog Title', 'pluglab' ),
				'section'         => 'blog_section',
				'priority'        => 2,
				'active_callback' => 'plugLab_blog_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Blog title...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'blog_sub_title',
			array(
				'default'           => $this->defaults['blog_sub_title'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'blog_sub_title',
			array(
				'label'           => __( 'Blog Sub-title', 'pluglab' ),
				'section'         => 'blog_section',
				'priority'        => 3,
				'active_callback' => 'plugLab_blog_fnback',
				'type'            => 'text',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Blog sub-title...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'blog_description',
			array(
				'default'           => $this->defaults['blog_description'],
				'transport'         => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			'blog_description',
			array(
				'label'           => __( 'Blog Description', 'pluglab' ),
				'section'         => 'blog_section',
				'priority'        => 4,
				'active_callback' => 'plugLab_blog_fnback',
				'type'            => 'textarea',
				'input_attrs'     => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Blog description...', 'pluglab' ),
				),
			)
		);

		$wp_customize->add_setting(
			'blog_meta_display',
			array(
				'default'           => $this->defaults['blog_meta_display'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'blog_meta_display',
				array(
					'priority'        => 5,
					'active_callback' => 'plugLab_blog_fnback',
					'label'           => __( 'Meta Information', 'pluglab' ),
					'section'         => 'blog_section',
				)
			)
		);

		// Test of Dropdown Select2 Control (Multi-Select)
		$wp_customize->add_setting(
			'bizstrait_theme_blog_category',
			array(
				'default'           => $this->defaults['bizstrait_theme_blog_category'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Dropdown_Select2_Custom(
				$wp_customize,
				'bizstrait_theme_blog_category',
				array(
					'label'           => __( 'Choose Blog Category', 'pluglab' ),
					'active_callback' => 'plugLab_blog_fnback',
					'description'     => esc_html__( 'Select category to show posts from...', 'pluglab' ),
					'section'         => 'blog_section',
					'input_attrs'     => array(
						'multiselect' => true,
					),
				)
			)
		);
	}

	public function scrollbar_btn( $wp_customize ) {
		$wp_customize->add_setting(
			'scrollbar_display',
			array(
				'default'   => true,
				'transport' => 'refresh',
				// 'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'scrollbar_display',
				array(
					'priority' => 1,
					'label'    => __( 'Display', 'pluglab' ),
					'section'  => 'bizstrait_scrollbar_btn',
				)
			)
		);

		/**
		 * Layout out style
		 */

		// $wp_customize->add_setting(
		// 'bizstrait_scrollbar_style',
		// array(
		// 'default'           => true,
		// 'transport'         => 'refresh',
		// 'sanitize_callback' => 'wp_filter_nohtml_kses',
		// )
		// );
		// $wp_customize->add_control(
		// new Bizstrait_Dropdown_Select_Design_Control(
		// $wp_customize,
		// 'bizstrait_scrollbar_style',
		// array(
		// 'label'       => __( 'Select Style', 'bizstrait' ),
		// 'priority' => 2,
		// 'section'     => 'bizstrait_scrollbar_btn',
		// 'input_attrs' => array(
		// 'placeholder' => __( 'Select a style', 'bizstrait' ),
		// 'multiselect' => false,
		// ),
		// 'choices'     => array(
		// 1 => __( 'Style 1', 'bizstrait' ),
		// 2 => __('Style 2', 'bizstrait'),
		// ),
		// )
		// )
		// );
	}

	public function template_settings( $wp_customize ) {
		/**
		 * Separator1
		 */
		$wp_customize->add_setting( 'separator_contactform1_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_contactform1_setting',
				array(
					'settings'      => 'separator_contactform1_setting',
					// 'active_callback' => 'plugLab_callout_fnback',
					'section'       => 'contactus_section',
					'separator_txt' => 'Contact Form',
				)
			)
		);

		// contcat form 7s
		$wp_customize->add_setting(
			'bizstrait_cf7_title',
			array(
				'default' => __( 'Get In Touch', 'bizstrait' ),
			)
		);

		$wp_customize->add_control(
			'bizstrait_cf7_title',
			array(
				'label'   => __( 'Form title', 'bizstrait' ),
				'section' => 'contactus_section',
				'type'    => 'text',
			)
		);

		/* sub-title */
		$wp_customize->add_setting(
			'bizstrait_cf7_sub_title',
			array(
				'default' => __( 'Quick Contact Form', 'bizstrait' ),
			)
		);

		$wp_customize->add_control(
			'bizstrait_cf7_sub_title',
			array(
				'label'   => __( 'Form sub title', 'bizstrait' ),
				'section' => 'contactus_section',
				'type'    => 'text',
			)
		);

		// contcat form 7s
		$wp_customize->add_setting(
			'cf7_shortcode',
			array(
				'default' => '',
			)
		);

		$wp_customize->add_control(
			'cf7_shortcode',
			array(
				'label'       => __( 'Contact form 7 shortcode', 'bizstrait' ),
				'section'     => 'contactus_section',
				'type'        => 'text',
				'input_attrs' => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => 'shortcode of cf7 plugin',
				),
			)
		);

		/**
		 * Separator2
		 */
		$wp_customize->add_setting( 'separator_map_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_map_setting',
				array(
					'settings'      => 'separator_map_setting',
					// 'active_callback' => 'plugLab_callout_fnback',
					'section'       => 'contactus_section',
					'separator_txt' => 'Google Map',
				)
			)
		);

		$wp_customize->add_setting(
			'tmpl_google_map_enable',
			array(
				'default'   => 1,
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'tmpl_google_map_enable',
				array(
					'label'   => esc_html__( 'Enable/Disable', 'bizstrait' ),
					'section' => 'contactus_section',
				)
			)
		);

		$wp_customize->add_setting(
			'contact_tmpl_google_map',
			array(
				'default' => '',
			)
		);

		$wp_customize->add_control(
			'contact_tmpl_google_map',
			array(
				'label'       => __( 'Google map shortcode', 'bizstrait' ),
				'section'     => 'contactus_section',
				'type'        => 'text',
				'input_attrs' => array(
					'class'       => 'my-custom-class',
					'style'       => 'border: 1px solid rebeccapurple',
					'placeholder' => 'shortcode of WP-Google-Map plugin',
				),
			)
		);

		/**
		 * Separator2
		 */
		$wp_customize->add_setting( 'separator_contactform2_setting' );
		$wp_customize->add_control(
			new PL_Customizer_Control_Separator_Section(
				$wp_customize,
				'separator_contactform2_setting',
				array(
					'settings'      => 'separator_contactform2_setting',
					// 'active_callback' => 'plugLab_callout_fnback',
					'section'       => 'contactus_section',
					'separator_txt' => 'Info Cards',
				)
			)
		);

		/*****************************************************
		sidebar contact cards
		 */

		$wp_customize->add_setting(
			'bizstrait_cards_title',
			array(
				'default' => __( 'Contact', 'bizstrait' ),
			)
		);

		$wp_customize->add_control(
			'bizstrait_cards_title',
			array(
				'label'   => __( 'Contact card title', 'bizstrait' ),
				'section' => 'contactus_section',
				'type'    => 'text',
			)
		);

		/* sub-title */
		$wp_customize->add_setting(
			'bizstrait_cards_sub_title',
			array(
				'default' => __( 'Our Contacts', 'bizstrait' ),
			)
		);

		$wp_customize->add_control(
			'bizstrait_cards_sub_title',
			array(
				'label'   => __( 'Contact card sub-title', 'bizstrait' ),
				'section' => 'contactus_section',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'bizstrait_sidebar_cards',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
				'default'           => pluglab_contact_info_default(),
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Repeater_New(
				$wp_customize,
				'bizstrait_sidebar_cards',
				array(
					'label'                             => esc_html__( 'Contact Details', 'bizstrait' ),
					'section'                           => 'contactus_section',
					'add_field_label'                   => esc_html__( 'Add New Detail', 'bizstrait' ),
					'item_name'                         => esc_html__( 'Contact Info', 'bizstrait' ),
					'customizer_repeater_icon_control'  => true,
					'customizer_repeater_title_control' => true,
					'customizer_repeater_text2_control' => true,
					'customizer_repeater_text_control'  => true,
				)
			)
		);

		/*****************************************
		 About Template
		*/

		$wp_customize->add_setting(
			'tmpl_aboutus_testimonial_enable',
			array(
				'default'   => 1,
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			new PL_Customizer_Control_Toggle_Switch_Custom(
				$wp_customize,
				'tmpl_aboutus_testimonial_enable',
				array(
					'label'   => esc_html__( 'Testimonials', 'bizstrait' ),
					'section' => 'aboutus_section',
				)
			)
		);
	}

	function upgradeNotice( $wp_customize ) {
		foreach ( $this->sections as $id => $section ) {
			$wp_customize->add_setting( $id );
			$wp_customize->add_control(
				new PL_Customizer_Control_Upgrade_Notice(
					$wp_customize,
					$id,
					array(
						'label'       => 'upgrade',
						'tname'       => 'Bizstrait Pro',
						'priority'    => 99,
						'section'     => $section,
						'setting'     => $id,
						'upgradeinfo' => 'https://unibirdtech.com/themes/bizstrait-pro/',
					)
				)
			);
		}
	}

	/**
	 * Set our Customizer default options
	 */
	function generate_defaults() {
		$customizer_defaults = array(
			'blog_meta_display'                      => '1',
			'cta_button_link_target'                 => '0',
			'bizstrait_theme_blog_category'          => '1',
			'blog_display'                           => 1,
			'testimonial_display'                    => 1,
			'slider_display'                         => 1,
			'social_newtab'                          => 1,
			'service_display'                        => 1,
			'service_body'                           => __( ' Service Body', 'pluglab' ),
			'social_urls'                            => '',
			'social_alignment'                       => 'alignright',
			'social_rss'                             => 0,
			'social_url_icons'                       => '',
			'contact_phone'                          => '',
			'search_menu_icon'                       => 0,
			'woocommerce_shop_sidebar'               => 1,
			'woocommerce_product_sidebar'            => 0,
			'sample_toggle_switch'                   => 0,
			'sample_slider_control'                  => 48,
			'sample_slider_control_small_step'       => 2,
			'sample_sortable_repeater_control'       => '',
			'sample_image_radio_button'              => 'sidebarright',
			'sample_text_radio_button'               => 'right',
			'sample_image_checkbox'                  => 'stylebold,styleallcaps',
			'sample_single_accordion'                => '',
			'testimonial_title'                      => __( 'GREAT REVIEWS', 'pluglab' ),
			'testimonial_sub_title'                  => __( 'Trusted Biggest Names', 'pluglab' ),
			'testimonial_description'                => __( 'Your brief about achievements', 'pluglab' ),
			'blog_title'                             => __( 'Our Blog', 'pluglab' ),
			'blog_sub_title'                         => __( 'Our Latest News', 'pluglab' ),
			'blog_description'                       => __( 'Our Latest updates', 'pluglab' ),
			'sample_alpha_color'                     => 'rgba(209,0,55,0.7)',
			'sample_wpcolorpicker_alpha_color'       => 'rgba(55,55,55,0.5)',
			'sample_wpcolorpicker_alpha_color2'      => 'rgba(33,33,33,0.8)',
			'sample_pill_checkbox'                   => 'tiger,elephant,hippo',
			'sample_pill_checkbox2'                  => 'captainmarvel,msmarvel,squirrelgirl',
			'sample_pill_checkbox3'                  => 'author,categories,comments',
			'sample_simple_notice'                   => '',
			'sample_dropdown_select2_control_single' => 'vic',
			'sample_dropdown_select2_control_multi'  => 'Antarctica/McMurdo,Australia/Melbourne,Australia/Broken_Hill',
			'sample_dropdown_select2_control_multi2' => 'Atlantic/Stanley,Australia/Darwin',
			'sample_dropdown_posts_control'          => '',
			'sample_tinymce_editor'                  => '',
			'service_description'                    => __( 'Business we operate in is like an intricate', 'pluglab' ),
			'service_sub_title'                      => __( "Services We're offering", 'pluglab' ),
			'service_title'                          => __( 'WHAT CAN WE OFFER', 'pluglab' ),
			'sample_google_font_select'              => json_encode(
				array(
					'font'          => 'Open Sans',
					'regularweight' => 'regular',
					'italicweight'  => 'italic',
					'boldweight'    => '700',
					'category'      => 'sans-serif',
				)
			),
			'sample_default_text'                    => '',
			'sample_email_text'                      => '',
			'sample_url_text'                        => '',
			'sample_number_text'                     => '',
			'sample_hidden_text'                     => '',
			'sample_date_text'                       => '',
			'sample_default_checkbox'                => 0,
			'sample_default_select'                  => 'jet-fuel',
			'sample_default_radio'                   => 'spider-man',
			'sample_default_dropdownpages'           => '1548',
			'sample_default_textarea'                => '',
			'sample_default_color'                   => '#333',
			'sample_default_media'                   => '',
			'sample_default_image'                   => '',
			'sample_default_cropped_image'           => '',
			'sample_date_only'                       => '2017-08-28',
			'sample_date_time'                       => '2017-08-28 16:30:00',
			'sample_date_time_no_past_date'          => date( 'Y-m-d' ),
			// 'callout1_title'                         => 'Strategy',
			// 'callout2_title'                         => 'Start Ups',
			// 'callout3_title'                         => 'Organisations',
			'callout_display'                        => 1,
			'callout1_icon'                          => 'fa-bullseye',
			'callout2_icon'                          => 'fa-rocket',
			'callout3_icon'                          => 'fa-comments',
			'callout1_title'                         => 'Avantage Services',
			'callout2_title'                         => 'Our Approach',
			'callout3_title'                         => 'Business Management',
			'callout1_description'                   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			'callout2_description'                   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			'callout3_description'                   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			'cta_title'                              => 'Pellentesque molestie laor',
			'about_display'                          => 1,
			'about_button_display'                   => 1,
			'about_button_link_target'               => 1,
			'about_button_link'                      => esc_html__( '#', 'pluglab' ),
			'about_title'                            => __( 'Lorem Ipsum', 'pluglab' ),
			'about_sub_title'                        => __( 'Lorem Ipsum', 'pluglab' ),
			'about_button'                           => __( 'Read More', 'pluglab' ),
			'cta_desc'                               => 'NEED A CONSULTATION?',
			'cta_btn_read'                           => 'CONTACT',
			'cta_btn_link'                           => '#',
			'about_tagline'                          => __( 'Years Of Experience', 'bizstrait' ),
			'copyright_text'                         => sprintf( esc_html__( 'Theme: %1$s by %2$s', 'bizstrait' ), 'Bizstrait', '<a href="https://unibirdtech.com/">Unibird Tech</a>' ),
		);

		return apply_filters( 'pluglab_customizer_defaults', $customizer_defaults );
	}
}
