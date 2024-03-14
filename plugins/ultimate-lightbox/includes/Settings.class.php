<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdulbSettings' ) ) {
/**
 * Class to handle configurable settings for Ultimate Lightbox
 *
 * @since 1.0.0
 */
class ewdulbSettings {

	/**
	 * Default values for settings
	 * @since 1.0.0
	 */
	public $defaults = array();

	/**
	 * Stored values for settings
	 * @since 1.0.0
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'load_settings_panel' ) );
	}

	/**
	 * Load the plugin's default settings
	 * @since 1.0.0
	 */
	public function set_defaults() {

		$this->defaults = array(

			'add_lightbox'					=> array(),
			'transition-type'				=> 'ewd-ulb-no-transition',
			'transition-speed'				=> 600,
			'background-close'				=> true,
			'gallery-loop'					=> true,
			'mousewheel-navigation'			=> true,
			'overlay-text-source'			=> 'alt',
			'show-thumbnails'				=> 'bottom',
			'show-thumbnail-toggle'			=> true,
			'autoplay-interval'				=> 5000,
			'show-progress-bar'				=> false,
			'mobile-hide-elements'			=> array( 'description', 'thumbnails' ), 
			'min-height'					=> 0,
			'min-width'						=> 0,
			'top-right-controls'			=> array( 'exit' ),
			'top-left-controls'				=> array( 'autoplay', 'zoom' ),
			'bottom-right-controls'			=> array( 'slide_counter' ),
			'bottom-left-controls'			=> array(),
			'arrow'							=> 'a',
			'icon-set'						=> 'a',
		);

		$this->defaults = apply_filters( 'ulb_defaults', $this->defaults );
	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 1.0.0
	 */
	public function get_setting( $setting ) {

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'ulb-settings' );
		}
		
		if ( ! empty( $this->settings[ $setting ] ) or isset( $this->settings[ $setting ] ) ) {
			return apply_filters( 'ulb-settings-' . $setting, $this->settings[ $setting ] );
		}

		if ( ! empty( $this->defaults[ $setting ] ) or isset( $this->defaults[ $setting ] ) ) {
			return apply_filters( 'ulb-settings-' . $setting, $this->defaults[ $setting ] );
		}

		return apply_filters( 'ulb-settings-' . $setting, null );
	}

	/**
	 * Set a setting to a particular value
	 * @since 1.0.0
	 */
	public function set_setting( $setting, $value ) {

		$this->settings[ $setting ] = $value;
	}

	/**
	 * Save all settings, to be used with set_setting
	 * @since 1.0.0
	 */
	public function save_settings() {
		
		update_option( 'ulb-settings', $this->settings );
	}

	/**
	 * Load the admin settings page
	 * @since 1.0.0
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {
		global $ulb_controller;

		require_once( EWD_ULB_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.6.13',
				'lib_url'       => EWD_ULB_PLUGIN_URL . '/lib/simple-admin-pages/',
				'theme'			=> 'purple',
			)
		);

		$sap->add_page(
			'menu',
			array(
				'id'            => 'ulb-settings',
				'title'         => __( 'Lightbox', 'ultimate-lightbox' ),
				'menu_title'    => __( 'Lightbox', 'ultimate-lightbox' ),
				'description'   => '',
				'capability'    => 'manage_options',
				'default_tab'   => 'ulb-basic-tab',
				'position'		=> 50.8,
				'icon'			=> 'dashicons-format-gallery',
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-basic-tab',
				'title'         => __( 'Basic', 'ultimate-lightbox' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-add-lightbox',
				'title'         => __( 'Add Lightbox Options', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-basic-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-add-lightbox',
			'checkbox',
			array(
				'id'			=> 'add-lightbox',
				'title'			=> __( 'Images with Lightbox', 'ultimate-lightbox' ),
				'description'	=> __( 'Use this section to choose which images have lightboxes.', 'ultimate-lightbox' ),
				'options'		=> array(
					'all_images' 				=> 'All Images',
					'galleries' 				=> 'All WordPress Galleries',
					'all_youtube'				=> 'All YouTube Videos',
					'woocommerce_product_page'	=> 'WooCommerce Product Page Images',
					'image_class'				=> 'Images with Class Set Below',
					'image_selector'			=> 'Images with CSS Selectors Set Below'
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-add-lightbox',
			'text',
			array(
				'id'            => 'image-class-list',
				'title'         => __( 'Classes to Add Lightbox', 'ultimate-lightbox' ),
				'description'	=> __( 'Can be a comma-separated list of classes to apply the lightbox to, with no extra spaces between the elements.', 'ultimate-lightbox' ),
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-add-lightbox',
			'text',
			array(
				'id'            => 'image-selector-list',
				'title'         => __( 'CSS Selectors to Add Lightbox', 'ultimate-lightbox' ),
				'description'	=> __( 'Can be a comma-separated list of CSS selectors to apply the lightbox to, with no extra spaces between the elements.', 'ultimate-lightbox' ),
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-general',
				'title'         => __( 'General', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-basic-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'textarea',
			array(
				'id'			=> 'custom-css',
				'title'			=> __( 'Custom CSS', 'ultimate-lightbox' ),
				'description'	=> __( 'You can add custom CSS styles to your lightbox in the box above.', 'ultimate-lightbox' ),			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'toggle',
			array(
				'id'			=> 'background-close',
				'title'			=> __( 'Close on Background Click', 'ultimate-lightbox' ),
				'description'	=> __( 'Should the lightbox close when the background is clicked?', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'radio',
			array(
				'id'			=> 'show-thumbnails',
				'title'			=> __( 'Show Thumbnail Images', 'ultimate-lightbox' ),
				'description'	=> __( 'Should thumbnails of other images in a specific gallery be shown?', 'ultimate-lightbox' ),
				'options'		=> array(
					'top'			=> __( 'Top', 'ultimate-lightbox' ),
					'bottom'		=> __( 'Bottom', 'ultimate-lightbox' ),
					'none'			=> __( 'None', 'ultimate-lightbox' )
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'toggle',
			array(
				'id'			=> 'show-overlay-text',
				'title'			=> __( 'Show Overlay Text', 'ultimate-lightbox' ),
				'description'	=> __( 'Should the text overlay show for images in the lightbox?', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'toggle',
			array(
				'id'			=> 'start-autoplay',
				'title'			=> __( 'Start Autoplay on Opening', 'ultimate-lightbox' ),
				'description'	=> __( 'Should autoplay start automatically when a gallery is opened?', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'text',
			array(
				'id'            => 'autoplay-interval',
				'title'         => __( 'Autoplay Interval (milliseconds)', 'ultimate-lightbox' ),
				'description'	=> __( 'How long should there be between transitions when autoplay is enabled? (Should be greater than 0)', 'ultimate-lightbox' ),
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'text',
			array(
				'id'            => 'min-height',
				'title'         => __( 'Minimum Image Height', 'ultimate-lightbox' ),
				'description'	=> __( 'What is the minimum height an image should have to be eligible to be opened in a lightbox?', 'ultimate-lightbox' ),
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'text',
			array(
				'id'            => 'min-width',
				'title'         => __( 'Minimum Image Width', 'ultimate-lightbox' ),
				'description'	=> __( 'What is the minimum width an image should have to be eligible to be opened in a lightbox?', 'ultimate-lightbox' ),
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-general',
			'select',
			array(
				'id'            => 'transition-type',
				'title'         => __( 'Transition Type', 'ultimate-lightbox' ),
				'description'   => __( 'Select the transition that happens when cycling through images in the lightbox.', 'ultimate-lightbox' ),
				'blank_option'	=> false,
				'options'       => array(
					'ewd-ulb-no-transition'		=> __( 'None', 'ultimate-lightbox' ),
					'ewd-ulb-horizontal-slide' 	=> __( 'Slide', 'ultimate-lightbox' )
				)
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-advanced-tab',
				'title'         => __( 'Advanced', 'ultimate-lightbox' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-advanced',
				'title'         => __( 'Advanced Options', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-advanced-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-advanced',
			'toggle',
			array(
				'id'			=> 'gallery-loop',
				'title'			=> __( 'Gallery Loop', 'ultimate-lightbox' ),
				'description'	=> __( 'Should it be possible to navigate from the last element back to the first?', 'ultimate-lightbox' )
			)
		);

		// $sap->add_setting(
		// 	'ulb-settings',
		// 	'ulb-advanced',
		// 	'toggle',
		// 	array(
		// 		'id'			=> 'mousewheel-navigation',
		// 		'title'			=> __( 'Mousewheel Navigation', 'ultimate-lightbox' ),
		// 		'description'	=> __( 'Should rolling the mousewheel transition a gallery between images?', 'ultimate-lightbox' )
		// 	)
		// );

		// $sap->add_setting(
		// 	'ulb-settings',
		// 	'ulb-advanced',
		// 	'toggle',
		// 	array(
		// 		'id'			=> 'curtain-slide',
		// 		'title'			=> __( 'Curtain Slide', 'ultimate-lightbox' ),
		// 		'description'	=> __( 'Should a curtain slide be added for images that are paired (By going to "Media" and clicking on an image)?', 'ultimate-lightbox' )
		// 	)
		// );

		$sap->add_setting(
			'ulb-settings',
			'ulb-advanced',
			'toggle',
			array(
				'id'			=> 'show-thumbnail-toggle',
				'title'			=> __( 'Show Thumbnail Toggle', 'ultimate-lightbox' ),
				'description'	=> __( 'Should the thumbnail toggle icon be shown, to hide or unhide thumbnail images?', 'ultimate-lightbox' )
			)
		);

		// $sap->add_setting(
		// 	'ulb-settings',
		// 	'ulb-advanced',
		// 	'toggle',
		// 	array(
		// 		'id'			=> 'show-progress-bar',
		// 		'title'			=> __( 'Show Autoplay Progress Bar', 'ultimate-lightbox' ),
		// 		'description'	=> __( 'Should a progress bar be displayed when autoplay is active?', 'ultimate-lightbox' )
		// 	)
		// );

		$sap->add_setting(
			'ulb-settings',
			'ulb-advanced',
			'checkbox',
			array(
				'id'			=> 'mobile-hide-elements',
				'title'			=> __( 'Hide Elements from Mobile View', 'ultimate-lightbox' ),
				'description'	=> __( 'Which parts of the lightbox should be hidden on smaller screens?', 'ultimate-lightbox' ),
				'options'		=> array(
					'title' 		=> 'Title',
					'description' 	=> 'Description',
					'thumbnails'	=> 'Thumbnails'
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-advanced',
			'radio',
			array(
				'id'			=> 'overlay-text-source',
				'title'			=> __( 'Overlay Text Source', 'ultimate-lightbox' ),
				'description'	=> __( 'Should the overlay text that shows on an image be pulled from the image alt text or the image caption?', 'ultimate-lightbox' ),
				'options'		=> array(
					'alt'			=> __( 'Alt Text', 'ultimate-lightbox' ),
					'caption'		=> __( 'Caption', 'ultimate-lightbox' )
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-advanced',
			'toggle',
			array(
				'id'			=> 'disable-other-lightboxes',
				'title'			=> __( 'Disable Other Lightboxes', 'ultimate-lightbox' ),
				'description'	=> __( 'Should other lightboxes be disabled? This option should only be used if there\'s no other way to disbale a hardcoded lightbox, and only works for a number of the most popular lightboxes.', 'ultimate-lightbox' )
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-controls-tab',
				'title'         => __( 'Controls', 'ultimate-lightbox' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-controls',
				'title'         => __( 'Control Options', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-controls-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-controls',
			'checkbox',
			array(
				'id'			=> 'top-right-controls',
				'title'			=> __( 'Top Right Controls', 'ultimate-lightbox' ),
				'description'	=> __( 'What control options should be in the top right toolbar area?', 'ultimate-lightbox' ),
				'columns'		=> '8',
				'options'		=> array(
					'exit' 			=> '<span class="ulb-toolbar-control ulb-exit">a</span>',
					'autoplay' 		=> '<span class="ulb-toolbar-control ulb-autoplay">a</span>',
					'zoom' 			=> '<span class="ulb-toolbar-control ulb-zoom">a</span>',
					'zoom_out' 		=> '<span class="ulb-toolbar-control ulb-zoom_out">a</span>',
					'slide_counter'	=> '<span class="ulb-toolbar-control ulb-slide_counter">a</span>',
					'download'		=> '<span class="ulb-toolbar-control ulb-download">a</span>',
					'fullscreen'	=> '<span class="ulb-toolbar-control ulb-fullscreen">a</span>',
					'fullsize'		=> '<span class="ulb-toolbar-control ulb-fullsize">a</span>',
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-controls',
			'checkbox',
			array(
				'id'			=> 'top-left-controls',
				'title'			=> __( 'Top Left Controls', 'ultimate-lightbox' ),
				'description'	=> __( 'What control options should be in the top left toolbar area?', 'ultimate-lightbox' ),
				'columns'		=> '8',
				'options'		=> array(
					'exit' 			=> '<span class="ulb-toolbar-control ulb-exit">a</span>',
					'autoplay' 		=> '<span class="ulb-toolbar-control ulb-autoplay">a</span>',
					'zoom' 			=> '<span class="ulb-toolbar-control ulb-zoom">a</span>',
					'zoom_out' 		=> '<span class="ulb-toolbar-control ulb-zoom_out">a</span>',
					'slide_counter'	=> '<span class="ulb-toolbar-control ulb-slide_counter">a</span>',
					'download'		=> '<span class="ulb-toolbar-control ulb-download">a</span>',
					'fullscreen'	=> '<span class="ulb-toolbar-control ulb-fullscreen">a</span>',
					'fullsize'		=> '<span class="ulb-toolbar-control ulb-fullsize">a</span>',
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-controls',
			'checkbox',
			array(
				'id'			=> 'bottom-right-controls',
				'title'			=> __( 'Bottom Right Controls', 'ultimate-lightbox' ),
				'description'	=> __( 'What control options should be in the bottom right toolbar area?', 'ultimate-lightbox' ),
				'columns'		=> '8',
				'options'		=> array(
					'exit' 			=> '<span class="ulb-toolbar-control ulb-exit">a</span>',
					'autoplay' 		=> '<span class="ulb-toolbar-control ulb-autoplay">a</span>',
					'zoom' 			=> '<span class="ulb-toolbar-control ulb-zoom">a</span>',
					'zoom_out' 		=> '<span class="ulb-toolbar-control ulb-zoom_out">a</span>',
					'slide_counter'	=> '<span class="ulb-toolbar-control ulb-slide_counter">a</span>',
					'download'		=> '<span class="ulb-toolbar-control ulb-download">a</span>',
					'fullscreen'	=> '<span class="ulb-toolbar-control ulb-fullscreen">a</span>',
					'fullsize'		=> '<span class="ulb-toolbar-control ulb-fullsize">a</span>',
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-controls',
			'checkbox',
			array(
				'id'			=> 'bottom-left-controls',
				'title'			=> __( 'Bottom Left Controls', 'ultimate-lightbox' ),
				'description'	=> __( 'What control options should be in the bottom left toolbar area?', 'ultimate-lightbox' ),
				'columns'		=> '8',
				'options'		=> array(
					'exit' 			=> '<span class="ulb-toolbar-control ulb-exit">a</span>',
					'autoplay' 		=> '<span class="ulb-toolbar-control ulb-autoplay">a</span>',
					'zoom' 			=> '<span class="ulb-toolbar-control ulb-zoom">a</span>',
					'zoom_out' 		=> '<span class="ulb-toolbar-control ulb-zoom_out">a</span>',
					'slide_counter'	=> '<span class="ulb-toolbar-control ulb-slide_counter">a</span>',
					'download'		=> '<span class="ulb-toolbar-control ulb-download">a</span>',
					'fullscreen'	=> '<span class="ulb-toolbar-control ulb-fullscreen">a</span>',
					'fullsize'		=> '<span class="ulb-toolbar-control ulb-fullsize">a</span>',
				)
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-icons-tab',
				'title'         => __( 'Icons', 'ultimate-lightbox' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-icons',
				'title'         => __( 'Icon Options', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-icons-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-icons',
			'radio',
			array(
				'id'			=> 'arrow',
				'title'			=> __( 'Arrows', 'ultimate-lightbox' ),
				'columns'		=> '3',
				'options'		=> array(
					'none'			=> __( 'No Arrow', 'ultimate-lightbox' ),
					'a'				=> '<span class="ulb-arrow">b</span>',
					'c'				=> '<span class="ulb-arrow">d</span>',
					'e'				=> '<span class="ulb-arrow">f</span>',
					'g'				=> '<span class="ulb-arrow">h</span>',
					'i'				=> '<span class="ulb-arrow">j</span>',
					'k'				=> '<span class="ulb-arrow">l</span>',
					'm'				=> '<span class="ulb-arrow">n</span>',
					'o'				=> '<span class="ulb-arrow">p</span>',
					'q'				=> '<span class="ulb-arrow">r</span>',
					'A'				=> '<span class="ulb-arrow">B</span>',
					'E'				=> '<span class="ulb-arrow">F</span>',
					'G'				=> '<span class="ulb-arrow">H</span>',
					'I'				=> '<span class="ulb-arrow">J</span>',
					'K'				=> '<span class="ulb-arrow">L</span>',
					'M'				=> '<span class="ulb-arrow">N</span>',
					'O'				=> '<span class="ulb-arrow">P</span>',
					'Q'				=> '<span class="ulb-arrow">R</span>',
				)
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-icons',
			'radio',
			array(
				'id'			=> 'icon-set',
				'title'			=> __( 'Icons', 'ultimate-lightbox' ),
				'columns'		=> '3',
				'options'		=> array(
					'a'				=> '<span class="ulb-exit">a</span><span class="ulb-autoplay">a</span><span class="ulb-zoom">a</span><span class="ulb-zoom_out">a</span><span class="ulb-download">a</span><span class="ulb-fullsize">a</span><span class="ulb-fullscreen">a</span><span class="ulb-regular_screen">a</span>',
					'b'				=> '<span class="ulb-exit">b</span><span class="ulb-autoplay">b</span><span class="ulb-zoom">b</span><span class="ulb-zoom_out">b</span><span class="ulb-download">b</span><span class="ulb-fullsize">b</span><span class="ulb-fullscreen">b</span><span class="ulb-regular_screen">b</span>',
					'c'				=> '<span class="ulb-exit">c</span><span class="ulb-autoplay">c</span><span class="ulb-zoom">c</span><span class="ulb-zoom_out">c</span><span class="ulb-download">c</span><span class="ulb-fullsize">c</span><span class="ulb-fullscreen">c</span><span class="ulb-regular_screen">c</span>',
					'd'				=> '<span class="ulb-exit">d</span><span class="ulb-autoplay">d</span><span class="ulb-zoom">d</span><span class="ulb-zoom_out">d</span><span class="ulb-download">d</span><span class="ulb-fullsize">d</span><span class="ulb-fullscreen">d</span><span class="ulb-regular_screen">d</span>',
					'e'				=> '<span class="ulb-exit">e</span><span class="ulb-autoplay">e</span><span class="ulb-zoom">e</span><span class="ulb-zoom_out">e</span><span class="ulb-download">e</span><span class="ulb-fullsize">e</span><span class="ulb-fullscreen">e</span><span class="ulb-regular_screen">e</span>',
					'f'				=> '<span class="ulb-exit">f</span><span class="ulb-autoplay">f</span><span class="ulb-zoom">f</span><span class="ulb-zoom_out">f</span><span class="ulb-download">f</span><span class="ulb-fullsize">f</span><span class="ulb-fullscreen">f</span><span class="ulb-regular_screen">f</span>',
					'g'				=> '<span class="ulb-exit">g</span><span class="ulb-autoplay">g</span><span class="ulb-zoom">g</span><span class="ulb-zoom_out">g</span><span class="ulb-download">g</span><span class="ulb-fullsize">g</span><span class="ulb-fullscreen">g</span><span class="ulb-regular_screen">g</span>',
				)
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-styling-tab',
				'title'         => __( 'Styling', 'ultimate-lightbox' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-styling-preset-styles',
				'title'         => __( 'Preset Styles', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-styling-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-preset-styles',
			'radio',
			array(
				'id'			=> 'preset-style',
				'title'			=> __( 'Style', 'ultimate-lightbox' ),
				'description'	=> __( 'Choose a preset style. Matte offers less obvious separation between the different areas, like the thumbnail bar, arrows, etc. Light gives the lightbox a white background instead of a black one. The contrast options add borders that more visibly separate the different areas in the lightbox. Please note that any styling options that you set below will override the preset styling.', 'ultimate-lightbox' ),
				'options'		=> array(
					'default'		=> __( 'Default', 'ultimate-lightbox' ),
					'matte'			=> __( 'Matte', 'ultimate-lightbox' ),
					'light'			=> __( 'Light', 'ultimate-lightbox' ),
					'contrast'		=> __( 'Dark Contrast', 'ultimate-lightbox' ),
					'lightcontrast'	=> __( 'Light Contrast', 'ultimate-lightbox' ),
				)
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-styling-title-and-description',
				'title'         => __( 'Title and Description', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-styling-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-title-and-description',
			'colorpicker',
			array(
				'id'			=> 'styling-title-font-color',
				'title'			=> __( 'Title Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-title-and-description',
			'text',
			array(
				'id'            => 'styling-title-font',
				'title'         => __( 'Title Font Family', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-title-and-description',
			'text',
			array(
				'id'            => 'styling-title-font-size',
				'title'         => __( 'Title Font Size', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-title-and-description',
			'colorpicker',
			array(
				'id'			=> 'styling-description-font-color',
				'title'			=> __( 'Description Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-title-and-description',
			'text',
			array(
				'id'            => 'styling-description-font',
				'title'         => __( 'Description Font Family', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-title-and-description',
			'text',
			array(
				'id'            => 'styling-description-font-size',
				'title'         => __( 'Description Font Size', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-styling-arrows-and-icons',
				'title'         => __( 'Arrows and Icons', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-styling-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-arrows-and-icons',
			'colorpicker',
			array(
				'id'			=> 'styling-arrow-color',
				'title'			=> __( 'Arrow Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-arrows-and-icons',
			'colorpicker',
			array(
				'id'			=> 'styling-arrow-background-color',
				'title'			=> __( 'Arrow Background Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-arrows-and-icons',
			'text',
			array(
				'id'            => 'styling-arrow-size',
				'title'         => __( 'Arrows Size (in "px", "em", etc.)', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-arrows-and-icons',
			'text',
			array(
				'id'            => 'styling-arrow-background-opacity',
				'title'         => __( 'Arrows Background Opacity (e.g. "0.4")', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-arrows-and-icons',
			'text',
			array(
				'id'            => 'styling-arrow-background-hover-opacity',
				'title'         => __( 'Arrows Background Hover Opacity (e.g. "0.7")', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-arrows-and-icons',
			'colorpicker',
			array(
				'id'			=> 'styling-icon-color',
				'title'			=> __( 'Icons Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-arrows-and-icons',
			'text',
			array(
				'id'            => 'styling-icon-size',
				'title'         => __( 'Icons Size (in "px", "em", etc.)', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-styling-background-toolbars-and-overlay',
				'title'         => __( 'Background, Toolbars & Overlay', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-styling-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-background-toolbars-and-overlay',
			'colorpicker',
			array(
				'id'			=> 'styling-background-overlay-color',
				'title'			=> __( 'Background Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-background-toolbars-and-overlay',
			'text',
			array(
				'id'            => 'styling-background-overlay-opacity',
				'title'         => __( 'Background Opacity (e.g. "0.4")', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-background-toolbars-and-overlay',
			'colorpicker',
			array(
				'id'			=> 'styling-toolbar-color',
				'title'			=> __( 'Toolbars Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-background-toolbars-and-overlay',
			'text',
			array(
				'id'            => 'styling-toolbar-opacity',
				'title'         => __( 'Toolbars Opacity (e.g. "0.4")', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-background-toolbars-and-overlay',
			'colorpicker',
			array(
				'id'			=> 'styling-image-overlay-color',
				'title'			=> __( 'Image Overlay Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-background-toolbars-and-overlay',
			'text',
			array(
				'id'            => 'styling-image-overlay-opacity',
				'title'         => __( 'Image Overlay Opacity (e.g. "0.4")', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap->add_section(
			'ulb-settings',
			array(
				'id'            => 'ulb-styling-thumbnails',
				'title'         => __( 'Thumbnails', 'ultimate-lightbox' ),
				'tab'	        => 'ulb-styling-tab',
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-thumbnails',
			'colorpicker',
			array(
				'id'			=> 'styling-thumbnail-bar-color',
				'title'			=> __( 'Thumbnail Bar Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-thumbnails',
			'colorpicker',
			array(
				'id'			=> 'styling-thumbnail-scroll-arrow-color',
				'title'			=> __( 'Thumbnail Scroll Arrow Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-thumbnails',
			'colorpicker',
			array(
				'id'			=> 'styling-thumbnail-active-border-color',
				'title'			=> __( 'Active Thumbnail Border Color', 'ultimate-lightbox' )
			)
		);

		$sap->add_setting(
			'ulb-settings',
			'ulb-styling-thumbnails',
			'text',
			array(
				'id'            => 'styling-thumbnail-bar-opacity',
				'title'         => __( 'Thumbnail Bar Opacity (e.g. "0.4")', 'ultimate-lightbox' ),
				'small'			=> true
			)
		);

		$sap = apply_filters( 'ulb_settings_page', $sap );

		$sap->add_admin_menus();

	}

}
} // endif;
