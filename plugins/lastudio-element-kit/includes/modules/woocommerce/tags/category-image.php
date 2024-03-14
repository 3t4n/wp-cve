<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Tags;

use LaStudioKitThemeBuilder\Modules\Woocommerce\Module;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Category_Image extends Base_Data_Tag {

	public function get_name() {
		return 'woocommerce-category-image-tag';
	}

	public function get_title() {
		return esc_html__( 'Category Image', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		$category_id = 0;

		if ( is_product_category() ) {
			$category_id = get_queried_object_id();
		} elseif ( is_product() ) {
			$product = wc_get_product();
			if ( $product ) {
				$category_ids = $product->get_category_ids();
				if ( ! empty( $category_ids ) ) {
					$category_id = $category_ids[0];
				}
			}
		}

		if ( $category_id ) {
			$image_id = get_term_meta( $category_id, 'thumbnail_id', true );
		}

		if ( empty( $image_id ) ) {
			return $this->get_settings( 'fallback' );
		}

		$src = wp_get_attachment_image_src( $image_id, 'full' );

		return [
			'id' => $image_id,
			'url' => $src[0],
		];
	}

    protected function register_controls() {
        $this->add_control(
            'fallback',
            [
                'label' => esc_html__( 'Fallback', 'lastudio-kit' ),
                'type' => Controls_Manager::MEDIA,
            ]
        );
    }
}
