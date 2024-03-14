<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Asset extends Feature {

	/**
	 * Feature construect
	 *
	 * @return void
	 * @var    object   $plugin     Feature configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		$this->WP          = $plugin->getWP();
		$this->key         = 'core_asset';
		$this->name        = 'Asset';
		$this->description = 'Plugin core assets feature';
	}

	/**
	 * Sanitize input
	 */
	public function sanitize() {
		/** Grab Data */
		$this->params = $_POST;
		$this->params = $this->params['fab_assets'];

		/** Sanitize Text Field */
		$this->params = (object) $this->WP->sanitizeTextField( $this->params );
	}

	/**
	 * Transform data before save
	 */
	public function transform() {
		/** Validate active/inactive asset */
		foreach ( $this->options as $base => $assets ) {
			foreach ( $assets as $key => &$value ) {
				$value->status = ( $this->params->$base[ $key ]['status'] === '1' ) ? true : false;
			}
		}

		return $this->options;
	}

}
