<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\Woocommerce\Tags\Traits\Tag_Product_Id;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Rating extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-rating-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Rating', 'lastudio-kit' );
	}

	protected function register_controls() {
		$this->add_control( 'field', [
			'label' => esc_html__( 'Format', 'lastudio-kit' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'average_rating' => esc_html__( 'Average Rating', 'lastudio-kit' ),
				'rating_count' => esc_html__( 'Rating Count', 'lastudio-kit' ),
				'review_count' => esc_html__( 'Review Count', 'lastudio-kit' ),
			],
			'default' => 'average_rating',
		] );

		$this->add_product_id_control();
	}

	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );
		if ( ! $product ) {
			return '';
		}

		$field = $this->get_settings( 'field' );
		$value = '';
		switch ( $field ) {
			case 'average_rating':
				$value = $product->get_average_rating();
				break;
			case 'rating_count':
				$value = $product->get_rating_count();
				break;
			case 'review_count':
				$value = $product->get_review_count();
				break;
		}

		// PHPCS - Safe WC data
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
