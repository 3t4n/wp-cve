<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_tabs_horizontal_variation_simple' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_tabs_horizontal_variation_simple( $variations ) {
		$variations['simple'] = esc_html__( 'Simple', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_tabs_horizontal_layouts', 'qi_addons_for_elementor_add_tabs_horizontal_variation_simple' );
}
