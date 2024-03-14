<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for locating templates and returning the proper template.
 * WPInventory supports template overrides.
 *
 * To override a template / view, simply copy the appropriate template
 * from the plugin /view directory into your WP Theme directory inside a folder titled wpinventory/
 *
 * @author WP Inventory Manager
 *
 */
class WPIMTemplate extends WPIMDB {


	private static $instance;

	private static $args;

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		parent::__construct();
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}

	/**
	 * Loads theme files in appropriate hierarchy:
	 *    1) child theme and
	 *    2) parent template will look in the wpinventory/views directory
	 *    3) views/ directory in the plugin
	 *
	 * @param string $template template file to search for
	 *
	 * @return string|boolean - template path or FALSE if not found
	 **/
	public static function get( $template ) {
		$template = apply_filters( 'wpim_pre_template', $template );
		// append .php to file name
		if ( substr( $template, - 4 ) != '.php' ) {
			$template .= '.php';
		}

		$file = FALSE;

		// check if there are overrides at all
		if ( locate_template( [ self::VIEWFOLDER ] ) ) {
			$overrides_exist = TRUE;
		} else {
			$overrides_exist = FALSE;
		}

		if ( $overrides_exist ) {
			// check the theme for specific file requested
			$file = locate_template( [ self::VIEWFOLDER . $template ], FALSE, FALSE );
		}

		$plugin_path = apply_filters( 'wpim_template_plugin_path', self::$path, $template );

		// if the theme file wasn't found, check our plugins views dirs
		if ( ! $file ) {
			$file = $plugin_path . 'views' . DIRECTORY_SEPARATOR . $template;
		}

		if ( ! file_exists( $file ) ) {
			$file = FALSE;
		}
		
		return apply_filters( 'wpinventory_template_' . $template, $file );
	}
}

