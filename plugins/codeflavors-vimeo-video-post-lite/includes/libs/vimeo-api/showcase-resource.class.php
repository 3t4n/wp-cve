<?php
/**
 * @author  CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Stand-alone resource registration for name "showcase".
 * Implemented only for back-end usage.
 */
class Showcase_Resource extends Album_Resource {
	/**
	 * @param string    $resource_id
	 * @param false     $user_id
	 * @param array     $params
	 */
	public function __construct( $resource_id, $user_id = false, $params = [] ) {
		parent::__construct( $resource_id, $user_id, $params );
		parent::set_name( 'showcase', __( 'Showcase', 'codeflavors-vimeo-video-post-lite' ) );
	}

	/**
	 * Disable for importers.
	 *
	 * Keep implementation only for back-end.
	 *
	 * @return false
	 */
	public function enabled_for_importers() {
		return false;
	}

	/**
	 * Disable for automatic imports.
	 *
	 * Keep implementation only for back-end.
	 *
	 * @return false
	 */
	public function has_automatic_import() {
		return false;
	}
}