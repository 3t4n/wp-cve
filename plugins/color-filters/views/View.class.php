<?php

/**
 * Base class for any view requested on the front end.
 *
 * @since 3.0.0
 */
class ewduwcfView extends ewduwcfBase {

	/**
	 * Post type to render
	 */
	public $post_type = null;

	/**
	 * Map types of content to the template which will render them
	 */
	public $content_map = array(
		'title'							 => 'content/title',
	);

	/**
	 * Initialize the class
	 * @since 3.0.0
	 */
	public function __construct( $args ) {

		// Parse the values passed
		$this->parse_args( $args );
		
		// Filter the content map so addons can customize what and how content
		// is output. Filters are specific to each view, so for this base view
		// you would use the filter 'us_content_map_ewduwcfView'
		$this->content_map = apply_filters( 'ewd_uwcf_content_map_' . get_class( $this ), $this->content_map );

	}

	/**
	 * Render the view and enqueue required stylesheets
	 *
	 * @note This function should always be overridden by an extending class
	 * @since 3.0.0
	 */
	public function render() {

		$this->set_error(
			array( 
				'type'		=> 'render() called on wrong class'
			)
		);
	}

	/**
	 * Load a template file for views
	 *
	 * First, it looks in the current theme's /ewd-uwcf-templates/ directory. Then it
	 * will check a parent theme's /ewd-uwcf-templates/ directory. If nothing is found
	 * there, it will retrieve the template from the plugin directory.

	 * @since 3.0.0
	 * @param string template Type of template to load (eg - reviews, review)
	 */
	function find_template( $template ) {

		$this->template_dirs = array(
			get_stylesheet_directory() . '/' . EWD_UWCF_TEMPLATE_DIR . '/',
			get_template_directory() . '/' . EWD_UWCF_TEMPLATE_DIR . '/',
			EWD_UWCF_PLUGIN_DIR . '/' . EWD_UWCF_TEMPLATE_DIR . '/'
		);
		
		$this->template_dirs = apply_filters( 'ewd_uwcf_template_directories', $this->template_dirs );

		foreach ( $this->template_dirs as $dir ) {
			if ( file_exists( $dir . $template . '.php' ) ) {
				return $dir . $template . '.php';
			}
		}

		return false;
	}

	/**
	 * Enqueue stylesheets
	 */
	public function enqueue_assets() {

		//enqueue assets here
	}

	public function get_option( $option_name ) {
		global $ewd_uwcf_controller;

		return ! empty( $this->$option_name ) ? $this->$option_name : $ewd_uwcf_controller->settings->get_setting( $option_name );
	}

	public function get_label( $label_name ) {
		global $ewd_uwcf_controller;

		if ( empty( $this->label_defaults ) ) { $this->set_label_defaults(); }

		return ! empty( $ewd_uwcf_controller->settings->get_setting( $label_name ) ) ? $ewd_uwcf_controller->settings->get_setting( $label_name ) : $this->label_defaults[ $label_name ];
	}

	public function set_label_defaults() {

		$this->label_defaults = array(
			'label-show-all-color'								=> __( 'Show All Colors', 'color-filters' ),
			'label-show-all-size'								=> __( 'Show All Sizes', 'color-filters' ),
			'label-show-all-category'							=> __( 'Show All Categories', 'color-filters' ),
			'label-show-all-tag'								=> __( 'Show All Tags', 'color-filters' ),
			'label-show-all-attribute'							=> __( 'Show All', 'color-filters' ),
			'label-rating'										=> __( 'Rating', 'color-filters' ),
			'label-thumbnail-colors'							=> __( 'Colors', 'color-filters' ),
			'label-thumbnail-sizes'								=> __( 'Sizes', 'color-filters' ),
			'label-thumbnail-categories'						=> __( 'Categories', 'color-filters' ),
			'label-thumbnail-tags'								=> __( 'Tags', 'color-filters' ),
			'label-thumbnail-attributes'						=> __( '%ss', 'color-filters' ),
			'label-product-page-colors'							=> __( 'Colors', 'color-filters' ),
			'label-product-page-sizes'							=> __( 'Sizes', 'color-filters' ),
		);
	}

	public function add_custom_styling() {
		global $ewd_uwcf_controller;

		echo '<style>';
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-color-icon-size' ) != '' ) { 

				echo '#ewd-uwcf-filtering-form .ewd-uwcf-style-swatch .ewd-uwcf-color-item, #ewd-uwcf-filtering-form .ewd-uwcf-style-swatch .ewd-uwcf-color-wrap, #ewd-uwcf-filtering-form .ewd-uwcf-style-swatch .ewd-uwcf-color-preview, #ewd-uwcf-filtering-form .ewd-uwcf-style-tiles .ewd-uwcf-color-item, #ewd-uwcf-filtering-form .ewd-uwcf-style-tiles .ewd-uwcf-color-wrap, #ewd-uwcf-filtering-form .ewd-uwcf-style-tiles .ewd-uwcf-color-preview { width: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-color-icon-size' ) ) . ' !important; height: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-color-icon-size' ) ) . ' !important; }'; 
				echo '#ewd-uwcf-filtering-form .ewd-uwcf-style-swatch .ewd-uwcf-color-item.ewd-uwcf-all-color, #ewd-uwcf-filtering-form .ewd-uwcf-style-tiles .ewd-uwcf-color-item.ewd-uwcf-all-color { width: 100% !important; height: auto !important; }'; 
			}

			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-widget-font-color' ) != '' ) { echo '#ewd-uwcf-filtering-form { color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-widget-font-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-widget-font-size' ) != '' ) { echo '#ewd-uwcf-filtering-form { font-size: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-widget-font-size' ) ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-fill-color' ) != '' ) { echo '#ewd-uwcf-ratings-slider, #ewd-uwcf-price-slider { background-color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-fill-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-empty-color' ) != '' ) { echo '#ewd-uwcf-ratings-slider .ui-widget-header, #ewd-uwcf-price-slider .ui-widget-header { background-color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-empty-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-handle-color' ) != '' ) { echo '#ewd-uwcf-ratings-slider .ui-slider-handle, #ewd-uwcf-price-slider .ui-slider-handle { background-color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-handle-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-text-color' ) != '' ) { echo '.ewd-uwcf-ratings-slider-min, .ewd-uwcf-ratings-slider-max, .ewd-uwcf-price-slider-min, .ewd-uwcf-price-slider-max { color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-text-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-font-size' ) != '' ) { echo '.ewd-uwcf-ratings-slider-min, .ewd-uwcf-ratings-slider-max, .ewd-uwcf-price-slider-min, .ewd-uwcf-price-slider-max { font-size: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-ratings-bar-font-size' ) ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-background-color' ) != '' ) { echo '.ewd-uwcf-reset-all { background-color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-background-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-text-color' ) != '' ) { echo '.ewd-uwcf-reset-all { color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-text-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-font-size' ) != '' ) { echo '.ewd-uwcf-reset-all { font-size: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-font-size' ) ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-hover-bg-color' ) != '' ) { echo '.ewd-uwcf-reset-all:hover { background-color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-hover-bg-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-hover-text-color' ) != '' ) { echo '.ewd-uwcf-reset-all:hover { color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-reset-all-button-hover-text-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-shop-thumbnails-font-color' ) != '' ) { echo '.ewd-uwcf-thumbnail-links, .ewd-uwcf-thumbnail-links a { color: ' . $ewd_uwcf_controller->settings->get_setting( 'styling-shop-thumbnails-font-color' ) . ' !important; }'; }
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-shop-thumbnails-font-size' ) != '' ) { echo '.ewd-uwcf-thumbnail-links, .ewd-uwcf-thumbnail-links a { font-size: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-shop-thumbnails-font-size' ) ) . ' !important; }'; }
			
			if ( $ewd_uwcf_controller->settings->get_setting( 'styling-shop-thumbnails-color-icon-size' ) != '' ) { 

				echo '.ewd-uwcf-shop-product-colors-container .ewd-uwcf-color-preview { width: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-shop-thumbnails-color-icon-size' ) ) . ' !important; }';
				echo '.ewd-uwcf-shop-product-colors-container .ewd-uwcf-color-preview { height: ' . ewd_check_font_size( $ewd_uwcf_controller->settings->get_setting( 'styling-shop-thumbnails-color-icon-size' ) ) . ' !important; }';
			}
			
			$ewd_uwcf_controller->settings->get_setting( 'custom-css' );

		echo  '</style>';
	}

}
