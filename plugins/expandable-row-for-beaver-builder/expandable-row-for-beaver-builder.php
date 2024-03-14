<?php
/**
 * Plugin Name: Expandable Row for Beaver Builder
 * Plugin URI: https://www.brainstormforce.com
 * Description: Toggle any row with this plugin in Beaver Builder
 * Version: 1.1.2
 * Author: Pratik Chaskar
 * Author URI: https://pratikchaskar.com
 * License: GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: bb-expandable-row
 */
define( 'BB_ER_ROW_DIR', plugin_dir_path( __FILE__ ) );
define( 'BB_ER_ROW_URL', plugins_url( '/', __FILE__ ) );

if ( ! class_exists( 'BSFBBERRow' ) ) {
	class BSFBBERRow {
		function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_extender' ) );
			add_action( 'init', array( $this, 'load_bber_row_texdomain' ) );
		}

		public function load_bber_row_texdomain() {
			load_plugin_textdomain( 'bb-expandable-row' );
		}

		function load_extender() {
			if ( class_exists( 'FLBuilder' ) ) {
				require_once 'expandable-row/expandable-row.php';
			} else {
				add_action( 'admin_notices', array( $this, 'admin_notices_function' ) );
				add_action( 'network_admin_notices', array( $this, 'admin_notices_function' ) );
			}
		}

		function admin_notices_function() {
			if ( file_exists( plugin_dir_path( 'bb-plugin-agency/fl-builder.php' ) )
			     || file_exists( plugin_dir_path( 'beaver-builder-lite-version/fl-builder.php' ) )
			) {
				$url = network_admin_url() . 'plugins.php?s=Beaver+Builder+Plugin';
			} else {
				$url = network_admin_url() . 'plugin-install.php?s=billyyoung&tab=search&type=author';
			}
			echo '<div class="notice notice-error">';
			echo "<p>" . sprintf( __( 'The <strong>BB Expandable Row</strong> plugin requires. <strong><a href="%s">Beaver Builder</strong></a> plugin installed & activated', 'bb-expandable-row' ), $url ) . "</p>";
			echo '</div>';
		}
	}

	new BSFBBERRow();
}