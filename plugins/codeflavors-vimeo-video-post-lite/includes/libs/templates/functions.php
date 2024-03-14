<?php

use Vimeotheque\Templates\Helper;

/**
 * Core Vimeotheque functions.
 */

/**
 * Get template part.
 *
 * VIMEOTHEQUE_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @param mixed  $slug Template slug.
 * @param string $name Template name (default: '').
 */
function vimeotheque_get_template_part( $slug, $name = '' ){
	if ( $name ) {
		$template = VIMEOTHEQUE_TEMPLATE_DEBUG_MODE ? '' : locate_template(
			[
				"{$slug}-{$name}.php",
				Helper::template_path() . "{$slug}-{$name}.php",
			]
		);

		if ( ! $template ) {
			$fallback = \Vimeotheque\Helper::get_path() . "/templates/{$slug}-{$name}.php";
			$template = file_exists( $fallback ) ? $fallback : '';
		}
	}

	if ( ! $template ) {
		// If template file doesn't exist, look in WP theme.
		$template = VIMEOTHEQUE_TEMPLATE_DEBUG_MODE ? '' : locate_template(
			[
				"{$slug}.php",
				Helper::template_path() . "{$slug}.php",
			]
		);
	}

	/**
	 * Template file filter.
	 *
	 * Allow 3rd party plugins to filter template file from their plugin.
	 *
	 * @param string $template  The template file path.
	 * @param string $slug      The template slug.
	 * @param string $name      The template name.
	 */
	$template = apply_filters( 'vimeotheque_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Retrieve the next video post.
 *
 * Used on single video pages, returns the next video post.
 *
 * @param bool         $in_same_term   Optional. Whether post should be in a same taxonomy term. Default false.
 * @param int[]|string $excluded_terms Optional. Array or comma-separated list of excluded term IDs. Default empty string.
 * @return WP_Post|null|string Post object if successful. Null if global $post is not set. Empty string if no
 *                             corresponding post exists.
 */
function vimeotheque_get_next_post(  $in_same_term = false, $excluded_terms = '' ){
	return Helper::get_adjacent_post( $in_same_term, $excluded_terms, false );
}

/**
 * Retrieve the previous video post.
 *
 * Used on single video pages, returns the previous video post.
 *
 * @param bool         $in_same_term   Optional. Whether post should be in a same taxonomy term. Default false.
 * @param int[]|string $excluded_terms Optional. Array or comma-separated list of excluded term IDs. Default empty string.
 * @return WP_Post|null|string Post object if successful. Null if global $post is not set. Empty string if no
 *                             corresponding post exists.
 */
function vimeotheque_get_previous_post(  $in_same_term = false, $excluded_terms = '' ){
	$post = get_adjacent_post( $in_same_term, $excluded_terms, true );
	return $post;
}



/**
 * Get the sidebar template.
 *
 * @return void
 */
function vimeotheque_get_sidebar(){
	vimeotheque_get_template_part( 'global/sidebar.php' );
}