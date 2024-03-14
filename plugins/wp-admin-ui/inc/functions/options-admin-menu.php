<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Admin menu
//=================================================================================================

//Hide menu page
if (array_key_exists( 'menu_structure', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_menu_remove_pages(){
		$wpui_admin_menu_option = get_option("wpui_admin_menu_option_name");
				
		if ($wpui_admin_menu_option !='') {

			$wpui_admin_menu_string_only = array_filter($wpui_admin_menu_option['wpui_admin_menu'], 'is_string');
	
			if ( ! empty ( $wpui_admin_menu_option ) ) {
				foreach ($wpui_admin_menu_string_only as $wpui_admin_menu_key => $wpui_admin_menu_value) {
					remove_menu_page( $wpui_admin_menu_value );
				}
			}
	
			$wpui_admin_menu_numeric_only = array_intersect_key($wpui_admin_menu_option['wpui_admin_menu'], array_flip(array_filter(array_keys($wpui_admin_menu_option['wpui_admin_menu']), 'is_numeric')));	
	
			foreach($wpui_admin_menu_numeric_only as $wpui_admin_menu_numeric_only_key=>$wpui_admin_menu_numeric_only_value){
				foreach((array)$wpui_admin_menu_numeric_only_value as $_wpui_admin_menu_numeric_only_key=>$_wpui_admin_menu_numeric_only_value){
					foreach((array)$_wpui_admin_menu_numeric_only_value as $__wpui_admin_menu_numeric_only_key=>$__wpui_admin_menu_numeric_only_value){
						foreach((array)$__wpui_admin_menu_numeric_only_value as $___wpui_admin_menu_numeric_only_key=>$___wpui_admin_menu_numeric_only_value){
							remove_submenu_page( esc_attr($_wpui_admin_menu_numeric_only_key), esc_attr($___wpui_admin_menu_numeric_only_value));
						}
					}
				}
			}
		}
	}
	add_action( 'admin_menu', 'wpui_admin_menu_remove_pages', 999 );
}

//Custom Admin Menu Order
if (array_key_exists( 'menu_structure', wpui_get_roles_cap($wpui_user_role))) {
	if (get_option( 'wpui_admin_menu_slug' ) !='') {
		function custom_menu_order() {
			$wpui_admin_menu_custom_list = get_option( 'wpui_admin_menu_slug' );
			return $wpui_admin_menu_custom_list;
		}
		add_filter( 'custom_menu_order', '__return_true', 999 );
		add_filter( 'menu_order', 'custom_menu_order', 999 );
	}
}

//Custom Admin Menu Name
if (array_key_exists( 'menu_structure', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_menu_label() {
		global $menu;
		global $submenu;

		$wpui_admin_menu_option = get_option("wpui_admin_menu_option_name");

		if ($wpui_admin_menu_option) {
			if ( ! empty ( $wpui_admin_menu_option ) ) {
				foreach ((array)$wpui_admin_menu_option as $wpui_admin_menu_key => $wpui_admin_menu_value) {
					foreach ((array)$wpui_admin_menu_value as $wpui_admin_menu_key2 => $wpui_admin_menu_value2) {
						if(is_int($wpui_admin_menu_key2)) {
							foreach ((array)$wpui_admin_menu_value2 as $wpui_admin_menu_key3 => $wpui_admin_menu_value3) {
								if ($wpui_admin_menu_value3 !='' && is_string($wpui_admin_menu_value3)) {
									$menu[$wpui_admin_menu_key2][0] = $wpui_admin_menu_value3;
								} else {
									foreach ((array)$wpui_admin_menu_value3 as $wpui_admin_menu_key4 => $wpui_admin_menu_value4) {
										foreach ((array)$wpui_admin_menu_value4 as $wpui_admin_menu_key5 => $wpui_admin_menu_value5) {
											if ($wpui_admin_menu_value5 !='' && is_string($wpui_admin_menu_value5)) {
												$submenu[$wpui_admin_menu_key3][$wpui_admin_menu_key5][0] = $wpui_admin_menu_value5;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	add_action( 'admin_menu', 'wpui_admin_menu_label', 1099 );
}

//All settings
if (array_key_exists( 'menu_all_settings', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_menu_all_settings() {
		$wpui_admin_menu_all_settings_option = get_option("wpui_admin_menu_option_name");
		if ( ! empty ( $wpui_admin_menu_all_settings_option ) ) {
			foreach ($wpui_admin_menu_all_settings_option as $key => $wpui_admin_menu_all_settings_value)
				$options[$key] = $wpui_admin_menu_all_settings_value;
			 if (isset($wpui_admin_menu_all_settings_option['wpui_admin_menu_all_settings'])) { 
			 	return $wpui_admin_menu_all_settings_option['wpui_admin_menu_all_settings'];
			 }
		}
	};

	if (wpui_admin_menu_all_settings() == '1') {
		function wpui_admin_menu_all_settings_link() {
			add_options_page(__('All Settings'), __('All Settings'), 'manage_options', 'options.php');
		}
		add_action('admin_menu', 'wpui_admin_menu_all_settings_link');
	}
}