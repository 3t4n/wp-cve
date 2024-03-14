<?php
/**
 * Deprecated functions
 *
 * Where functions come to die.
 *
 * @version  1.1.0
 * @category Core
 * @package  EverAccounting\Functions
 * @author   EverAccounting
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get global currencies.
 *
 * @since 1.0.0
 * @return array  List of currencies.
 * @deprecated 1.1.0
 */
function eaccounting_get_global_currencies() {
	return eaccounting_get_currency_codes();
}
