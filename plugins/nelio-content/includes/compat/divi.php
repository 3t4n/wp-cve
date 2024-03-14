<?php
/**
 * Compatibility with DIVI Page Builder.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/compat
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.4.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

function nc_compat_divi_enable_shortcodes_in_ajax( $actions ) {
	array_push( $actions, 'nelio_content_get_post_for_auto_sharing' );
	return $actions;
}//end nc_compat_divi_enable_shortcodes_in_ajax()
add_filter( 'et_builder_load_actions', 'nc_compat_divi_enable_shortcodes_in_ajax' );

