<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/includes
 * @author     Accedeme
 */
class wp_accedeme_activator {

	public static function activate()
	{
		require_once ACCEDEME_DIR . 'includes/wp-accedeme-helpers.php';
        $helpers = new wp_accedeme_helpers();

        if ( !$helpers->accedemeIsTableExist() ) {
            $helpers->accedemeInitTable();
        }

        if ( $website_key = $helpers->accedemeGetWebsiteKey() ) {
			$helpers->accedemeInsert( $website_key );
        }

		if ( is_wp_error( $website_key ) ) {
			return false;
		}
		return true;
	}
}