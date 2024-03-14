<?php
/**
 * Plugin Name
 *
 * @package           KITCHEN_SINK
 * @author            InstallActivateGo.com
 * @copyright         Copyright (C) 2014-2023, InstallActivateGo.com
 *
 * @wordpress-plugin
 * Plugin Name:       InstallActivateGo.com Display Kitchen Sink
 * Plugin URI:        https://installactivatego.com/kitchen-sink
 * Description:       All users will have the Kitchen Sink displayed in Visual mode for both the Page and Post Editors.
 * Version:           3.1.2
 * Requires at least: 3.1
 * Requires PHP:      5
 * Author:            InstallActivateGo.com
 * Author URI:        https://installactivatego.com
 * Text Domain:       jonradio-display-kitchen-sink
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Update URI:        https://downloads.wordpress.org/plugin/jonradio-display-kitchen-sink.3.1.2.zip
 */

defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {
	DEFINE( 'JR_DKS__FILE__', __FILE__ );

	require_once( plugin_dir_path( JR_DKS__FILE__ ) . 'includes/admin.php' );
}

?>