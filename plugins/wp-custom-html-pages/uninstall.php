<?php

// Copyright (c), Milos Stojanovic

/*
WP Custom HTML Pages is free software: you can redistribute it and/or modify it 
under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 2 of the License, or any later version.

WP Custom HTML Pages is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with WP Custom HTML Pages. 
If not, see https://www.gnu.org/licenses/gpl-2.0.html .
*/

// if uninstall.php is not called by WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

if(get_option('wpchtmlp_opt_remove_table_on_uninstall')) {
    // drop a database table
    global $wpdb;

      if ( is_multisite() ) {
	      $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	      foreach ( $blog_ids as $blog_id ) { //iterate sites in multisite
	          switch_to_blog( $blog_id );
	          WPCHTMLP_delete_tables();
	          restore_current_blog();
	      }
	  } else { //not multisite
	      WPCHTMLP_delete_tables();
	  }

}

function WPCHTMLP_delete_tables() {
	global $wpdb;
    $table_name = $wpdb->prefix . 'wpchtmlp_pages';
    $wpdb->query("DROP TABLE IF EXISTS ".$table_name);
}

    delete_option('wpchtmlp_opt_editor_type');
    delete_option('wpchtmlp_opt_editor_style');
    delete_option('wpchtmlp_opt_allow_wp-admin');
    delete_option('wpchtmlp_opt_filter_params');
    delete_option('wpchtmlp_opt_remove_table_on_uninstall');
