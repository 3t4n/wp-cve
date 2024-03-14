<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Price extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-price-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'lastudio-kit' );
	}

	protected function register_controls() {
		$this->add_control( 'format', [
			'label' => esc_html__( 'Format', 'lastudio-kit' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'both' => esc_html__( 'Both', 'lastudio-kit' ),
				'original' => esc_html__( 'Original', 'lastudio-kit' ),
				'sale' => esc_html__( 'Sale', 'lastudio-kit' ),
			],
			'default' => 'both',
		] );

		$this->add_product_id_control();
	}

	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );
		if ( ! $product ) {
			return '';
		}

		$format = $this->get_settings( 'format' );
		$value = '';
		switch ( $format ) {
			case 'both':
				$value = $product->get_price_html();
				break;
			case 'original':
				$value = wc_price( $product->get_regular_price() ) . $product->get_price_suffix();
				break;
			case 'sale' && $product->is_on_sale():
				$value = wc_price( $product->get_sale_price() ) . $product->get_price_suffix();
				break;
		}

		// PHPCS - Just passing WC price as is
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
