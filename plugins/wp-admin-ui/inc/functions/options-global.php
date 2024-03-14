<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Global
//=================================================================================================
//Custom admin CSS
if (array_key_exists( 'custom_admin_css', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_custom_css() {
		$wpui_global_custom_css_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_custom_css_option ) ) {
			foreach ($wpui_global_custom_css_option as $key => $wpui_global_custom_css_value)
				$options[$key] = $wpui_global_custom_css_value;
			 if (isset($wpui_global_custom_css_option['wpui_global_custom_css'])) { 
			 	return $wpui_global_custom_css_option['wpui_global_custom_css'];
			 }
		}
	};

	if (wpui_global_custom_css() != '') {
		function wpui_load_custom_admin_css() {
			?>
		    <style type="text/css">
		    	<?php echo wpui_global_custom_css(); ?>
		    </style>
		    <?php
		}
		add_action( 'admin_enqueue_scripts', 'wpui_load_custom_admin_css' );
	}
}

//WP version in footer
if (array_key_exists( 'remove_wp_version', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_version_footer() {
		$wpui_global_version_footer_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_version_footer_option ) ) {
			foreach ($wpui_global_version_footer_option as $key => $wpui_global_version_footer_value)
				$options[$key] = $wpui_global_version_footer_value;
			 if (isset($wpui_global_version_footer_option['wpui_global_version_footer'])) { 
			 	return $wpui_global_version_footer_option['wpui_global_version_footer'];
			 }
		}
	};

	if (wpui_global_version_footer() == '1') {
		function wpui_remove_version_footer() {
			remove_filter( 'update_footer', 'core_update_footer' ); 
		}

		add_action( 'admin_menu', 'wpui_remove_version_footer' );
	}
}
//Custom WP version in footer
if (array_key_exists( 'custom_wp_version', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_custom_version_footer() {
		$wpui_global_custom_version_footer_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_custom_version_footer_option ) ) {
			foreach ($wpui_global_custom_version_footer_option as $key => $wpui_global_custom_version_footer_value)
				$options[$key] = $wpui_global_custom_version_footer_value;
			 if (isset($wpui_global_custom_version_footer_option['wpui_global_custom_version_footer'])) { 
			 	return $wpui_global_custom_version_footer_option['wpui_global_custom_version_footer'];
			 }
		}
	};

	if (wpui_global_custom_version_footer() != '') {
		function wpui_custom_version_footer() {
			return  wpui_global_custom_version_footer();
		}
		add_action( 'update_footer', 'wpui_custom_version_footer' );
	}
}
//Remove WP credits in footer
if (array_key_exists( 'remove_wp_version', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_credits_footer() {
		$wpui_global_credits_footer_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_credits_footer_option ) ) {
			foreach ($wpui_global_credits_footer_option as $key => $wpui_global_credits_footer_value)
				$options[$key] = $wpui_global_credits_footer_value;
			 if (isset($wpui_global_credits_footer_option['wpui_global_credits_footer'])) { 
			 	return $wpui_global_credits_footer_option['wpui_global_credits_footer'];
			 }
		}
	};

	if (wpui_global_credits_footer() == '1') {
		function wpui_remove_credits_footer() {
			return '';
		}
		add_filter('admin_footer_text', 'wpui_remove_credits_footer');
	}
}
//Custom WP custom credits in footer
if (array_key_exists( 'custom_wp_version', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_custom_credits_footer() {
		$wpui_global_custom_credits_footer_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_custom_credits_footer_option ) ) {
			foreach ($wpui_global_custom_credits_footer_option as $key => $wpui_global_custom_credits_footer_value)
				$options[$key] = $wpui_global_custom_credits_footer_value;
			 if (isset($wpui_global_custom_credits_footer_option['wpui_global_custom_credits_footer'])) { 
			 	return $wpui_global_custom_credits_footer_option['wpui_global_custom_credits_footer'];
			 }
		}
	};

	if (wpui_global_custom_credits_footer() != '') {
		function wpui_custom_credits_footer() {
			return wpui_global_custom_credits_footer();
		}
		add_filter('admin_footer_text', 'wpui_custom_credits_footer');
	}
}

//Custom Favicon
if (array_key_exists( 'custom_favicon', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_custom_favicon() {
		$wpui_global_custom_favicon_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_custom_favicon_option ) ) {
			foreach ($wpui_global_custom_favicon_option as $key => $wpui_global_custom_favicon_value)
				$options[$key] = $wpui_global_custom_favicon_value;
			 if (isset($wpui_global_custom_favicon_option['wpui_global_custom_favicon'])) { 
			 	return $wpui_global_custom_favicon_option['wpui_global_custom_favicon'];
			 }
		}
	};

	if (wpui_global_custom_favicon() != '') {
		function wpui_admin_favicon() {
			echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.wpui_global_custom_favicon().'" />';
		}
		add_action('admin_head', 'wpui_admin_favicon');
	}
}

//Remove help tab
if (array_key_exists( 'remove_help_tab', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_help_tab() {
		$wpui_global_help_tab_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_help_tab_option ) ) {
			foreach ($wpui_global_help_tab_option as $key => $wpui_global_help_tab_value)
				$options[$key] = $wpui_global_help_tab_value;
			 if (isset($wpui_global_help_tab_option['wpui_global_help_tab'])) { 
			 	return $wpui_global_help_tab_option['wpui_global_help_tab'];
			 }
		}
	};

	if (wpui_global_help_tab() == '1') {
		add_filter( 'contextual_help', 'wpui_remove_help', 999, 3 );

		function wpui_remove_help( $old_help, $screen_id, $screen ){
			    $screen->remove_help_tabs();
			    return $old_help;
		}
	}
}

//Remove screen options tab
if (array_key_exists( 'remove_screen_tab', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_screen_options_tab() {
		$wpui_global_screen_options_tab_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_screen_options_tab_option ) ) {
			foreach ($wpui_global_screen_options_tab_option as $key => $wpui_global_screen_options_tab_value)
				$options[$key] = $wpui_global_screen_options_tab_value;
			 if (isset($wpui_global_screen_options_tab_option['wpui_global_screen_options_tab'])) { 
			 	return $wpui_global_screen_options_tab_option['wpui_global_screen_options_tab'];
			 }
		}
	};

	if (wpui_global_screen_options_tab() == '1') {
		add_filter('screen_options_show_screen', '__return_false');
	}
}

//Open Sans Font
if (array_key_exists( 'disable_open_sans', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_global_open_sans() {
        $wpui_global_open_sans_option = get_option("wpui_global_option_name");
        if ( ! empty ( $wpui_global_open_sans_option ) ) {
            foreach ($wpui_global_open_sans_option as $key => $wpui_global_open_sans_value)
                $options[$key] = $wpui_global_open_sans_value;
             if (isset($wpui_global_open_sans_option['wpui_global_open_sans'])) { 
                return $wpui_global_open_sans_option['wpui_global_open_sans'];
             }
        }
    };

    if (wpui_global_open_sans() == '1') {
        function wpui_global_open_sans_loading() {
            wp_deregister_style( 'open-sans' );
            wp_register_style( 'open-sans', false );
        }
        add_action( 'admin_enqueue_scripts', 'wpui_global_open_sans_loading', 999 );
    }
}

//Custom avatar
if (array_key_exists( 'custom_avatar', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_global_custom_avatar() {
        $wpui_global_custom_avatar_option = get_option("wpui_global_option_name");
        if ( ! empty ( $wpui_global_custom_avatar_option ) ) {
            foreach ($wpui_global_custom_avatar_option as $key => $wpui_global_custom_avatar_value)
                $options[$key] = $wpui_global_custom_avatar_value;
             if (isset($wpui_global_custom_avatar_option['wpui_global_custom_avatar'])) { 
                return $wpui_global_custom_avatar_option['wpui_global_custom_avatar'];
             }
        }
    };

    if (wpui_global_custom_avatar() != '') {
		function wpui_new_avatar($avatar_defaults) {
		    $wpui_avatar = wpui_global_custom_avatar();
		    $avatar_defaults[$wpui_avatar] = 'WPUI';
		    return $avatar_defaults;
		}
		add_filter( 'avatar_defaults', 'wpui_new_avatar', 999 );
    }
}

//Remove WP update notifications
if (array_key_exists( 'disable_wp_update_notifications', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_update_notification() {
		$wpui_global_update_notification_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_update_notification_option ) ) {
			foreach ($wpui_global_update_notification_option as $key => $wpui_global_update_notification_value)
				$options[$key] = $wpui_global_update_notification_value;
			 if (isset($wpui_global_update_notification_option['wpui_global_update_notification'])) { 
			 	return $wpui_global_update_notification_option['wpui_global_update_notification'];
			 }
		}
	};

	if (wpui_global_update_notification() == '1') {
		add_action('after_setup_theme','wpui_remove_core_updates');

		remove_action('load-update-core.php','wp_update_plugins');
		add_filter('pre_site_transient_update_plugins','__return_null');

		function wpui_remove_core_updates(){
			global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
		}
		add_filter('pre_site_transient_update_core','wpui_remove_core_updates');
		add_filter('pre_site_transient_update_plugins','wpui_remove_core_updates');
		add_filter('pre_site_transient_update_themes','wpui_remove_core_updates');

		add_action('admin_menu', 'wpui_wphidenag');
		function wpui_wphidenag() {
			remove_action('admin_notices', 'update_nag', 3);
		}
	}
}

//Hide autogenerated password message
if (array_key_exists( 'hide_pwd_msg', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_password_notification() {
		$wpui_global_password_notification_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_password_notification_option ) ) {
			foreach ($wpui_global_password_notification_option as $key => $wpui_global_password_notification_value)
				$options[$key] = $wpui_global_password_notification_value;
			 if (isset($wpui_global_password_notification_option['wpui_global_password_notification'])) { 
			 	return $wpui_global_password_notification_option['wpui_global_password_notification'];
			 }
		}
	};

	if (wpui_global_password_notification() == '1') {
		function wpui_stop_password_nag( $val ){
			return 0;
		}
		add_filter( 'get_user_option_default_password_nag' ,'wpui_stop_password_nag' , 10 );
	}
}

//Number of items per page
if (array_key_exists( 'items_per_page_list', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_edit_per_page() {
		$wpui_global_edit_per_page_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_edit_per_page_option ) ) {
			foreach ($wpui_global_edit_per_page_option as $key => $wpui_global_edit_per_page_value)
				$options[$key] = $wpui_global_edit_per_page_value;
			 if (isset($wpui_global_edit_per_page_option['wpui_global_edit_per_page'])) { 
			 	return $wpui_global_edit_per_page_option['wpui_global_edit_per_page'];
			 }
		}
	};

	if (wpui_global_edit_per_page() != '') {
		add_action('admin_init', 'wpui_global_number_items', 999);

		function wpui_global_number_items() {
			$wpui_all_users = get_users();
			$wpui_post_types = get_post_types( '', 'names' ); 
			//CPT
			foreach ($wpui_post_types as $post_type) {
				foreach ( $wpui_all_users as $wpui_user ) {
					//if ( !get_user_meta( $wpui_user->ID, 'edit_'.$post_type.'_per_page', true ) ) {
						update_user_meta( $wpui_user->ID, 'edit_'.$post_type.'_per_page', wpui_global_edit_per_page() );
					//}
				}	
			}
		}
	}
}

//Default View Mode
if (array_key_exists( 'view_mode', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_default_view_mode() {
		$wpui_global_default_view_mode_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_default_view_mode_option ) ) {
			foreach ($wpui_global_default_view_mode_option as $key => $wpui_global_default_view_mode_value)
				$options[$key] = $wpui_global_default_view_mode_value;
			 if (isset($wpui_global_default_view_mode_option['wpui_global_default_view_mode'])) { 
			 	return $wpui_global_default_view_mode_option['wpui_global_default_view_mode'];
			 }
		}
	};

	if (wpui_global_default_view_mode() != '') {
		function wpui_global_default_view_mode_display() {
		    if ( !isset( $_REQUEST['mode'] ) ) {
		        $_REQUEST['mode'] = wpui_global_default_view_mode();
		    }
		}
		add_action( 'load-edit.php', 'wpui_global_default_view_mode_display', 999 );
	}
}

//Disable file editor
if (array_key_exists( 'disable_file_editor', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_file_editor() {
		$wpui_global_disable_file_editor_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_file_editor_option ) ) {
			foreach ($wpui_global_disable_file_editor_option as $key => $wpui_global_disable_file_editor_value)
				$options[$key] = $wpui_global_disable_file_editor_value;
			if (isset($wpui_global_disable_file_editor_option['wpui_global_disable_file_editor'])) {
				return $wpui_global_disable_file_editor_option['wpui_global_disable_file_editor'];
			}
		}
	};

	if (wpui_global_disable_file_editor() != '' && !defined('DISALLOW_FILE_EDIT') ) {
		define( 'DISALLOW_FILE_EDIT', true );
	}
}

//Disable file modifications
if (array_key_exists( 'disable_plugin_theme_update_installation', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_file_mods() {
		$wpui_global_disable_file_mods_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_file_mods_option ) ) {
			foreach ($wpui_global_disable_file_mods_option as $key => $wpui_global_disable_file_mods_value)
				$options[$key] = $wpui_global_disable_file_mods_value;
			if (isset($wpui_global_disable_file_mods_option['wpui_global_disable_file_mods'])) {
				return $wpui_global_disable_file_mods_option['wpui_global_disable_file_mods'];
			}
		}
	};

	if (wpui_global_disable_file_mods() != '' && !defined('DISALLOW_FILE_MODS') ) {
		define('DISALLOW_FILE_MODS', true);
	}
}

//Block WordPress admin
if (array_key_exists( 'block_admin', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_block_admin() {
		$wpui_global_block_admin_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_block_admin_option ) ) {
			foreach ($wpui_global_block_admin_option as $key => $wpui_global_block_admin_value)
				$options[$key] = $wpui_global_block_admin_value;
			if (isset($wpui_global_block_admin_option['wpui_global_block_admin'])) {
				return $wpui_global_block_admin_option['wpui_global_block_admin'];
			}
		}
	};

	if (wpui_global_block_admin() =='1') {
        function wpui_block_wp_admin(){
        	if ( !defined( 'DOING_AJAX' )) {
	            wp_redirect( get_bloginfo('url') );
	            exit;
        	}
        }
        add_action('admin_init', 'wpui_block_wp_admin', 1);
	}
}

//Disable all updates
if (array_key_exists( 'disable_all_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_all_wp_udpates() {
		$wpui_global_disable_all_wp_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_all_wp_udpates_option ) ) {
			foreach ($wpui_global_disable_all_wp_udpates_option as $key => $wpui_global_disable_all_wp_udpates_value)
				$options[$key] = $wpui_global_disable_all_wp_udpates_value;
			if (isset($wpui_global_disable_all_wp_udpates_option['wpui_global_disable_all_wp_udpates'])) {
				return $wpui_global_disable_all_wp_udpates_option['wpui_global_disable_all_wp_udpates'];
			}
		}
	};

	if (wpui_global_disable_all_wp_udpates() != '') {
		add_filter( 'automatic_updater_disabled', '__return_true' );
	}
}
//Disable core updates
if (array_key_exists( 'disable_core_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_core_udpates() {
		$wpui_global_disable_core_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_core_udpates_option ) ) {
			foreach ($wpui_global_disable_core_udpates_option as $key => $wpui_global_disable_core_udpates_value)
				$options[$key] = $wpui_global_disable_core_udpates_value;
			if (isset($wpui_global_disable_core_udpates_option['wpui_global_disable_core_udpates'])) {
				return $wpui_global_disable_core_udpates_option['wpui_global_disable_core_udpates'];
			}
		}
	};

	if (wpui_global_disable_core_udpates() != '') {
		add_filter( 'auto_update_core', '__return_false' );
	}
}

//Disable dev updates
if (array_key_exists( 'disable_core_dev_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_core_dev_udpates() {
		$wpui_global_disable_core_dev_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_core_dev_udpates_option ) ) {
			foreach ($wpui_global_disable_core_dev_udpates_option as $key => $wpui_global_disable_core_dev_udpates_value)
				$options[$key] = $wpui_global_disable_core_dev_udpates_value;
			if (isset($wpui_global_disable_core_dev_udpates_option['wpui_global_disable_core_dev_udpates'])) {
				return $wpui_global_disable_core_dev_udpates_option['wpui_global_disable_core_dev_udpates'];
			}
		}
	};

	if (wpui_global_disable_core_dev_udpates() != '') {
		add_filter( 'allow_dev_auto_core_updates', '__return_false' );
	}
}

//Disable minor updates
if (array_key_exists( 'disable_minor_core_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_core_minor_udpates() {
		$wpui_global_disable_core_minor_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_core_minor_udpates_option ) ) {
			foreach ($wpui_global_disable_core_minor_udpates_option as $key => $wpui_global_disable_core_minor_udpates_value)
				$options[$key] = $wpui_global_disable_core_minor_udpates_value;
			if (isset($wpui_global_disable_core_minor_udpates_option['wpui_global_disable_core_minor_udpates'])) {
				return $wpui_global_disable_core_minor_udpates_option['wpui_global_disable_core_minor_udpates'];
			}
		}
	};

	if (wpui_global_disable_core_minor_udpates() != '') {
		add_filter( 'allow_minor_auto_core_updates', '__return_false' );
	}
}

//Disable major updates
if (array_key_exists( 'disable_major_core_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_core_major_udpates() {
		$wpui_global_disable_core_major_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_core_major_udpates_option ) ) {
			foreach ($wpui_global_disable_core_major_udpates_option as $key => $wpui_global_disable_core_major_udpates_value)
				$options[$key] = $wpui_global_disable_core_major_udpates_value;
			if (isset($wpui_global_disable_core_major_udpates_option['wpui_global_disable_core_major_udpates'])) {
				return $wpui_global_disable_core_major_udpates_option['wpui_global_disable_core_major_udpates'];
			}
		}
	};

	if (wpui_global_disable_core_major_udpates() != '') {
		add_filter( 'allow_major_auto_core_updates', '__return_false' );
	}
}

//Enable updates on VCS
if (array_key_exists( 'enable_updates_vcs', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_enable_core_vcs_udpates() {
		$wpui_global_enable_core_vcs_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_enable_core_vcs_udpates_option ) ) {
			foreach ($wpui_global_enable_core_vcs_udpates_option as $key => $wpui_global_enable_core_vcs_udpates_value)
				$options[$key] = $wpui_global_enable_core_vcs_udpates_value;
			if (isset($wpui_global_enable_core_vcs_udpates_option['wpui_global_enable_core_vcs_udpates'])) {
				return $wpui_global_enable_core_vcs_udpates_option['wpui_global_enable_core_vcs_udpates'];
			}
		}
	};

	if (wpui_global_enable_core_vcs_udpates() != '') {
		add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', 1 );
	}
}

//Disable plugin updates
if (array_key_exists( 'disable_automatic_plugins_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_plugin_udpates() {
		$wpui_global_disable_plugin_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_plugin_udpates_option ) ) {
			foreach ($wpui_global_disable_plugin_udpates_option as $key => $wpui_global_disable_plugin_udpates_value)
				$options[$key] = $wpui_global_disable_plugin_udpates_value;
			if (isset($wpui_global_disable_plugin_udpates_option['wpui_global_disable_plugin_udpates'])) {
				return $wpui_global_disable_plugin_udpates_option['wpui_global_disable_plugin_udpates'];
			}
		}
	};

	if (wpui_global_disable_plugin_udpates() != '') {
		add_filter( 'auto_update_plugin', '__return_false' );
	}
}

//Disable themes updates
if (array_key_exists( 'disable_automatic_themes_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_theme_udpates() {
		$wpui_global_disable_theme_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_theme_udpates_option ) ) {
			foreach ($wpui_global_disable_theme_udpates_option as $key => $wpui_global_disable_theme_udpates_value)
				$options[$key] = $wpui_global_disable_theme_udpates_value;
			if (isset($wpui_global_disable_theme_udpates_option['wpui_global_disable_theme_udpates'])) {
				return $wpui_global_disable_theme_udpates_option['wpui_global_disable_theme_udpates'];
			}
		}
	};

	if (wpui_global_disable_theme_udpates() != '') {
		add_filter( 'auto_update_theme', '__return_false' );
	}
}

//Disable translation updates
if (array_key_exists( 'disable_automatic_translations_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_translation_udpates() {
		$wpui_global_disable_translation_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_translation_udpates_option ) ) {
			foreach ($wpui_global_disable_translation_udpates_option as $key => $wpui_global_disable_translation_udpates_value)
				$options[$key] = $wpui_global_disable_translation_udpates_value;
			if (isset($wpui_global_disable_translation_udpates_option['wpui_global_disable_translation_udpates'])) {
				return $wpui_global_disable_translation_udpates_option['wpui_global_disable_translation_udpates'];
			}
		}
	};

	if (wpui_global_disable_translation_udpates() != '') {
		add_filter( 'auto_update_translation', '__return_false' );
	}
}

//Disable email updates
if (array_key_exists( 'disable_updates_emails', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_email_udpates() {
		$wpui_global_disable_email_udpates_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_email_udpates_option ) ) {
			foreach ($wpui_global_disable_email_udpates_option as $key => $wpui_global_disable_email_udpates_value)
				$options[$key] = $wpui_global_disable_email_udpates_value;
			if (isset($wpui_global_disable_email_udpates_option['wpui_global_disable_email_udpates'])) {
				return $wpui_global_disable_email_udpates_option['wpui_global_disable_email_udpates'];
			}
		}
	};

	if (wpui_global_disable_email_udpates() != '') {
		add_filter( 'auto_core_update_send_email', '__return_false' );
	}
}

//Disable JS Concatenation
if (array_key_exists( 'disable_js_concatenation', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_global_disable_js_concatenation() {
		$wpui_global_disable_js_concatenation_option = get_option("wpui_global_option_name");
		if ( ! empty ( $wpui_global_disable_js_concatenation_option ) ) {
			foreach ($wpui_global_disable_js_concatenation_option as $key => $wpui_global_disable_js_concatenation_value)
				$options[$key] = $wpui_global_disable_js_concatenation_value;
			if (isset($wpui_global_disable_js_concatenation_option['wpui_global_disable_js_concatenation'])) {
				return $wpui_global_disable_js_concatenation_option['wpui_global_disable_js_concatenation'];
			}
		}
	};

	if (wpui_global_disable_js_concatenation() != '' && !defined('CONCATENATE_SCRIPTS') ) {
		define( 'CONCATENATE_SCRIPTS', false );
	}
}
