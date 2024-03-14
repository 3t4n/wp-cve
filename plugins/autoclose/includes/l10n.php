<?php
/**
 * Language functions
 *
 * @since 2.0.0
 *
 * @package AutoClose
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Initialises text domain for l10n.
 *
 * @since   1.4
 */
function ald_acc_lang_init() {
	load_plugin_textdomain( 'autoclose', false, dirname( plugin_basename( ACC_PLUGIN_FILE ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'ald_acc_lang_init' );
