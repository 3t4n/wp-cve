<?php

namespace WP_VGWORT;

/**
 * Base class for all Admin Plugin Pages
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Page {
	/**
	 * @var object holds plugin reference
	 */
	protected object $plugin;

	/**
	 * constructor
	 */
	public function __construct( object &$plugin ) {
		$this->plugin = $plugin;
	}
}