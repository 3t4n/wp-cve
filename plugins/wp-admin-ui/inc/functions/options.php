<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Enable Settings for specific roles
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpui_roles_list_role() {
	$wpui_roles_list_role_option = get_option("wpui_roles_option_name");
	if ( ! empty ( $wpui_roles_list_role_option ) ) {
		foreach ($wpui_roles_list_role_option as $key => $wpui_roles_list_role_value)
			$options[$key] = $wpui_roles_list_role_value;
		 if (isset($wpui_roles_list_role_option['wpui_roles_list_role'])) { 
		 	return $wpui_roles_list_role_option['wpui_roles_list_role'];
		 }
	}
};

function wpui_get_roles_cap($wpui_user_role) {
	$wpui_get_roles_cap_option = get_option("wpui_roles_option_name");
	if ( ! empty ( $wpui_get_roles_cap_option ) ) {
		foreach ($wpui_get_roles_cap_option as $key => $wpui_get_roles_cap_value)
			$options[$key] = $wpui_get_roles_cap_value;
		 if (isset($wpui_get_roles_cap_option['wpui_roles_list_role'][$wpui_user_role])) { 
		 	return $wpui_get_roles_cap_option['wpui_roles_list_role'][$wpui_user_role];
		 }
	}
};

///////////////////////////////////////////////////////////////////////////////////////////////////
//WPUI Core
///////////////////////////////////////////////////////////////////////////////////////////////////

add_action('init', 'wpui_enable', 999);
function wpui_enable() {
	//Back END
	if (is_admin()){
		require_once ( dirname( __FILE__ ) . '/options-import-export.php'); //Import Export
	    
	    global $wp_roles;
		
		//Get current user role
		if(isset(wp_get_current_user()->roles[0])) {
			$wpui_user_role = wp_get_current_user()->roles[0];
			//If current user role matchs values from wpui settings then apply
			if (function_exists('wpui_roles_list_role')) {
				if (wpui_roles_list_role() != '' ) {
					if( array_key_exists( $wpui_user_role, wpui_roles_list_role())) {
						require_once ( dirname( __FILE__ ) . '/options-global.php'); //Global
						require_once ( dirname( __FILE__ ) . '/options-dashboard.php'); //Dashboard

						if (isset($_GET['page']) && ($_GET['page'] === 'wpui-admin-menu')) {
							//Do nothing, avoid conflicts
						} else {
							require_once ( dirname( __FILE__ ) . '/options-admin-menu.php'); //Admin Menu
						}
						require_once ( dirname( __FILE__ ) . '/options-admin-bar.php'); //Admin Bar
						require_once ( dirname( __FILE__ ) . '/options-editor.php'); //Editor
						require_once ( dirname( __FILE__ ) . '/options-media-library.php'); //Media Library
						require_once ( dirname( __FILE__ ) . '/options-profil.php'); //Profil
					}
				}
			}	
		}
	}
	//Front END
	if (!is_admin()){
	    global $wp_roles;
		
		//Get current user role
		if(isset(wp_get_current_user()->roles[0])) {
			$wpui_user_role = wp_get_current_user()->roles[0];
			//If current user role matchs values from wpui settings then apply
			if (function_exists('wpui_roles_list_role')) {
				if (wpui_roles_list_role() != '' ) {
					if( array_key_exists( $wpui_user_role, wpui_roles_list_role())) {
						require_once ( dirname( __FILE__ ) . '/options-admin-bar.php'); //Admin Bar
					}
				}
			}	
		}
	}
}