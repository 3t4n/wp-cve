<?php
namespace CbParallax\Includes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin deactivation.
 *
 * @link
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_deactivator {
	
	/**
	 * The variable that holds the name of the capability which is necessary
	 * to interact with this plugin.
	 *
	 * @since    0.1.0
	 * @access   static
	 * @var      string $capability
	 */
	public static $capability = 'cb_parallax_edit';
	
	/**
	 * Removes the capability necessary to interact with this plugin to the user, if the user has the role of an administrator.
	 *
	 * @return void
	 */
	public static function deactivate() {
		
		// Gets the administrator role.
		$role = get_role( 'administrator' );
		
		// If the acting user has admin rights, the capability gets removed.
		if ( ! empty( $role ) ) {
			$role->remove_cap( self::$capability );
		}
	}
}
