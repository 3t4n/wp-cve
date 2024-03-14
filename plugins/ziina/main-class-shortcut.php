<?php
/**
 * Main class shortcut
 *
 * @package ZiinaPayment
 */

use ZiinaPayment\Main;

/**
 * Shortcut for getting Main class instance
 *
 * @return Main
 */
function ziina_payment(): Main {
	return Main::get_instance();
}
