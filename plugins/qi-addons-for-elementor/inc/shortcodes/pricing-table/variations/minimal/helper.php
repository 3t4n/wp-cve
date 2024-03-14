<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_pricing_table_variation_minimal' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_pricing_table_variation_minimal( $variations ) {

		$variations['minimal'] = esc_html__( 'Minimal', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_pricing_table_layouts', 'qi_addons_for_elementor_add_pricing_table_variation_minimal' );
}
