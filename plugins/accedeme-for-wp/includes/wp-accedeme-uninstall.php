<?php

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/includes
 * @author     Accedeme
 */

class wp_accedeme_uninstall {

    public static function uninstall()
    {
        require_once ACCEDEME_DIR . 'includes/wp-accedeme-helpers.php';
        $helpers = new wp_accedeme_helpers();

        if ( $helpers->accedemeIsTableExist() ) {
            $helpers->accedemeRemoveTable();
        }
    }
}
