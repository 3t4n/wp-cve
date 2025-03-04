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

namespace CoffeeCode\WPEmerge\Application;

use CoffeeCode\Pimple\Container;
use CoffeeCode\WPEmerge\Exceptions\ClassNotFoundException;

/**
 * Generic class instance factory.
 */
class GenericFactory {
	/**
	 * Container.
	 *
	 * @var Container
	 */
	protected $container = null;

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 * @param Container $container
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Make a class instance.
	 *
	 * @throws ClassNotFoundException
	 * @param  string $class
	 * @return object
	 */
	public function make( $class ) {
		if ( isset( $this->container[ $class ] ) ) {
			return $this->container[ $class ];
		}

		if ( ! class_exists( $class ) ) {
			throw new ClassNotFoundException( 'Class not found: ' . $class );
		}

		return new $class();
	}
}
