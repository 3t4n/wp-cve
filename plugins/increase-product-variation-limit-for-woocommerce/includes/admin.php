<?php

class IncreaseProductVariationLimitAdmin {

	public function __construct () {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_filter( 'woocommerce_general_settings', [ $this, 'add_setting' ] );
		add_filter( 'woocommerce_ajax_variation_threshold', [ $this, 'ajax_variation_threshold' ] );
	}

	/**
	 * Add WooCommerce general settings section
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	public function add_setting( $settings ) {
		$settings[] = [
			'title' => __( 'Variation AJAX limit', 'increase-product-variation-limit-for-woocommerce' ),
			'type'  => 'title',
			'desc'  => __( 'When your variable product has more than 30 variations, WooCommerce starts to use ajax to load your selected variation. Here you can modify this limit.', 'increase-product-variation-limit-for-woocommerce' ),
			'id'    => IPVL_PREFIX . 'custom_variation_limit_title',
		];

		$settings[] = [
			'title'             => __( 'Variation limit', 'increase-product-variation-limit-for-woocommerce' ),
			'desc'              => __( 'Set variation limit.', 'increase-product-variation-limit-for-woocommerce' ),
			'id'                => IPVL_PREFIX . 'custom_variation_limit',
			'css'               => 'width:50px;',
			'default'           => '30',
			'desc_tip'          => true,
			'type'              => 'number',
			'custom_attributes' => [
				'min'  => 0,
				'step' => 1,
			]
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => IPVL_PREFIX . 'custom_variation_limit_title',
		];

		return $settings;
	}

	/**
	 * Set new variation threshold
	 *
	 * @param $limit
	 *
	 * @return int
	 */
	public function ajax_variation_threshold( $limit ) {
	
		$new_limit = get_option( IPVL_PREFIX . 'custom_variation_limit' );
		
		if ( false !== $new_limit ) {
			return absint( $new_limit );
		}
		
		return $limit;
	}
}

new IncreaseProductVariationLimitAdmin();
