<?php
/*
 * Plugin Name:       Floating Awesome Button
 * Plugin URI:        https://artistudio.xyz
 * Description:       Floating Awesome Button (FAB) is customizable action button that can help you display custom content (modal, shortcodes, widgets, links, etc).
 * Version:           1.6.1
 * Author:            Agung Sundoro
 * Author URI:        https://wiki.artistudio.xyz/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * SOFTWARE LICENSE INFORMATION
 *
 * Copyright 2021 Artistudio, all rights reserved.
 *
 * For detailed information regarding to the licensing of
 * this software, please review the license.txt
*/

! defined( 'WPINC ' ) || die;

/** Load Composer Vendor */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/** Load Freemius */
require plugin_dir_path( __FILE__ ) . 'freemius.php';

/** Initiate Plugin */
$fab = new Fab\Plugin();
$fab->run();

/** Activation Hook */
register_activation_hook( __FILE__, array( $fab, 'activate' ) );

/** Uninstall Hook */
register_uninstall_hook( __FILE__, 'uninstall_fab_plugin' );
function uninstall_fab_plugin() {
	delete_option( 'fab_config' ); }
