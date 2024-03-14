<?php

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class PL_Theme_Biznol_Customizer {

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
			'biznol_social_icons_upgrade_notice' => 'top_header',
		);
		add_action( 'customize_register', array( $this, 'pluglab_bsns_tmp_customizer_panel' ) );
		add_action( 'customize_register', array( $this, 'pluglab_bsns_tmp_customizer_section' ) );
		add_action( 'customize_register', array( $this, 'pluglab_bsns_tmp_customizer_control' ) );
	}

	public function pluglab_bsns_tmp_customizer_panel( $wp_customize ) {
		$wp_customize->add_panel(
			'homepage_template_settings',
			array(
				'title'       => __( 'Homepage Sections Setting' ),
				'description' => 'Template sections setting to manage like hide/show, etc.', // Include html tags such as <p>.
			// 'priority'    => 1, // Mixed with top-level-section hierarchy.
			)
		);
	}

	public function pluglab_bsns_tmp_customizer_section( $wp_customize ) {

		$wp_customize->add_section(
			'top_header',
			array(
				'priority' => 2,
				'title'    => __( 'Top Header', 'pluglab' ),
				'panel'    => 'biznol_header_setting',
			)
		);

		$wp_customize->add_section(
			'slider_section',
			array(
				'title'       => __( 'Slider', 'pluglab' ),
				'description' => esc_html__( 'Manage slider banner', 'pluglab' ),
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
	}

	public function pluglab_bsns_tmp_customizer_control( $wp_customize ) {

		/**
		 * slider start
		 */
		$this->slider( $wp_customize );

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
	}

	function header( $wp_customize ) {

		/**
		 * @todo: add this in next
		 */
		// Logo Width //
		// $wp_customize->add_setting(
		// 'logo_width',
		// array(
		// 'default'           => '140',
		// 'capability'        => 'edit_theme_options',
		// 'sanitize_callback' => 'biznol_sanitize_range_value',
		// 'transport'         => 'postMessage',
		// )
		// );
		// $wp_customize->add_control(
		// new PL_Customizer_Control_Range_Slider(
		// $wp_customize,
		// 'logo_width',
		// array(
		// 'label'       => __( 'Logo Width', 'pluglab' ),
		// 'section'     => 'title_tagline',
		// 'input_attrs' => array(
		// 'min'  => 0,
		// 'max'  => 500,
		// 'step' => 1,
		// 'suffix' => 'px', //optional suffix
		// ),
		// )
		// )
		// );

		$wp_customize->add_setting(
			'hide_show_top_details',
			array(
				'default'    => '1',
				'capability' => 'edit_theme_options',
				// 'sanitize_callback' => 'pluglab_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'hide_show_top_details',
			array(
				'label'   => esc_html__( 'Hide/Show', 'pluglab' ),
				'section' => 'top_header',
				'type'    => 'checkbox',
			)
		);

		// icon //
		$wp_customize->add_setting(
			'top_mail_icon',
			array(
				'default'           => 'fa-map-marker',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Icon_Picker(
				$wp_customize,
				'top_mail_icon',
				array(
					'label'   => __( 'Icon', 'pluglab' ),
					'section' => 'top_header',
					'iconset' => 'fa',
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
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
				),
			)
		);

		/* $wp_customize->add_setting(
			'top_phone_icon',
			array(
				'default'           => 'fa-map-marker',
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Icon_Picker(
				$wp_customize,
				'top_phone_icon',
				array(
					'label'   => __( 'Icon', 'pluglab' ),
					'section' => 'top_header',
					'iconset' => 'fa',
				)
			)
		); */

		// Top Text //
		/* $wp_customize->add_setting(
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
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
				),
			)
		); */

		$wp_customize->add_setting(
			'social_icon_enable_disable',
			array(
				'default'    => '1',
				'capability' => 'edit_theme_options',
				// 'sanitize_callback' => 'pluglab_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'social_icon_enable_disable',
			array(
				'label'   => esc_html__( 'Hide/Show Social icon', 'pluglab' ),
				'section' => 'top_header',
				'type'    => 'checkbox',
			)
		);

		/**
		 * Customizer Repeater
		 */
		$wp_customize->add_setting(
			'biznol_social_icons',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
				'default'           => pluglab_get_social_icon_default(),
			)
		);

		$wp_customize->add_control(
			new PL_customizer_Control_Repeater(
				$wp_customize,
				'biznol_social_icons',
				array(
					'label'                            => esc_html__( 'Social Icons', 'pluglab' ),
					'section'                          => 'top_header',
					'add_field_label'                  => esc_html__( 'Add New Social', 'pluglab' ),
					'item_name'                        => esc_html__( 'Social', 'pluglab' ),
					'customizer_repeater_icon_control' => true,
					'customizer_repeater_link_control' => true,
				)
			)
		);

		/* $wp_customize->add_setting(
			'hire_us_btn_enable_disable',
			array(
				'default'    => '1',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_control(
			'hire_us_btn_enable_disable',
			array(
				'label'   => esc_html__( 'Hide/Show', 'pluglab' ),
				'section' => 'top_header',
				'type'    => 'checkbox',
				'priority'=> 100
			)
		);

		$wp_customize->add_setting(
			'hire_btn_text',
			array(
				'default' => 'HIRE US!',
			)
		);
		$wp_customize->add_control(
			'hire_btn_text',
			array(
				'label'       => __( 'Button Text', 'pluglab' ),
				'section'     => 'top_header',
				'type'        => 'text',
				'priority'=> 100,
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
				),
			)
		);

		$wp_customize->add_setting(
			'hire_btn_link',
			array(
				'capability' => 'edit_theme_options',
				'default'    => '#',
			)
		);

		$wp_customize->add_control(
			'hire_btn_link',
			array(
				'label'   => __( 'Link', 'biznol' ),
				'section' => 'top_header',
				'priority'=> 100,
				'type'    => 'text',
			)
		); */
	}

	function slider( $wp_customize ) {
		$wp_customize->add_setting(
			'slider_display',
			array(
				'default'           => $this->defaults['slider_display'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'pluglab_customizer_switch_sanitization',
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
			new PL_customizer_Control_Repeater(
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
					'customizer_repeater_text2_control'    => false,
					'customizer_repeater_link2_control'    => false,
					'customizer_repeater_shortcode_control' => false,
					'customizer_repeater_repeater_control' => false,
					'customizer_repeater_color_control'    => false,
					'customizer_repeater_color2_control'   => false,
				)
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
			'service_repeater',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
			)
		);
		$wp_customize->add_control(
			new PL_customizer_Control_Repeater(
				$wp_customize,
				'service_repeater',
				array(
					'label'                                => esc_html__( 'Service Content', 'pluglab' ),
					'section'                              => 'service_section',
					'priority'                             => 5,
					'active_callback'                      => 'plugLab_service_fnback',
					'add_field_label'                      => esc_html__( 'Add New Service', 'pluglab' ),
					'item_name'                            => esc_html__( 'Service', 'pluglab' ),
					'customizer_repeater_image_control'    => false,
					'customizer_repeater_icon_control'     => true,
					'customizer_repeater_image_icon_choice_control' => false,
					'customizer_repeater_title_control'    => true,
					'customizer_repeater_subtitle_control' => true,
					'customizer_repeater_text_control'     => true,
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
	}

	function testimonial( $wp_customize ) {
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
			'testimonial_repeater',
			array(
				'sanitize_callback' => 'customizer_repeater_sanitize',
			)
		);
		$wp_customize->add_control(
			new PL_customizer_Control_Repeater(
				$wp_customize,
				'testimonial_repeater',
				array(
					'label'                                => esc_html__( 'Testimonial Content', 'customizer-repeater' ),
					'section'                              => 'testimonial_section',
					'priority'                             => 5,
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
			'biznol_theme_blog_category',
			array(
				'default'           => $this->defaults['biznol_theme_blog_category'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control(
			new PL_Customizer_Control_Dropdown_Select2_Custom(
				$wp_customize,
				'biznol_theme_blog_category',
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

	function upgradeNotice( $wp_customize ) {

		foreach ( $this->sections as $id => $section ) {
			$wp_customize->add_setting( $id );
			$wp_customize->add_control(
				new PL_Customizer_Control_Upgrade_Notice(
					$wp_customize,
					$id,
					array(
						'label'       => 'upgrade',
						'tname'       => 'Biznol Pro',
						'section'     => $section,
						'setting'     => $id,
						'priority' => 99,
						'upgradeinfo' => 'https://unibirdtech.com/themes/biznol-pro/',
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
			'biznol_theme_blog_category'             => '1',
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
			'blog_title'                             => __( 'Times Today', 'pluglab' ),
			'blog_sub_title'                         => __( 'Avantage Blog Posts', 'pluglab' ),
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
			'callout1_title'                         => 'Strategy',
			'callout2_title'                         => 'Start Ups',
			'callout3_title'                         => 'Organisations',
			'cta_title'                              => 'Pellentesque molestie laor',
			'cta_desc'                               => 'NEED A CONSULTATION?',
			'cta_btn_read'                           => 'CONTACT',
			'cta_btn_link'                           => '#',
		);

		return apply_filters( 'pluglab_customizer_defaults', $customizer_defaults );
	}

}
