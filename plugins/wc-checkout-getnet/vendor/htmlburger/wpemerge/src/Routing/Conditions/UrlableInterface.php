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

namespace CoffeeCode\WPEmerge\Routing\Conditions;

/**
 * Interface signifying that an object can be converted to a URL.
 */
interface UrlableInterface {
	/**
	 * Convert to URL.
	 *
	 * @param  array  $arguments
	 * @return string
	 */
	public function toUrl( $arguments = [] );
}
