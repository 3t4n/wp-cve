<?php
/**
 *  Object that stores integrations objects.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer\Integrations;

/**
 * Content exporter object.
 */
class Integrations {

	/**
	 * Class that handle elementor integrartion.
	 *
	 * @var Elementor
	 */
	protected $elementor = null;

	/**
	 * Object construct.
	 */
	public function __construct() {

		$this->elementor = new Elementor();
	}
}
