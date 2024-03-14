<?php
/**
 * Support Elementor.
 *
 * @package Sight
 */

namespace Sight_Elementor;

/**
 * Class Sight_Elementor_Integraion
 */
class Sight_Elementor_Integraion {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Sight_Elementor_Integraion The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Sight_Elementor_Integraion An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Register Сontrols
	 *
	 * Register new Elementor controls.
	 */
	public function register_controls() {
		// Its is now safe to include Сontrols files.
		require_once SIGHT_PATH . 'elementor/control-custom-post.php';

		// Register Сontrols.
		\Elementor\Plugin::instance()->controls_manager->register_control( 'custom_post', new Controls\Sight_Control_Custom_Post() );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files.
		require_once SIGHT_PATH . 'elementor/widget-portfolio.php';

		// Register Widgets.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Sight_Widget_Portfolio() );
	}

	/**
	 * Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 */
	public function __construct() {
		require_once SIGHT_PATH . 'elementor/helper.php';

		// Actions registered.
		add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
	}
}

// Instantiate Sight_Elementor_Integraion Class.
Sight_Elementor_Integraion::instance();
