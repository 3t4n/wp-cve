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

use CoffeeCode\WPEmerge\Responses\ResponsableInterface;

/**
 * Represent and render a view to a string.
 */
interface ViewInterface extends HasContextInterface, ResponsableInterface {
	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Set name.
	 *
	 * @param  string $name
	 * @return static $this
	 */
	public function setName( $name );

	/**
	 * Render the view to a string.
	 *
	 * @return string
	 */
	public function toString();
}
