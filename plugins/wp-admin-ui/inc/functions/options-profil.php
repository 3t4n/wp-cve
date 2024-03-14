<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Profil
//=================================================================================================

//Visual Editor
if (array_key_exists( 'remove_disable_visual_editor', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_visual_editor() {
		$wpui_profil_visual_editor_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_visual_editor_option ) ) {
			foreach ($wpui_profil_visual_editor_option as $key => $wpui_profil_visual_editor_value)
				$options[$key] = $wpui_profil_visual_editor_value;
			 if (isset($wpui_profil_visual_editor_option['wpui_profil_visual_editor'])) { 
			 	return $wpui_profil_visual_editor_option['wpui_profil_visual_editor'];
			 }
		}
	};

	if (wpui_profil_visual_editor() == '1') {
		add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_visual_editor' );
		add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_visual_editor' );

		function wpui_profil_remove_visual_editor( $hook ) {
		    ?>
		    <style type="text/css">
		        #your-profile .form-table .user-rich-editing-wrap { display:none!important;visibility:hidden!important; }
		    </style>
		    <?php
		} 
	}
}

//Color Scheme
if (array_key_exists( 'remove_admin_color_scheme', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_admin_color_scheme() {
		$wpui_profil_admin_color_scheme_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_admin_color_scheme_option ) ) {
			foreach ($wpui_profil_admin_color_scheme_option as $key => $wpui_profil_admin_color_scheme_value)
				$options[$key] = $wpui_profil_admin_color_scheme_value;
			 if (isset($wpui_profil_admin_color_scheme_option['wpui_profil_admin_color_scheme'])) { 
			 	return $wpui_profil_admin_color_scheme_option['wpui_profil_admin_color_scheme'];
			 }
		}
	};

	if (wpui_profil_admin_color_scheme() == '1') {
		remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
	}
}

//Keyword shortcuts
if (array_key_exists( 'remove_enable_keyboard_shortcuts', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_keyword_shortcuts() {
		$wpui_profil_keyword_shortcuts_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_keyword_shortcuts_option ) ) {
			foreach ($wpui_profil_keyword_shortcuts_option as $key => $wpui_profil_keyword_shortcuts_value)
				$options[$key] = $wpui_profil_keyword_shortcuts_value;
			 if (isset($wpui_profil_keyword_shortcuts_option['wpui_profil_keyword_shortcuts'])) { 
			 	return $wpui_profil_keyword_shortcuts_option['wpui_profil_keyword_shortcuts'];
			 }
		}
	};

	if (wpui_profil_keyword_shortcuts() == '1') {
		add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_keyword_shortcuts' );
		add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_keyword_shortcuts' );

		function wpui_profil_remove_keyword_shortcuts( $hook ) {
		    ?>
		    <style type="text/css">
		        #your-profile .form-table .user-comment-shortcuts-wrap { display:none!important;visibility:hidden!important; }
		    </style>
		    <?php
		} 
	}
}

//Default color scheme
if (array_key_exists( 'default_admin_color_scheme', wpui_get_roles_cap($wpui_user_role))) {

	/* WPUI One */
	wp_admin_css_color(
		'wpui-one',
	   	__('WPUI Algua'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-one/colors.min.css', __FILE__ ),
	   	array('#247ba0', '#70c1b3', '#ff1654', '#ffffff')
	);

	/* WPUI Two */
	wp_admin_css_color(
		'wpui-two',
	   	__('WPUI Dark'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-two/colors.min.css', __FILE__ ),
	   	array('#011627', '#fdfffc', '#2ec4b6', '#e71d36', '#ff9f1c')
	);

	/* WPUI Three */
	wp_admin_css_color(
		'wpui-three',
	   	__('WPUI Teal'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-three/colors.min.css', __FILE__ ),
	   	array('#114b5f', '#fdfffc', '#028090', '#f45b69')
	);

	/* WPUI Four */
	wp_admin_css_color(
		'wpui-four',
	   	__('WPUI Ice'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-four/colors.min.css', __FILE__ ),
	   	array('#007EA7', '#00A7EB', '#00161F', '#ffffff')
	);

	/* WPUI Five */
	wp_admin_css_color(
		'wpui-five',
	   	__('WPUI Army'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-five/colors.min.css', __FILE__ ),
	   	array('#487D58', '#FAF3D9', '#65B0AB', '#F3F3F3')
	);

	/* WPUI Six */
	wp_admin_css_color(
		'wpui-six',
	   	__('WPUI Bayonne'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-six/colors.min.css', __FILE__ ),
	   	array('#990D35', '#D52941', '#FCD581', '#ffffff')
	);

	/* WPUI Seven */
	wp_admin_css_color(
		'wpui-seven',
	   	__('WPUI Fashion'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-seven/colors.min.css', __FILE__ ),
	   	array('#554971', '#b8f3ff', '#36213e', '#63768d')
	);

	/* WPUI Eight */
	wp_admin_css_color(
		'wpui-eight',
	   	__('WPUI Cafe'),
	   	plugins_url( '../../assets/css/color-schemes/wpui-eight/colors.min.css', __FILE__ ),
	   	array('#181818', '#B0966C', '#FDD692', '#ffffff')
	);
}

//Toolbar
if (array_key_exists( 'remove_show_toolbar', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_show_toolbar() {
		$wpui_profil_show_toolbar_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_show_toolbar_option ) ) {
			foreach ($wpui_profil_show_toolbar_option as $key => $wpui_profil_show_toolbar_value)
				$options[$key] = $wpui_profil_show_toolbar_value;
			 if (isset($wpui_profil_show_toolbar_option['wpui_profil_show_toolbar'])) { 
			 	return $wpui_profil_show_toolbar_option['wpui_profil_show_toolbar'];
			 }
		}
	};

	if (wpui_profil_show_toolbar() == '1') {
		add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_show_toolbar' );
		add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_show_toolbar' );

		function wpui_profil_remove_show_toolbar( $hook ) {
		    ?>
		    <style type="text/css">
		        #your-profile .form-table .show-admin-bar { display:none!important;visibility:hidden!important; }
		    </style>
		    <?php
		} 
	}

	if (wpui_profil_show_toolbar() == '1' && wpui_profil_keyword_shortcuts() == '1' && wpui_profil_admin_color_scheme() == '1' && wpui_profil_visual_editor() == '1') {
		add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_title' );
		add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_title' );

		function wpui_profil_remove_title( $hook ) {
		    ?>
		    <style type="text/css">
		        #your-profile p+h3 { display:none!important;visibility:hidden!important; }
		    </style>
		    <?php
		} 
	}
}

//Facebook field
if (array_key_exists( 'add_facebook_field', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_facebook_field() {
		$wpui_profil_facebook_field_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_facebook_field_option ) ) {
			foreach ($wpui_profil_facebook_field_option as $key => $wpui_profil_facebook_field_value)
				$options[$key] = $wpui_profil_facebook_field_value;
			 if (isset($wpui_profil_facebook_field_option['wpui_profil_facebook_field'])) { 
			 	return $wpui_profil_facebook_field_option['wpui_profil_facebook_field'];
			 }
		}
	};

	if (wpui_profil_facebook_field() == '1') {
		function wpui_profil_add_facebook_field($profile_fields) {

			$profile_fields['wpui-facebook'] = __('Facebook URL','wp-admin-ui');
			
			return $profile_fields;
		}
		add_filter('user_contactmethods', 'wpui_profil_add_facebook_field', 999);
	}
}

//Twitter field
if (array_key_exists( 'add_twitter_field', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_twitter_field() {
		$wpui_profil_twitter_field_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_twitter_field_option ) ) {
			foreach ($wpui_profil_twitter_field_option as $key => $wpui_profil_twitter_field_value)
				$options[$key] = $wpui_profil_twitter_field_value;
			 if (isset($wpui_profil_twitter_field_option['wpui_profil_twitter_field'])) { 
			 	return $wpui_profil_twitter_field_option['wpui_profil_twitter_field'];
			 }
		}
	};

	if (wpui_profil_twitter_field() == '1') {
		function wpui_profil_add_twitter_field($profile_fields) {

			$profile_fields['wpui-twitter'] = __('Twitter URL','wp-admin-ui');
			
			return $profile_fields;
		}
		add_filter('user_contactmethods', 'wpui_profil_add_twitter_field', 999);
	}
}

//Instagram field
if (array_key_exists( 'add_instagram_field', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_instagram_field() {
		$wpui_profil_instagram_field_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_instagram_field_option ) ) {
			foreach ($wpui_profil_instagram_field_option as $key => $wpui_profil_instagram_field_value)
				$options[$key] = $wpui_profil_instagram_field_value;
			 if (isset($wpui_profil_instagram_field_option['wpui_profil_instagram_field'])) { 
			 	return $wpui_profil_instagram_field_option['wpui_profil_instagram_field'];
			 }
		}
	};

	if (wpui_profil_instagram_field() == '1') {
		function wpui_profil_add_instagram_field($profile_fields) {

			$profile_fields['wpui-instagram'] = __('Instagram URL','wp-admin-ui');
			
			return $profile_fields;
		}
		add_filter('user_contactmethods', 'wpui_profil_add_instagram_field', 999);
	}
}

//LinkedIn field
if (array_key_exists( 'add_linkedin_field', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_profil_linkedin_field() {
		$wpui_profil_linkedin_field_option = get_option("wpui_profil_option_name");
		if ( ! empty ( $wpui_profil_linkedin_field_option ) ) {
			foreach ($wpui_profil_linkedin_field_option as $key => $wpui_profil_linkedin_field_value)
				$options[$key] = $wpui_profil_linkedin_field_value;
			 if (isset($wpui_profil_linkedin_field_option['wpui_profil_linkedin_field'])) { 
			 	return $wpui_profil_linkedin_field_option['wpui_profil_linkedin_field'];
			 }
		}
	};

	if (wpui_profil_linkedin_field() == '1') {
		function wpui_profil_add_linkedin_field($profile_fields) {

			$profile_fields['wpui-linkedin'] = __('LinkedIn URL','wp-admin-ui');
			
			return $profile_fields;
		}
		add_filter('user_contactmethods', 'wpui_profil_add_linkedin_field', 999);
	}
}