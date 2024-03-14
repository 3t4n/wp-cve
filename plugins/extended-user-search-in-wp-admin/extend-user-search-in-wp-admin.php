<?php
/*
Plugin Name: Extended User Search In WP-Admin
Plugin URI: https://wordpress.org/plugins/extended-user-search-in-wp-admin/
Description:  Extend user search by First Name or Last Name or Full Name or by bio
Version: 3
Author: Amit Mittal
Author URI: 
*/


/* version check */
global $wp_version;

if(version_compare($wp_version,"4.9.6","<")) {
	exit(__('Extended User Search In WP-admin requires WordPress version4.9 or higher. 
				<a href="http://codex.wordpress.org/Upgrading_Wordpress">Please update!</a>', 'extended-user-search-in-WP-admin'));
}

// Plugin is build to be used only in wpadmin
if(is_admin()) {
	// Action to inject custom search queries
    add_action('pre_user_query', 'extend_user_search');	

   // Main function responsible for enhancing the search
    function extend_user_search($wp_user_query) {
        if(false === strpos($wp_user_query->query_where, '@') && !empty($_GET["s"])) {
            global $wpdb;
            $uids=array();			
			$flsiwa_add = "";
			// Escaped query string
			$qstr = esc_sql($_GET["s"]);
			if(preg_match('/\s/',$qstr)){
				$pieces = explode(" ", $qstr);
				$user_ids_collector = $wpdb->get_results("SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (meta_key='first_name' AND LOWER(meta_value) LIKE '%".$pieces[0]."%')");
	            foreach($user_ids_collector as $maf) {
	                if(strtolower(get_user_meta($maf->user_id, 'last_name', true)) == strtolower($pieces[1])){
						array_push($uids,$maf->user_id);
	                }
	            }
			}else{				
				$user_ids_collector = $wpdb->get_results("SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (meta_key='first_name' OR meta_key='last_name'".$flsiwa_add.") AND LOWER(meta_value) LIKE '%".$qstr."%'");
					foreach($user_ids_collector as $maf) {
	                array_push($uids,$maf->user_id);
	            }
			}
			/*Description*/
			$user_ids_collector = $wpdb->get_results("SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE meta_key='description' AND LOWER(meta_value) LIKE '%".$qstr."%'");
			foreach($user_ids_collector as $maf) {
				if(!in_array($maf->user_id,$uids)) {
                    array_push($uids,$maf->user_id);
                }
            }
			/*Description END*/	
            $users_ids_collector = $wpdb->get_results("SELECT DISTINCT ID FROM $wpdb->users WHERE LOWER(user_nicename) LIKE '%".$qstr."%' OR LOWER(user_email) LIKE '%".$qstr."%'");
            foreach($users_ids_collector as $maf) {
                if(!in_array($maf->ID,$uids)) {
                    array_push($uids,$maf->ID);
                }
            }
            $id_string = implode(",",$uids);
			if (!empty($id_string))
			{
				$search_meta = "ID IN ($id_string)";
				$wp_user_query->query_where = str_replace(
					'WHERE 1=1 AND (',
					"WHERE 1=1 AND ( " . $search_meta . " OR ",
					$wp_user_query->query_where );
			}
        }
        return $wp_user_query;
    }
}

register_activation_hook(__FILE__,"extended_user_search_in_wpadmin_activate");

function extended_user_search_in_wpadmin_activate() {
	register_uninstall_hook(__FILE__,"extended_user_search_in_wpadmin_uninstall");
}

function extended_user_search_in_wpadmin_uninstall() {
	delete_option('flsiwa_meta_fields');
}

?>