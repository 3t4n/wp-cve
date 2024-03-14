<?php
/**
 * Deprecated functions
 *
 * Where functions come to die.
 *
 * @author   Automattic
 * @category Core
 * @package  YITH\Search\Functions
 * @version  2.0.0
 */

if ( ! function_exists( 'yith_wcas_deprecated_hook' ) ) {
	/**
	 * Wrapper for deprecated hook so we can apply some extra logic.
	 *
	 * @param string $hook The hook that was used.
	 * @param string $version The version of WordPress that deprecated the hook.
	 * @param string $replacement The hook that should have been used.
	 * @param string $message A message regarding the change.
	 *
	 * @since 3.3.0
	 */
	function yith_wcas_deprecated_hook( $hook, $version, $replacement = null, $message = null ) {
		// @codingStandardsIgnoreStart
		if ( wp_doing_ajax() ) {
			do_action( 'deprecated_hook_run', $hook, $replacement, $version, $message );

			$message    = empty( $message ) ? '' : ' ' . $message;
			$log_string = "{$hook} is deprecated since version {$version}";
			$log_string .= $replacement ? "! Use {$replacement} instead." : ' with no alternative available.';

		} else {
			_deprecated_hook( $hook, $version, $replacement, $message );
		}
		// @codingStandardsIgnoreEnd
	}
}

if ( ! function_exists( 'yith_get_shop_categories' ) ) {
	/**
	 * Get the categories of shop.
	 *
	 * @param   bool $show_all Flag.
	 *
	 * @return int|WP_Error|WP_Term[]
	 */
	function yith_wcas_get_shop_categories( $show_all = true ) {

		$args = apply_filters(
			'yith_wcas_form_cat_args',
			array(
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( ! $show_all ) {
			$args = array_merge(
				$args,
				array(
					'parent'       => 0,
					'hierarchical' => 0,
				)
			);
		}

		$terms = get_terms( 'product_cat', apply_filters( 'yith_wcas_form_cat_args', $args ) );

		return $terms;
	}
}

if ( ! function_exists( 'getmicrotime' ) ) {
	/**
	 * Get microtime.
	 *
	 * @return float
	 */
	function getmicrotime() {
		list($usec, $sec) = explode( ' ', microtime() );
		return ( (float) $usec + (float) $sec );
	}
}
