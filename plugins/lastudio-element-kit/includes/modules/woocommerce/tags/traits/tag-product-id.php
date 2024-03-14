<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Tags\Traits;

use LaStudioKitExtensions\Elementor\Controls\Control_Query as QueryControlModule;

trait Tag_Product_Id {
	public function add_product_id_control() {
		$this->add_control(
			'product_id',
			[
				'label' => esc_html__( 'Product', 'lastudio-kit' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'options' => [],
				'label_block' => true,
				'autocomplete' => [
					'object' => QueryControlModule::QUERY_OBJECT_POST,
					'query' => [
						'post_type' => [ 'product' ],
					],
				],
				// Since we're using the `wc_get_product` method to retrieve products, when no product selected manually
				// by the dynamic tag - the default should be `false` so the method will use the product id given in the
				// http request instead.
				'default' => false,
			]
		);
	}
}
