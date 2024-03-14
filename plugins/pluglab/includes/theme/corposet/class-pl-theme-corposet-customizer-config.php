<?php

class PL_Theme_Corposet_Customizer_Config {

	public function __construct() {
		add_filter( 'repeater_input_labels_filter', array( $this, 'slider_subtitle_repeater_labels' ), 10, 3 );
		add_filter( 'customizer_repeater_input_types_filter', array( $this, 'slider_subtitle_repeater_input' ), 10, 3 );

		add_filter( 'repeater_input_labels_filter', array( $this, 'slider_repeater_text_label' ), 10, 3 );
		add_filter( 'customizer_repeater_input_types_filter', array( $this, 'slider_repeater_text_input' ), 10, 3 );

		add_filter( 'repeater_input_labels_filter', array( $this, 'slider_repeater_text2_label' ), 10, 3 );
		add_filter( 'customizer_repeater_input_types_filter', array( $this, 'slider_repeater_text2_input' ), 10, 3 );

		add_filter( 'repeater_input_labels_filter', array( $this, 'slider_repeater_button_one_link_label' ), 10, 3 );
		add_filter( 'repeater_input_labels_filter', array( $this, 'slider_repeater_button_two_link_label' ), 10, 3 );

		add_filter( 'repeater_input_labels_filter', array( $this, 'service_repeater_text_label' ), 10, 3 );
		add_filter( 'customizer_repeater_input_types_filter', array( $this, 'service_repeater_text_input' ), 10, 3 );

		add_filter( 'repeater_input_labels_filter', array( $this, 'service_subtitle_repeater_labels' ), 10, 3 );
		add_filter( 'customizer_repeater_input_types_filter', array( $this, 'service_subtitle_repeater_input' ), 10, 3 );

		add_filter( 'repeater_input_labels_filter', array( $this, 'team_repeater_title_label' ), 10, 3 );
		add_filter( 'repeater_input_labels_filter', array( $this, 'team_repeater_subtitle_label' ), 10, 3 );

		add_filter( 'repeater_input_labels_filter', array( $this, 'testimonial_repeater_subtitle_label' ), 10, 3 );
		add_filter( 'repeater_input_labels_filter', array( $this, 'testimonial_repeater_title_label' ), 10, 3 );
		add_filter( 'customizer_repeater_input_types_filter', array( $this, 'testimonial_repeater_subtitle_input' ), 10, 3 );
		add_filter( 'repeater_input_labels_filter', array( $this, 'testimonial_repeater_text_label' ), 10, 3 );
		add_filter( 'customizer_repeater_input_types_filter', array( $this, 'testimonial_repeater_text_input' ), 10, 3 );
		add_filter( 'repeater_input_labels_filter', array( $this, 'testimonial_repeater_text2_label' ), 10, 3 );

		add_action( 'customize_register', array( $this, 'selector' ), 11 );

		// add_action('customize_register', array($this, 'service_title_render_callback'));
	}

	public function slider_subtitle_repeater_labels( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) {
			if ( $control === 'customizer_repeater_subtitle_control' ) {
				return esc_html__( 'Slide description', 'pluglab' );
			}
		}
		return $string;
	}

	function slider_subtitle_repeater_input( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) { // here is the id of the control you want to change
			if ( $control === 'customizer_repeater_subtitle_control' ) { // Here is the input you want to change
				return 'textarea';
			}
		}
		return $string;
	}

	public function slider_repeater_text_label( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) {
			if ( $control === 'customizer_repeater_text_control' ) {
				return esc_html__( 'Button one text', 'pluglab' );
			}
		}
		return $string;
	}

	function slider_repeater_text_input( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) { // here is the id of the control you want to change
			if ( $control === 'customizer_repeater_text_control' ) { // Here is the input you want to change
				return '';
			}
		}
		return $string;
	}

	public function slider_repeater_text2_label( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) {
			if ( $control === 'customizer_repeater_text2_control' ) {
				return esc_html__( 'Button two text', 'pluglab' );
			}
		}
		return $string;
	}

	function slider_repeater_text2_input( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) { // here is the id of the control you want to change
			if ( $control === 'customizer_repeater_text2_control' ) { // Here is the input you want to change
				return '';
			}
		}
		return $string;
	}

	public function slider_repeater_button_one_link_label( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) {
			if ( $control === 'customizer_repeater_link_control' ) {
				return esc_html__( 'Button one text link (URL)', 'pluglab' );
			}
		}
		return $string;
	}

	public function slider_repeater_button_two_link_label( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) {
			if ( $control === 'customizer_repeater_link2_control' ) {
				return esc_html__( 'Button two text link (URL)', 'pluglab' );
			}
		}
		return $string;
	}

	// service
	public function service_repeater_text_label( $string, $id, $control ) {
		if ( $id === 'service_repeater' ) {
			if ( $control === 'customizer_repeater_text_control' ) {
				return esc_html__( 'Button text', 'pluglab' );
			}
		}
		return $string;
	}

	function service_repeater_text_input( $string, $id, $control ) {
		if ( $id === 'service_repeater' ) { // here is the id of the control you want to change
			if ( $control === 'customizer_repeater_text_control' ) { // Here is the input you want to change
				return '';
			}
		}
		return $string;
	}

	public function service_subtitle_repeater_labels( $string, $id, $control ) {
		if ( $id === 'service_repeater' ) {
			if ( $control === 'customizer_repeater_subtitle_control' ) {
				return esc_html__( 'Service description', 'corposet' );
			}
		}
		return $string;
	}

	function service_subtitle_repeater_input( $string, $id, $control ) {
		if ( $id === 'service_repeater' ) { // here is the id of the control you want to change
			if ( $control === 'customizer_repeater_subtitle_control' ) { // Here is the input you want to change
				return 'textarea';
			}
		}
		return $string;
	}

	// team
	public function team_repeater_title_label( $string, $id, $control ) {
		if ( $id === 'team_repeater' ) {
			if ( $control === 'customizer_repeater_title_control' ) {
				return esc_html__( 'Name', 'pluglab' );
			}
		}
		return $string;
	}

	public function team_repeater_subtitle_label( $string, $id, $control ) {
		if ( $id === 'team_repeater' ) {
			if ( $control === 'customizer_repeater_subtitle_control' ) {
				return esc_html__( 'Designation', 'pluglab' );
			}
		}
		return $string;
	}

	// testimonial
	public function testimonial_repeater_title_label( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) {
			if ( $control === 'customizer_repeater_title_control' ) {
				return esc_html__( 'Best line', 'pluglab' );
			}
		}
		return $string;
	}

	public function testimonial_repeater_subtitle_label( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) {
			if ( $control === 'customizer_repeater_subtitle_control' ) {
				return esc_html__( 'Great Words', 'pluglab' );
			}
		}
		return $string;
	}

	function testimonial_repeater_subtitle_input( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) { // here is the id of the control you want to change
			if ( $control === 'customizer_repeater_subtitle_control' ) { // Here is the input you want to change
				return 'textarea';
			}
		}
		return $string;
	}

	public function testimonial_repeater_text_label( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) {
			if ( $control === 'customizer_repeater_text_control' ) {
				return esc_html__( 'Name', 'pluglab' );
			}
		}
		return $string;
	}

	function testimonial_repeater_text_input( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) { // here is the id of the control you want to change
			if ( $control === 'customizer_repeater_text_control' ) { // Here is the input you want to change
				return '';
			}
		}
		return $string;
	}

	public function testimonial_repeater_text2_label( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) {
			if ( $control === 'customizer_repeater_text2_control' ) {
				return esc_html__( 'Designation', 'pluglab' );
			}
		}
		return $string;
	}

	public function selector( $wp_customize ) {
		/**
		 * slider
		 */
		$wp_customize->selective_refresh->add_partial(
			'slider_repeater',
			array(
				'selector' => '.sliderhome .slide',
				'settings' => 'slider_repeater',
			)
		);

		// callout icon
		$wp_customize->selective_refresh->add_partial(
			'callout1_icon',
			array(
				'selector'        => '#callout-section > div > div:nth-child(1) > div > i',
				'settings'        => 'callout1_icon',
				'render_callback' => function() {
					return get_theme_mod( 'callout1_icon' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout2_icon',
			array(
				'selector'        => '#callout-section > div > div:nth-child(2) > div > i',
				'settings'        => 'callout2_icon',
				'render_callback' => function() {
					return get_theme_mod( 'callout2_icon' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout3_icon',
			array(
				'selector'        => '#callout-section > div > div:nth-child(3) > div > i',
				'settings'        => 'callout3_icon',
				'render_callback' => function() {
					return get_theme_mod( 'callout3_icon' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout4_icon',
			array(
				'selector'        => '#callout-section > div > div:nth-child(4) > div > i',
				'settings'        => 'callout4_icon',
				'render_callback' => function() {
					return get_theme_mod( 'callout4_icon' );
				},
			)
		);
		// callout Title
		$wp_customize->selective_refresh->add_partial(
			'callout1_title',
			array(
				'selector'        => '#callout-section > div > div:nth-child(1) > div > div > h5',
				'settings'        => 'callout1_title',
				'render_callback' => function() {
					return get_theme_mod( 'callout1_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout2_title',
			array(
				'selector'        => '#callout-section > div > div:nth-child(2) > div > div > h5',
				'settings'        => 'callout2_title',
				'render_callback' => function() {
					return get_theme_mod( 'callout2_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout3_title',
			array(
				'selector'        => '#callout-section > div > div:nth-child(3) > div > div > h5',
				'settings'        => 'callout3_title',
				'render_callback' => function() {
					return get_theme_mod( 'callout3_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout4_title',
			array(
				'selector'        => '#callout-section > div > div:nth-child(4) > div > div > h5',
				'settings'        => 'callout4_title',
				'render_callback' => function() {
					return get_theme_mod( 'callout4_title' );
				},
			)
		);
		// callout Description
		$wp_customize->selective_refresh->add_partial(
			'callout1_desc',
			array(
				'selector'        => '#callout-section > div > div:nth-child(1) > div > div > p',
				'settings'        => 'callout1_desc',
				'render_callback' => function() {
					return get_theme_mod( 'callout1_desc' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout2_desc',
			array(
				'selector'        => '#callout-section > div > div:nth-child(2) > div > div > p',
				'settings'        => 'callout2_desc',
				'render_callback' => function() {
					return get_theme_mod( 'callout2_desc' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout3_desc',
			array(
				'selector'        => '#callout-section > div > div:nth-child(3) > div > div > p',
				'settings'        => 'callout3_desc',
				'render_callback' => function() {
					return get_theme_mod( 'callout3_desc' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout4_desc',
			array(
				'selector'        => '#callout-section > div > div:nth-child(4) > div > div > p',
				'settings'        => 'callout4_desc',
				'render_callback' => function() {
					return get_theme_mod( 'callout4_desc' );
				},
			)
		);

		/**
		 * Service
		 */
		$wp_customize->selective_refresh->add_partial(
			'service_title',
			array(
				'selector'        => 'section.section.services div.section-heading h3',
				'settings'        => 'service_title',
				'render_callback' => function () {
					return get_theme_mod( 'service_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_sub_title',
			array(
				'selector'        => 'section.section.services div.section-heading h2',
				'settings'        => 'service_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'service_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_description',
			array(
				'selector'        => 'section.section.services div.section-heading p',
				'settings'        => 'service_description',
				'render_callback' => function () {
					return get_theme_mod( 'service_description' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_repeater',
			array(
				'selector'        => 'section.section.services div.row',
				'settings'        => 'service_repeater',
				'render_callback' => function () {
					return get_theme_mod( 'service_repeater' );
				},
			)
		);
		/**
		 * corposet_social_icons head
		 */
		$wp_customize->selective_refresh->add_partial(
			'corposet_social_icons',
			array(
				'selector'        => 'header ul.social',
				'settings'        => 'corposet_social_icons',
				'render_callback' => function () {
					return get_theme_mod( 'corposet_social_icons' );
				},
			)
		);
		/**
		 * top_header_mail_text head
		 */
		$wp_customize->selective_refresh->add_partial(
			'top_header_mail_text',
			array(
				'selector'        => 'header ul.mail-phone',
				'settings'        => 'top_header_mail_text',
				'render_callback' => function () {
					return get_theme_mod( 'top_header_mail_text' );
				},
			)
		);
		/**
		 * footer_socialicons_display footer
		 */
		$wp_customize->selective_refresh->add_partial(
			'footer_socialicons_display',
			array(
				'selector'        => 'footer ul.social',
				'settings'        => 'footer_socialicons_display',
				'render_callback' => function () {
					return get_theme_mod( 'footer_socialicons_display' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'testimonial_sub_title',
			array(
				'selector'        => 'section.section.testimonials div.section-heading > h2',
				'settings'        => 'testimonial_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'testimonial_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'testimonial_description',
			array(
				'selector'        => '.section.testimonials .section-heading p',
				'settings'        => 'testimonial_description',
				'render_callback' => function() {
					return get_theme_mod( 'testimonial_description' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'testimonial_repeater',
			array(
				'selector'        => 'section.section.testimonials div.row',
				'settings'        => 'testimonial_repeater',
				'render_callback' => function () {
					return get_theme_mod( 'testimonial_repeater' );
				},
			)
		);
		/**
		 * Blogs
		 */
		$wp_customize->selective_refresh->add_partial(
			'blog_title',
			array(
				'selector'        => 'div.section.bg-grey.blog-home div.section-heading h3',
				'settings'        => 'blog_title',
				'render_callback' => function () {
					return get_theme_mod( 'blog_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blog_sub_title',
			array(
				'selector'        => 'div.section.blog-home div.section-heading h2',
				'settings'        => 'blog_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'blog_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blog_description',
			array(
				'selector'        => 'div.section.blog-home div.section-heading p',
				'settings'        => 'blog_description',
				'render_callback' => function () {
					return get_theme_mod( 'blog_description' );
				},
			)
		);

		/**
		 * Team
		 */
		$wp_customize->selective_refresh->add_partial(
			'team_title',
			array(
				'selector'        => 'div.section.blog-home div.section-heading p',
				'settings'        => 'team_title',
				'render_callback' => function () {
					return get_theme_mod( 'team_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'team_sub_title',
			array(
				'selector'        => 'section.best_team .ititle',
				'settings'        => 'team_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'team_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'team_repeater',
			array(
				'selector'        => 'section.best_team .section-heading + .row',
				'settings'        => 'team_repeater',
				'render_callback' => function () {
					return get_theme_mod( 'team_repeater' );
				},
			)
		);
		/**
		 * About
		 */
		$wp_customize->selective_refresh->add_partial(
			'about_title',
			array(
				'selector'        => 'div.section.about.pdt0 div.col-md-6.pr-5 > div > h3',
				'settings'        => 'about_title',
				'render_callback' => function () {
					return get_theme_mod( 'about_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'about_sub_title',
			array(
				'selector'        => 'div.section.about.pdt0 > div > div > div.col-md-6.pr-5 > div > h2',
				'settings'        => 'about_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'about_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'about_button',
			array(
				'selector'        => 'div.section.about.pdt0 > div > div > div.col-md-6.pr-5 > div > div.signature > a',
				'settings'        => 'about_button',
				'render_callback' => function () {
					return get_theme_mod( 'about_button' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'corposet_about_section_content',
			array(
				'selector'        => 'div.section.about div.edirc',
				'settings'        => 'corposet_about_section_content',
				'render_callback' => function () {
					return get_theme_mod( 'corposet_about_section_content' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'about_image1',
			array(
				'selector'        => 'section.about-us .col-md-6:nth-child(1)',
				'settings'        => 'about_image1',
				'render_callback' => function () {
					return get_theme_mod( 'about_image1' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'corposet_price_section_content',
			array(
				'selector'        => '.priceEditor',
				'settings'        => 'corposet_price_section_content',
				'render_callback' => function () {
					return get_theme_mod( 'corposet_price_section_content' );
				},
			)
		);


		/*
		 * Projects
		 */
		$wp_customize->selective_refresh->add_partial(
			'portfolio_title',
			array(
				'selector'        => 'div.section.project-section div.section-heading.text-center h3',
				'settings'        => 'portfolio_title',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'portfolio_sub_title',
			array(
				'selector'        => 'div.section.project-section div.section-heading.text-center h2',
				'settings'        => 'portfolio_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'portfolio_description',
			array(
				'selector'        => 'div.section.project-section div.section-heading.text-center p',
				'settings'        => 'portfolio_description',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio_description' );
				},
			)
		);
		/* project 1 */
		
		$wp_customize->selective_refresh->add_partial(
			'portfolio1_title',
			array(
				'selector'        => 'div.section.bg-grey.project-section div.row.px-3.project-content > div > div.owl-stage-outer > div > div:nth-child(1) > div > div > div.bottom_text > h2',
				'settings'        => 'portfolio1_title',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio1_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'portfolio1_desc',
			array(
				'selector'        => 'div.section.bg-grey.project-section > div > div.row.px-3.project-content > div > div.owl-stage-outer > div > div:nth-child(1) > div > div > div.bottom_text > p',
				'settings'        => 'portfolio1_desc',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio1_desc' );
				},
			)
		);
		/* project 2 */
		
		$wp_customize->selective_refresh->add_partial(
			'portfolio2_title',
			array(
				'selector'        => 'div.section.bg-grey.project-section div.row.px-3.project-content > div > div.owl-stage-outer > div > div:nth-child(2) > div > div > div.bottom_text > h2',
				'settings'        => 'portfolio2_title',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio2_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'portfolio2_desc',
			array(
				'selector'        => 'div.section.bg-grey.project-section > div > div.row.px-3.project-content > div > div.owl-stage-outer > div > div:nth-child(2) > div > div > div.bottom_text > p',
				'settings'        => 'portfolio2_desc',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio2_desc' );
				},
			)
		);
		/* project 3 */
		
		$wp_customize->selective_refresh->add_partial(
			'portfolio3_title',
			array(
				'selector'        => 'div.section.bg-grey.project-section div.row.px-3.project-content > div > div.owl-stage-outer > div > div:nth-child(3) > div > div > div.bottom_text > h2',
				'settings'        => 'portfolio3_title',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio3_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'portfolio3_desc',
			array(
				'selector'        => 'div.section.bg-grey.project-section > div > div.row.px-3.project-content > div > div.owl-stage-outer > div > div:nth-child(3) > div > div > div.bottom_text > p',
				'settings'        => 'portfolio3_desc',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio3_desc' );
				},
			)
		);

		/**
		 * Price
		 */
		$wp_customize->selective_refresh->add_partial(
			'price_title',
			array(
				'selector'        => 'section.prices .sub-title',
				'settings'        => 'price_title',
				'render_callback' => function () {
					return get_theme_mod( 'price_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'price_sub_title',
			array(
				'selector'        => 'section.prices .ititle',
				'settings'        => 'price_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'price_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'corposet_price_section_content',
			array(
				'selector'        => 'section.prices .row',
				'settings'        => 'corposet_price_section_content',
				'render_callback' => function () {
					return get_theme_mod( 'corposet_price_section_content' );
				},
			)
		);
		/**
		 * Clients
		 */
		$wp_customize->selective_refresh->add_partial(
			'client_repeater',
			array(
				'selector'        => 'section.clients .container',
				'settings'        => 'client_repeater',
				'render_callback' => function () {
					return get_theme_mod( 'client_repeater' );
				},
			)
		);
		/*
		 * @todo: check existance wehre
		 */
		$wp_customize->selective_refresh->add_partial(
			'sections_order',
			array(
				'settings'        => 'sections_order',
				'render_callback' => function () {
					return get_theme_mod( 'sections_order' );
				},
			)
		);
	}

}
