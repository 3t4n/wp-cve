<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Admin bar
//=================================================================================================

//WP Logo
if (array_key_exists( 'remove_wp_logo', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_wp_logo() {
		$wpui_admin_bar_wp_logo_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_wp_logo_option ) ) {
			foreach ($wpui_admin_bar_wp_logo_option as $key => $wpui_admin_bar_wp_logo_value)
				$options[$key] = $wpui_admin_bar_wp_logo_value;
			 if (isset($wpui_admin_bar_wp_logo_option['wpui_admin_bar_wp_logo'])) { 
			 	return $wpui_admin_bar_wp_logo_option['wpui_admin_bar_wp_logo'];
			 }
		}
	};
}

//Custom Logo
if (array_key_exists( 'custom_wp_logo', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_custom_logo() {
		$wpui_admin_bar_custom_logo_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_custom_logo_option ) ) {
			foreach ($wpui_admin_bar_custom_logo_option as $key => $wpui_admin_bar_custom_logo_value)
				$options[$key] = $wpui_admin_bar_custom_logo_value;
			 if (isset($wpui_admin_bar_custom_logo_option['wpui_admin_bar_custom_logo'])) { 
			 	return $wpui_admin_bar_custom_logo_option['wpui_admin_bar_custom_logo'];
			 }
		}
	};
	if (wpui_admin_bar_custom_logo() != '') {
		function wpui_admin_bar_custom_logo_css() {
		echo '<style type="text/css">
			#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
				background-image: url('. wpui_admin_bar_custom_logo() .') !important;
				background-position: 0 0;
				color:rgba(0, 0, 0, 0);
				background-size: cover;
			}
			#wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
				background-position: 0 0;
			}
			#wpadminbar #wp-admin-bar-wp-logo-default,
			#wpadminbar #wp-admin-bar-wp-logo-external {
				display: none;
			}
			</style>
		';
		}
		add_action('wp_before_admin_bar_render', 'wpui_admin_bar_custom_logo_css', 999);
	}
}

//Site Name
if (array_key_exists( 'remove_site_name', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_site_name() {
		$wpui_admin_bar_site_name_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_site_name_option ) ) {
			foreach ($wpui_admin_bar_site_name_option as $key => $wpui_admin_bar_site_name_value)
				$options[$key] = $wpui_admin_bar_site_name_value;
			 if (isset($wpui_admin_bar_site_name_option['wpui_admin_bar_site_name'])) { 
			 	return $wpui_admin_bar_site_name_option['wpui_admin_bar_site_name'];
			 }
		}
	};
}

//My Account
if (array_key_exists( 'remove_my_account', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_my_account() {
		$wpui_admin_bar_my_account_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_my_account_option ) ) {
			foreach ($wpui_admin_bar_my_account_option as $key => $wpui_admin_bar_my_account_value)
				$options[$key] = $wpui_admin_bar_my_account_value;
			 if (isset($wpui_admin_bar_my_account_option['wpui_admin_bar_my_account'])) { 
			 	return $wpui_admin_bar_my_account_option['wpui_admin_bar_my_account'];
			 }
		}
	};
}

//Menu Toggle
if (array_key_exists( 'remove_menu_toggle', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_menu_toggle() {
		$wpui_admin_bar_menu_toggle_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_menu_toggle_option ) ) {
			foreach ($wpui_admin_bar_menu_toggle_option as $key => $wpui_admin_bar_menu_toggle_value)
				$options[$key] = $wpui_admin_bar_menu_toggle_value;
			 if (isset($wpui_admin_bar_menu_toggle_option['wpui_admin_bar_menu_toggle'])) { 
			 	return $wpui_admin_bar_menu_toggle_option['wpui_admin_bar_menu_toggle'];
			 }
		}
	};
}

//Edit
if (array_key_exists( 'remove_edit', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_edit() {
		$wpui_admin_bar_edit_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_edit_option ) ) {
			foreach ($wpui_admin_bar_edit_option as $key => $wpui_admin_bar_edit_value)
				$options[$key] = $wpui_admin_bar_edit_value;
			 if (isset($wpui_admin_bar_edit_option['wpui_admin_bar_edit'])) { 
			 	return $wpui_admin_bar_edit_option['wpui_admin_bar_edit'];
			 }
		}
	};
}

//View
if (array_key_exists( 'remove_view', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_view() {
		$wpui_admin_bar_view_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_view_option ) ) {
			foreach ($wpui_admin_bar_view_option as $key => $wpui_admin_bar_view_value)
				$options[$key] = $wpui_admin_bar_view_value;
			 if (isset($wpui_admin_bar_view_option['wpui_admin_bar_view'])) { 
			 	return $wpui_admin_bar_view_option['wpui_admin_bar_view'];
			 }
		}
	};
}

//Preview
if (array_key_exists( 'remove_preview', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_preview() {
		$wpui_admin_bar_preview_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_preview_option ) ) {
			foreach ($wpui_admin_bar_preview_option as $key => $wpui_admin_bar_preview_value)
				$options[$key] = $wpui_admin_bar_preview_value;
			 if (isset($wpui_admin_bar_preview_option['wpui_admin_bar_preview'])) { 
			 	return $wpui_admin_bar_preview_option['wpui_admin_bar_preview'];
			 }
		}
	};
}

//Comments
if (array_key_exists( 'remove_comments', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_comments() {
		$wpui_admin_bar_comments_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_comments_option ) ) {
			foreach ($wpui_admin_bar_comments_option as $key => $wpui_admin_bar_comments_value)
				$options[$key] = $wpui_admin_bar_comments_value;
			 if (isset($wpui_admin_bar_comments_option['wpui_admin_bar_comments'])) { 
			 	return $wpui_admin_bar_comments_option['wpui_admin_bar_comments'];
			 }
		}
	};
}

//New Content
if (array_key_exists( 'remove_new_content', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_new_content() {
		$wpui_admin_bar_new_content_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_new_content_option ) ) {
			foreach ($wpui_admin_bar_new_content_option as $key => $wpui_admin_bar_new_content_value)
				$options[$key] = $wpui_admin_bar_new_content_value;
			 if (isset($wpui_admin_bar_new_content_option['wpui_admin_bar_new_content'])) { 
			 	return $wpui_admin_bar_new_content_option['wpui_admin_bar_new_content'];
			 }
		}
	};
}

//View Site
if (array_key_exists( 'remove_view_site', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_view_site() {
		$wpui_admin_bar_view_site_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_view_site_option ) ) {
			foreach ($wpui_admin_bar_view_site_option as $key => $wpui_admin_bar_view_site_value)
				$options[$key] = $wpui_admin_bar_view_site_value;
			 if (isset($wpui_admin_bar_view_site_option['wpui_admin_bar_view_site'])) { 
			 	return $wpui_admin_bar_view_site_option['wpui_admin_bar_view_site'];
			 }
		}
	};
}

//Updates
if (array_key_exists( 'remove_updates', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_updates() {
		$wpui_admin_bar_updates_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_updates_option ) ) {
			foreach ($wpui_admin_bar_updates_option as $key => $wpui_admin_bar_updates_value)
				$options[$key] = $wpui_admin_bar_updates_value;
			 if (isset($wpui_admin_bar_updates_option['wpui_admin_bar_updates'])) { 
			 	return $wpui_admin_bar_updates_option['wpui_admin_bar_updates'];
			 }
		}
	};
}

//Customize
if (array_key_exists( 'remove_customize', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_customize() {
		$wpui_admin_bar_customize_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_customize_option ) ) {
			foreach ($wpui_admin_bar_customize_option as $key => $wpui_admin_bar_customize_value)
				$options[$key] = $wpui_admin_bar_customize_value;
			 if (isset($wpui_admin_bar_customize_option['wpui_admin_bar_customize'])) { 
			 	return $wpui_admin_bar_customize_option['wpui_admin_bar_customize'];
			 }
		}
	};
}

//Search
if (array_key_exists( 'remove_search', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_search() {
		$wpui_admin_bar_search_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_search_option ) ) {
			foreach ($wpui_admin_bar_search_option as $key => $wpui_admin_bar_search_value)
				$options[$key] = $wpui_admin_bar_search_value;
			 if (isset($wpui_admin_bar_search_option['wpui_admin_bar_search'])) { 
			 	return $wpui_admin_bar_search_option['wpui_admin_bar_search'];
			 }
		}
	};
}

//Howdy
if (array_key_exists( 'remove_howdy', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_howdy() {
		$wpui_admin_bar_howdy_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_howdy_option ) ) {
			foreach ($wpui_admin_bar_howdy_option as $key => $wpui_admin_bar_howdy_value)
				$options[$key] = $wpui_admin_bar_howdy_value;
			 if (isset($wpui_admin_bar_howdy_option['wpui_admin_bar_howdy'])) { 
			 	return $wpui_admin_bar_howdy_option['wpui_admin_bar_howdy'];
			 }
		}
	};
}

//WP Admin UI
if (array_key_exists( 'remove_wpui', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_wpui() {
		$wpui_admin_bar_wpui_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_wpui_option ) ) {
			foreach ($wpui_admin_bar_wpui_option as $key => $wpui_admin_bar_wpui_value)
				$options[$key] = $wpui_admin_bar_wpui_value;
			 if (isset($wpui_admin_bar_wpui_option['wpui_admin_bar_wpui'])) { 
			 	return $wpui_admin_bar_wpui_option['wpui_admin_bar_wpui'];
			 }
		}
	};
}

add_action( 'admin_bar_menu', 'wpui_admin_bar_remove_items', 999 );

function wpui_admin_bar_remove_items( $wp_admin_bar ) {
	global $wp_admin_bar;
	if (function_exists('wpui_admin_bar_wp_logo')) {
		if (wpui_admin_bar_wp_logo() == '1') {
			$wp_admin_bar->remove_node( 'wp-logo' );
		}
	}
	if (function_exists('wpui_admin_bar_site_name')) {
		if (wpui_admin_bar_site_name() == '1') {
			$wp_admin_bar->remove_menu('site-name');
		}
	}
	if (function_exists('wpui_admin_bar_my_account')) {
		if (wpui_admin_bar_my_account() == '1') {
			$wp_admin_bar->remove_node( 'my-account' );
		}
	}
	if (function_exists('wpui_admin_bar_menu_toggle')) {
		if (wpui_admin_bar_menu_toggle() == '1') {
			$wp_admin_bar->remove_node( 'menu-toggle' );
		}
	}
	if (function_exists('wpui_admin_bar_edit')) {
		if (wpui_admin_bar_edit() == '1') {
			$wp_admin_bar->remove_menu( 'edit' );
		}
	}
	if (function_exists('wpui_admin_bar_preview')) {
		if (wpui_admin_bar_preview() == '1') {
			$wp_admin_bar->remove_menu( 'preview' );
		}
	}
	if (function_exists('wpui_admin_bar_view')) {
		if (wpui_admin_bar_view() == '1') {
			$wp_admin_bar->remove_menu( 'view' );
		}
	}
	if (function_exists('wpui_admin_bar_comments')) {
		if (wpui_admin_bar_comments() == '1') {
			$wp_admin_bar->remove_menu( 'comments' );
		}
	}
	if (function_exists('wpui_admin_bar_new_content')) {
		if (wpui_admin_bar_new_content() == '1') {
			$wp_admin_bar->remove_menu( 'new-content' );
		}
	}
	if (function_exists('wpui_admin_bar_view_site')) {
		if (wpui_admin_bar_view_site() == '1') {
			$wp_admin_bar->remove_menu( 'view-site' );
		}
	}
	if (function_exists('wpui_admin_bar_updates')) {
		if (wpui_admin_bar_updates() == '1') {
			$wp_admin_bar->remove_menu( 'updates' );
		}
	}
	if (function_exists('wpui_admin_bar_customize')) {
		if (wpui_admin_bar_customize() == '1') {
			$wp_admin_bar->remove_menu( 'customize' );
		}	
	}
	if (function_exists('wpui_admin_bar_search')) {
		if (wpui_admin_bar_search() == '1') {
			$wp_admin_bar->remove_menu( 'search' );
		}
	}
	if (function_exists('wpui_admin_bar_howdy')) {
		if (wpui_admin_bar_howdy() == '1') {
			$wpui_my_account = $wp_admin_bar->get_node('my-account');
		    $wpui_custom_title = str_replace( __('Howdy,', 'wp-admin-ui'), '', $wpui_my_account->title );
		    $wp_admin_bar->add_node( array(
		        'id' => 'my-account',
		        'title' => '<span class="dashicons dashicons-admin-users" style="font: 400 20px/1 dashicons;top:2px;position:relative;padding: 4px 0;"></span>',
		    ) );
		}
	}
	if (function_exists('wpui_admin_bar_wpui')) {
		if (wpui_admin_bar_wpui() == '1') {
			$wp_admin_bar->remove_menu( 'wpui_custom_top_level' );
		}
	}
}

//Disable admin bar in FE
if (array_key_exists( 'disable_admin_bar', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_bar_disable() {
		$wpui_admin_bar_disable_option = get_option("wpui_admin_bar_option_name");
		if ( ! empty ( $wpui_admin_bar_disable_option ) ) {
			foreach ($wpui_admin_bar_disable_option as $key => $wpui_admin_bar_disable_value)
				$options[$key] = $wpui_admin_bar_disable_value;
			 if (isset($wpui_admin_bar_disable_option['wpui_admin_bar_disable'])) { 
			 	return $wpui_admin_bar_disable_option['wpui_admin_bar_disable'];
			 }
		}
	};

	if (wpui_admin_bar_disable() == '1') {
		add_filter('show_admin_bar', '__return_false');
	}
}