<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) || die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Order extends Feature {

	/**
	 * Feature construect
	 *
	 * @return void
	 * @var    object   $plugin     Feature configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		$this->WP          = $plugin->getWP();
		$this->key         = 'core_order';
		$this->name        = 'Order';
		$this->description = 'Drag & Drop to Reorder';
	}

	/**
	 * Sanitize input
	 */
	public function sanitize() {
		/** Grab Data */
		$this->params = $_POST;
		$this->params = $this->params['fab_order'];

		/** Sanitize Text Field */
		$this->params = (object) array( 'fab_order' => sanitize_text_field( $this->params ) );
	}

	/**
	 * Transform data before save
	 */
	public function transform() {
		$this->options = json_decode( $this->params->fab_order );
		return $this->options;
	}

}
