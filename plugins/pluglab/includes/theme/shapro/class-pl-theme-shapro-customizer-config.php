<?php

class PL_Theme_Shapro_Customizer_Config {

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

		/* top_header_phone_text */
		$wp_customize->selective_refresh->add_partial(
			'top_header_phone_text',
			array(
				'selector' => '.topbar ul.left li:nth-child(2) a',
				'settings' => 'top_header_phone_text',
				'render_callback' => function () {
					return get_theme_mod( 'top_header_phone_text' );
				},
			)
		);

		/* shapro_social_icons */
		$wp_customize->selective_refresh->add_partial(
			'shapro_social_icons',
			array(
				'selector' => '.topbar ul.right > li:nth-child(1) a',
				'settings' => 'shapro_social_icons',
				'render_callback' => function () {
					return get_theme_mod( 'shapro_social_icons' );
				},
			)
		);

		/* hire_btn_text */
		$wp_customize->selective_refresh->add_partial(
			'hire_btn_text',
			array(
				'selector' => '.topbar ul.right a.quote_btn',
				'settings' => 'hire_btn_text',
				'render_callback' => function () {
					return get_theme_mod( 'hire_btn_text' );
				},
			)
		);

		/**
		 * blogname
		 */
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector' => '.site-title',
				'settings' => 'blogname',
				'render_callback' => function () {
					return get_theme_mod( 'blogname' );
				},
			)
		);

		/**
		 * blogdescription
		 */
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector' => '.site-description',
				'settings' => 'blogdescription',
				'render_callback' => function () {
					return get_theme_mod( 'blogdescription' );
				},
			)
		);

		// callout icon
		$wp_customize->selective_refresh->add_partial(
			'callout1_icon',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(1) i',
				'settings'        => 'callout1_icon',
				'render_callback' => function() {
					return get_theme_mod( 'callout1_icon' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout2_icon',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(2) i',
				'settings'        => 'callout2_icon',
				'render_callback' => function() {
					return get_theme_mod( 'callout2_icon' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout3_icon',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(3) i',
				'settings'        => 'callout3_icon',
				'render_callback' => function() {
					return get_theme_mod( 'callout3_icon' );},

			)
		);
		// callout Title
		$wp_customize->selective_refresh->add_partial(
			'callout1_title',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(1) h5',
				'settings'        => 'callout1_title',
				'render_callback' => function() {
					return get_theme_mod( 'callout1_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout2_title',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(2) h5',
				'settings'        => 'callout2_title',
				'render_callback' => function() {
					return get_theme_mod( 'callout2_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout3_title',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(3) h5',
				'settings'        => 'callout3_title',
				'render_callback' => function() {
					return get_theme_mod( 'callout3_title' );},

			)
		);
		// callout Description
		$wp_customize->selective_refresh->add_partial(
			'callout1_desc',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(1) p',
				'settings'        => 'callout1_desc',
				'render_callback' => function() {
					return get_theme_mod( 'callout1_desc' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout2_desc',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(2) p',
				'settings'        => 'callout2_desc',
				'render_callback' => function() {
					return get_theme_mod( 'callout2_desc' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'callout3_desc',
			array(
				'selector'        => '.section.features .col-md-4:nth-child(3) p',
				'settings'        => 'callout3_desc',
				'render_callback' => function() {
					return get_theme_mod( 'callout3_desc' );},

			)
		);
		// Service
		$wp_customize->selective_refresh->add_partial(
			'service_title',
			array(
				'selector'        => '#service-section .sub-title',
				'settings'        => 'service_title',
				'render_callback' => function() {
					return get_theme_mod( 'service_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_sub_title',
			array(
				'selector'        => '#service-section .ititle',
				'settings'        => 'service_sub_title',
				'render_callback' => function() {
					return get_theme_mod( 'service_sub_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_description',
			array(
				'selector'        => '#service-section .section-heading p',
				'settings'        => 'service_description',
				'render_callback' => function() {
					return get_theme_mod( 'service_description' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'service_repeater',
			array(
				'selector'        => '#service-section .row',
				'settings'        => 'service_repeater',
				'render_callback' => function() {
					return get_theme_mod( 'service_repeater' );},

			)
		);
		// Testimonial
		$wp_customize->selective_refresh->add_partial(
			'testimonial_title',
			array(
				'selector'        => '.section.testimonials .sub-title',
				'settings'        => 'testimonial_title',
				'render_callback' => function() {
					return get_theme_mod( 'testimonial_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'testimonial_sub_title',
			array(
				'selector'        => '.section.testimonials .ititle',
				'settings'        => 'testimonial_sub_title',
				'render_callback' => function() {
					return get_theme_mod( 'testimonial_sub_title' );},

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
				'selector'        => '.section.testimonials .row',
				'settings'        => 'testimonial_repeater',
				'render_callback' => function() {
					return get_theme_mod( 'testimonial_repeater' );},

			)
		);
		// Blogs
		$wp_customize->selective_refresh->add_partial(
			'blog_title',
			array(
				'selector'        => '.section.blogs .sub-title',
				'settings'        => 'blog_title',
				'render_callback' => function() {
					return get_theme_mod( 'blog_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blog_sub_title',
			array(
				'selector'        => '.section.blogs .ititle',
				'settings'        => 'blog_sub_title',
				'render_callback' => function() {
					return get_theme_mod( 'blog_sub_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blog_description',
			array(
				'selector'        => '.section.blogs .section-heading p',
				'settings'        => 'blog_description',
				'render_callback' => function() {
					return get_theme_mod( 'blog_description' );},

			)
		);
		/**
		 * Call To Action
		 */
		$wp_customize->selective_refresh->add_partial(
			'cta_title',
			array(
				'selector'        => '#call-to-action .sub-title',
				'settings'        => 'cta_title',
				'render_callback' => function() {
					return get_theme_mod( 'cta_title' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'cta_desc',
			array(
				'selector'        => '#call-to-action .ititle',
				'settings'        => 'cta_desc',
				'render_callback' => function() {
					return get_theme_mod( 'cta_desc' );},

			)
		);
		$wp_customize->selective_refresh->add_partial(
			'cta_btn_read',
			array(
				'selector'        => '#call-to-action a.btn',
				'settings'        => 'cta_btn_read',
				'render_callback' => function() {
					return get_theme_mod( 'cta_btn_read' );},

			)
		);
	}



}
