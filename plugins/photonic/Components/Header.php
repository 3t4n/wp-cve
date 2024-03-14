<?php

namespace Photonic_Plugin\Components;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

/**
 * Class Header
 *
 * @package Photonic_Plugin\Components
 */
class Header implements Printable {
	public $id; // Used primarily for Flickr collections, to facilitate expansion

	/**
	 * @var $title string All headers have a title
	 */
	public $title;

	/**
	 * @var $description string Some headers have a description
	 */
	public $description;

	/**
	 * @var $thumb_url string The URL for the thumbnail to be shown in the header. Exists if the platform provides an album thumbnail
	 */
	public $thumb_url;

	/**
	 * @var string $page_url The URL for the level 2 or level 3 object represented by the header
	 */
	public $page_url;

	/**
	 * @var string $header_for Indicates what type of object is being displayed like gallery / photoset / album etc. This is added to the CSS class.
	 */
	public $header_for;

	/**
	 * @var array $hidden_elements Contains the elements that should be hidden from the header display.
	 */
	public $hidden_elements = [];

	/**
	 * @var array $counters Contains counts of the object that the header represents. In most cases this has just one value. Zenfolio objects have multiple values.
	 */
	public $counters = [];

	/**
	 * @var bool $enable_link Should clicking on the thumbnail / title take you anywhere?
	 */
	public $enable_link;

	/**
	 * @var string $display_location Is this header in a local, modal, lighbtox or template location?
	 */
	public $display_location = 'local';

	/**
	 * @var bool $iterate_level_3 If this is a level 3 header, this field indicates whether an expansion icon should be shown. This is to improve performance for Flickr collections.
	 */
	public $iterate_level_3 = true;

	/**
	 * @var $layout string What layout is this a header for? Also used by Flickr, when the "+" is clicked for a collection
	 */
	public $layout;

	/**
	 * {@inheritDoc} - a Header
	 */
	public function html(Base $module, Core_Layout $layout = null, $print = false): string {
		$ret = $layout->generate_header_markup($this, $module);
		if ($print) {
			echo wp_kses($ret, Photonic::$safe_tags);
		}
		return wp_kses($ret, Photonic::$safe_tags);
	}
}
