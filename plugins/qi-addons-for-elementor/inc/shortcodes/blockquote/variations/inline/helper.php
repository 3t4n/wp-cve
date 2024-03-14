<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_blockquote_variation_inline' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_blockquote_variation_inline( $variations ) {
		$variations['inline'] = esc_html__( 'Inline', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_blockquote_layouts', 'qi_addons_for_elementor_add_blockquote_variation_inline' );
}
