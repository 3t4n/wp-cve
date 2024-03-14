<?php
namespace Powerfolio;
use Powerfolio\Widgets\ELPT_Portfolio_Widget;
use Powerfolio\Widgets\ELPT_Image_Gallery_Widget;
use Powerfolio\Widgets\ELPT_Portfolio_Carousel;
use Powerfolio\Widgets\PWGD_Post_Grid_Widget;
use Powerfolio\Widgets\PWGD_Product_Grid_Widget;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class Register_Powerfolio_Elementor_Widgets {

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

		add_action( 'elementor/frontend/before_register_scripts', function() {
			
			// Isotope and Packery
			wp_enqueue_script( 'jquery-isotope', plugin_dir_url( __FILE__ ) . '../vendor/isotope/js/isotope.pkgd.min.js', array('jquery', 'imagesloaded'), '3.0.6', true );
			wp_enqueue_script( 'jquery-packery', plugin_dir_url( __FILE__ ) . '../vendor/isotope/js/packery-mode.pkgd.min.js', array('jquery','jquery-isotope', 'imagesloaded'), '2.0.1', true );		

			//Image Lightbox
			if ( apply_filters( 'elpt-enable-simple-lightbox', TRUE ) === TRUE ) {
				wp_enqueue_script( 'simple-lightbox-js', plugin_dir_url( __FILE__ ) .  '../vendor/simplelightbox/dist/simple-lightbox.min.js', array('jquery'), '20151218', true );
				wp_enqueue_style( 'simple-lightbox-css', plugin_dir_url( __FILE__ ) .  '../vendor/simplelightbox/dist/simplelightbox.min.css' );
				wp_enqueue_script( 'elpt-portfoliojs-lightbox',  plugin_dir_url( __FILE__ ) . '../assets/js/custom-portfolio-lightbox.js', array('jquery'), '20151215', true );	
			}		

			//Custom CSS
			wp_enqueue_style( 'elpt-portfolio-css', plugin_dir_url( __FILE__ ) .  '../assets/css/powerfolio_css.css' );
							
			//JS				
			wp_enqueue_script( 'elpt-portfolio-js', plugin_dir_url( __FILE__ ) . '../assets/js/custom-portfolio.js', array('jquery','jquery-isotope','jquery-packery'), '20151215', true );		
			
			// Carousel
			if ( \Powerfolio_Carousel::is_carousel_enabled() ) {
				wp_enqueue_script( 'imagesLoaded' );
				wp_enqueue_style( 'owl-carousel-css', plugin_dir_url( __FILE__ ) . '../vendor/owl.carousel/assets/owl.carousel.css' );
				wp_enqueue_style( 'owl-carousel-theme-css', plugin_dir_url( __FILE__ ) . '../vendor/owl.carousel/assets/owl.theme.default.min.css' );
				wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __FILE__ ) . '../vendor/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
			}

			// Post and Product grids
			wp_enqueue_style( 'pwrgrids-css', plugin_dir_url( __FILE__ ) .  '../assets/css/pwrgrids_css.css' );
			wp_enqueue_style( 'font-awesome-free', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
			wp_enqueue_script( 'pwgd-custom-js', plugin_dir_url( __FILE__ ) . '../assets/js/pwrgrids-custom-js.js', array('jquery','jquery-isotope','jquery-packery'), '20151215', true );				

		} );

		add_action( 'elementor/frontend/element_ready/widget', function() {	
			//wp_enqueue_script( 'elpt-portfolio-js-elementor', plugin_dir_url( __FILE__ ) . '../js/custom-portfolio-elementor.js', array('jquery', 'isotope'), '99999999', true );				
		} );

		add_action( 'elementor/editor/before_enqueue_scripts', function() {			
			wp_enqueue_script( 'jquery-isotope', plugin_dir_url( __FILE__ ) . '../vendor/isotope/js/isotope.pkgd.js', array('jquery', 'imagesloaded'), '3.0.6', true );
			wp_enqueue_script( 'jquery-packery', plugin_dir_url( __FILE__ ) . '../vendor/isotope/js/packery-mode.pkgd.min.js', array('jquery', 'jquery-isotope'), '2.0.1', true );
			wp_enqueue_script( 'elpt-portfolio-js-elementor', plugin_dir_url( __FILE__ ) . '../assets/js/custom-portfolio-elementor.js', array('jquery', 'jquery-isotope'), '99999999', true );	


			// Carousel
			if ( \Powerfolio_Carousel::is_carousel_enabled() ) {
				wp_enqueue_script( 'imagesLoaded' );
				wp_enqueue_style( 'owl-carousel-css', plugin_dir_url( __FILE__ ) . '../vendor/owl.carousel/assets/owl.carousel.css' );
				wp_enqueue_style( 'owl-carousel-theme-css', plugin_dir_url( __FILE__ ) . '../vendor/owl.carousel/assets/owl.theme.default.min.css' );
				wp_enqueue_script( 'owl-carousel-js', plugin_dir_url( __FILE__ ) . '../vendor/owl.carousel/owl.carousel.min.js', array('jquery'), '20151215', true );
				wp_enqueue_script( 'elpug-carousel-elementor-js', plugin_dir_url( __FILE__ ) . '../assets/js/custom-carousel-portfolio-elementor.js', array('jquery'), '20151215', true );
			}

			// Post Grid
			wp_enqueue_script( 'pwgd-custom-js-elementor', plugin_dir_url( __FILE__ ) . '../assets/js/pwrgrids-custom-js-elementor.js', array('jquery', 'jquery-isotope'), '99999999', true );	
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
		require __DIR__ . '/elementor-widgets/portfolio_widget.php';
		require __DIR__ . '/elementor-widgets/image_gallery_widget.php';

		// Carousel
		if ( \Powerfolio_Carousel::is_carousel_enabled() ) {
			require __DIR__ . '/elementor-widgets/portfolio_carousel_widget.php';
		}

		// Post & Product Grids
		require __DIR__ . '/elementor-widgets/post_grid_widget.php';
		//Woocommerce
		if ( class_exists( 'WooCommerce' ) ) {
			require __DIR__ . '/elementor-widgets/product_grid_widget.php';
		}
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register( new ELPT_Portfolio_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new ELPT_Image_Gallery_Widget() );

		// Post and Product grid
		\Elementor\Plugin::instance()->widgets_manager->register( new PWGD_Post_Grid_Widget() );
		if ( class_exists( 'WooCommerce' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register( new PWGD_Product_Grid_Widget() );
		}

		// PRO version widgets
		if ( \Powerfolio_Carousel::is_carousel_enabled() ) {
			\Elementor\Plugin::instance()->widgets_manager->register( new ELPT_Portfolio_Carousel() );
		}
	}
}

new Register_Powerfolio_Elementor_Widgets();
