<?php
/**
 * Plausible Analytics | Filters.
 * @since      1.2.5
 * @package    WordPress
 * @subpackage Plausible Analytics
 */

namespace Plausible\Analytics\WP;

defined( 'ABSPATH' ) || exit;

class Compatibility {
	/**
	 * A list of filters and actions to prevent our script from being manipulated by other plugins, known to cause issues.
	 * Our script is already <1KB, so there's no need to minify, combine or optimize it in any other way.
	 * @return void
	 */
	public function __construct() {
		// Autoptimize
		if ( defined( 'AUTOPTIMIZE_PLUGIN_VERSION' ) ) {
			add_filter( 'autoptimize_filter_js_exclude', [ $this, 'exclude_plausible_js_as_string' ] );
		}

		// WP Rocket
		if ( defined( 'WP_ROCKET_VERSION' ) ) {
			add_filter( 'rocket_excluded_inline_js_content', [ $this, 'exclude_plausible_inline_js' ] );
			add_filter( 'rocket_exclude_js', [ $this, 'exclude_plausible_js' ] );
			add_filter( 'rocket_minify_excluded_external_js', [ $this, 'exclude_plausible_js' ] );
			add_filter( 'rocket_delay_js_scripts', [ $this, 'exclude_plausible_js' ] );
		}

		// SG Optimizer
		if ( defined( '\SiteGround_Optimizer\VERSION' ) ) {
			add_filter( 'sgo_javascript_combine_exclude', [ $this, 'exclude_js_by_handle' ] );
			add_filter( 'sgo_js_minify_exclude', [ $this, 'exclude_js_by_handle' ] );
			add_filter( 'sgo_js_async_exclude', [ $this, 'exclude_js_by_handle' ] );
			add_filter( 'sgo_javascript_combine_excluded_inline_content', [ $this, 'exclude_plausible_inline_js' ] );
			add_filter( 'sgo_javascript_combine_excluded_external_paths', [ $this, 'exclude_plausible_js' ] );
		}

		// WP Optimize
		if ( defined( 'WPO_VERSION' ) ) {
			add_filter( 'wp-optimize-minify-default-exclusions', [ $this, 'exclude_plausible_js' ] );
		}

		// LiteSpeed Cache
		if ( defined( 'LSCWP_V' ) ) {
			add_filter( 'litespeed_optimize_js_excludes', [ $this, 'exclude_plausible_js' ] );
			add_filter( 'litespeed_optm_js_defer_exc', [ $this, 'exclude_plausible_inline_js' ] );
			add_filter( 'litespeed_optm_gm_js_exc', [ $this, 'exclude_plausible_inline_js' ] );
		}
	}

	/**
	 * Adds window.plausible
	 *
	 * @param mixed $exclude_js
	 *
	 * @return string
	 */
	public function exclude_plausible_js_as_string( $exclude_js ) {
		$exclude_js .= ', window.plausible, ' . Helpers::get_js_url( true );

		return $exclude_js;
	}

	/**
	 * Dear WP Rocket/SG Optimizer/Etc., don't minify/combine our inline JS, please.
	 * @filter rocket_excluded_inline_js_content
	 * @since  1.2.5
	 *
	 * @param array $inline_js
	 *
	 * @return array
	 */
	public function exclude_plausible_inline_js( $inline_js ) {
		if ( ! isset( $inline_js[ 'plausible' ] ) ) {
			$inline_js[ 'plausible' ] = 'window.plausible';
		}

		return $inline_js;
	}

	/**
	 * Dear WP Rocket/SG Optimizer/Etc., don't minify/combine/delay our external JS, please.
	 * @filter rocket_exclude_js
	 * @filter rocket_minify_excluded_external_js
	 * @since  1.2.5
	 *
	 * @param array $excluded_js
	 *
	 * @return array
	 */
	public function exclude_plausible_js( $excluded_js ) {
		$excluded_js[] = Helpers::get_js_url( true );

		return $excluded_js;
	}

	/**
	 * Dear WP Rocket/SG Optimizer/Etc., don't minify/combine/delay our external JS, please.
	 * @filter rocket_exclude_js
	 * @filter rocket_minify_excluded_external_js
	 * @since  1.2.5
	 *
	 * @param array $excluded_js
	 *
	 * @return array
	 */
	public function exclude_js_by_handle( $excluded_handles ) {
		$excluded_handles[] = 'plausible-analytics';

		return $excluded_handles;
	}
}
