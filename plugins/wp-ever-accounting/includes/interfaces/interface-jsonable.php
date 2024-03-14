<?php
/**
 * Interface JSONable loader.
 *
 * @since       1.0.2
 * @subpackage  Interfaces
 * @package     EverAccounting\Includes
 */

namespace EverAccounting\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface for any object that can be cast to JSON.
 */
interface JSONable {
	/**
	 * Returns object as JSON string.
	 *
	 * @param int $options JSON options.
	 * @param int $depth   JSON depth.
	 * @since 1.0.2
	 */
	public function to_json( $options = 0, $depth = 512);
}
