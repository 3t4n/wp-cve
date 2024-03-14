<?php
/*
Plugin Name: Product Category Slider for Elementor
Plugin URI: https://theimran.com/themes/wordpress-plugin/woocommerce-product-category-slider-for-elementor-page-builder/
Description: WooCommerce Category Slider elementor helps you display WooCommerce Categories aesthetically in a nice sliding manner. You can manage and show your product categories with thumbnail, child category (beside), description, shop now button with an easy to use shortcode generator interface with many handy options.
Version: 1.0.5
Author: Theimran WordPress Shop
Author URI: http://www.theimran.com/
Copyright: Theimran WordPress Shop
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: product-category-slider-for-elementor
*/
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}

define('PCSFE_PLUGIN_PATH', plugin_dir_path( __file__ ));
define('PCSFE_TEXT_DOMAIN', 'product-category-slider-for-elementor');
require_once PCSFE_PLUGIN_PATH . 'pcsfe_elementor.php';


/**
 * Product Category Slider Main Class
 */
class Product_Category_Slider_Main
{

	function __construct()
	{
		add_action( 'wp_enqueue_scripts', array($this, 'pcsfe_slider_scripts' ), 99 );
		add_action( 'admin_enqueue_scripts', array($this, 'pcsfe_admin_scripts') );
		$plugin = plugin_basename( __FILE__ );
		add_filter( "plugin_action_links_$plugin", array($this, 'plugin_add_settings_link') );
		add_action('plugins_loaded', array( $this, 'pcsfe_init' ) );
	}

	public function pcsfe_slider_scripts() {
		wp_enqueue_style( 'pcsfe-grid', plugin_dir_url(__file__) . 'asset/css/grid.css' );
		wp_enqueue_style( 'owl-carousel', plugin_dir_url(__file__) . 'asset/css/owl.carousel.css' );
		wp_enqueue_style( 'pcsfe-style', plugin_dir_url(__file__) . 'asset/css/style.css' );
		wp_enqueue_script( 'owl-carousel', plugin_dir_url(__file__) . 'asset/js/owl.carousel.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'pcsfe-main', plugin_dir_url(__file__) . 'asset/js/main.js', array( 'jquery' ), null, true );
	}

	public function pcsfe_admin_scripts(){
		wp_enqueue_style( 'pcsfe-admin-style', plugin_dir_url(__file__) . 'asset/admin/style.css' );
	}

	public function plugin_add_settings_link( $links ) {
	    $settings_link = '<a style="color: red; font-weight: 700;" target="_blank" href="'.esc_url('https://theimran.com/themes/wordpress-plugin/woocommerce-product-category-slider-for-elementor-page-builder/').'">Go To Pro</a>';
	    array_push( $links, $settings_link );
	    return $links;
	}

	public function pcsfe_init(){
		add_image_size( 'pcsfe-thumbnail-medium', 770, 433.13, true );
		add_image_size( 'pcsfe-thumbnail-large', 1200, 675, true );
		add_image_size( 'pcsfe-thumbnail-featured', 930, 650, true );
		add_image_size( 'pcsfe-thumbnail-full', 1920, 1080, true );
		add_image_size( 'pcsfe-thumbnail-small', 380, 360, true );
		add_image_size( 'pcsfe-thumbnail-tall', 380, 500, true );
	}
}

$init_product_category_slider_main = new Product_Category_Slider_Main;





/**
 * Adding Getting Started Page in admin menu
 */
function pcsfe_admin_notice() {
    global $pagenow;
    	?>
		<div class="welcome-message product-category-slider-notice notice notice-info">
	        <div class="notice-wrapper">
	            <div class="notice-text">
	                <h3><?php esc_html_e('WooCommerce Product Category Slider For Elementor Pro version is', 'pcsfe') ?> <strong><?php esc_html_e('20% off Now.', 'pcsfe') ?></strong> <a href="<?php echo esc_url('https://theimran.com/themes/wordpress-plugin/woocommerce-product-category-slider-for-elementor-page-builder/');?>"><?php esc_html_e('Click Here', 'pcsfe') ?></a> <?php esc_html_e('to See the Pro Version Details.', 'pcsfe'); ?></h3>
	                <p class="dismiss-link"><strong><a href="?pcsfe-update-notice=1"><?php esc_html_e( 'Dismiss','pcsfe' ); ?></a></strong></p>
	            </div>
	        </div>
		</div>
    	<?php
}

add_action( 'admin_notices', 'pcsfe_admin_notice' );

if( ! function_exists( 'pcsfe_ignore_admin_notice' ) ) :
/**
 * Adding Getting Started Page in admin menu
 */
function pcsfe_ignore_admin_notice() {

    /* If user clicks to ignore the notice, add that to their user meta */
    if ( isset( $_GET['pcsfe-update-notice'] ) && $_GET['pcsfe-update-notice'] = '1' ) {

        update_option( 'pcsfe-update-notice', true );
    }
}
endif;
add_action( 'admin_init', 'pcsfe_ignore_admin_notice' );
