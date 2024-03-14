<?php
namespace Yay_Swatches\Engine\Compatibles;

use Yay_Swatches\Utils\SingletonTrait;

defined( 'ABSPATH' ) || exit;

// Link plugin: https://woocommerce.com/products/product-bundles/

class WooCommerceProductBundles {
	use SingletonTrait;

	public function __construct() {

		if ( ! class_exists( 'WC_Bundles' ) ) {
			return;
		}

		add_action( 'yay_swatches_data_localize', array( $this, 'yay_swatches_add_data_localize' ), 10, 1 );
		add_action( 'woocommerce_bundle_add_to_cart', array( $this, 'woocommerce_bundle_add_to_cart' ) );

	}

	public function yay_swatches_add_data_localize( $data ) {
		$data['wc_product_bundles_active'] = 'yes';
		return $data;
	}

	public function woocommerce_bundle_add_to_cart() {
		wc_enqueue_js( 'yay_swatch_product_bundle_compatibles(yaySwatches)' );
	}

}
