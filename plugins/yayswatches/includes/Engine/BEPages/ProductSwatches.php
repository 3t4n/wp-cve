<?php

namespace Yay_Swatches\Engine\BEPages;

use Yay_Swatches\Utils\SingletonTrait;
use Yay_Swatches\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

class ProductSwatches {
	use SingletonTrait;
	protected function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		// Yayswatches settings tab: add new tab
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'yay_swatch_add_product_tab' ), 10, 1 );
		// Yayswatches settings tab: add content
		add_filter( 'woocommerce_product_data_panels', array( $this, 'yay_swatch_product_data_panels' ) );

	}

	public function admin_enqueue_scripts( $page ) {
		global $post;
		if ( ! isset( $post->post_type ) || 'product' !== $post->post_type ) {
			return false;
		}
		wp_enqueue_style( 'yay-swatches-admin-style', YAY_SWATCHES_PLUGIN_URL . 'src/admin-style.css', array(), YAY_SWATCHES_VERSION );
	}

	public function yay_swatch_get_product_variation() {
		global $post;
		$data = false;
		if ( $post && 'product' === $post->post_type ) {
			$product = wc_get_product( $post->ID );
			return $product;
		}
		return $data;
	}

	// Add New YaySwatches Settings Tab
	public function yay_swatch_add_product_tab( $tabs ) {
		$yay_swatches_tab = array(
			'label'    => esc_html__( 'YaySwatches Settings', 'yay-swatches' ),
			'target'   => 'yayswatches-product-data-tab-options',
			'class'    => array( 'yay_swatches_settings_tab', 'show_if_variable' ),
			'priority' => 68,
		);
		$tabs[]           = $yay_swatches_tab;
		return $tabs;
	}

	public function yay_swatch_product_data_panels() {
		$product = $this->yay_swatch_get_product_variation();
		echo '<div id="yayswatches-product-data-tab-options" class="yay-swatches-settings-product-tab-options-wrapper panel wc-metaboxes-wrapper hidden">';
		$this->generate_product_attribute_html( $product );
		echo '</div>';
	}

	public function generate_product_attribute_html( $product ) {
		require_once YAY_SWATCHES_PLUGIN_TEMPLATE . '/yay-swatches-go-pro-template.php';
		require_once YAY_SWATCHES_PLUGIN_TEMPLATE . '/yay-swatches-product-data-options-template.php';
	}

}
