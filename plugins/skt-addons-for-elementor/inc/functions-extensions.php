<?php
/**
 * Filters function defination
 *
 * @package Skt_Addons_Elementor
 * @since 1.0
 *
 */
defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'skt_addons_elementor_is_adminbar_menu_enabled' ) ) {
	/**
	 * Check if Adminbar is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_adminbar_menu_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/adminbar_menu', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_background_overlay_enabled' ) ) {
	/**
	 * Check if Background Overlay is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_background_overlay_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/background_overlay', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_css_transform_enabled' ) ) {
	/**
	 * Check if CSS Transform is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_css_transform_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/css_transform', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_floating_effects_enabled' ) ) {
	/**
	 * Check if Floating Effects is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_floating_effects_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/floating_effects', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_grid_layer_enabled' ) ) {
	/**
	 * Check if Grid Layer is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_grid_layer_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/grid_layer', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_wrapper_link_enabled' ) ) {
	/**
	 * Check if Wrapper Link is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_wrapper_link_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/wrapper_link', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_skt_addons_elementor_clone_enabled' ) ) {
	/**
	 * Check if Skt Clone is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_skt_addons_elementor_clone_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/skt_addons_elementor_clone', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_on_demand_cache_enabled' ) ) {
	/**
	 * Check if On Demand Cache is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_on_demand_cache_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/on_demand_cache', true );
	}
}

if ( ! function_exists( 'skt_addons_elementor_is_shape_divider_enabled' ) ) {
	/**
	 * Check if Skt Shape Divider is enabled
	 *
	 * @return bool
	 */
	function skt_addons_elementor_is_shape_divider_enabled() {
		return apply_filters( 'sktaddonselementor/extensions/shape_divider', true );
	}
}

/**
 * Check if Image Masking is enabled
 *
 * @return bool
 */
function sktaddonselementorextra_is_image_masking_enabled() {
	return apply_filters( 'sktaddonselementor/extensions/image_masking', true );
}

/**
 * Check if Display Condition is enabled
 *
 * @return bool
 */
function sktaddonselementorextra_is_display_condition_enabled() {
	return apply_filters( 'sktaddonselementor/extensions/display_condition', true );
}

/**
 * Check if Skt Particle Effects is enabled
 *
 * @return bool
 */
function sktaddonselementorextra_is_skt_addons_elementor_particle_effects_enabled() {
	return apply_filters( 'sktaddonselementor/extensions/skt_addons_elementor_particle_effects', true );
}