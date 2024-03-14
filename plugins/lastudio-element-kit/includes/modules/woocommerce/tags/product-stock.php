<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Stock extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-stock-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Stock', 'lastudio-kit' );
	}

	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );
		if ( ! $product ) {
			return;
		}

		if ( 'yes' === $this->get_settings( 'show_text' ) ) {
			$value = wc_get_stock_html( $product );
		} else {
			$value = (int) $product->get_stock_quantity();
		}

		// PHPCS - `wc_get_stock_html` is safe, and `get_stock_quantity` protected with (int).
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	protected function register_controls() {
		$this->add_control(
			'show_text',
			[
				'label' => esc_html__( 'Show Text', 'lastudio-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'lastudio-kit' ),
				'label_off' => esc_html__( 'Hide', 'lastudio-kit' ),
			]
		);

		$this->add_product_id_control();
	}
}
