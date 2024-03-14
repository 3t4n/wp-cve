<?php

namespace PTU\Vendor;

\defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Support.
 */
class WPBakery {

	/**
	 * The WPBakery class constructor.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Disable builder completely on admin post types
		\add_filter( 'vc_is_valid_post_type_be', array( $this, 'disable_editor' ), 10, 2 );

		// Remove the front-end editor button from the plugin admin post types
		\add_filter( 'vc_show_button_fe', array( $this, 'remove_fe_editor_button' ), 99, 3 );

	}

	/**
	 * Disable builder completely on admin post types.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return $type
	 */
	public function disable_editor( $check, $type ) {
		if ( 'ptu' === $type || 'ptu_tax' === $type ) {
			return false;
		}
		return $check;
	}

	/**
	 * Remove the front-end editor button from the plugin admin post types.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return $result
	 */
	public function remove_fe_editor_button( $result, $post_id, $type ) {
		if ( 'ptu' === $type || 'ptu_tax' === $type ) {
			return false;
		}
		return $result;
	}

}

new WPBakery;
