<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <hi@atanas.dev>
 * @copyright 2017-2019 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\WPEmerge\View;

/**
 * Interface that view engines must implement
 */
interface ViewEngineInterface extends ViewFinderInterface {
	/**
	 * Create a view instance from the first view name that exists.
	 *
	 * @param  string[]      $views
	 * @return ViewInterface
	 */
	public function make( $views );
}
