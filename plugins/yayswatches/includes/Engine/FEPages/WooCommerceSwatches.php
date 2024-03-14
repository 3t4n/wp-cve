<?php

namespace Yay_Swatches\Engine\FEPages;

use Yay_Swatches\Utils\SingletonTrait;
use Yay_Swatches\Helpers\Helper;
defined( 'ABSPATH' ) || exit;

class WooCommerceSwatches {

	use SingletonTrait;

	private $default_swatch_customize_settings;
	private $default_button_customize_settings;
	private $default_sold_out_settings;
	private $sold_out_customize_settings;
	private $current_theme;


	protected function __construct() {

		$this->default_swatch_customize_settings = Helper::get_default_swatch_customize_settings();
		$this->default_button_customize_settings = Helper::get_default_button_customize_settings();
		$this->default_sold_out_settings         = Helper::get_default_sold_out_settings();
		$this->sold_out_customize_settings       = get_option( 'yay-swatches-sold-out-customize-settings', $this->default_sold_out_settings );
		$this->current_theme                     = Helper::get_current_theme_active();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'custom_yayswatches_on_single_page' ), PHP_INT_MAX, 2 );

		// Add 'yay-swatches-wrapper' class to body tag
		add_action( 'body_class', array( $this, 'yay_swatches_add_body_class' ) );

		add_filter( 'woocommerce_available_variation', array( $this, 'woocommerce_available_variation' ), 10, 3 );

	}

	public function enqueue_scripts() {

		$jquery_params = apply_filters( 'yay_swatches_jquery_params_args', array( 'jquery' ) );

		wp_enqueue_script( 'yay-swatches-callback', YAY_SWATCHES_PLUGIN_URL . 'src/callback.js', array_merge( $jquery_params, array( 'wc-add-to-cart-variation' ) ), YAY_SWATCHES_VERSION, true );
		wp_register_script( 'yay-swatches', YAY_SWATCHES_PLUGIN_URL . 'src/frontend-script.js', $jquery_params, YAY_SWATCHES_VERSION, true );
		wp_enqueue_script( 'yay-swatches-tooltip', YAY_SWATCHES_PLUGIN_URL . 'src/tooltip.js', $jquery_params, YAY_SWATCHES_VERSION, true );
		wp_enqueue_style( 'yay-swatches-style', YAY_SWATCHES_PLUGIN_URL . 'src/style.css', array(), YAY_SWATCHES_VERSION );

		$default_sold_out_settings = Helper::get_default_sold_out_settings();
		$data_localize             = array(
			'ajaxurl'         => esc_url( admin_url( 'admin-ajax.php' ) ),
			'nonce'           => wp_create_nonce( 'yay-swatches-nonce' ),
			'is_product_page' => Helper::is_product_page() ? 'yes' : 'no',
			'is_theme_active' => Helper::get_current_theme_active(),
			'sold_out'        => get_option( 'yay-swatches-sold-out-customize-settings', $default_sold_out_settings ),
		);

		if ( class_exists( 'WC_Composite_Products' ) ) {
			$data_localize['wc_composite_products_active'] = 'yes';
		}

		wp_localize_script(
			'yay-swatches',
			'yaySwatches',
			apply_filters( 'yay_swatches_data_localize', $data_localize )
		);

		wp_enqueue_script( 'yay-swatches' );
	}

	public function custom_yayswatches_on_single_page( $html, $args ) {
		$attribute      = $args['attribute'];
		$product        = $args['product'];
		$attribute_slug = sanitize_title( $attribute );
		$product_ID     = $product->get_ID();
		$terms_taxonomy = Helper::get_all_terms_by_sort( $product_ID, $attribute );
		$attribute_id   = $terms_taxonomy ? wc_attribute_taxonomy_id_by_name( $attribute ) : $attribute_slug;
		$attribute_type = get_option( 'yay-swatches-attribute-style-' . $attribute_id, 'dropdown' );
		if ( 'dropdown' === $attribute_type ) {
			return $html;
		}

		ob_start();
		require YAY_SWATCHES_PLUGIN_TEMPLATE . '/yay-swatches-term-template.php';
		$html = ob_get_clean();
		return $html;
	}

	public function woocommerce_available_variation( $variant, $wc_product_variant, $variation ) {
		if ( ! wp_doing_ajax() ) {
			return $variant;
		}
		$available_variants = $wc_product_variant->get_available_variations( 'objects' );
		$yay_variants       = array();
		foreach ( $available_variants as $product_variant ) {
			if ( $product_variant->is_in_stock() ) {
				$yay_variants[] = $product_variant->get_attributes();
			}
		}
		$variant['yay_variants'] = $yay_variants;
		return $variant;
	}

	public function yay_swatches_add_body_class( $classes ) {
		$yay_swatches_wrapper_class = '';
		if ( is_singular( 'product' ) ) {
			$yay_swatches_wrapper_class .= 'yay-swatches-product-details-wrapper yay-swatches-wrapper yay-swatches-wrapper-' . $this->current_theme;
		}
		return array_merge( $classes, array( $yay_swatches_wrapper_class ) );
	}

}
