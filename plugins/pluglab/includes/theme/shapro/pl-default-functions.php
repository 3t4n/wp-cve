<?php

if ( ! function_exists( 'slider_default_json' ) ) {

	function slider_default_json() {
		return json_encode(
			array(
				array(
					'image_url' => PL_PLUGIN_URL . 'assets/images/slider1.jpg',
					'title'     => 'Exceeding Your Expectations',
					'subtitle'  => 'Business we operate in is like an intricate game of strategy and chess, where every move counts and you keep score with money',
					'text'      => 'Curabitur',
					'text2'     => 'Phasellus',
					'link'      => '#',
					'link2'     => '#',
					'id'        => 'customizer_repeater_77y7op1f70b00',
				),
				array(
					'image_url' => PL_PLUGIN_URL . 'assets/images/slider2.jpg',
					'title'     => 'Future Is Bright Think Avantage',
					'subtitle'  => 'Business we operate in is like an intricate game of strategy and chess, where every move counts and you keep score with money.',
					'text'      => 'Curabitur',
					'text2'     => 'Phasellus',
					'link'      => '#',
					'link2'     => '#',
					'id'        => 'customizer_repeater_77y7op1f70b01',
				),
			)
		);
	}
}

if ( ! function_exists( 'service_default_json' ) ) {

	function service_default_json() {
		return json_encode(
			array(
				array(
					'image_url'  => PL_PLUGIN_URL . 'assets/images/ser1.jpg',
					'title'      => 'Avantage Services',
					'subtitle'   => 'Business we operate in is like an intricate',
					'text'       => 'Curabitur',
					'link'       => '#',
					'choice'     => 'customizer_repeater_icon',
					'icon_value' => 'fa-first-order',
					'id'         => 'customizer_repeater_97y7op8f70b00',
				),
				array(
					'image_url'  => PL_PLUGIN_URL . 'assets/images/ser2.jpg',
					'title'      => 'Our Approach',
					'subtitle'   => 'Business we operate in is like an intricate',
					'text'       => 'Curabitur',
					'link'       => '#',
					'choice'     => 'customizer_repeater_icon',
					'icon_value' => 'fa-phone',
					'id'         => 'customizer_repeater_99y7op8f70b00',
				),
				array(
					'image_url'  => PL_PLUGIN_URL . 'assets/images/ser3.jpg',
					'title'      => 'Business Management',
					'subtitle'   => 'Business we operate in is like an intricate',
					'text'       => 'Curabitur',
					'link'       => '#',
					'choice'     => 'customizer_repeater_icon',
					'icon_value' => 'fa-certificate',
					'id'         => 'customizer_repeater_90y7op8f70b00',
				),
			)
		);
	}
}

if ( ! function_exists( 'testimonial_default_json' ) ) {

	function testimonial_default_json() {
		return json_encode(
			array(
				array(
					'title'     => 'Absolutely spot-on!',
					'subtitle'  => 'Donec eget ex nec leo mattis dignissim.',
					'text'      => 'Curabitur',
					'text2'     => 'Curabitur',
					'link'      => '#',
					'link2'     => '#',
					'choice'    => 'customizer_repeater_icon',
					'image_url' => PL_PLUGIN_URL . 'assets/images/circle.png',
					'id'        => 'customizer_repeater_90y7op8f70b40',
				),
				array(
					'title'     => 'Best decision ever',
					'subtitle'  => 'Donec eget ex nec leo mattis dignissim.',
					'text'      => 'Curabitur',
					'text2'     => 'Curabitur',
					'link'      => '#',
					'choice'    => 'customizer_repeater_icon',
					'image_url' => PL_PLUGIN_URL . 'assets/images/circle1.png',
					'id'        => 'customizer_repeater_91y7op8f70b00',
				),
				array(
					'title'     => 'Saved my Business',
					'subtitle'  => 'Donec eget ex nec leo mattis dignissim.',
					'text'      => 'Curabitur',
					'text2'     => 'Curabitur',
					'link'      => '#',
					'choice'    => 'customizer_repeater_icon',
					'image_url' => PL_PLUGIN_URL . 'assets/images/circle2.png',
					'id'        => 'customizer_repeater_95h7op8f70b00',
				),
			)
		);
	}
}

if ( ! function_exists( 'pluglab_get_social_icon_default' ) ) {

	function pluglab_get_social_icon_default() {
		return apply_filters(
			'pluglab_get_social_icon_default',
			json_encode(
				array(
					array(
						'icon_value' => esc_html__( 'fa-facebook', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_001',
					),
					array(
						'icon_value' => esc_html__( 'fa-twitter', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_003',
					),
					array(
						'icon_value' => esc_html__( 'fa-linkedin', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_004',
					),
					array(
						'icon_value' => esc_html__( 'fa-instagram', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_005',
					),
				)
			)
		);
	}
}

if ( ! function_exists( 'pluglab_contact_info_default' ) ) {

	function pluglab_contact_info_default() {
		return apply_filters(
			'pluglab_contact_info_default',
			json_encode(
				array(
					array(
						'icon_value' => esc_html__( 'fa-phone', 'pluglab' ),
						'title'      => esc_html__( 'Contact Us', 'pluglab' ),
						'text'   => __( '134-566-7680', 'pluglab' ),
						'text2'   => __( '134-566-7680, USA 00202', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_011',
					),
					array(
						'icon_value' => esc_html__( 'fa-envelope', 'pluglab' ),
						'title'      => esc_html__( 'Email Us', 'pluglab' ),
						'text'   => __( 'info@yoursite.com', 'pluglab' ),
						'text2'   => __( 'info@yoursite.com', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_0233',
					),
					array(
						'icon_value' => esc_html__( 'fa-globe', 'pluglab' ),
						'title'      => esc_html__( 'Office Address', 'pluglab' ),
						'text'   => __( 'New York Brooklyn Bridge South St', 'pluglab' ),
						'text2'   => '',
						'id'         => 'customizer_repeater_header_social_0344',
					),
				)
			)
		);
	}
}

function plugLab_customizer_repeater_defaut_json( $wp_customize ) {

	$slider_default_data = $wp_customize->get_setting( 'slider_repeater' );
	if ( ! empty( $slider_default_data ) ) {
		$slider_default_data->default = slider_default_json();
	}

	$service_default_data = $wp_customize->get_setting( 'service_repeater' );
	if ( ! empty( $service_default_data ) ) {
		$service_default_data->default = service_default_json();
	}

	$testimonial_default_data = $wp_customize->get_setting( 'testimonial_repeater' );
	if ( ! empty( $testimonial_default_data ) ) {
		$testimonial_default_data->default = testimonial_default_json();
	}
}

add_action( 'customize_register', 'plugLab_customizer_repeater_defaut_json', 12 );

function plugLab_activeCallback( $wp_customize ) {

	// Slider
	function plugLab_slider_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'slider_display' )->value() ) {
			return true;
		}
		return false;
	}

	// callout
	function plugLab_callout_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'callout_display' )->value() ) {
			return true;
		}
		return false;
	}

	// service
	function plugLab_service_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'service_display' )->value() ) {
			return true;
		}
		return false;
	}

	// cta
	function plugLab_cta_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'cta_display' )->value() ) {
			return true;
		}
		return false;
	}

	// testimonial
	function plugLab_testimonial_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'testimonial_display' )->value() ) {
			return true;
		}
		return false;
	}

	// blog
	function plugLab_blog_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'blog_display' )->value() ) {
			return true;
		}
		return false;
	}
	function plugLab_blog_home_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'blog_display' )->value() && (bool) $control->manager->get_setting( 'blog_meta_display' )->value()) {
			return true;
		}
		return false;
	}

}

add_action( 'customize_register', 'plugLab_activeCallback' );
