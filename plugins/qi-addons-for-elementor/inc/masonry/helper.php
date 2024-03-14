<?php

if ( ! function_exists( 'qi_addons_for_elementor_register_masonry_scripts' ) ) {
	/**
	 * Function that include modules 3rd party scripts
	 */
	function qi_addons_for_elementor_register_masonry_scripts() {
		wp_register_script( 'isotope', QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/masonry/assets/js/plugins/isotope.pkgd.min.js', array( 'jquery' ), false, true );
		wp_register_script( 'packery', QI_ADDONS_FOR_ELEMENTOR_INC_URL_PATH . '/masonry/assets/js/plugins/packery-mode.pkgd.min.js', array( 'jquery' ), false, true );
	}

	add_action( 'qi_addons_for_elementor_action_before_main_js', 'qi_addons_for_elementor_register_masonry_scripts' );
}

if ( ! function_exists( 'qi_addons_for_elementor_include_masonry_scripts' ) ) {
	/**
	 * Function that include modules 3rd party scripts
	 */
	function qi_addons_for_elementor_include_masonry_scripts() {
		wp_enqueue_script( 'isotope' );
		wp_enqueue_script( 'packery' );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_enqueue_masonry_scripts_for_shortcodes' ) ) {
	/**
	 * Function that enqueue modules 3rd party scripts for shortcodes
	 *
	 * @param array $atts
	 */
	function qi_addons_for_elementor_enqueue_masonry_scripts_for_shortcodes( $atts ) {

		if ( isset( $atts['behavior'] ) && 'masonry' === $atts['behavior'] ) {
			qi_addons_for_elementor_include_masonry_scripts();
		}
	}

	add_action( 'qi_addons_for_elementor_action_list_shortcodes_load_assets', 'qi_addons_for_elementor_enqueue_masonry_scripts_for_shortcodes' );
}

if ( ! function_exists( 'qi_addons_for_elementor_register_masonry_scripts_for_list_shortcodes' ) ) {
	/**
	 * Function that set module 3rd party scripts for list shortcodes
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_register_masonry_scripts_for_list_shortcodes( $scripts ) {

		$scripts['isotope'] = array(
			'registered' => true,
		);
		$scripts['packery'] = array(
			'registered' => true,
		);

		return $scripts;
	}

	add_filter( 'qi_addons_for_elementor_filter_register_list_shortcode_scripts', 'qi_addons_for_elementor_register_masonry_scripts_for_list_shortcodes' );
}
