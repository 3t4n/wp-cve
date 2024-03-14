<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_parallax_images_variation_default' ) ) {
	/**
	 * Function that add variation layout for this module
	 *
	 * @param array $variations
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_parallax_images_variation_default( $variations ) {
		$variations['default'] = esc_html__( 'Default', 'qi-addons-for-elementor' );

		return $variations;
	}

	add_filter( 'qi_addons_for_elementor_filter_parallax_images_layouts', 'qi_addons_for_elementor_add_parallax_images_variation_default' );
}
