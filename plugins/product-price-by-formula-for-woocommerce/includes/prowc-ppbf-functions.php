<?php
/**
 * Product Price by Formula for WooCommerce - Functions
 *
 * @version 2.3.2
 * @since   2.3.2
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'prowc_ppbf_process_atts_shortcodes' ) ) {
	/**
	 * prowc_ppbf_process_atts_shortcodes.
	 *
	 * @version 2.3.2
	 * @since   2.3.2
	 */
	function prowc_ppbf_process_atts_shortcodes( $value ) {
		return do_shortcode( str_replace( array( '{', '}' ), array( '[', ']' ), $value ) );
	}
}
