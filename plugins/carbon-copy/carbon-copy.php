<?php
/* --------------------------------------------------
Plugin Name: Carbon Copy
Plugin URI: https://endurtech.com/carbon-copy-wordpress-plugin/
Description: Copy pages, posts, menus, widgets and more quickly and conveniently.
Author: WP Gear Pro
Author URI: https://wpgearpro.com
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 5.4
Tested up to: 6.4
Version: 1.3.1

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
-------------------------------------------------- */
if( ! defined( 'ABSPATH' ) )
{
  exit(); // No direct access
}

define( 'CARBON_COPY_CURRENT_VERSION', '1.3.1' );

add_filter( "plugin_action_links_".plugin_basename(__FILE__), "carbon_copy_plugin_actions", 10, 4 );
function carbon_copy_plugin_actions( $actions, $plugin_file, $plugin_data, $context )
{
	array_unshift( $actions,
		sprintf( '<a href="%s" aria-label="%s">%s</a>',
			menu_page_url( 'carboncopy', false ),
			esc_attr__( 'Settings for Carbon Copy', 'carbon-copy' ),
			esc_html__( "Settings", 'default' )
		)
	);
	return $actions;
}

require_once( dirname(__FILE__).'/carbon-copy-common.php' );

if( is_admin() )
{
	require_once( dirname(__FILE__).'/carbon-copy-admin.php' );
}

// Plugin Deactivation Database Cleanup, you're welcome!
register_deactivation_hook( __FILE__, 'carbon_copy_deactivation_cleaner' );
function carbon_copy_deactivation_cleaner()
{
	// Check if we should clean database values
	$carbon_copy_deactivation_cleaner_init = get_option( 'carbon_copy_cleaner' );
	if( $carbon_copy_deactivation_cleaner_init == '1' )
	{
		delete_option( 'carbon_copy_copytitle' );
		delete_option( 'carbon_copy_copydate' );
		delete_option( 'carbon_copy_copystatus' );
		delete_option( 'carbon_copy_copyslug' );
		delete_option( 'carbon_copy_copyexcerpt' );
		delete_option( 'carbon_copy_copycontent' );
		delete_option( 'carbon_copy_copythumbnail' );
		delete_option( 'carbon_copy_copytemplate' );
		delete_option( 'carbon_copy_copyformat' );
		delete_option( 'carbon_copy_copyauthor' );
		delete_option( 'carbon_copy_copypassword' );
		delete_option( 'carbon_copy_copyattachments' );
		delete_option( 'carbon_copy_copychildren' );
		delete_option( 'carbon_copy_copycomments' );
		delete_option( 'carbon_copy_copymenuorder' );

	    	delete_option( 'carbon_copy_widgets_classic' );
		delete_option( 'carbon_copy_widgets' );
		delete_option( 'carbon_copy_menus' );

		delete_option( 'carbon_copy_roles' );

		delete_option( 'carbon_copy_types_enabled' );

		delete_option( 'carbon_copy_taxonomies_blacklist' );

		delete_option( 'carbon_copy_title_prefix' );
		delete_option( 'carbon_copy_title_suffix' );
		delete_option( 'carbon_copy_increase_menu_order_by' );
		delete_option( 'carbon_copy_blacklist' );

		delete_option( 'carbon_copy_show_row' );
		delete_option( 'carbon_copy_show_adminbar' );
		delete_option( 'carbon_copy_show_submitbox' );
		delete_option( 'carbon_copy_show_bulkactions' );

		delete_option( 'carbon_copy_show_original_column' );
		delete_option( 'carbon_copy_show_original_in_post_states' );
		delete_option( 'carbon_copy_show_original_meta_box' );

		delete_option( 'carbon_copy_cleaner' );

		delete_option( 'carbon_copy_version' );
	}
}