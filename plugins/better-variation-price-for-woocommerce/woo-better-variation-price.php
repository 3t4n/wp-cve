<?php
/*
Plugin Name: Better Variation Price for Woocommerce
Plugin Slug: wbvp
Text Domain: better-variation-price-for-woocommerce
Description: Replace the Woocommerce variable products price range with the lowest price or the selected variation price.
Version: 1.2.2
Author: Josserand Gallot
Author URI: https://josserandgallot.com/
Text Domain: better-variation-price-for-woocommerce
Domain Path: /languages
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'WBVP' ) ) :

class WBVP {

	/**
	 * @var string
	 */
	public $version;

	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @var string
	 */
	private $plugin_name;

	/**
	 * @var array
	 */
	private $options;

	public function __construct() {
		$this->version = '1.2.2';
		$this->plugin_path = plugin_dir_url( __FILE__ );
		$this->plugin_name = plugin_basename( __FILE__ );
		$this->options['better_variation'] = get_option( 'wbvp_better_variation', 'yes' );
		$this->options['show_lowest_price'] = get_option( 'wbvp_lowest_price', 'yes' );
		$this->options['hide_reset'] = get_option( 'wbvp_hide_reset', 'yes' );
	}

	/**
	 * Initialize the plugin
	 */
	public function initialize() {

		$this->setup_translation();

		if ( $this->options['better_variation'] == 'yes' )
			$this->better_variation();

		if ( $this->options['show_lowest_price'] == 'yes' )
			$this->show_lowest_price();

		if ( $this->options['hide_reset'] == 'yes' )
			$this->hide_reset();

		if ( is_admin() )
			$this->create_option_page();

	}

	/**
	 * Activate Better Variation
	 */
	private function better_variation() {
		add_action( 'wp_enqueue_scripts', function () {
			wp_enqueue_script( 'wbvp', $this->plugin_path . 'assets/js/plugin.min.js', ['jquery'], false, true );
		} );
	}

	/**
	 * Hide reset variation link
	 */
	private function hide_reset() {
		add_filter( 'woocommerce_reset_variations_link', '__return_null' );
	}

	/**
	 * Show lowest price instead of price range
	 */
	private function show_lowest_price() {
		add_filter( 'woocommerce_bundle_force_old_style_price_html', '__return_true' );
		add_filter( 'woocommerce_variable_sale_price_html', [$this, 'lowest_price_html'], 10, 2 );
		add_filter( 'woocommerce_variable_price_html', [$this, 'lowest_price_html'], 10, 2 );
	}

	public static function lowest_price_html ( $price, $product ) {

		$variation_prices = $product->get_variation_prices();
		$lowest_regular_price = min( $variation_prices['regular_price'] );
		$lowest_sale_price = min( $variation_prices['sale_price'] );

		if ( floatval( $lowest_sale_price ) < floatval( $lowest_regular_price ) ) {
			$lowest_variation_id = array_search( $lowest_sale_price, $variation_prices['sale_price'] );
		} else {
			$lowest_variation_id = array_search( $lowest_regular_price, $variation_prices['regular_price'] );
		}

		$lowest_variation = wc_get_product( $lowest_variation_id );
		$lowest_variation_price_html = $lowest_variation->get_price_html();

		$html_price = '<span class="price-from">' . __( 'From:', 'better-variation-price-for-woocommerce' ) . '</span> ' . $lowest_variation_price_html;

		return $html_price;

	}

	/**
	 * Create Option Page
	 */
	private function create_option_page() {

		/* Add section to Woocommerce product tab */
		add_filter( 'woocommerce_get_sections_products', function ( $sections ) {
			$sections['wbvp'] = __( 'Better Variation Price', 'better-variation-price-for-woocommerce' );
			return $sections;
		} );

		/* Create the option page */
		add_filter( 'woocommerce_get_settings_products', function ( $settings, $current_section ) {
			if ( $current_section != 'wbvp' ) return $settings;

			$wbvp_settings = [
				[
					'name'		=> __( 'Better Variation Price for Woocommerce', 'better-variation-price-for-woocommerce' ),
					'id'		=> 'wbvp',
					'type'		=> 'title',
					'desc'		=> __( 'The following options are used to configure Better Variation Price for Woocommerce', 'better-variation-price-for-woocommerce' ),
				], [
					'name'		=> __( 'Better Variation Price', 'better-variation-price-for-woocommerce' ),
					'id'		=> 'wbvp_better_variation',
					'type'		=> 'checkbox',
					'default'	=> 'yes',
					'desc'		=> __( 'Change main price when selecting variation.', 'better-variation-price-for-woocommerce' )
				], [
					'name'		=> __( 'Show lowest price', 'better-variation-price-for-woocommerce' ),
					'id'		=> 'wbvp_lowest_price',
					'type'		=> 'checkbox',
					'default'	=> 'yes',
					'desc'		=> __( 'Show lowest variation price instead of price range.', 'better-variation-price-for-woocommerce' )
				], [
					'name'		=> __( 'Hide Reset Variations Link', 'better-variation-price-for-woocommerce' ),
					'id'		=> 'wbvp_hide_reset',
					'type'		=> 'checkbox',
					'default'	=> 'yes',
					'desc'		=> __( 'Hide the "clear" link that appears when you select a variation.', 'better-variation-price-for-woocommerce' )
				],
			];

			$wbvp_settings[] = [ 'type' => 'sectionend', 'id' => 'wbvp' ];
			return $wbvp_settings;

		}, 10, 2);

		/* Plugin settings shortcut */
		add_filter( 'plugin_action_links_' . $this->plugin_name, function ( $links ) {
			$action_links = array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=wbvp' ) . '" aria-label="' . __( 'View Better Variation Price for Woocommerce settings', 'better-variation-price-for-woocommerce' ) . '">' . __( 'Settings', 'better-variation-price-for-woocommerce' ) . '</a>',
			);
			return array_merge( $action_links, $links );
		}, 10, 4 );

	}

	/**
	 * Setup translation
	 */
	private function setup_translation() {

		add_action( 'init', function() {
			load_plugin_textdomain( 'better-variation-price-for-woocommerce', false, 'better-variation-price-for-woocommerce/languages' );
		});

		add_filter( 'load_textdomain_mofile', function($mofile, $domain) {
			if ( 'better-variation-price-for-woocommerce' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
				$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
				$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
			}
			return $mofile;
		}, 10, 2 );

	}

}

/**
 * Run the main class
 */
function run_wbvp() {
	$wbvp = new WBVP();
	$wbvp->initialize();
}
run_wbvp();

endif;
