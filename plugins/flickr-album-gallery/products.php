<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WPFFAG_Product_Menu {
	public static function create_menu() {
		$products = add_submenu_page( 'edit.php?post_type=fa_gallery', 'Upgrade To Pro', 'Upgrade To Pro', 'manage_options', 'wpffag_products', array( 'WPFFAG_Product_Menu', 'products' ) );
		add_action( 'admin_print_styles-' . $products, array( 'WPFFAG_Product_Menu', 'products_assets' ) );
	}

	public static function products() { ?>
		<div class="wpfrank-products-container">
			<div class="wpfrank-products-title"><h1>Our Premium Products</h1></div>
			<div class="wpfrank-products">
				<div class="wpfrank-product">
					<div class="wpfrank-product-title">Flickr Album Gallery Pro</div>
					<img class="wpfrank-product-image" src="<?php echo esc_url( FAG_PLUGIN_URL . 'img/products/Flickr-Album-Gallery-Pro.jpg' ); ?>">
					<div class="wpfrank-product-tagline">Premium Flickr Plugin To Fetch And Display Albums On Website</div>
					<div class="wpfrank-product-links">
						<a target="_blank" href="http://wpfrank.com/demo/flickr-album-gallery-pro/" class="wpfrank-link-button wpfrank-try-now">Live Demo</a>
						<a target="_blank" href="http://wpfrank.com/account/signup/flickr-album-gallery-pro" class="wpfrank-link-button wpfrank-buy-now">Buy Now</a>
					</div>
				</div>
				<div class="wpfrank-product">
					<div class="wpfrank-product-title">Ultimate Responsive Image Slider Pro</div>
					<img class="wpfrank-product-image" src="<?php echo esc_url( FAG_PLUGIN_URL . 'img/products/Ultimate-Responsive-Image-Slider-Pro.jpg' ); ?>">
					<div class="wpfrank-product-tagline">Premium WordPress Slider Plugin With Five Elegant Design Layouts</div>
					<div class="wpfrank-product-links">
						<a target="_blank" href="http://wpfrank.com/demo/ultimate-responsive-image-slider-pro/" class="wpfrank-link-button wpfrank-try-now">Live Demo</a>
						<a target="_blank" href="http://wpfrank.com/account/signup/ultimate-responsive-image-slider-pro" class="wpfrank-link-button wpfrank-buy-now">Buy Now</a>
					</div>
				</div>
				<!--
				<div class="wpfrank-product">
					<div class="wpfrank-product-title">Appointment Scheduler Pro</div>
					<img class="wpfrank-product-image" src="<?php //echo esc_url( FAG_PLUGIN_URL . 'img/products/Appointment-Scheduler-Pro.jpg' ); ?>">
					<div class="wpfrank-product-tagline">Premium Appointment Plugin To Add Booking System On Website</div>
					<div class="wpfrank-product-links">
						<a target="_blank" href="http://wpfrank.com/demo/appointment-scheduler-pro/" class="wpfrank-link-button wpfrank-try-now">Live Demo</a>
						<a target="_blank" href="http://wpfrank.com/account/signup/appointment-scheduler-pro" class="wpfrank-link-button wpfrank-buy-now">Buy Now</a>
					</div>
				</div>
				-->
				<div class="wpfrank-product">
					<div class="wpfrank-product-title">Slider Factory Pro</div>
					<img class="wpfrank-product-image" height="236px" src="<?php echo esc_url( FAG_PLUGIN_URL . 'img/products/Slider-Factory-Pro.png' ); ?>">
					<div class="wpfrank-product-tagline">Latest Slider plugin to add automatic slide show with multiple layouts.</div>
					<div class="wpfrank-product-links">
						<a target="_blank" href="https://wpfrank.com/wordpress-plugins/slider-factory-pro/" class="wpfrank-link-button wpfrank-try-now">Live Demo</a>
						<a target="_blank" href="https://wpfrank.com/account/signup/slider-factory-pro" class="wpfrank-link-button wpfrank-buy-now">Buy Now</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public static function products_assets() {
		wp_enqueue_style( 'wpfrank-products', FAG_PLUGIN_URL . 'css/wpfrank-products.css', array(), '1.2', 'all' );
	}
}
add_action( 'admin_menu', array( 'WPFFAG_Product_Menu', 'create_menu' ) );
?>
