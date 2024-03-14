<?php
/*
  Plugin Name: Add Post Last Updated Date For WP
  Description: This plugin is used to add last updated date and time of any wordpress post. Supported for both single site and multisite.
  Author: Aftab Muni
  Version: 1.0
  Author URI: https://aftabmuni.com/
 */

/*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope tPLUW it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 */

define('AMM_PLUW_VERSION', '1.0');
define('AMM_PLUW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AMM_PLUW_DONATE_LINK', 'https://www.paypal.me/aftabmuni');

function amm_pluw_activate_plugin(){}
register_activation_hook(__FILE__, 'amm_pluw_activate_plugin');

function amm_pluw_deactivate_plugin(){}
register_deactivation_hook(__FILE__, 'amm_pluw_deactivate_plugin');

if( !function_exists("amm_pluw_add_last_modified_date_in_post")){
	function amm_pluw_add_last_modified_date_in_post($content){
		$post_last_modified_utc = get_the_modified_time('U');
		$post_created_utc = get_the_date('U');
		
		if(is_single() && in_the_loop()){
			//echo $post_last_modified_utc . ' ' . $post_created_utc;exit;
			$new_content = '';
			if ($post_created_utc != $post_last_modified_utc) {
				$updated_date_time = get_the_modified_time('jS F Y, h:i a');
				$new_content .= '<p class="last-updt-div"><span class="last-updt-txt">Last Updated on: </span><strong class="last-updt-date">' . $updated_date_time.' </strong></p> ';
				$new_content .= $content;
				return $new_content;	
			}else{
			    return $content;
			}
		}else {
			return $content;
		}		
	}
	add_filter('the_content', 'amm_pluw_add_last_modified_date_in_post');
}
add_filter('plugin_row_meta', 'amm_pluw_plugin_row_meta', 10, 2);
function amm_pluw_plugin_row_meta($meta, $file) {
	if ( strpos( $file, basename(__FILE__) ) !== false ) {
		$meta[] = '<a href="'.AMM_PLUW_DONATE_LINK.'" target="_blank">' . esc_html__('Donate', 'amm_pluw') . '</a>';
	}
	return $meta;
}
?>