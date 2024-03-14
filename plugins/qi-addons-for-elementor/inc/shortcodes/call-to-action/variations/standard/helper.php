<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_call_to_action_variation_standard' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_call_to_action_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_call_to_action_layouts', 'qi_addons_for_elementor_add_call_to_action_variation_standard' );
}
