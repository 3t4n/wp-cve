<?php

class ThemeRain_Customizer {

	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue' ], 11 );
	}

	public function enqueue() {
		wp_enqueue_style( 'trc-customizer', TRC_ASSETS_URL . '/css/customizer.css', array( 'customize-preview' ) );
	}

	public function customize_register( $wp_customize ) {
		require_once dirname(__FILE__) . '/controls/title-control.php';
		require_once dirname(__FILE__) . '/controls/fonts-control.php';

		$sections = apply_filters( 'themerain_customizer', array() );
		$i        = 30;

		if ( ! is_array( $sections ) ) {
			return;
		}

		foreach( $sections as $section ) {
			$i++;
			$section_id = 'themerain_section_' . $i;

			if ( isset( $section['label'] ) ) {
				$this->section( $wp_customize, $section_id, $section['label'], $i );
			}

			foreach( $section['controls'] as $control ) {
				$this->setting( $wp_customize, $control );
				$this->control( $wp_customize, $control, $section_id );
			}
		}
	}

	public function section( $wp_customize, $id, $label, $priority ) {
		$args = array(
			'title'    => $label,
			'priority' => $priority
		);

		$wp_customize->add_section( $id, $args );
	}

	public function setting( $wp_customize, $control ) {
		switch( $control['type'] ) {
			case 'textarea':
				$sanitize = 'themerain_sanitize_svg';
				break;
			case 'url':
				$sanitize = 'esc_url_raw';
				break;
			case 'checkbox':
				$sanitize = 'absint';
				break;
			case 'number':
				$sanitize = 'absint';
				break;
			case 'image':
				$sanitize = 'esc_url_raw';
				break;
			case 'color':
				$sanitize = 'sanitize_hex_color';
				break;
			default:
				$sanitize = 'wp_kses_post';
		}

		$args = array(
			'sanitize_callback' => $sanitize
		);

		if ( isset( $control['std'] ) ) {
			$args['default'] = $control['std'];
		}

		$wp_customize->add_setting( $control['id'], $args );
	}

	public function control( $wp_customize, $control, $section_id ) {
		$args = array(
			'label' => $control['label'],
			'type'  => $control['type']
		);

		if ( isset( $control['section'] ) ) {
			$args['section'] = $control['section'];
		} else {
			$args['section'] = $section_id;
		}

		if ( isset( $control['desc'] ) ) {
			$args['description'] = $control['desc'];
		}

		if ( isset( $control['choices'] ) ) {
			$args['choices'] = $control['choices'];
		}

		if ( $control['type'] === 'media' ) {
			$args['mime_type'] = 'image';
		}

		if ( $control['type'] === 'fonts' ) {
			$fonts           = apply_filters( 'themerain_get_fonts', array() );
			$default_font    = isset( $control['choices'] ) ? array( 'Default' => $control['choices'] ) : '';
			$fonts           = ( $default_font ) ? $default_font + $fonts : $fonts;
			$args['choices'] = $fonts;
		}

		if ( $control['type'] === 'section-title' ) {
			$wp_customize->add_control( new TRC_Customize_Title_Control( $wp_customize, $control['id'], $args ) );
		} elseif ( $control['type'] === 'image' ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $control['id'], $args ) );
		} elseif ( $control['type'] === 'media' ) {
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $control['id'], $args ) );
		} elseif ( $control['type'] === 'color' ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control['id'], $args ) );
		} elseif ( $control['type'] === 'fonts' ) {
			$wp_customize->add_control( new ThemeRain_Customize_Fonts_Control( $wp_customize, $control['id'], $args ) );
		} else {
			$wp_customize->add_control( $control['id'], $args );
		}
	}
}

new ThemeRain_Customizer();

/**
 * Sanitize HTML.
 */
function themerain_sanitize_svg( $html ) {
	$kses_defaults = wp_kses_allowed_html( 'post' );

	$svg_args = array(
		'svg' => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true,
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array( 'd' => true, 'fill' => true ),
	);

	$allowed = array_merge( $kses_defaults, $svg_args );

	return wp_kses( $html, $allowed );
}
