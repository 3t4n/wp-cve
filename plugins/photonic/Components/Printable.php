<?php
namespace Photonic_Plugin\Components;

use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

/**
 * Interface Printable
 * Represents a printable component in a Photonic gallery.
 *
 * @package Photonic_Plugin\Components
 */
interface Printable {
	/**
	 * Generates and prints the markup for a Photonic component
	 *
	 * @param Base $module
	 * @param Core_Layout $layout
	 * @param false $print
	 * @return mixed
	 */
	public function html(Base $module, Core_Layout $layout, $print = false): string;
}
