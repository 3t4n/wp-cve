<?php

namespace Codemanas\Typesense\Backend;

use WP_Customize_Color_Control;

class Customizer {
	public static ?Customizer $instance = null;

	/**
	 * @return Customizer|null
	 */
	public static function get_instance(): ?Customizer {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	public function __construct() {
		$search_config_settings = Admin::get_search_config_settings();
		if ( ! $search_config_settings['hijack_wp_search'] || $search_config_settings['hijack_wp_search__type'] != 'instant_search' ) {
			return;
		}
		add_action( 'customize_register', [ $this, 'customizer' ] );
		add_action( 'customize_preview_init', [ $this, 'customize_preview_js' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'customize_control_js' ] );
	}

	/**
	 * Bind JS handlers to instantly live-preview changes.
	 */
	public function customize_preview_js() {
		wp_enqueue_script( 'cmswt-customize-preview', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/customizer/customize-preview.js', [ 'customize-preview' ], '', true );
	}

	public function customize_control_js() {
		wp_enqueue_script( 'cmswt-customize-control', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/customizer/customize-control.js', [
			'customize-controls',
			'customize-preview'
		], '', true );
	}

	/**
	 * @return array
	 */
	public function get_formatted_available_post_types(): array {
		$search_config_settings      = Admin::get_search_config_settings();
		$available_post_type_choices = [];
		foreach ( $search_config_settings['available_post_types'] as $available_post_type ) {
			$available_post_type_choices[ $available_post_type['value'] ] = $available_post_type['label'];
		}

		return $available_post_type_choices;
	}

	/**
	 * @return array
	 */
	public function get_formatted_enabled_post_types(): array {
		$search_config_settings = Admin::get_search_config_settings();

		return $search_config_settings['enabled_post_types'];
	}

	public function sanitize_available_post_types( $input ) {
		if ( strpos( $input, ',' ) !== false ) {
			$input = explode( ',', $input );
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[ $key ] = sanitize_text_field( $value );
			}
			$input = implode( ',', $input );
		} else {
			$input = sanitize_text_field( $input );
		}

		return $input;
	}

	/**
	 * @param \WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 */
	public function customizer( \WP_Customize_Manager $wp_customize ) {

//		$wp_customize->add_panel( 'typesense', [
//			'priority'       => 200,
//			'capability'     => 'manage_options',
//			'theme_supports' => '',
//			'title'          => __( 'Typesense', 'search-with-typesense' ),
//		] );


		$wp_customize->add_section( 'typesense_popup', array(
			'title'          => __( 'Typesense Search', 'search-with-typesense' ),
			'description'    => __( 'Change the popup design', 'search-with-typesense' ),
			//'panel'          => 'typesense', // Not typically needed.
			'priority'       => 160,
			'capability'     => 'manage_options',
			'theme_supports' => '', // Rarely needed.
		) );

		//select post types / categories to show
		$wp_customize->add_setting( 'typesense_customizer_instant_search[available_post_types]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => $this->get_formatted_enabled_post_types(),
			'transport'            => 'refresh',
			'sanitize_callback'    => [ $this, 'sanitize_available_post_types' ],
			'sanitize_js_callback' => '', // Basically to_json.
		] );

		// var_dump( $this->get_formatted_enabled_post_types() );

		$wp_customize->add_control( new CustomizerSelect2( $wp_customize, 'typesense_customizer_instant_search[available_post_types]', [
			'type'     => 'cm-select2',
			'priority' => 10, // Within the section.
			'section'  => 'typesense_popup', // Required, core or custom.
			'label'    => __( 'Select Post Types or Categories to show', 'search-with-typesense' ),
			'choices'  => $this->get_formatted_available_post_types()
			//'active_callback' => '',
		] ) );

		//search placeholder
		$wp_customize->add_setting( 'typesense_customizer_instant_search[search_placeholder]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => __( 'Search...', 'search-with-typesense' ),
			'transport'            => 'postMessage',
			'sanitize_callback'    => '',
			'sanitize_js_callback' => '', // Basically to_json.,
		] );
		$wp_customize->add_control( 'typesense_customizer_instant_search[search_placeholder]', [
			'type'        => 'text',
			'priority'    => 10, // Within the section.
			'section'     => 'typesense_popup', // Required, core or custom.
			'label'       => __( 'Placeholder Text', 'search-with-typesense' ),
			'description' => __( 'Change the placeholder text' ),

			//'active_callback' => '',
		] );

		//Sort By
		$wp_customize->add_setting( 'typesense_customizer_instant_search[sort_by]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => 'show',
			'transport'            => 'postMessage',
			'sanitize_callback'    => '',
			'sanitize_js_callback' => '', // Basically to_json.
		] );
		$wp_customize->add_control( 'typesense_customizer_instant_search[sort_by]', [
			'type'        => 'radio',
			'priority'    => 10, // Within the section.
			'section'     => 'typesense_popup', // Required, core or custom.
			'label'       => __( 'Show or Hide Sort by options', 'search-with-typesense' ),
			'description' => __( 'Show / Hide Sort by' ),
			'choices'     => [
				'show' => __( 'Show', 'search-with-typesense' ),
				'hide' => __( 'Hide', 'search-with-typesense' ),
			],
			//'active_callback' => '',
		] );

		//Filter
		$wp_customize->add_setting( 'typesense_customizer_instant_search[filter]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => 'show',
			'transport'            => 'postMessage',
			'sanitize_callback'    => '',
			'sanitize_js_callback' => '', // Basically to_json.
		] );
		$wp_customize->add_control( 'typesense_customizer_instant_search[filter]', [
			'type'        => 'radio',
			'priority'    => 10, // Within the section.
			'section'     => 'typesense_popup', // Required, core or custom.
			'label'       => __( 'Show or Hide Filters', 'search-with-typesense' ),
			'description' => __( 'Show / Hide or Infinite Pagination' ),
			'choices'     => [
				'show' => __( 'Show', 'search-with-typesense' ),
				'hide' => __( 'Hide', 'search-with-typesense' ),
			],
			//'active_callback' => '',
		] );

		//per page
		$wp_customize->add_setting( 'typesense_customizer_instant_search[results_per_page]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => '12',
			'transport'            => 'refresh', // or postMessage
			'sanitize_callback'    => '',
			'sanitize_js_callback' => '', // Basically to_json.
		] );
		$wp_customize->add_control( 'typesense_customizer_instant_search[results_per_page]', [
			'type'        => 'number',
			'priority'    => 10, // Within the section.
			'section'     => 'typesense_popup', // Required, core or custom.
			'label'       => __( 'Results per page', 'search-with-typesense' ),
			'description' => __( 'No of results to show at one time' ),
			//'active_callback' => '',
			'input_attrs' => [
				'min'  => 1,
				'step' => 1,
			],
		] );

		//number of columns
		$wp_customize->add_setting( 'typesense_customizer_instant_search[no_of_cols]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => '1',
			'transport'            => 'postMessage',
			'sanitize_callback'    => '',
			'sanitize_js_callback' => '', // Basically to_json.
		] );
		$wp_customize->add_control( 'typesense_customizer_instant_search[no_of_cols]', [
			'type'        => 'number',
			'priority'    => 10, // Within the section.
			'section'     => 'typesense_popup', // Required, core or custom.
			'label'       => __( 'Items per row', 'search-with-typesense' ),
			'description' => __( 'This allows you to change how many items to show per row' ),
			//'active_callback' => '',
			'input_attrs' => [
				'min'  => 1,
				'max'  => 4,
				'step' => 1,
			],
		] );

		//Pagination
		$wp_customize->add_setting( 'typesense_customizer_instant_search[pagination]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => 'show',
			'transport'            => 'refresh', // or postMessage
			'sanitize_callback'    => '',
			'sanitize_js_callback' => '', // Basically to_json.
		] );
		$wp_customize->add_control( 'typesense_customizer_instant_search[pagination]', [
			'type'        => 'radio',
			'priority'    => 10, // Within the section.
			'section'     => 'typesense_popup', // Required, core or custom.
			'label'       => __( 'Pagination', 'search-with-typesense' ),
			'description' => __( 'Show / Hide or Infinite Pagination' ),
			'choices'     => [
				'show'     => __( 'Show', 'search-with-typesense' ),
				'infinite' => __( 'Infinite Scrolling', 'search-with-typesense' ),
				'hide'     => __( 'Hide', 'search-with-typesense' ),
			],
			//'active_callback' => '',
		] );

		//select color
		$wp_customize->add_setting( 'typesense_customizer_instant_search[color]', [
			'type'                 => 'option', // or 'option'
			'capability'           => 'manage_options',
			'theme_supports'       => '', // Rarely needed.
			'default'              => '#ffc168',
			'transport'            => 'postMessage',
			'sanitize_callback'    => '',
			'sanitize_js_callback' => '', // Basically to_json.
		] );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'typesense_customizer_instant_search[color]', [
			'type'        => 'color',
			'priority'    => 10, // Within the section.
			'section'     => 'typesense_popup', // Required, core or custom.
			'label'       => __( 'Highlight Color', 'search-with-typesense' ),
			'description' => __( 'This allows you to change the highlight color of the matched text' ),
			//'active_callback' => '',
		] ) );

	}
}