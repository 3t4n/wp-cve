<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_process_variation_horizontal' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_process_variation_horizontal( $variations ) {
		$variations['horizontal'] = esc_html__( 'Horizontal', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_process_layouts', 'qi_addons_for_elementor_add_process_variation_horizontal' );
}
