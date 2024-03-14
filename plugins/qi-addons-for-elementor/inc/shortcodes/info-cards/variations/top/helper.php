<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_info_cards_variation_top' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_info_cards_variation_top( $variations ) {
		$variations['top'] = esc_html__( 'Top', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_info_cards_layouts', 'qi_addons_for_elementor_add_info_cards_variation_top' );
}
