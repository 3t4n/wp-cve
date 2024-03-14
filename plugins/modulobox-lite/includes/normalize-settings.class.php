<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ModuloBox Normalize settings
 *
 * @class ModuloBox_Normalize_Settings
 * @version	1.0.0
 * @since 1.0.0
 */
class ModuloBox_Normalize_Settings {

	/**
	 * Options
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private $options = array();

	/**
	 * Accessibility
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private $accessibility = array(
		'closeLabel',
		'nextLabel',
		'prevLabel',
	);

	/**
	 * 3rd party gallery/grid to attach
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private $modules = array(
		'wp-image'   => 'a > img[class*="wp-image-"]:not([data-id])', // :not([data-id]) prevent issue with Gutenberg gallery block.
		'wp-gallery' => '.gallery-icon > a, .blocks-gallery-item a',
	);

	/**
	 * Allowed JavaScript options
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private $allowed = array(
		'mediaSelector',
		'threshold',
		'attraction',
		'friction',
		'rightToLeft',
		'loop',
		'preload',
		'unload',
		'timeToIdle',
		'fadeIfSettle',
		'controls',
		'prevNext',
		'prevNextTouch',
		'counterMessage',
		'caption',
		'autoCaption',
		'captionSmallDevice',
		'spacing',
		'smartResize',
		'overflow',
		'loadError',
		'noContent',
		'prevNextKey',
		'escapeToClose',
		'dragToClose',
		'tapToClose',
	);

	/**
	 * Cloning disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __clone() {
	}

	/**
	 * De-serialization disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __wakeup() {
	}

	/**
	 * Construct disabled
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( $options = '' ) {

		// Merge options with default values
		$default = include( MOBX_INCLUDES_PATH . 'default.php' );
		$this->options = wp_parse_args( (array) $options, $default );

		self::set_attraction();
		self::set_friction();
		self::set_gallery();
		self::set_accessibility();
		self::set_modules();

	}

	/**
	 * Return settings array
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_settings() {

		$data = array(
			'options'        => $this->options,
			'accessibility'  => $this->options['accessibility'],
			'mobileDevice'   => $this->options['mobileDevice'],
			'gallery'        => $this->options['gallery'],
			'debugMode'      => $this->options['debugMode'],
			'modules'        => $this->options['modules'],
		);

		// Unset unecessary options for ModuloBox instance (JS)
		foreach ( $data['options'] as $name => $val ) {

			if ( ! in_array( $name, $this->allowed ) ) {
				unset( $data['options'][ $name ] );
			}
		}

		foreach ( $data as $name => $options ) {
			unset( $data['options'][ $name ] );
		}

		return $data;

	}

	/**
	 * Set attraction values
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_attraction() {

		$this->options['attraction'] = array(
			'slider' => floatval( $this->options['sliderAttraction'] ),
			'slide'  => floatval( $this->options['slideAttraction'] ),
		);

	}

	/**
	 * Set friction values
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_friction() {

		$this->options['friction'] = array(
			'slider' => floatval( $this->options['sliderFriction'] ),
			'slide'  => floatval( $this->options['slideFriction'] ),
		);

	}

	/**
	 * Set gallery shortcode styles
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_gallery() {

		if ( ! $this->options['galleryShortcode'] ) {

			$this->options['galleryCaptionFont'] = '';

		} else {

			$this->options['gallery'] = array(
				'caption'   => $this->options['galleryCaption'],
				'rowHeight' => $this->options['galleryRowHeight'],
				'spacing'   => $this->options['gallerySpacing'],
			);

			$this->options['mediaSelector'] .= ( empty( $this->options['mediaSelector'] ) ? '' : ', ' ) . '.mobx-gallery figure > a';

		}

	}

	/**
	 * Set accessibility labels
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_accessibility() {

		$this->options['accessibility'] = array();

		foreach ( $this->accessibility as $label ) {

			$this->options['accessibility'][ $label ] = esc_html( $this->options[ $label ] );

		}

		$this->options['accessibility']['title'] = $this->options['buttonsTitle'];

	}

	/**
	 * Handle galleries
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_modules() {

		$this->options['modules'] = array( 'accessibility' );

		foreach ( $this->modules as $module => $selector ) {

			if ( $this->options[ $module ] ) {

				$this->options['mediaSelector'] .= empty( $this->options['mediaSelector'] ) ? $selector : ', ' . $selector;
				array_push( $this->options['modules'], $module );

			}
		}

	}
}
