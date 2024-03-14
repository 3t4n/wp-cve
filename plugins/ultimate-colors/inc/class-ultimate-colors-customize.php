<?php
/**
 * Change colors in the Customizer.
 */

/**
 * Customizer class.
 */
class Ultimate_Colors_Customizer {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_preview_init', array( $this, 'customizer_live_preview' ) );
		add_action( 'wp_head', array( $this, 'output' ) );
	}

	/**
	 * Register typography settings in the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function register( WP_Customize_Manager $wp_customize ) {
		$option = get_option( 'ultimate-colors' );
		if ( empty( $option['elements'] ) ) {
			return;
		}

		foreach ( $option['elements'] as $element ) {
			$key        = "{$element['selector']}-{$element['property']}";
			$setting_id = "ultimate_colors_customize[$key]";

			$wp_customize->add_setting( $setting_id, array(
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
			$wp_customize->add_control( new WP_Customize_Color_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'    => __( $element['label'], 'ultimate-colors' ),
					'section'  => 'colors',
					'settings' => $setting_id,
				)
			) );
		}
	}

	/**
	 * Enqueue scripts for live preview.
	 */
	public function customizer_live_preview() {
		wp_enqueue_script( 'ultimate-colors', Ultimate_Colors::instance()->url . 'js/customizer.js', array(
			'jquery',
			'customize-preview',
		), '', true );

		$option   = get_option( 'ultimate-colors' );
		$elements = empty( $option['elements'] ) ? array() : $option['elements'];
		wp_localize_script( 'ultimate-colors', 'Ultimate_Colors', $elements );
	}

	/**
	 * Output CSS in the header.
	 */
	public function output() {
		$option = get_option( 'ultimate-colors' );
		if ( empty( $option['elements'] ) ) {
			return;
		}

		$css = array();
		foreach ( $option['elements'] as $element ) {
			$css[] = $this->element_css( $element );
		}
		$css = array_filter( $css );

		if ( $css ) {
			echo "\n<!-- This site uses the Ultimate Colors plugin v1.0.0 to customize colors - https://gretathemes.com -->\n";
			echo "<style>\n" . wp_strip_all_tags( implode( "\n", array_filter( $css ) ) ) . "\n</style>\n";
		}
	}

	/**
	 * Get CSS for a single element.
	 *
	 * @param  array $element Element parameter
	 *
	 * @return string
	 */
	public function element_css( $element ) {
		$key       = "{$element['selector']}-{$element['property']}";
		$customize = get_option( 'ultimate_colors_customize', array() );
		if ( empty( $customize[ $key ] ) ) {
			return '';
		}

		return sprintf( '%s { %s: %s%s; }',
			$element['selector'],
			$element['property'],
			$customize[ $key ],
			is_customize_preview() ? '' : ' !important'
		);
	}
}
