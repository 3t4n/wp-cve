<?php

namespace CTXFeed\V5\API;

/**
 * Class RestFactory
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\API
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class RestFactory {
	public static function load( $class, $version ) {
		$class = "\CTXFeed\V5\API\\" . ucfirst( $version ) . "\\" . $class;
		if ( class_exists( $class ) ) {
			return $class::instance();
		}

		return null;
	}
}
