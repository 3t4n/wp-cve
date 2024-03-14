<?php 

//Reject if accessed directly
defined( 'ABSPATH' ) || die( 'Our survey says: ... X.' );


//Name any additional tables in the database (take care with multisite)
/*
global $wpdb;
$wpdb->PLUGIN_TEMPLATE_TABLE = $wpdb->prefix . 'PLUGIN_TEMPLATE_TABLE';
*/


/* Activation hook 
 * 
 * Uses the database to determine the version number and checks against
 * the current code version number. It then runs through all outstanding
 * update scripts in order of version number. If this is a fresh
 * install, it will run through all the update scripts (consider the
 * first update script as an install script).
 */
register_activation_hook( bop_nav_search_box_item_plugin_path( 'init.php' ), function(){
	
	define( 'BOP_PLUGIN_ACTIVATING', true );
	
	$db_version = get_site_option( 'bop_nav_search_box_item_version', '0.0.0', false );
	$pd = get_plugin_data( bop_nav_search_box_item_plugin_path( 'init.php' ), false, false );
	
	if( version_compare( $db_version, $pd['Version'], '<' ) ){
		
		if( $handle = opendir( bop_nav_search_box_item_plugin_path( 'updates' ) ) ){
			
			$updates = array();
			
			while( false !== ( $entry = readdir( $handle ) ) ){
				if( $entry != '.' && $entry != '..' ) {
					if( version_compare( $db_version, $entry, '<' ) ){
						$updates[] = $entry;
					}
					
				}
			}
			
			if( ! empty( $updates ) ){
				
				define( 'BOP_PLUGIN_UPDATING', true );
				
				usort( $updates, 'version_compare' );
				
				foreach( $updates as $update ){
					require_once( bop_nav_search_box_item_plugin_path( "updates/{$update}/update.php" ) );
				}
				
			}
			
			closedir($handle);
			
		}
		
		update_option( 'bop_nav_search_box_item_version', $pd['Version'], false );
	}
	
	
	//Register warning of recommended PHP/WP update
	if( version_compare( phpversion(), '5.6.0', '<' ) || version_compare( $GLOBALS['wp_version'], '4.4.2', '<' ) ){
		update_option( 'bop_nav_search_box_item_recommended_versions_warn', true, false );
	}
	
} );

//Show recommended version warnings
add_action( 'admin_init', function(){
	if( current_user_can( 'activate_plugins' ) && get_option( 'bop_nav_search_box_item_recommended_versions_warn', false ) ){
		add_action( 'admin_notices', function(){
			if( version_compare( phpversion(), '5.6.0', '<' ) ){
				?>
				<div class="notice notice-warning">
					<p><?php 
						printf( __( 'Warning: Your version of PHP is old (%s). It is recommended you update your server. See <a href="http://php.net/supported-versions.php">here</a> for more information.', 'bop-nav-search-box-item' ), phpversion() );
					?></p>
				</div>
				<?php
			}
			if( version_compare( $GLOBALS['wp_version'], '4.4.2', '<' ) ){
				?>
				<div class="notice notice-warning">
					<p><?php 
						printf( __( 'Warning: Your version of WordPress is old (%s). It is recommended you update.', 'bop-nav-search-box-item' ), $GLOBALS['wp_version'] );
					?></p>
				</div>
				<?php
			}
		} );
		update_option( 'bop_nav_search_box_item_recommended_versions_warn', false, false );
	}
} );


/** Deactivation hook
 * 
 * Runs deactivate.php
 * 
 */
register_deactivation_hook( bop_nav_search_box_item_plugin_path( 'init.php' ), function(){
	
	define( 'BOP_PLUGIN_DEACTIVATING', true );
	
	require_once( bop_nav_search_box_item_plugin_path( 'deactivate.php' ) );
} );


/* Set up translations */
add_action( 'plugins_loaded', function(){
    load_plugin_textdomain( 'bop-nav-search-box-item', false, basename( dirname( bop_nav_search_box_item_plugin_path( 'init.php' ) ) ) . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR );
} );
