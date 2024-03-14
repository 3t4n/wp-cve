<?php
/**
 * Plugin Name: Ultimate Colors
 * Plugin URI:  https://gretathemes.com
 * Description: Easy customize colors for your website.
 * Version:     1.0.1
 * Author:      GretaThemes
 * Author URI:  https://gretathemes.com
 * License:     GPL2+
 * Text Domain: ultimate-colors
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || die;

/**
 * Main plugin class.
 * @author GretaThemes <info@gretathemes.com>
 */
class Ultimate_Colors {
	/**
	 * @var object The reference to singleton instance of this class
	 */
	private static $instance;

	/**
	 * Plugin dir path.
	 * @var string
	 */
	public $dir;

	/**
	 * Plugin dir URL.
	 * @var string
	 */
	public $url;

	/**
	 * Returns the singleton instance of this class.
	 *
	 * @return object The singleton instance.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set plugin constants.
	 * Protected constructor to prevent creating a new instance of the singleton via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		$this->dir = plugin_dir_path( __FILE__ );
		$this->url = plugin_dir_url( __FILE__ );
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {
		$this->set_default();

		require_once $this->dir . 'inc/class-ultimate-colors-customize.php';
		new Ultimate_Colors_Customizer;

		require_once $this->dir . 'inc/class-ultimate-colors-dashboard-widget.php';
		new Ultimate_Colors_Dashboard_Widget;

		if ( ! $this->get_theme_support( 'no_settings' ) ) {
			// Register plugin settings page. Allow themes to disable settings with theme support.
			require_once $this->dir . 'inc/class-ultimate-colors-settings.php';
			new Ultimate_Colors_Settings;
		}
	}

	/**
	 * Set plugin default option.
	 */
	protected function set_default() {
		$option = get_option( 'ultimate-colors' );
		if ( ! empty( $option ) ) {
			return;
		}
		$option = array(
			'elements' => array(
				array(
					'label'    => esc_html__( 'Text color', 'ultimate-colors' ),
					'selector' => 'body',
					'property' => 'color',
				),
				array(
					'label'    => esc_html__( 'Heading 1 color', 'ultimate-colors' ),
					'selector' => 'h1',
					'property' => 'color',
				),
				array(
					'label'    => esc_html__( 'Heading 2 color', 'ultimate-colors' ),
					'selector' => 'h2',
					'property' => 'color',
				),
				array(
					'label'    => esc_html__( 'Heading 3 color', 'ultimate-colors' ),
					'selector' => 'h3',
					'property' => 'color',
				),
				array(
					'label'    => esc_html__( 'Heading 4 color', 'ultimate-colors' ),
					'selector' => 'h4',
					'property' => 'color',
				),
				array(
					'label'    => esc_html__( 'Heading 5 color', 'ultimate-colors' ),
					'selector' => 'h5',
					'property' => 'color',
				),
				array(
					'label'    => esc_html__( 'Heading 6 color', 'ultimate-colors' ),
					'selector' => 'h6',
					'property' => 'color',
				),
			),
		);

		// Allow theme to setup the default elements via theme support.
		if ( $default_elements = $this->get_theme_support( 'default_elements' ) ) {
			$option['elements'] = $default_elements;
		}
		add_option( 'ultimate-colors', $option );
	}

	/**
	 * Get theme support.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get_theme_support( $name ) {
		$theme_support = get_theme_support( 'ultimate-colors' );
		if ( ! $theme_support || empty( $theme_support[0] ) || empty( $theme_support[0][ $name ] ) ) {
			return false;
		}

		return $theme_support[0][ $name ];
	}
}

add_action( 'init', array( Ultimate_Colors::instance(), 'init' ) );
