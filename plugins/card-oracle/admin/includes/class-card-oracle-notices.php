<?php
/**
 * Admin Notices Class
 *
 * @package     Card_Oracle
 * @subpackage  Admin/Notices
 * @copyright   Copyright (c) 2020, Christopher Graham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.16.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CardOracleNotices Class
 *
 * @since 0.16.0
 */
class CardOracleNotices {

	/**
	 * Get things started
	 *
	 * @since 0.16.0
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'display' ) );
	}

	/**
	 * Show relevant notices
	 *
	 * @since 0.16.0
	 *
	 * @param string $notice The name of the notice.
	 * @param string $message The text to display.
	 * @param string $type The type of the notice, error, warn, update, info.
	 */
	public function add( $notice, $message, $type ) {
		add_settings_error( 'card-oracle-notices', $notice, $message, $type );
	}

	/**
	 * Show relevant notices
	 *
	 * @since 0.16.0
	 */
	public function display() {
		settings_errors( 'card-oracle-notices' );
	}

	/**
	 * Show relevant notices
	 *
	 * @since 0.16.0
	 *
	 * @param string $notice The name of the notice.
	 * @param string $message The text to display.
	 * @param string $type The type of the notice, error, warn, update, info.
	 */
	public function add_display( $notice, $message, $type ) {
		add_settings_error( 'card-oracle-notices', $notice, $message, $type );

		settings_errors( 'card-oracle-notices' );
	}
}

// Initiate the logging system.
$GLOBALS['co_notices'] = new CardOracleNotices();
