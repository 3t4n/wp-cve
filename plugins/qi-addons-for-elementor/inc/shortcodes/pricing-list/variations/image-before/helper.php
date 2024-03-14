<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_pricing_list_variation_image_before' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pricing_list_variation_image_before( $variations ) {
		$variations['image-before'] = esc_html__( 'Image Before', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_list_layouts', 'qi_addons_for_elementor_add_pricing_list_variation_image_before' );
}
