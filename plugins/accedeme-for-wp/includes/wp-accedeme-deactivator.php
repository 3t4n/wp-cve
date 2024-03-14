<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/includes
 * @author     Accedeme
 */
class wp_accedeme_deactivator {

	public static function deactivate()
	{
		require_once ACCEDEME_DIR . 'includes/wp-accedeme-helpers.php';
		$helpers = new wp_accedeme_helpers();

        if ( $helpers->accedemeIsTableExist() ) {
		    $helpers->accedemeRemoveTable();
        }
	}
}
