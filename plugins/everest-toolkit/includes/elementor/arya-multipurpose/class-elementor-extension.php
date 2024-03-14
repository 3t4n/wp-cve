<?php
/**
 * Main Elementor Test Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Everest_Toolkit_Elementor_Extension {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Test_Extension The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Add Plugin actions
		add_action( 'elementor/elements/categories_registered', [ $this, 'init_categories' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	/**
	 * Load Widget Categories
	 *
	 * Load function files for initializing widget categories
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_categories() {

		// Registering Widget Categories.
		\Elementor\Plugin::instance()->elements_manager->add_category(
	        'arya-multipurpose-widgets',
	        [
	            'title'  => esc_html__( 'Arya Multipurpose Default Widgets', 'everest-toolkit' ),
	            'icon' => 'font'
	        ]
	    );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/banner-one.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/about-one.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/features.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/services-one.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/counter-one.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/blog.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/team.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/testimonials.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/skills.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/contact-one.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/cta.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'arya-multipurpose/widgets/partners.php';

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Banner_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_About_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Features() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Services_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Counter_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Blog() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Team() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Testimonial() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Skill() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Contact_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_CTA() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Everest_Toolkit_Partners() );
	}
}

Everest_Toolkit_Elementor_Extension::instance();