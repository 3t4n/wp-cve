<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) || die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Elementor extends Feature {

	/**
	 * Feature construect
	 *
	 * @return void
	 * @var    object   $plugin     Feature configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		$this->key         = 'elementor';
		$this->name        = 'Elementor';
		$this->description = 'Elementor core hooks';
	}

}
