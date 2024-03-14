<?php
/**
 * Interface Stringable loader.
 *
 * @since       1.1.0
 * @subpackage  Interfaces
 * @package     EverAccounting\Includes
 */

namespace EverAccounting\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface for any object that can be casted to string.
 */
interface Stringable {

	/**
	 * Returns object as string.
	 *
	 * @since 1.1.0
	 */
	public function __toString();
}
