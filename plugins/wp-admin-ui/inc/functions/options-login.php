<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Login
//=================================================================================================
global $wp_version;
//Custom login CSS
function wpui_login_custom_css() {
	$wpui_login_custom_css_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_custom_css_option ) ) {
		foreach ($wpui_login_custom_css_option as $key => $wpui_login_custom_css_value)
			$options[$key] = $wpui_login_custom_css_value;
		 if (isset($wpui_login_custom_css_option['wpui_login_custom_css'])) { 
		 	return $wpui_login_custom_css_option['wpui_login_custom_css'];
		 }
	}
};

if (wpui_login_custom_css() != '') {
	function wpui_custom_login_css() {
		?>
	    <style type="text/css">
	    	<?php echo wpui_login_custom_css(); ?>
	    </style>
	    <?php
	}
	add_action('login_head', 'wpui_custom_login_css');
}

//Custom login url logo
function wpui_login_url_logo() {
	$wpui_login_logo_url_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_logo_url_option ) ) {
		foreach ($wpui_login_logo_url_option as $key => $wpui_login_logo_url_value)
			$options[$key] = $wpui_login_logo_url_value;
		 if (isset($wpui_login_logo_url_option['wpui_login_logo_url'])) { 
		 	return $wpui_login_logo_url_option['wpui_login_logo_url'];
		 }
	}
};

if (wpui_login_url_logo() != '') {
	function wpui_logo_url_login(){
		return esc_url(wpui_login_url_logo()); 
	}
	add_filter('login_headerurl', 'wpui_logo_url_login', 9999);
}

//Custom login logo
function wpui_login_logo() {
	$wpui_login_logo_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_logo_option ) ) {
		foreach ($wpui_login_logo_option as $key => $wpui_login_logo_value)
			$options[$key] = $wpui_login_logo_value;
		 if (isset($wpui_login_logo_option['wpui_login_logo'])) { 
		 	return $wpui_login_logo_option['wpui_login_logo'];
		 }
	}
};

if (wpui_login_logo() != '') {
	function wpui_logo_login(){
		?>
	    <style type="text/css">
	    	.login h1 a {
	    		background-image: url(<?php echo wpui_login_logo(); ?>);
			    -webkit-background-size: 320px;
			    background-size: 320px;
			    background-position: center top;
			    background-repeat: no-repeat;
			    height: 75px;
			    margin: 0 auto;
			    padding: 0;
			    width: 320px;
			}
	    </style>
	    <?php
	}
	add_filter('login_headerurl', 'wpui_logo_login');
}

//Custom login logo title
function wpui_login_custom_logo_title() {
	$wpui_login_custom_logo_title_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_custom_logo_title_option ) ) {
		foreach ($wpui_login_custom_logo_title_option as $key => $wpui_login_custom_logo_title_value)
			$options[$key] = $wpui_login_custom_logo_title_value;
		 if (isset($wpui_login_custom_logo_title_option['wpui_login_custom_logo_title'])) { 
		 	return $wpui_login_custom_logo_title_option['wpui_login_custom_logo_title'];
		 }
	}
};

if (wpui_login_custom_logo_title() != '') {
	function wpui_login_logo_url_title() {
	    return wpui_login_custom_logo_title();
	}

	if (version_compare( $wp_version, '5.2' ) >= 0) {
		add_filter( 'login_headertext', 'wpui_login_logo_url_title', 999 );
	} else {
		add_filter( 'login_headertitle', 'wpui_login_logo_url_title', 999 );
	}
}

//Custom bg img
function wpui_login_custom_bg_img() {
	$wpui_login_custom_bg_img_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_custom_bg_img_option ) ) {
		foreach ($wpui_login_custom_bg_img_option as $key => $wpui_login_custom_bg_img_value)
			$options[$key] = $wpui_login_custom_bg_img_value;
		 if (isset($wpui_login_custom_bg_img_option['wpui_login_custom_bg_img'])) { 
		 	return $wpui_login_custom_bg_img_option['wpui_login_custom_bg_img'];
		 }
	}
};

if (wpui_login_custom_bg_img() != '') {
	function wpui_login_bg_img() {
	    ?>
	    <style type="text/css">
	    	body {background: url(<?php echo wpui_login_custom_bg_img(); ?>) no-repeat 50% 50% / cover;}
	    </style>
	    <?php
	}
	if (version_compare( $wp_version, '5.2' ) >= 0) {
		add_filter( 'login_headertext', 'wpui_login_bg_img' );
	} else {
		add_filter( 'login_headertitle', 'wpui_login_bg_img' );
	}
}

//Remember me
function wpui_login_always_checked() {
	$wpui_login_always_checked_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_always_checked_option ) ) {
		foreach ($wpui_login_always_checked_option as $key => $wpui_login_always_checked_value)
			$options[$key] = $wpui_login_always_checked_value;
		 if (isset($wpui_login_always_checked_option['wpui_login_always_checked'])) { 
		 	return $wpui_login_always_checked_option['wpui_login_always_checked'];
		 }
	}
};

if (wpui_login_always_checked() == '1') {
	function wpui_login_checked_remember_me() {
		add_filter( 'login_footer', 'wpui_rememberme_checked' );
	}
	add_action( 'init', 'wpui_login_checked_remember_me' );

	function wpui_rememberme_checked() {
		echo "<script>document.getElementById('rememberme').checked = true;</script>";
	}
}

//Remove error message
function wpui_login_error_message() {
	$wpui_login_error_message_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_error_message_option ) ) {
		foreach ($wpui_login_error_message_option as $key => $wpui_login_error_message_value)
			$options[$key] = $wpui_login_error_message_value;
		 if (isset($wpui_login_error_message_option['wpui_login_error_message'])) { 
		 	return $wpui_login_error_message_option['wpui_login_error_message'];
		 }
	}
};

if (wpui_login_error_message() == '1') {
	add_filter('login_errors','wpui_login_custom_error_message');

	function wpui_login_custom_error_message($error){
		$error = __('Your credentials are incorrect','wp-admin-ui');
		return $error;
	}
}

//Disable shake effect
function wpui_login_shake_effect_js() {
	$wpui_login_shake_effect_js_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_shake_effect_js_option ) ) {
		foreach ($wpui_login_shake_effect_js_option as $key => $wpui_login_shake_effect_js_value)
			$options[$key] = $wpui_login_shake_effect_js_value;
		 if (isset($wpui_login_shake_effect_js_option['wpui_login_shake_effect'])) { 
		 	return $wpui_login_shake_effect_js_option['wpui_login_shake_effect'];
		 }
	}
};

if (wpui_login_shake_effect_js() == '1') {
	function wpui_login_remove_shake_effect() {
	    remove_action('login_head', 'wp_shake_js', 12);
	}
	add_action('login_head', 'wpui_login_remove_shake_effect', 1);
}

//Redirect after logout
function wpui_login_logout_redirect() {
	$wpui_login_logout_redirect_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_logout_redirect_option ) ) {
		foreach ($wpui_login_logout_redirect_option as $key => $wpui_login_logout_redirect_value)
			$options[$key] = $wpui_login_logout_redirect_value;
		if (isset($wpui_login_logout_redirect_option['wpui_login_logout_redirect'])) {
			return $wpui_login_logout_redirect_option['wpui_login_logout_redirect'];
		}
	}
};

if (wpui_login_logout_redirect() != '' ) {
	function wpui_redirect_users_logout() {
	     wp_redirect(esc_url(wpui_login_logout_redirect()));
	     exit;
	}
	add_action('wp_logout','wpui_redirect_users_logout');
}

//Redirect after registration
function wpui_login_register_redirect() {
	$wpui_login_register_redirect_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_register_redirect_option ) ) {
		foreach ($wpui_login_register_redirect_option as $key => $wpui_login_register_redirect_value)
			$options[$key] = $wpui_login_register_redirect_value;
		if (isset($wpui_login_register_redirect_option['wpui_login_register_redirect'])) {
			return $wpui_login_register_redirect_option['wpui_login_register_redirect'];
		}
	}
};

if (wpui_login_register_redirect() != '' ) {
	function wpui_redirect_users_registration() {
	     return wpui_login_register_redirect();
	}
	add_filter('registration_redirect','wpui_redirect_users_registration');
}

//Disable Email login
function wpui_login_disable_email() {
	$wpui_login_disable_email_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_disable_email_option ) ) {
		foreach ($wpui_login_disable_email_option as $key => $wpui_login_disable_email_value)
			$options[$key] = $wpui_login_disable_email_value;
		if (isset($wpui_login_disable_email_option['wpui_login_disable_email'])) {
			return $wpui_login_disable_email_option['wpui_login_disable_email'];
		}
	}
};

if (wpui_login_disable_email() == '1' ) {
	remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );
}