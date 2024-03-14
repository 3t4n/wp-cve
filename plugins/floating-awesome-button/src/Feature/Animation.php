<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Animation extends Feature {

	/**
	 * Feature construect
	 *
	 * @return void
	 * @var    object   $plugin     Feature configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		$this->WP          = $plugin->getWP();
		$this->key         = 'core_animation';
		$this->name        = 'Animation';
		$this->description = 'To see animation reference you can go to <code><a href="https://daneden.github.io/animate.css/" target="_blank">Animate.css</a></code>.';
	}

	/**
	 * Sanitize input
	 */
	public function sanitize() {
		/** Grab Data */
		$this->params = $_POST;
		$this->params = $this->params['fab_animation'];

		/** Sanitize Text Field */
		$this->params = (object) $this->WP->sanitizeTextField( $this->params );
	}

	/**
	 * Transform data before save
	 */
	public function transform() {
		$this->options->enable   = ( in_array( $this->params->enable, array( 'true', '1', 1 ) ) ) ? 1 : 0;
        $this->params->elements = isset($this->params->elements) ? $this->params->elements : [];
		$this->options->elements = (object) array_merge(
            (array) $this->options->elements,
            (array) $this->params->elements);
		return $this->options;
	}

}
