<?php
namespace ElpugBlogroll;

use ElpugBlogroll\Widgets\ELPUG_Blogroll;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class ELPUG_Register_Blogroll {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

		add_action( 'elementor/frontend/after_register_scripts', function() {

			//Owl Carousel
			wp_enqueue_style( 'owl-carousel-css', plugin_dir_url( __FILE__ ) . '../../assets/js/owl.carousel/assets/owl.carousel.css' );
			wp_enqueue_style( 'owl-carousel-theme-css', plugin_dir_url( __FILE__ ) . '../../assets/js/owl.carousel/assets/owl.theme.default.min.css' );
			wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __FILE__ ) . '../../assets/js/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
	
			//Blogroll
			wp_enqueue_script( 'elpug-blogroll-elementor-js', plugin_dir_url( __FILE__ ) . '../js/custom-blogroll-elementor.js', array('jquery'), '20151215', true );	
			wp_enqueue_style( 'elpug-blogroll-css', plugin_dir_url( __FILE__ ) . '../css/elpug_blogroll.css' );
			
			//Custom CSS
			//wp_enqueue_style( 'elpt-portfolio-css', plugin_dir_url( __FILE__ ) .  '../css/elpt_portfolio_css.css' );
		} );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function includes() {
		require __DIR__ . '/widgets/blogroll.php';
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ELPUG_Blogroll() );
	}
}

new ELPUG_Register_Blogroll();