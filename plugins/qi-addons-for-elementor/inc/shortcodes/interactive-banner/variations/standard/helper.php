<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_interactive_banner_variation_standard' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_interactive_banner_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_banner_layouts', 'qi_addons_for_elementor_add_interactive_banner_variation_standard' );
}

if ( ! function_exists( 'qi_addons_for_elementor_interactive_banner_standard_hide_option' ) ) {
	function qi_addons_for_elementor_interactive_banner_standard_hide_option( $layouts ) {
		$layouts['standard'] = 'standard';

		return $layouts;
	}

	add_filter( 'qi_addons_for_elementor_filter_interactive_banner_layout_hide_text', 'qi_addons_for_elementor_interactive_banner_standard_hide_option' );
}
