<?php

class PL_Theme_Biznol_Customizer_Config {

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
				return esc_html__( 'Slide description', 'your-textdomain' );
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
				return esc_html__( 'Button one text', 'your-textdomain' );
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
				return esc_html__( 'Button two text', 'your-textdomain' );
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
				return esc_html__( 'Button one text link (URL)', 'your-textdomain' );
			}
		}
		return $string;
	}

	public function slider_repeater_button_two_link_label( $string, $id, $control ) {
		if ( $id === 'slider_repeater' ) {
			if ( $control === 'customizer_repeater_link2_control' ) {
				return esc_html__( 'Button two text link (URL)', 'your-textdomain' );
			}
		}
		return $string;
	}

	// service
	public function service_repeater_text_label( $string, $id, $control ) {
		if ( $id === 'service_repeater' ) {
			if ( $control === 'customizer_repeater_text_control' ) {
				return esc_html__( 'Button text', 'your-textdomain' );
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
				return esc_html__( 'Service description', 'biznol' );
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
				return esc_html__( 'Name', 'your-textdomain' );
			}
		}
		return $string;
	}

	public function team_repeater_subtitle_label( $string, $id, $control ) {
		if ( $id === 'team_repeater' ) {
			if ( $control === 'customizer_repeater_subtitle_control' ) {
				return esc_html__( 'Designation', 'your-textdomain' );
			}
		}
		return $string;
	}

	// testimonial
	public function testimonial_repeater_title_label( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) {
			if ( $control === 'customizer_repeater_title_control' ) {
				return esc_html__( 'Best line', 'your-textdomain' );
			}
		}
		return $string;
	}

	public function testimonial_repeater_subtitle_label( $string, $id, $control ) {
		if ( $id === 'testimonial_repeater' ) {
			if ( $control === 'customizer_repeater_subtitle_control' ) {
				return esc_html__( 'Great Words', 'your-textdomain' );
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
				return esc_html__( 'Name', 'your-textdomain' );
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
				return esc_html__( 'Brief Introduction', 'your-textdomain' );
			}
		}
		return $string;
	}

	public function selector( $wp_customize ) {

		/* top_header_mail_text */
		$wp_customize->selective_refresh->add_partial(
			'top_header_mail_text',
			array(
				'selector' => '.topbar ul.left li:nth-child(1) a',
				'settings' => 'top_header_mail_text',
				'render_callback' => function () {
					return get_theme_mod( 'top_header_mail_text' );
				},
			)
		);

		/* biznol_social_icons */
		$wp_customize->selective_refresh->add_partial(
			'biznol_social_icons',
			array(
				'selector' => '.topbar ul.right > li:nth-child(1) a',
				'settings' => 'biznol_social_icons',
				'render_callback' => function () {
					return get_theme_mod( 'biznol_social_icons' );
				},
			)
		);

		/**
		 * slider
		 */
		$wp_customize->selective_refresh->add_partial(
			'slider_repeater',
			array(
				'selector' => '.sliderhome .owl-slide-text',
				'settings' => 'slider_repeater',
			)
		);
		/**
		 * Service
		 */
		$wp_customize->selective_refresh->add_partial(
			'service_title',
			array(
				'selector'        => 'section.our-service .sub-title',
				'settings'        => 'service_title',
				'render_callback' => function () {
					return get_theme_mod( 'service_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_sub_title',
			array(
				'selector'        => 'section.our-service .ititle',
				'settings'        => 'service_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'service_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_repeater',
			array(
				'selector'        => 'section.our-service .row',
				'settings'        => 'service_repeater',
				'render_callback' => function () {
					return get_theme_mod( 'service_repeater' );
				},
			)
		);
		/**
		 * Testimonial
		 */
		$wp_customize->selective_refresh->add_partial(
			'testimonial_title',
			array(
				'selector'        => 'section.Testimonials .sub-title',
				'settings'        => 'testimonial_title',
				'render_callback' => function () {
					return get_theme_mod( 'testimonial_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'testimonial_sub_title',
			array(
				'selector'        => 'section.Testimonials .ititle',
				'settings'        => 'testimonial_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'testimonial_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'testimonial_repeater',
			array(
				'selector'        => 'section.Testimonials .owl-Testimonial',
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
				'selector'        => 'section.latest_news .sub-title',
				'settings'        => 'blog_title',
				'render_callback' => function () {
					return get_theme_mod( 'blog_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blog_sub_title',
			array(
				'selector'        => 'section.latest_news .ititle',
				'settings'        => 'blog_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'blog_sub_title' );
				},
			)
		);

		/**
		 * Team
		 */
		$wp_customize->selective_refresh->add_partial(
			'team_title',
			array(
				'selector'        => 'section.best_team .sub-title',
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
				'selector'        => 'section.about-us .sub-title',
				'settings'        => 'about_title',
				'render_callback' => function () {
					return get_theme_mod( 'about_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'about_sub_title',
			array(
				'selector'        => 'section.about-us .ititle',
				'settings'        => 'about_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'about_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'about_button',
			array(
				'selector'        => 'section.about-us a.btn',
				'settings'        => 'about_button',
				'render_callback' => function () {
					return get_theme_mod( 'about_button' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'biznol_about_section_content',
			array(
				'selector'        => 'section.about-us .edirc',
				'settings'        => 'biznol_about_section_content',
				'render_callback' => function () {
					return get_theme_mod( 'biznol_about_section_content' );
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
			'biznol_price_section_content',
			array(
				'selector'        => '.priceEditor',
				'settings'        => 'biznol_price_section_content',
				'render_callback' => function () {
					return get_theme_mod( 'biznol_price_section_content' );
				},
			)
		);
		/**
		 * Funfact
		 */
		$wp_customize->selective_refresh->add_partial(
			'funfact_repeater',
			array(
				'selector'        => '.funfacts .container',
				'settings'        => 'funfact_repeater',
				'render_callback' => function () {
					return get_theme_mod( 'funfact_repeater' );
				},
			)
		);

		/*
		 * Projects
		 */
		$wp_customize->selective_refresh->add_partial(
			'portfolio_title',
			array(
				'selector'        => 'section.project .sub-title',
				'settings'        => 'portfolio_title',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'portfolio_sub_title',
			array(
				'selector'        => 'section.project .ititle',
				'settings'        => 'portfolio_sub_title',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio_sub_title' );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'portfolio_selected_category_id',
			array(
				'selector'        => 'section.project .section-heading + .row',
				'settings'        => 'portfolio_selected_category_id',
				'render_callback' => function () {
					return get_theme_mod( 'portfolio_selected_category_id' );
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
			'biznol_price_section_content',
			array(
				'selector'        => 'section.prices .row',
				'settings'        => 'biznol_price_section_content',
				'render_callback' => function () {
					return get_theme_mod( 'biznol_price_section_content' );
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
