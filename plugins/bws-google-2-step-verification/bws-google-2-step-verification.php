<?php
/*
Plugin Name: 2-Step Verification by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/google-2-step-verification/
Description: Stronger security solution which protects your WordPress website from hacks and unauthorized login attempts.
Author: BestWebSoft
Text Domain: bws-google-2-step-verification
Domain Path: /languages
Version: 1.1.0
Author URI: https://bestwebsoft.com/
License: GPLv3 or later
*/

/*  © Copyright 2021  BestWebSoft  ( https://support.bestwebsoft.com )

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Add plugin settings page to the dashboard menu */
if ( ! function_exists( 'gglstpvrfctn_admin_menu' ) ) {
	function gglstpvrfctn_admin_menu() {
		global $submenu, $gglstpvrfctn_plugin_info, $wp_version;
		if ( ! is_plugin_active( 'bws-google-2-step-verification-pro/bws-google-2-step-verification-pro.php' ) ) {
			add_menu_page(
				'2-Step Verification', /* $page_title */
				'2-Step', /* $menu_title */
				'manage_options', /* $capability */
				'google-2-step-verification.php', /* $menu_slug */
				'gglstpvrfctn_settings_page', /* $callable_function */
				'none' /* $icon_url */
			);

			$settings = add_submenu_page(
				'google-2-step-verification.php', /* $parent_slug */
				'2-Step Verification', /* $page_title */
				__( 'Settings', 'bws-google-2-step-verification' ), /* $menu_title */
				'manage_options', /* $capability */
				'google-2-step-verification.php', /* $menu_slug */
				'gglstpvrfctn_settings_page' /* $callable_function */
			);

			add_submenu_page(
				'google-2-step-verification.php', /* $parent_slug */
				'BWS Panel', /* $page_title */
				'BWS Panel', /* $menu_title */
				'manage_options', /* $capability */
				'gglstpvrfctn-bws-panel', /* $menu_slug */
				'bws_add_menu_render' /* $callable_function */
			);

			/* Add "Go Pro" submenu link */
			if ( isset( $submenu['google-2-step-verification.php'] ) ) {
				$submenu['google-2-step-verification.php'][] = array(
					'<span style="color:#d86463"> ' . __( 'Upgrade to Pro', 'bws-google-2-step-verification' ) . '</span>',
					'manage_options',
					'https://bestwebsoft.com/products/wordpress/plugins/google-2-step-verification/?k=cafb0895a5730b761de64b55183d7a5b&pn=670&v=' . $gglstpvrfctn_plugin_info["Version"] . '&wp_v=' . $wp_version );
			}

			add_action( 'load-' . $settings, 'gglstpvrfctn_add_tabs' );
		}
	}
}

if ( ! function_exists( 'gglstpvrfctn_plugins_loaded' ) ) {
	function gglstpvrfctn_plugins_loaded() {
		/* Internationalization, first(!) */
		load_plugin_textdomain( 'bws-google-2-step-verification', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/* Initialization */
if ( ! function_exists( 'gglstpvrfctn_init' ) ) {
	function gglstpvrfctn_init() {
		global $gglstpvrfctn_plugin_info, $gglstpvrfctn_options, $gglstpvrfctn_enabled_methods;

		if ( empty( $gglstpvrfctn_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$gglstpvrfctn_plugin_info = get_plugin_data( __FILE__ );
		}

		/* add general functions */
		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		/* check compatible with current WP version */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $gglstpvrfctn_plugin_info, '4.5' );

		/* Get/Register and check settings for plugin */
		gglstpvrfctn_settings();

		$gglstpvrfctn_enabled_methods = array();
		foreach ( $gglstpvrfctn_options['methods'] as $method => $is_enabled ) {
			if ( $is_enabled ) {
				$gglstpvrfctn_enabled_methods[ $method ] = $method;
			}
		}

		if ( ! is_admin() && ! empty( $gglstpvrfctn_enabled_methods ) ) {
			add_action( 'login_enqueue_scripts', 'gglstpvrfctn_login_enqueue_scripts' );
			add_action( 'wp_enqueue_scripts', 'gglstpvrfctn_enqueue_scripts' );

			add_action( 'login_form', 'gglstpvrfctn_login_form' );
			add_action( 'gglstpvrfctn_custom_login_form', 'gglstpvrfctn_custom_login_form' );
			add_filter( 'authenticate', 'gglstpvrfctn_authenticate', 21, 1 );
            add_action( 'bp_login_widget_form', 'gglstpvrfctn_bp_form' );
            add_action( 'woocommerce_login_form', 'gglstpvrfctn_woocommerce_login_form' );

			/* Default reset password */
			add_action( 'lostpassword_form', 'gglstpvrfctn_reset_password_form' );
			add_filter( 'allow_password_reset', 'gglstpvrfctn_allow_password_reset' ); // also work in reset_pass woocommerce

			/* Default Register form */
			add_action( 'register_form', 'gglstpvrfctn_login_form', 99 );
			add_action( 'registration_errors', 'gglstpvrfctn_register_check', 10, 1 );
			if ( is_multisite() )
				add_action( 'signup_header', 'gglstpvrfctn_login_form', 99 );
		}
	}
}

/* Function for admin_init */
if ( ! function_exists( 'gglstpvrfctn_admin_init' ) ) {
	function gglstpvrfctn_admin_init() {
		/* Add variable for bws_menu */

		global $pagenow, $current_user,
			$bws_plugin_info,
			$gglstpvrfctn_plugin_info, $gglstpvrfctn_options, $gglstpvrfctn_enabled_methods;

		/* Function for bws menu */
		if ( empty( $bws_plugin_info ) ) {
			$bws_plugin_info = array( 'id' => '670', 'version' => $gglstpvrfctn_plugin_info["Version"] );
		}

		if ( ! empty( $gglstpvrfctn_enabled_methods ) && array_intersect( ( array )$current_user->roles, $gglstpvrfctn_options['enabled_roles'] ) ) {
			/* adding custom fields to the Edit User page so that admin would be able to disable verification for user if needed */
			add_action( 'show_user_profile', 'gglstpvrfctn_show_user_profile' );
			add_action( 'edit_user_profile', 'gglstpvrfctn_edit_user_profile' );
			/* update user profile information */
			add_action( 'personal_options_update', 'gglstpvrfctn_personal_options_update' );
			add_action( 'edit_user_profile_update', 'gglstpvrfctn_edit_user_profile_update' );
			add_action( 'user_profile_update_errors', 'gglstpvrfctn_user_profile_update_errors' );
			if ( isset( $_GET['action'] ) && 'gglstpvrfctn_download_codes' == $_GET['action'] ) {
				gglstpvrfctn_download_backup_codes();
			}
		}

		if ( 'plugins.php' == $pagenow ) {
			if ( function_exists( 'bws_plugin_banner_go_pro' ) )
				bws_plugin_banner_go_pro( $gglstpvrfctn_options, $gglstpvrfctn_plugin_info, 'gglstpvrfctn', 'google-2-step-verification', '2714538480080da6f027d0606a3c29ef', '670', 'bws-google-2-step-verification' );
		}
	}
}

if ( ! function_exists( 'gglstpvrfctn_settings' ) ) {
	function gglstpvrfctn_settings() {
		global $gglstpvrfctn_options, $gglstpvrfctn_plugin_info;

		/* Install the option defaults */
		if ( ! get_option( 'gglstpvrfctn_options' ) ) {
			$options_default = gglstpvrfctn_get_options_default();
			add_option( 'gglstpvrfctn_options', $options_default );
		}
		/* Get options from the database */
		$gglstpvrfctn_options = get_option( 'gglstpvrfctn_options' );

		if ( ! isset( $gglstpvrfctn_options['plugin_option_version'] ) || $gglstpvrfctn_options['plugin_option_version'] != $gglstpvrfctn_plugin_info["Version"] ) {
			$options_default = gglstpvrfctn_get_options_default();
			$gglstpvrfctn_options = array_replace_recursive( $options_default, $gglstpvrfctn_options );
			$gglstpvrfctn_options['plugin_option_version'] = $gglstpvrfctn_plugin_info["Version"];

			/* show pro features */
			$gglstpvrfctn_options['hide_premium_options'] = array();

			$update_option = true;
		}

		if ( is_multisite() ) {
			if ( ! get_site_option( 'gglstpvrfctn_key' ) ) {
				update_site_option( 'gglstpvrfctn_key', gglstpvrfctn_generate_code( 16, 'base32' ) );
			}
		} else {
			if ( ! get_option( 'gglstpvrfctn_key' ) ) {
				update_option( 'gglstpvrfctn_key', gglstpvrfctn_generate_code( 16, 'base32' ) );
			}
		}

		if ( isset( $update_option ) ) {
			update_option( 'gglstpvrfctn_options', $gglstpvrfctn_options );
		}
	}
}

if ( ! function_exists( 'gglstpvrfctn_get_options_default' ) ) {
	function gglstpvrfctn_get_options_default() {
		global $gglstpvrfctn_plugin_info, $wp_roles;

		$default_options = array(
			'plugin_option_version'			=> $gglstpvrfctn_plugin_info["Version"],
			'display_settings_notice'		=> 1,
			'display_hint_notice'			=> 1, /* Display notice about plugin settings page doesn't enable 2-Step verification, but profile settings do */
			'suggest_feature_banner'		=> 1,
			/* end general options */
			'methods'						=> array(
				'email'							=> 1,
				'authenticator'					=> 0,
				'backup_code'					=> 0,
				'sms'							=> 0,
                'question'						=> 0
			),
			'default_email_method'			=> 0,
			'authenticator_time_window'		=> 0, /* Time window for authenticator app in minutes. 0 - use default(30 sec) */
			'email_expiration'				=> 3, /* Email code expiration time in minutes */
			'notification_fail'				=> 0, /* Allow enabling notifications about failed attempts */
			'enabled_roles'					=> array_keys( $wp_roles->roles ), /* All roles are enabled by default */
			'notification_fail_email_subject'	=> __( 'Failed Verification Attempt', 'bws-google-2-step-verification' ),
			'notification_fail_email_message'	=> __( "Failed 2-Step verification attempt has just been registered at {site_url}.", 'bws-google-2-step-verification' ) . "\n\n" .
													__( "If it was you, just ignore this message.", 'bws-google-2-step-verification' ) . "\n" .
													__( 'You have received this message because failed attempt notifications are enabled for your account. You can manage your account settings on the following page', 'bws-google-2-step-verification' ) . ":\n" .
													"{profile_page}",
			'new_code_email_subject'			=> __( 'Verification Code', 'bws-google-2-step-verification' ),
			'new_code_email_message'			=> __( "Your verification code is {new_code}.", 'bws-google-2-step-verification' ) . "\n\n" .
													__( "You have received this message because someone has just tried to sign in to your account at {site_url} and requested 2-Step verification code.", 'bws-google-2-step-verification' ),
			'firebase'						=> array(
				'apikey'						=> ''
			)
		);

		return $default_options;
	}
}

if ( ! function_exists( 'gglstpvrfctn_plugin_activate' ) ) {
	function gglstpvrfctn_plugin_activate() {
		if ( is_multisite() ) {
			switch_to_blog( 1 );
			register_uninstall_hook( __FILE__, 'gglstpvrfctn_delete_options' );
			restore_current_blog();
		} else {
			register_uninstall_hook( __FILE__, 'gglstpvrfctn_delete_options' );
		}
	}
}

/**
 * Function to add stylesheets - icon for menu
 */
if ( ! function_exists( 'gglstpvrfctn_admin_head' ) ) {
	function gglstpvrfctn_admin_head() { ?>
		<style type="text/css">
			.menu-top.toplevel_page_google-2-step-verification .wp-menu-image {
				font-family: 'bwsicons' !important;
			}
			.menu-top.toplevel_page_google-2-step-verification .wp-menu-image:before {
				content: "\e93e";
				font-family: 'bwsicons' !important;
			}
		</style>
	<?php }
}

/**
 * 'authenticate' hook filter to check 2-Step auth code
 * @since 1.0.0
 * @param	object	$user	WP_User or WP_Error object
 * @return	object	$user	Returns WP_User object if verification is passed or WP_Error if not.
 */
if ( ! function_exists( 'gglstpvrfctn_authenticate' ) ) {
	function gglstpvrfctn_authenticate( $user ) {
		global $gglstpvrfctn_options;
		if ( ! ( $user instanceof WP_User ) ) {
			return $user;
		}
        // validation only for standard form
        if ( isset( $_POST['gglstpvrfctn_bp'] ) || isset( $_POST['gglstpvrfctn_wc'] ) ) {
            return $user;
        }

		if ( empty( $gglstpvrfctn_options ) ) {
			$gglstpvrfctn_options = gglstpvrfctn_get_options_default();
		}

		$email_init_time = get_user_meta( $user->ID, 'gglstpvrfctn_email_init_time', true );
		$email_expiration_time = intval( $email_init_time ) + intval( $gglstpvrfctn_options['email_expiration'] ) * 60;
		$current_time = time();

		/* User has enabled second step verification */
		if ( isset( $_REQUEST['gglstpvrfctn-request-email'] )
		|| ( isset( $_REQUEST['gglstpvrfctn-resend'] ) && $current_time > $email_init_time && $current_time <= $email_expiration_time ) ) {
			return gglstpvrfctn_request_email( $user->ID );
		}

		$code = isset( $_POST['gglstpvrfctn-code'] ) ? $_POST['gglstpvrfctn-code'] : '';

		$allow = gglstpvrfctn_verify_code( $user->ID, $code );

		return ( is_wp_error( $allow ) ) ? $allow : $user;
	}
}

/**
 * Function is called when the user clicks "request email code" button with disabled JS.
 * Checks if email sending is enabled in plugin settings and in user options and sends an email.
 * @since 1.0.0
 * @param	int		$user_id	ID of user, that should receive an email
 * @return	object	$result		Returns WP_Error object with success/fail message
 */
if ( ! function_exists( 'gglstpvrfctn_request_email' ) ) {
	function gglstpvrfctn_request_email( $user_id ) {
		global $gglstpvrfctn_enabled_methods, $gglstpvrfctn_user_options;
		
		if ( empty( $gglstpvrfctn_user_options ) ) {
			gglstpvrfctn_get_user_options( $user_id );
		}

		$result = new WP_Error;
		if ( isset( $gglstpvrfctn_enabled_methods['email'] ) && ! empty( $gglstpvrfctn_user_options['enabled'] ) && ! empty( $gglstpvrfctn_user_options['email'] ) ) {
			if ( gglstpvrfctn_send_email( $user_id, 'email_code' ) ) {
				$error_message = __( 'Verification code has been sent. Please check your email.', 'bws-google-2-step-verification' );
			} else {
				$error_message = __( 'Some problems occured during email sending.', 'bws-google-2-step-verification' );
			}
		} else {
			$error_message = __( 'Verification with email code is disabled for this user.', 'bws-google-2-step-verification' );
		}
		$result->add( 'gglstpvrfctn_email_message', $error_message );

		return $result;
	}
}
/**
 * Function checks the code for each available and enabled verification method.
 * Return true if no methods available or if the code is correct for one of methods and false otherwise
 * @since 1.0.0
 * @param	int				$user_id			ID of user, that checks the code
 * @param	string			$code				the code to check
 * @return	bool|object		true|WP_Error		Returns WP_Error object with fail message if code is wrong or true if no methods are available or the code is correct
 */
if ( ! function_exists( 'gglstpvrfctn_verify_code' ) ) {
	function gglstpvrfctn_verify_code( $user_id, $code = '' ) {
		global $gglstpvrfctn_options, $gglstpvrfctn_enabled_methods;
		$user_options = gglstpvrfctn_get_user_options( $user_id );

		if ( empty( $user_options['enabled'] ) || ( 1 == $gglstpvrfctn_options['default_email_method'] && 1 == $user_options['enabled'] && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'register' ) ) {
			return true;
		}

		$error = new WP_Error();

		$check = false;

		$code = trim( stripslashes( esc_html( $code ) ) );
		$code = str_replace( array( " ", "-", ".", "\t", "\n", "\0", "\x0B" ), '', $code );

		$backup_codes = get_user_meta( $user_id, 'gglstpvrfctn_backup_code' );

		foreach ( $gglstpvrfctn_enabled_methods as $method ) {
			if ( ! (
				empty( $user_options[ $method ] ) ||
				( 'authenticator' == $method && ! get_user_meta( $user_id, 'gglstpvrfctn_user_secret', true ) ) ||
				( 'backup_code' == $method && empty( $backup_codes ) )
			) ) {
				if ( 'sms' == $method ) {
					$check = true;
					$userdata = get_user_meta( $user_id , 'gglstpvrfctn_sms_check', true );
					if ( 'true' == $userdata ){
						delete_user_meta( $user_id, 'gglstpvrfctn_sms_check' );
						return true;
					}
				} else {
					$check = true;
					$check_function = "gglstpvrfctn_check_{$method}_code";
					if ( $check_function( $user_id, $code ) ) {
						return true;
					}
				}
			}
		}

		/* If no verification methods available for this user */
		if ( ! $check ) {
			return true;
		}

		/* Verification Failed */
		$error_message = sprintf(
			'<strong>%s</strong>:&nbsp;%s',
			__( 'ERROR', 'bws-google-2-step-verification' ),
			__( '2-Step verification failed.', 'bws-google-2-step-verification' )
		);
		$error->add( 'gglstpvrfctn_verification_failed', $error_message );

		/* Send failed attempt notification */
		if ( ! empty( $gglstpvrfctn_options['notification_fail'] ) && ! empty( $user_options['notification_fail'] ) ) {
			gglstpvrfctn_send_email( $user_id, 'notification_fail' );
		}

		return $error;
	}
}

/* Function to display the content of the plugin's admin page. */
if ( ! function_exists( 'gglstpvrfctn_settings_page' ) ) {
	function gglstpvrfctn_settings_page() {
		if ( ! class_exists( 'Bws_Settings_Tabs' ) )
            require_once( dirname( __FILE__ ) . '/bws_menu/class-bws-settings.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-gglstpvrfctn-settings.php' );
		$page = new Gglstpvrfctn_Settings_Tabs( plugin_basename( __FILE__ ) ); 
		if ( method_exists( $page,'add_request_feature' ) )
			$page->add_request_feature(); ?>
		<div class="wrap gglstpvrfctn-wrap">
			<h1>2-Step Verification <?php _e( 'Settings', 'bws-google-2-step-verification' ); ?></h1>
			<?php $page->display_content(); ?>
		</div>
	<?php }
}

if ( ! function_exists( 'gglstpvrfctn_enqueue_scripts' ) ) {
	function gglstpvrfctn_enqueue_scripts() {
		global $gglstpvrfctn_enabled_methods;
		if (
			! empty( $gglstpvrfctn_enabled_methods ) &&
			( defined( 'BWS_ENQUEUE_ALL_SCRIPTS' ) && BWS_ENQUEUE_ALL_SCRIPTS )
		) {
			gglstpvrfctn_login_enqueue_scripts();
		}
	}
}

/* Enqueue plugin scripts and styles in the admin panel */
if ( ! function_exists( 'gglstpvrfctn_admin_enqueue_scripts' ) ) {
	function gglstpvrfctn_admin_enqueue_scripts() {
		global $gglstpvrfctn_plugin_info, $current_user;
		$screen = get_current_screen();
		if ( 'profile' != $screen->id ) {
			/* Plugin settings page */
			if ( isset( $_GET['page'] ) && 'google-2-step-verification.php' == $_GET['page'] ) {
				bws_enqueue_settings_scripts();
				bws_plugins_include_codemirror();
				wp_enqueue_script( 'gglstpvrfctn_firebase_app_script', plugins_url( 'js/firebase/firebase-app.js', __FILE__ ) );
				wp_enqueue_script( 'gglstpvrfctn_firebase_auth_script', plugins_url( 'js/firebase/firebase-auth.js', __FILE__ ) );
				wp_enqueue_script( 'gglstpvrfctn_firebase_check_code_script', plugins_url( 'js/firebase/firebase-validate-code.js', __FILE__ ) );
			}
			wp_enqueue_style( 'gglstpvrfctn_stylesheet', plugins_url( 'css/admin-style.css', __FILE__ ), array(), $gglstpvrfctn_plugin_info['Version'] );
			wp_enqueue_script( 'gglstpvrfctn_admin_script', plugins_url( 'js/admin-script.js', __FILE__ ), array( 'jquery' ), $gglstpvrfctn_plugin_info['Version'] );

			$admin_script_vars = array(
				'invalid_sms_message'	=> __( 'SMS not sent. Your API key is invalid','bws-google-2-step-verification' ),
				'success_message'	=> __( 'The verification is successfully completed.','bws-google-2-step-verification' ),
				'invalid_message'	=>  __( 'The verification code is invalid.','bws-google-2-step-verification' )
			);
			wp_localize_script( 'gglstpvrfctn_admin_script', 'gglstpvrfctnAdminScriptVars', $admin_script_vars );
		} else {
			/* Profile settings page */
			wp_enqueue_style( 'gglstpvrfctn_profile_stylesheet', plugins_url( 'css/profile-style.css', __FILE__ ), array( 'dashicons' ), $gglstpvrfctn_plugin_info['Version'] );
			wp_enqueue_script( 'gglstpvrfctn_jquery_qr', plugins_url( 'js/qr-code.js', __FILE__ ), array( 'jquery' ), $gglstpvrfctn_plugin_info['Version'] );
			wp_enqueue_script( 'gglstpvrfctn_profile_script', plugins_url( 'js/profile-script.js', __FILE__ ), array( 'jquery', 'gglstpvrfctn_jquery_qr' ), $gglstpvrfctn_plugin_info['Version'] );
			
			$profile_script_vars = array(
				'ajax_nonce'	=> wp_create_nonce( 'gglstpvrfctn_ajax_nonce_value' ),
				'username'		=> $current_user->user_login,
				'hostname'		=> $_SERVER['HTTP_HOST']
			);
			wp_localize_script( 'gglstpvrfctn_profile_script', 'gglstpvrfctnScriptVars', $profile_script_vars );
		}
	}
}

/* Enqueue plugin scripts and styles for the login form */
if ( ! function_exists( 'gglstpvrfctn_login_enqueue_scripts' ) ) {
	function gglstpvrfctn_login_enqueue_scripts() {
		global $gglstpvrfctn_plugin_info;

		wp_enqueue_style( 'gglstpvrfctn_login_stylesheet', plugins_url( 'css/style.css', __FILE__ ), array(), $gglstpvrfctn_plugin_info['Version'] );
		wp_enqueue_script( 'gglstpvrfctn_login_script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery' ), $gglstpvrfctn_plugin_info['Version'] );
		wp_enqueue_script( 'gglstpvrfctn_firebase_app_script', plugins_url( 'js/firebase/firebase-app.js', __FILE__ ) );
		wp_enqueue_script( 'gglstpvrfctn_firebase_auth_script', plugins_url( 'js/firebase/firebase-auth.js', __FILE__ ) );
		wp_enqueue_script( 'gglstpvrfctn_firebase_check_code_script', plugins_url( 'js/firebase/firebase-validate-code.js', __FILE__ ) );

		$login_script_vars = array(
			'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
			'ajax_nonce'			=> wp_create_nonce( 'gglstpvrfctn_login_script' ),
			'custom_login_fields'	=> apply_filters( 'gglstpvrfctn_custom_login_fields', '' ), /* Custom Login fields, which value also could be used to get username/useremail */
			'resending_message'		=> __( 'Verification code has been resent. Please check your email.', 'bws-google-2-step-verification' ),
			'err_message'			=> __( 'Invalid SMS code.', 'bws-google-2-step-verification' )
		);
		wp_localize_script( 'gglstpvrfctn_login_script', 'gglstpvrfctnLoginVars', $login_script_vars );
	}
}

if ( ! function_exists ( 'gglstpvrfctn_plugin_banner' ) ) {
	function gglstpvrfctn_plugin_banner() {
		global $hook_suffix, $gglstpvrfctn_options, $gglstpvrfctn_enabled_methods, $gglstpvrfctn_plugin_info, $current_user;
		
		if ( 'plugins.php' == $hook_suffix ) {
			bws_plugin_banner_to_settings( $gglstpvrfctn_plugin_info, 'gglstpvrfctn_options', 'bws-google-2-step-verification', 'admin.php?page=google-2-step-verification.php' );
		}

		if ( isset( $_POST['gglstpvrfctn_hide_banner'] ) && ! defined( 'DOING_AJAX' ) ) {
			gglstpvrfctn_hide_settings_notice();
		}

		if ( isset( $_GET['page'] ) && 'google-2-step-verification.php' == $_GET['page'] ) {
			bws_plugin_suggest_feature_banner( $gglstpvrfctn_plugin_info, 'gglstpvrfctn_options', 'bws-google-2-step-verification' );
		} elseif ( 'profile.php' != $hook_suffix ) {
			$user_role_enabled = (
				! in_array( 'administrator', ( array )$current_user->roles ) &&
				!! array_intersect( ( array )$current_user->roles, $gglstpvrfctn_options['enabled_roles'] )
			);
			if (
				$user_role_enabled &&
				! empty( $gglstpvrfctn_enabled_methods ) &&
				! get_user_meta( $current_user->ID, 'gglstpvrfctn_hide_profile_banner' )
			) { ?>
				<div class="update-nag bws-notice notice is-dismissible below-h2 gglstpvrfctn-banner gglstpvrfctn-profile-banner" >
					<form class="gglstpvrfctn-banner-form" action="" method="post">
						<span>
							<?php printf(
								'%1$s <a href="%2$s">%3$s</a>.',
								__( 'Protect your personal account by enabling 2-step verification option on your profile page.', 'bws-google-2-step-verification' ),
								( is_multisite() ) ? admin_url( 'user/profile.php#gglstpvrfctn-enabled' ) : admin_url( 'profile.php#gglstpvrfctn-enabled' ),
								__( 'Go to my profile', 'bws-google-2-step-verification' )
							); ?>
						</span>
						<button class="notice-dismiss gglstpvrfctn-banner-dismiss" title="<?php _e( 'Close notice', 'bws-google-2-step-verification' ); ?>"></button>
						<input type="hidden" id="gglstpvrfctn_hide_banner" name="gglstpvrfctn_hide_banner" value="profile" />
						<input type="hidden" id="gglstpvrfctn_settings_nonce" name="gglstpvrfctn_settings_nonce" value="<?php echo wp_create_nonce( 'gglstpvrfctn-settings-nonce' ); ?>">
					</form>
				</div>
			<?php }
		}
	}
}

/* Write user metadata to not show plugin notice on "cross" button click */
if ( ! function_exists( 'gglstpvrfctn_hide_settings_notice' ) ) {
	function gglstpvrfctn_hide_settings_notice() {
		global $current_user;

		$update = false;
		if (
			isset( $_POST['gglstpvrfctn_settings_nonce'] ) &&
			!! wp_verify_nonce( $_POST['gglstpvrfctn_settings_nonce'], 'gglstpvrfctn-settings-nonce' ) &&
			isset( $_POST['gglstpvrfctn_hide_banner'] )
		) {
			if ( 'profile' == $_POST['gglstpvrfctn_hide_banner'] ) {
				update_user_meta( $current_user->ID, 'gglstpvrfctn_hide_profile_banner', 1 );
				$update = true;
			} elseif ( 'settings' == $_POST['gglstpvrfctn_hide_banner'] ) {
				update_user_meta( $current_user->ID, 'gglstpvrfctn_hide_settings_banner', 1 );
				$update = true;
			}
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && $update ) {
			echo "1";
			wp_die();
		}
	}
}

/**
 * Function to check the email code.
 * Returns false if code is incorrect or has expired, true otherwise.
 * If the code is correct or has expired, the function removes this code from usermeta.
 * @since 1.0.0
 * @param	int				$user_id			ID of user, that checks the code
 * @param	string			$user_code			the code to check
 * @return	bool			$result				Returns false if the code is incorrect or has expired, true otherwise
 */
if ( ! function_exists( 'gglstpvrfctn_check_email_code' ) ) {
	function gglstpvrfctn_check_email_code( $user_id = 0, $user_code = '' ) {
		global $gglstpvrfctn_options;
		$email_init_time = get_user_meta( $user_id, 'gglstpvrfctn_email_init_time', true );

		if ( ! $email_init_time ) {
			return false;
		}

		$email_expiration_time = intval( $email_init_time ) + intval( $gglstpvrfctn_options['email_expiration'] ) * 60;
		$current_time = time();

		$result = $clean = false;

		/* check if email code has not expired */
		if (
			0 == intval( $gglstpvrfctn_options['email_expiration'] ) ||
			( ( $current_time > $email_init_time ) && ( $current_time <= $email_expiration_time ) )
		) {
			/* email code has not expired */
			$key = is_multisite() ? get_site_option( 'gglstpvrfctn_key' ) : get_option( 'gglstpvrfctn_key' );
			$key .= $user_id;
			$email_code = get_user_meta( $user_id, 'gglstpvrfctn_email_code', true );
			if ( md5( $user_code . $key ) === $email_code ) {
				$result = $clean = true;
			}
		} else {
			/* email code has expired */
			$clean = true;
		}

		if ( $clean ) {
			/* Clean used and expired codes */
			delete_user_meta( $user_id, 'gglstpvrfctn_email_code' );
			delete_user_meta( $user_id, 'gglstpvrfctn_email_init_time' );
		}
		return $result;
	}
}

/**
 * Function to check the authenticator code.
 * Returns false if code is incorrect, true otherwise.
 * If the code is correct or has expired, the function removes this code from usermeta.
 * @since 1.0.0
 * @param	int				$user_id			ID of user, that checks the code
 * @param	string			$user_code			the code to check
 * @param	string			$test_secret		optional, used in case of testing new user secret. Used instead of the secret from db if set.
 * @return	bool			$result				Returns false if the code is incorrect, true otherwise
 */
if ( ! function_exists( 'gglstpvrfctn_check_authenticator_code' ) ) {
	function gglstpvrfctn_check_authenticator_code( $user_id = 0, $user_code = '', $test_secret = '' ) {
		global $gglstpvrfctn_options;
		if ( ! empty( $test_secret ) ) {
			$secret = $test_secret;
		} else {
			$secret = gglstpvrfctn_get_user_secret( intval( $user_id ) );
		}
		require_once( dirname( __FILE__ ) . '/includes/class-base32.php' );
		$base32 = new Base32( Base32::csRFC3548 );
		$scale = intval( $gglstpvrfctn_options['authenticator_time_window'] ); /* time window in minutes, a half on each side */

		try {
			$key = $base32->toString( $secret );
		} catch ( Exception $e ) {
			return false;
		}

		for ( $i = -$scale; $i <= $scale; $i++ ) {
			$time = floor( time() / 30 ) + $i;
			$current_counter = array( 0, 0, 0, 0, 0, 0, 0, 0 );
			for ( $counter = 7; $counter >= 0; $counter-- ) {
				$current_counter[ $counter ] = pack ( 'C*', $time );
				$time = $time >> 8;
			}
			$bin_counter = implode( $current_counter );

			if ( strlen( $bin_counter ) < 8 ) {
				$bin_counter = str_repeat( chr( 0 ), 8 - strlen( $bin_counter ) ) . $bin_counter;
			}

			$hash = hash_hmac( 'sha1', $bin_counter, $key );

			$length = 6;
			$hash_converted = array();
			foreach( str_split( $hash, 2 ) as $hex ) {
				$hash_converted[]= hexdec( $hex );
			}

			$offset = $hash_converted[ 19 ] & 0xf;

			$code = (
				( ( $hash_converted[ $offset + 0 ] & 0x7f ) << 24 ) |
				( ( $hash_converted[ $offset + 1 ] & 0xff ) << 16 ) |
				( ( $hash_converted[ $offset + 2 ] & 0xff ) << 8 ) |
				( $hash_converted[ $offset + 3 ] & 0xff )
			) % pow( 10, $length );

			if ( $code == $user_code ) {
				return true;
			}
		}

		return false;
	}
}

/**
 * Function to check the backup code.
 * Returns false if code is incorrect, true otherwise.
 * If the code is correct, the function removes this code from usermeta.
 * @since 1.0.0
 * @param	int				$user_id			ID of user, that checks the code
 * @param	string			$user_code			the code to check
 * @return	bool			$result				Returns false if the code is incorrect, true otherwise
 */
if ( ! function_exists( 'gglstpvrfctn_check_backup_code_code' ) ) {
	function gglstpvrfctn_check_backup_code_code( $user_id = 0, $user_code = '' ) {
		$backup_codes = get_user_meta( $user_id, 'gglstpvrfctn_backup_code' );
		if ( !! $backup_codes ) {
			foreach ( $backup_codes as $key => $encrypted_code ) {
				$decrypted_code = gglstpvrfctn_decode_string( $encrypted_code, $user_id );
				if ( $user_code == $decrypted_code ) {
					delete_user_meta( $user_id, 'gglstpvrfctn_backup_code', $encrypted_code );
					return true;
				}
			}
		}

		return false;
	}
}

/**
 * Generates random code of specified length and type
 * @since 1.0.0
 * @param	int				$length			Code length
 * @param	string			$type			Code type. Available values are: "letters", "numbers", "both". Otherwise base32 alphabet will be used.
 * @return	string			$code			Returns the code generated according to specified params
 */
if ( ! function_exists( 'gglstpvrfctn_generate_code' ) ) {
	function gglstpvrfctn_generate_code( $length = 16, $type = 'base32' ) {
		if ( 'numbers' == $type ) {
			$alphabet = '0123456789';
		} elseif ( 'letters' == $type ) {
			$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		} elseif ( 'both' == $type  ) {
			$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		} else {
			/* Base32 */
			$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
		}
		$chars = str_split( $alphabet );
		$count = strlen( $alphabet ) - 1;
		$code = '';
		for ( $i = 0; $i < $length ; $i++ ) {
			$code .= $chars[ rand( 0, $count ) ];
		}

		$code = strtoupper( $code );

		return $code;
	}
}

/**
 * Encrypts user string
 * @since 1.0.0
 * @param	string			$string			The string to encrypt
 * @return	string			$encrypted		Returns the encrypted string
 */
if ( ! function_exists( 'gglstpvrfctn_encode_string' ) ) {
	function gglstpvrfctn_encode_string( $string ) {
		global $current_user;

		$key = is_multisite() ? get_site_option( 'gglstpvrfctn_key' ) : get_option( 'gglstpvrfctn_key' );
		$key .= $current_user->ID;

		if ( function_exists( 'openssl_encrypt' ) ) {
			$iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( 'AES-128-CBC' ) );
			$data = $iv . openssl_encrypt( $string, 'AES-128-CBC', $key, 1, $iv );
		} else {
			/* string will be stored unencrypted */
			$data = $key . base64_encode( $string );
		}

		$encrypted = base64_encode( $data );

		return $encrypted;
	}
}

/**
 * Decrypts user string
 * @since 1.0.0
 * @param	string			$encrypted			The string to decrypt
 * @param	int				$user_id			ID of user, the string was encoded for
 * @return	string			$decrypted			Returns the decrypted string
 */
if ( ! function_exists( 'gglstpvrfctn_decode_string' ) ) {
	function gglstpvrfctn_decode_string( $encrypted, $user_id = 0 ) {
		$key = is_multisite() ? get_site_option( 'gglstpvrfctn_key' ) : get_option( 'gglstpvrfctn_key' );
		$key .= $user_id;

		$data = base64_decode( $encrypted );

		if ( function_exists( 'openssl_decrypt' ) ) {
			$iv_size = openssl_cipher_iv_length( 'AES-128-CBC' );
			$iv = substr( $data, 0, $iv_size );
			$data = substr( $data, $iv_size );
			$decrypted = openssl_decrypt( $data, 'AES-128-CBC', $key, 1, $iv );
		} else {
			/* string should be unencrypted */
			$data = substr( $data, strlen( $key ) );
			$decrypted = base64_decode( $data );
		}

		$decrypted = rtrim( $decrypted, "\x00..\x1F" );

		return $decrypted;
	}
}

/**
 * Gets user secret from meta and returns decrypted user secret
 * @since 1.0.0
 * @param	int				$user_id			ID of user, to get the secret for
 * @return	string|bool		$decrypted|false	Returns the decrypted user secret if exist, false otherwise
 */
if ( ! function_exists( 'gglstpvrfctn_get_user_secret' ) ) {
	function gglstpvrfctn_get_user_secret( $user_id = 0 ) {
		$meta_value = get_user_meta( $user_id, 'gglstpvrfctn_user_secret', true );

		if ( ! $meta_value ) {
			return false;
		}

		return gglstpvrfctn_decode_string( $meta_value, $user_id );
	}
}

/**
 * Get available backup codes. Returns false if no codes left or an array of decoded codes.
 * @since 1.0.0
 * @param	int				$user_id			ID of user, to get the backup codes for
 * @return	bool|array		false|$codes		Returns false if no codes left, array of decoded codes otherwise
 */
if ( ! function_exists( 'gglstpvrfctn_get_backup_codes' ) ) {
	function gglstpvrfctn_get_backup_codes( $user_id = 0 ) {
		$codes = get_user_meta( $user_id, 'gglstpvrfctn_backup_code' );

		if ( ! $codes ) {
			return false;
		}

		foreach ( $codes as $key => $code ) {
			$codes[ $key ] = gglstpvrfctn_decode_string( $code, $user_id );
		}

		return $codes;
	}
}

/**
 * Checks if the user gets correct code for specified secret via AJAX. Writes new encoded secret to usermeta and echoes 'SUCCESS' on success, or echoes 'WRONG_CODE' otherwise
 * @since 1.0.0
 * @param	void
 * @return	void
 */
if ( ! function_exists( 'gglstpvrfctn_test_secret' ) ) {
	function gglstpvrfctn_test_secret() {
		global $current_user;

		check_ajax_referer( 'gglstpvrfctn_ajax_nonce_value', 'gglstpvrfctn_nonce' );
		if ( isset( $_REQUEST['gglstpvrfctn_test_code'] ) && isset( $_REQUEST['gglstpvrfctn_secret'] ) ) {

			$user_code = trim( stripslashes( sanitize_text_field( $_REQUEST['gglstpvrfctn_test_code'] ) ) );
			$test_secret = trim( stripslashes( sanitize_text_field( $_REQUEST['gglstpvrfctn_secret'] ) ) );

			if ( gglstpvrfctn_check_authenticator_code( 0, $user_code, $test_secret ) ) {
				$encrypted_secret = gglstpvrfctn_encode_string( $test_secret );
				update_user_meta( $current_user->ID, 'gglstpvrfctn_user_secret', $encrypted_secret );
				echo "SUCCESS";
			} else {
				echo "WRONG_CODE";
			}
		}
		wp_die();
	}
}

/**
 * Get new secret via AJAX. Generates new secret code and echoes it.
 * @since 1.0.0
 * @param	void
 * @return	void
 */
if ( ! function_exists( 'gglstpvrfctn_get_new_secret' ) ) {
	function gglstpvrfctn_get_new_secret() {
		check_ajax_referer( 'gglstpvrfctn_ajax_nonce_value', 'gglstpvrfctn_nonce' );
		echo gglstpvrfctn_generate_code( 16, 'base32' );
		wp_die();
	}
}

/**
 * Generates new backup codes for current user, saves encrypted codes to usermeta and returns array of new plain codes.
 * @since 1.0.0
 * @param	void
 * @return	array	$codes		Returns an array of plain strings with new backup codes
 */
if ( ! function_exists( 'gglstpvrfctn_generate_backup_codes' ) ) {
	function gglstpvrfctn_generate_backup_codes() {
		global $current_user;

		$codes = array();
		delete_user_meta( $current_user->ID, 'gglstpvrfctn_backup_code' );
		for ( $i = 0; $i < 10 ; $i++ ) {
			$codes[ $i ] = gglstpvrfctn_generate_code( 8, 'numbers' );
			add_user_meta( $current_user->ID, 'gglstpvrfctn_backup_code', gglstpvrfctn_encode_string( $codes[ $i ] ) );
		}
		return $codes;
	}
}

/**
 * Generates new backup codes, saves encrypted codes to usermeta and echoes new codes on AJAX request.
 * @since 1.0.0
 * @param	void
 * @return	array	$codes		Returns an array of plain strings with new backup codes
 */
if ( ! function_exists( 'gglstpvrfctn_get_new_backup_codes' ) ) {
	function gglstpvrfctn_get_new_backup_codes() {
		check_ajax_referer( 'gglstpvrfctn_ajax_nonce_value', 'gglstpvrfctn_nonce' );

		$codes = gglstpvrfctn_generate_backup_codes();
		$result = '';
		foreach ( $codes as $code ) {
			$result .= "<tr><td>$code</td></tr>";
		}
		echo $result;
		wp_die();
	}
}

/**
 * Download available backup codes on URL request. Displays plain text document with available backup codes and forces it to be downloaded
 * @since 1.0.0
 * @param	void
 * @return	void
 */
if ( ! function_exists( 'gglstpvrfctn_download_backup_codes' ) ) {
	function gglstpvrfctn_download_backup_codes() {
		global $current_user;

		$nonce = isset( $_GET['gglstpvrfctn_nonce'] ) ? $_GET['gglstpvrfctn_nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'gglstpvrfctn_backup_codes_link' ) ) {
			wp_die();
		} else {
			$codes = gglstpvrfctn_get_backup_codes( $current_user->ID );

			if ( ! $codes ) {
				$content = __( "You have no backup codes.", 'bws-google-2-step-verification' );
			} else {
				$content = sprintf(
					__( 'Backup codes for %s', 'bws-google-2-step-verification' ),
					sprintf(
						"%s (%s):\n\n",
						$_SERVER['HTTP_HOST'],
						$current_user->user_email
					)
				);
				$i = 0;
				foreach ( $codes as $code ) {
					$i++;
					$content .= "{$i}.\t{$code}\n";
				}
			}

			$content .= "\n\n";
			$content .= __( "Visit the following page to generate new codes", 'bws-google-2-step-verification' ) . ":\n";
			$content .= admin_url( 'profile.php#gglstpvrfctn-generate-backup-codes' );

			header( 'Content-Type: text/plain' ); /* you can change this based on the file type */
			header( 'Content-Disposition: attachment; filename="backup_codes.txt"' );
			echo $content;
			exit();
		}
	}
}

/**
 * Get verification options status for specified user id
 * @since 1.0.0
 * @param	int		$user_id	ID of user, to get the verification options for
 * @return	array	array()		Returns an array of useroptions: array( 'enabled' => 1/0, 'methods' => array( 'email|authenticator|backup_code' => 1/0 ) )
 */
if ( ! function_exists( 'gglstpvrfctn_get_user_options' ) ) {
	function gglstpvrfctn_get_user_options( $user_id = 0 ) {
		global $gglstpvrfctn_options, $gglstpvrfctn_user_options;

		$user = get_userdata( $user_id );
		if ( ! array_intersect( ( array )$user->roles, $gglstpvrfctn_options['enabled_roles'] ) ) {
			return array( 'enabled' => 0 );
		}

		$gglstpvrfctn_user_options = get_user_meta( $user_id, "gglstpvrfctn_user_options", true );

		if ( ! $gglstpvrfctn_user_options ) {
			$gglstpvrfctn_user_options = array( 'enabled' => 0 );
		} else {
			$gglstpvrfctn_user_options = json_decode( $gglstpvrfctn_user_options, true );
		}
		if ( is_array( $gglstpvrfctn_user_options ) ) {
			return $gglstpvrfctn_user_options;
		}

		return array( 'enabled' => 0 );
	}
}

/**
 * Get verification status for specified user
 * @since 1.0.0
 * @param	int		$user_id		ID of user, to get the verification status for
 * @return	bool	true|false		Returns user options 'enabled' bool value
 */
if ( ! function_exists( 'gglstpvrfctn_is_verification_enabled' ) ) {
	function gglstpvrfctn_is_verification_enabled( $user_id = 0 ) {
		$userinfo = gglstpvrfctn_get_user_options( $user_id );
		return !! $userinfo['enabled'];
	}
}

/**
 * Sends user email with according theme and message.
 * @since 1.0.0
 * @param	int		$user_id		ID of user which email address would be used to send the message
 * @param	string	$origin			The origin of sending the email. Available values: "email_code", "notification_fail"
 * @return	bool	true|false		Returns true on email send seccess, false on fail
 */
if ( ! function_exists( 'gglstpvrfctn_send_email' ) ) {
	function gglstpvrfctn_send_email( $user_id = 0, $origin = '' ) {
		global $gglstpvrfctn_options;

		$user = get_userdata( $user_id );

		if ( ! $user instanceof WP_User ) {
			return false;
		}

		$args = array(
			'user_name'		=> $user->display_name,
			'user_email'	=> $user->user_email
		);

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$args['user_ip'] = filter_var( $_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$args['user_ip'] = filter_var( $_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP );
		} else {
			$args['user_ip'] = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP );
		}

		if ( 'email_code' == $origin ) {
			$email_code = gglstpvrfctn_generate_code( 8, 'numbers' );
			$key = is_multisite() ? get_site_option( 'gglstpvrfctn_key' ) : get_option( 'gglstpvrfctn_key' );
			$key .= $user_id;
			$code = md5( $email_code . $key );
			$current_time = time();
			update_user_meta( $user_id, 'gglstpvrfctn_email_code', $code );
			update_user_meta( $user_id, 'gglstpvrfctn_email_init_time', $current_time );

			$args['new_code']  = $email_code;

			$subject = $gglstpvrfctn_options['new_code_email_subject'];
			$message = $gglstpvrfctn_options['new_code_email_message'];
		} elseif ( 'notification_fail' == $origin ) {

			$subject = $gglstpvrfctn_options['notification_fail_email_subject'];
			$message = $gglstpvrfctn_options['notification_fail_email_message'];
		} else {
			return false;
		}

		$subject = gglstpvrfctn_replace_shortcodes( $subject );
		$message = gglstpvrfctn_replace_shortcodes( $message, $args );

		$headers[] = 'MIME-Version: 1.0' . "\r\n";
		$headers[] = 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers[] = "X-Mailer: PHP \r\n";
		$headers[] = 'From: ' . get_option( 'blogname' ) . ' < ' . get_bloginfo( 'admin_email' ) . '>' . "\r\n";

		$mail = wp_mail( $user->user_email, $subject, $message, $headers );
		if ( $mail ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'gglstpvrfctn_send_sms' ) ) {
	function gglstpvrfctn_send_sms( $user_id = 0, $origin = '' ) {
		$user = get_userdata( $user_id );
		if ( ! $user instanceof WP_User ) {
			return false;
		}
		if ( 'sms_code' == $origin ) {
			return true;
		}
		return false;
	}
}

/**
 * Sends user email with according theme and message.
 * @since 1.0.0
 * @param	string	$text		text template message with shortcodes
 * @param	array	$args		An array of values that the corresponding shortcodes should be replaced with
 * @return	string	$text		Returns text message with the shortcodes replaced
 */
if ( ! function_exists( 'gglstpvrfctn_replace_shortcodes' ) ) {
	function gglstpvrfctn_replace_shortcodes( $text, $args = array() ) {

		$new_code				= isset( $args['new_code'] ) ? $args['new_code'] : '';
		$user_name				= isset( $args['user_name'] ) ? $args['user_name'] : '';
		$user_email				= isset( $args['user_email'] ) ? $args['user_email'] : '';
		$site_name				= get_bloginfo( 'name' );
		$site_url				= site_url();
		$when					= current_time( 'mysql' );
		$ip						= isset( $args['user_ip'] ) ? $args['user_ip'] : '';
		$profile_page			= ( is_multisite() ) ? admin_url( 'user/profile.php' ) : admin_url( 'profile.php' );

		$text = preg_replace( "/\n/", "<br>", $text );
		$text = preg_replace( "/\{new_code\}/", $new_code, $text );
		$text = preg_replace( "/\{user_name\}/", $user_name, $text );
		$text = preg_replace( "/\{user_email\}/", $user_email, $text );
		$text = preg_replace( "/\{site_name\}/", $site_name, $text );
		$text = preg_replace( "/\{site_url\}/", $site_url, $text );
		$text = preg_replace( "/\{when\}/", $when, $text );
		$text = preg_replace( "/\{ip\}/", $ip, $text );
		$text = preg_replace( "/\{profile_page\}/", $profile_page , $text );

		return $text;
	}
}

/**
 * Serves for sending email code on AJAX request.
 * Retrieves user by provided login/email, checks if email sending is available and enabled for this user, writes new encrypted email code to usermeta and sends email to the user.
 * Echoes an array( 'result'=> 1/0, 'message'=>$succes_message ) in JSON
 * @since 1.0.0
 * @param	void
 * @return	void
 */
if ( ! function_exists( 'gglstpvrfctn_request_email_code' ) ) {
	function gglstpvrfctn_request_email_code() {
		check_ajax_referer( 'gglstpvrfctn_login_script', 'gglstpvrfctn_ajax_nonce' );
		global $gglstpvrfctn_enabled_methods;

		$result = array( 'result' => 0 );
		if ( isset( $gglstpvrfctn_enabled_methods['email'] ) && ! empty( $_POST['gglstpvrfctn_login'] ) ) {
			$login = sanitize_user( $_POST['gglstpvrfctn_login'] );
			$user = get_user_by( 'login', $login );
			if ( ! $user && strpos( $login, '@' ) ) {
				$user = get_user_by( 'email', $login );
			}
			if ( $user ) {
				$user_options = gglstpvrfctn_get_user_options( $user->ID );
				if ( ! empty( $user_options['enabled'] ) && ! empty( $user_options['email'] ) ) {
					if ( gglstpvrfctn_send_email( $user->ID, 'email_code' ) ) {
						$result['result'] = 1;
						$result['message'] = __( 'Verification code has been sent. Please check your email.', 'bws-google-2-step-verification' );
					}
				}
			}
		}
		echo json_encode( $result );
		wp_die();
	}
}

/**
 * Serves for sending sms code on AJAX request.
 */
if ( ! function_exists( 'gglstpvrfctn_request_sms_code' ) ) {
	function gglstpvrfctn_request_sms_code() {

		check_ajax_referer( 'gglstpvrfctn_login_script', 'gglstpvrfctn_ajax_nonce' );

		global $gglstpvrfctn_enabled_methods, $gglstpvrfctn_options;
		$result = array( 'result' => 0 );
		if ( isset( $gglstpvrfctn_enabled_methods['sms'] ) && ! empty( $_POST['gglstpvrfctn_login'] ) ) {
			$login = sanitize_user( $_POST['gglstpvrfctn_login'] );
			
			$user = get_user_by( 'login', $login );
			if ( ! $user && strpos( $login, '@' ) ) {
				$user = get_user_by( 'email', $login );
			}
			if ( $user ) {
				$user_options = gglstpvrfctn_get_user_options( $user->ID );
				if ( ! empty( $user_options['enabled'] ) && ! empty( $user_options['sms'] ) ) {
					if ( gglstpvrfctn_send_sms( $user->ID, 'sms_code' ) ) {
						$result['result'] = 1;
						$result['message'] = __( 'Verification code has been sent. Please check your phone.', 'bws-google-2-step-verification' );
						$result['phone'] = $user_options['phone'];
						$result['apikey'] = $gglstpvrfctn_options['firebase'][ 'apikey' ];
					}
				}
			}
		}
		echo json_encode( $result );
		wp_die();
	}
}

/**
 * Check via AJAX if verification option is enabled for user
 * Retrieves user by provided login/email, gets user verification options array and echoes it in JSON
 * @since 1.0.0
 * @param	void
 * @return	void
 */
if ( ! function_exists( 'gglstpvrfctn_check_verification_options' ) ) {
	function gglstpvrfctn_check_verification_options() {
		check_ajax_referer( 'gglstpvrfctn_login_script', 'gglstpvrfctn_ajax_nonce' );

		global $gglstpvrfctn_enabled_methods, $gglstpvrfctn_options;

		if ( empty( $gglstpvrfctn_options ) ) {
			$gglstpvrfctn_options = gglstpvrfctn_get_options_default();
		}

		$result = array( 'enabled' => 0, 'methods' => array(), 'expiration_time' => $gglstpvrfctn_options['email_expiration'] );

		if ( ! empty( $_POST['gglstpvrfctn_login'] ) ) {
			$login = sanitize_user( $_POST['gglstpvrfctn_login'] );
			
			$user = get_user_by( 'login', $login );
			if ( ! $user && strpos( $login, '@' ) ) {
				$user = get_user_by( 'email', $login );
			}
			if ( $user ) {
				$user_options = gglstpvrfctn_get_user_options( $user->ID );
				if ( ! empty( $user_options['enabled'] ) ) {
					foreach ( $gglstpvrfctn_enabled_methods as $method ) {
						if ( ! empty( $user_options[ $method ] ) ) {
							$result['methods'][] = $method;
						}
					}

					if ( ! empty( $result['methods'] ) ) {
						$result['enabled'] = 1;
					}
				}
			}
		}
		echo json_encode( $result );
		wp_die();
	}
}

/* Add Two Step fields to the Login form */
if ( ! function_exists( 'gglstpvrfctn_login_form' ) ) {
	function gglstpvrfctn_login_form() {
	    global $gglstpvrfctn_options; ?>
		<input type="submit" name="login" value="Submit" style="display: none;"><!-- fake button to be clicked when Enter key is pressed to submit the form -->
		<div class="gglstpvrfctn-login-wrap hidden">
			<p>
				<label for="gglstpvrfctn-code">
					<span><?php _e( '2-Step Verification value', 'bws-google-2-step-verification' ); ?></span>
					<br>
					<input type="text" id="gglstpvrfctn-code" name="gglstpvrfctn-code">
				</label>
			</p>
			<noscript><p class="gglstpvrfctn-description"><?php _e( 'Insert the code if you enabled 2-Step verification for your profile', 'bws-google-2-step-verification' ); ?></p></noscript>

            <?php if ( $gglstpvrfctn_options['methods']['question'] ) { ?>
                <button id="gglstpvrfctn-see-question" class="gglstpvrfctn-link-button gglstpvrfctn-see-question">
                    <?php _e( 'Show secret question', 'bws-google-2-step-verification' ); ?>
                </button >
                <div id="gglstpvrfctn-secret-question-container"></div>
            <?php } ?>

            <button class="gglstpvrfctn-request-email gglstpvrfctn-link-button hidden" name="gglstpvrfctn-request-email">
				<?php _e( 'Send me a code via email', 'bws-google-2-step-verification' ); ?>
			</button>

			<button class="gglstpvrfctn-request-sms gglstpvrfctn-link-button hidden" name="gglstpvrfctn-request-sms">
				<?php _e( 'Send me a code via sms', 'bws-google-2-step-verification' ); ?>
			</button>

			<div class="gglstpvrfctn-resending">
				<p><?php _e( 'The code has been already sent.', 'bws-google-2-step-verification' ); ?></p>
				<button class="button" id="gglstpvrfctn-resend" name="gglstpvrfctn-resend"><?php _e( 'Resend Code', 'bws-google-2-step-verification' ); ?></button>
				<button class="button" id="gglstpvrfctn-cancel"><?php _e( 'Cancel', 'bws-google-2-step-verification' ); ?></button>
			</div>

			<div class="clear gglstpvrfctn-clear"></div>
			<div id="gglstpvrfctn-recaptcha-container" style="transform: scale(0.9);-webkit-transform: scale(0.9);transform-origin :0 0;-webkit-transform-origin: 0 0;"></div>
		</div><!-- .gglstpvrfctn-login-wrap -->
	<?php }
}

/**
 *  Check question code for login, register or other forms
 *
 *  @param	int	    $user_id
 *  @param	string	$answer
 *
 * @return bool     response verification result
 */
if ( ! function_exists( 'gglstpvrfctn_check_question_code' ) ) {
	function gglstpvrfctn_check_question_code( $user_id, $answer ) {

		if ( empty ( $user_id ) || empty ( $answer ) ) {
			return false;
		}

		$answer_prepare = sanitize_text_field( $answer );

		/* get and unserialize data from user meta */
		$user_meta_question_data = get_user_meta( $user_id, 'gglstpvrfctn_secret_question_data', true );
		$question_data = unserialize( $user_meta_question_data );

		/* check answer */
        if ( (string) $answer_prepare === $question_data['answer'] ) {
            return true;
        }

        return false;
	}
}

/* Add Two Step fields to the custom Login form */
if ( ! function_exists( 'gglstpvrfctn_custom_login_form' ) ) {
	function gglstpvrfctn_custom_login_form() {
		gglstpvrfctn_login_enqueue_scripts();
		gglstpvrfctn_login_form();
	}
}

/* Add hidden input to BuddyPress Login form */
if ( ! function_exists( 'gglstpvrfctn_bp_form' ) ) {
    function gglstpvrfctn_bp_form() { ?>
        <input type="hidden" name="gglstpvrfctn_bp" value="1"/>
    <?php }
}

/* Add Two Step fields to the WooCommerce Login form */
if ( ! function_exists( 'gglstpvrfctn_woocommerce_login_form' ) ) {
    function gglstpvrfctn_woocommerce_login_form() { ?>
        <input type="hidden" name="gglstpvrfctn_wc" value="1"/>
    <?php }
}

/* Adding "2-Step Verification" block to the Edit current User page */
if ( ! function_exists( 'gglstpvrfctn_show_user_profile' ) ) {
	function gglstpvrfctn_show_user_profile() {
		global $gglstpvrfctn_options, $gglstpvrfctn_enabled_methods, $current_user;

		/* If verification is disabled */
		if ( ! array_intersect( ( array )$current_user->roles, $gglstpvrfctn_options['enabled_roles'] ) ) {
			return;
		}

		/* Retrieving user options */
		$user_options = gglstpvrfctn_get_user_options( $current_user->ID );
		$enabled = isset( $user_options['enabled'] ) ? !! $user_options['enabled'] : false;

		/* Check Authenticator Secret */
		$secret_is_verified	= !! gglstpvrfctn_get_user_secret( $current_user->ID );
		$verified_style		= $secret_is_verified ? '' : 'style="display: none;"';
		$not_verified_style	= ! $secret_is_verified ? '' : 'style="display: none;"';
		$new_secret = gglstpvrfctn_generate_code( 16, 'base32' );

		/* Check backup codes */
		$backup_codes = gglstpvrfctn_get_backup_codes( $current_user->ID );
		if ( ! $backup_codes ) {
			$backup_codes = array();
		}

		/* Check question codes */
        $user_meta_question_data = get_user_meta( $current_user->ID, 'gglstpvrfctn_secret_question_data', true );
        $question_data = unserialize( $user_meta_question_data );

		$codes_count = count( $backup_codes );
		$no_codes_style = ( empty( $codes_count ) ) ? "display: none;" : "";
		$download_url = admin_url( 'admin.php?action=gglstpvrfctn_download_codes&gglstpvrfctn_nonce=' . wp_create_nonce( 'gglstpvrfctn_backup_codes_link' ) ); ?>
		<h2><?php _e( '2-Step Verification', 'bws-google-2-step-verification' ); ?></h2>
		<table class="form-table">
			<tr>
				<th>
					<?php _e( 'Verification', 'bws-google-2-step-verification' ); ?>
				</th>
				<td>
					<label>
						<input type="checkbox" name="gglstpvrfctn-enabled" id="gglstpvrfctn-enabled" value="1" <?php checked( $enabled ); ?> >
						<span class="bws_info">
							<?php _e( 'Enable to activate 2-Step Verification.', 'bws-google-2-step-verification' ); ?>
						</span>
					</label>
				</td>
			</tr>
		</table>
		<div id="gglstpvrfctn-settings-wrapper">
			<table class="form-table">
				<tr>
					<th><?php _e( 'Verification Methods', 'bws-google-2-step-verification' ); ?></th>
					<td>
						<fieldset>
							<?php if ( isset( $gglstpvrfctn_enabled_methods['email'] ) ) { ?>
								<label>
									<input type="checkbox" class="gglstpvrfctn-methods" name="gglstpvrfctn_email" data-method="email" value="1" <?php checked( ! empty( $user_options[ 'email' ] ) ); ?> >
									<span><?php _e( 'Email', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
							<?php }
							if ( isset( $gglstpvrfctn_enabled_methods['authenticator'] ) ) { ?>
								<label>
									<input type="checkbox" class="gglstpvrfctn-methods" name="gglstpvrfctn_authenticator" data-method="authenticator" value="1" <?php checked( ! empty( $user_options[ 'authenticator' ] ) ); ?> >
									<span><?php _e( 'Authenticator app', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
							<?php }
							if ( isset( $gglstpvrfctn_enabled_methods['backup_code'] ) ) { ?>
								<label>
									<input type="checkbox" class="gglstpvrfctn-methods" name="gglstpvrfctn_backup_code" data-method="backup_code" value="1" <?php checked( ! empty( $user_options[ 'backup_code' ] ) ); ?> >
									<span><?php _e( 'Backup codes', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
							<?php }
                            if ( isset( $gglstpvrfctn_enabled_methods['question'] ) ) { ?>
                                <label>
                                    <input type="checkbox" class="gglstpvrfctn-methods" name="gglstpvrfctn_question" data-method="question" value="1" <?php checked( ! empty( $user_options[ 'question' ] ) ); ?> >
                                    <span><?php _e( 'Secret question', 'bws-google-2-step-verification' ); ?></span>
                                </label><br>
                            <?php }
							if ( isset( $gglstpvrfctn_enabled_methods['sms'] ) ) { ?>
								<label>
									<input type="checkbox" class="gglstpvrfctn-methods" name="gglstpvrfctn_sms" data-method="sms" value="1" <?php checked( ! empty( $user_options[ 'sms' ] ) ); ?> >
									<span><?php _e( 'SMS code', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
								<label id="gglstpvrfctn-phone-label">
									<span><?php _e( 'Phone number', 'bws-google-2-step-verification' ); ?></span><br>
									<input type="tel" class="gglstpvrfctn-userphone" name="gglstpvrfctn_phone" required="required" placeholder="+1234567890" value="<?php if ( ! empty( $user_options[ 'phone' ] ) ) {  echo $user_options[ 'phone' ]; } ?>">
									<span class="gglstpvrfctn-error"><?php _e( 'A valid phone number is required', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
							<?php } ?>
						</fieldset>
					</td>
				</tr>
				<?php if ( ! empty( $gglstpvrfctn_options['notification_fail'] ) ) { ?>
					<tr class="gglstpvrfctn-personal-settings">
						<th><?php _e( 'Failed Attempt Notifications', 'bws-google-2-step-verification' ); ?></th>
						<td>
							<label>
								<input type="checkbox" id="gglstpvrfctn_notification_fail" name="gglstpvrfctn_notification_fail" value="1" <?php checked( ! empty( $user_options[ 'notification_fail' ] ) ); ?> >
								<span class="bws_info"><?php _e( 'Enable if you want to receive email notifications about each failed verification attempt.', 'bws-google-2-step-verification' ); ?></span>
							</label>
						</td>
					</tr>
				<?php } ?>
			</table>
			<?php if ( isset( $gglstpvrfctn_enabled_methods['authenticator'] ) ) { ?>
				<input type="submit" name="gglstpvrfctn-check-code" value="<?php echo $new_secret; ?>" style="display: none;">
				<div id="gglstpvrfctn_wrapper_authenticator">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e( 'Authenticator App', 'bws-google-2-step-verification' ); ?></th>
							<td>
								<fieldset>
									<div id="gglstpvrfctn-status">
										<span id="gglstpvrfctn-not-verified" <?php echo $not_verified_style; ?>>
											<?php _e( 'Not verified', 'bws-google-2-step-verification' ); ?>
										</span>
										<span id="gglstpvrfctn-verified" <?php echo $verified_style; ?> >
											<?php _e( 'Verified', 'bws-google-2-step-verification' ); ?>
										</span>
										<button id="gglstpvrfctn-get-new-secret" class="gglstpvrfctn-link-button" name="gglstpvrfctn-get-new-secret" <?php echo $verified_style; ?>>
											<?php _e( 'Edit', 'bws-google-2-step-verification' ); ?>
										</button>
										<button id="gglstpvrfctn-cancel-new-secret" class="gglstpvrfctn-link-button" name="gglstpvrfctn-cancel-new-secret">
											<?php _e( 'Cancel', 'bws-google-2-step-verification' ); ?>
										</button>
									</div>
									<div id="gglstpvrfctn-test-success-message">
										<?php _e( 'Done! Your Authenticator app is set.', 'bws-google-2-step-verification' ); ?>
									</div>
									<div id="gglstpvrfctn-secret-block" <?php echo $not_verified_style; ?>>
										<ol>
											<li>
												<?php printf(
													__( 'Download and install Authenticator application on your %s, %s or %s device.', 'bws-google-2-step-verification' ),
														'<a href="https://itunes.apple.com/app/google-authenticator/id388497605" target="_blank">iOS</a>',
														'<a href="https://play.google.com/store/search?q=authenticator+totp" target="_blank">Android</a>',
														'<a href="https://www.microsoft.com/store/p/microsoft-authenticator/9nblgggzmcj6" target="_blank">Windows Phone</a>'
												); ?>
											</li>
											<li>
												<div class="hide-if-no-js">
													<?php _e( 'Scan the following QR code using Authenticator application.', 'bws-google-2-step-verification' ); ?>
													<div id="gglstpvrfctn-qr-link" data-gglstpvrfctn-secret="<?php echo $new_secret; ?>"></div>
													<a href="#" id="gglstpvrfctn-cannot-scan"><?php _e( "Can't scan the QR code?", 'bws-google-2-step-verification' ); ?></a>
												</div>
												<div id="gglstpvrfctn-secret-manual">
													<p><?php _e( 'Enter the secret key below into your Authenticator application manually', 'bws-google-2-step-verification' ); ?>:</p>
													<p id="gglstpvrfctn-new-secret"><strong><?php echo chunk_split( $new_secret, 4, '&nbsp;' ); ?></strong></p>
												</div>
											</li>
											<li>
												<noscript>
													<?php _e( 'Once you have entered the secret key, enter and verify the 6-digit verification code generated by the Authenticator app', 'bws-google-2-step-verification' ); ?>:
												</noscript>
												<span class="hide-if-no-js"><?php _e( 'Once you have scanned the QR code (or entered the secret key manually), enter and verify the 6-digit verification code generated by the Authenticator app', 'bws-google-2-step-verification' ); ?>:</span>
												<div id="gglstpvrfctn-check-wrap">
													<input type="text" name="gglstpvrfctn-code-test" class="bws_no_bind_notice" id="gglstpvrfctn-code-test" placeholder="XXXXXX">
													<button class="button button-small" id="gglstpvrfctn-check-code" name="gglstpvrfctn-check-code" value="<?php echo $new_secret; ?>"><?php _e( 'Verify', 'bws-google-2-step-verification' ); ?></button>
												</div>
												<div id="gglstpvrfctn-test-fail-message">
													<?php _e( 'Wrong code, try again.', 'bws-google-2-step-verification' ); ?>
												</div>
											</li>
										</ol>
									</div>
								</fieldset>
							</td>
						</tr>
					</table>
				</div><!-- #gglstpvrfctn_wrapper_authenticator -->
			<?php }
			if ( isset( $gglstpvrfctn_enabled_methods['backup_code'] ) ) {  ?>
				<div id="gglstpvrfctn_wrapper_backup_code">
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e( 'Backup Codes', 'bws-google-2-step-verification' ); ?></th>
							<td>
								<div class="gglstpvrfctn-codes-count-wrapper">
									<span>
										<?php printf(
											__( 'Codes left: %s', 'bws-google-2-step-verification' ),
											sprintf(
												'<strong><span class="gglstpvrfctn-codes-count%1$s" data-gglstpvrfctn-codes-count="%2$s">%2$s</span></strong>',
												( 4 > $codes_count ) ? ' gglstpvrfctn-few-codes' : '',
												$codes_count
											)
										); ?>
									</span>&nbsp;
									<a href="<?php echo $download_url; ?>" id="gglstpvrfctn-download-codes" class="button" style="<?php echo $no_codes_style; ?>">
										<?php _e( 'Download as TXT', 'bws-google-2-step-verification' ); ?>
									</a>
									<button id="gglstpvrfctn-generate-backup-codes" class="button" name="gglstpvrfctn-generate-backup-codes">
										<?php _e( 'Generate New Codes', 'bws-google-2-step-verification' ); ?>
									</button>
								</div>
								<div class="bws_info">
									<?php _e( 'Each Backup code can be used only once. You should keep it somewhere safe but accessible.', 'bws-google-2-step-verification' ); ?>
								</div>
								<button id="gglstpvrfctn-toggle-codes" class="gglstpvrfctn-link-button hide-if-no-js" data-show-text="<?php _e( 'Show Codes', 'bws-google-2-step-verification' ); ?>" data-hide-text="<?php _e( 'Hide Codes', 'bws-google-2-step-verification' ); ?>" style="<?php echo $no_codes_style; ?>">
									<?php _e( 'Show Codes', 'bws-google-2-step-verification' ); ?>
								</button>
								<div id="gglstpvrfctn-backup-codes-wrapper">
									<table id="gglstpvrfctn-backup-codes-list">
										<?php foreach ( $backup_codes as $code ) { ?>
											<tr>
												<td><?php echo $code; ?></td>
											</tr>
										<?php } ?>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</div><!-- #gglstpvrfctn_wrapper_backup_code -->
			<?php }
            if ( isset( $gglstpvrfctn_enabled_methods['question'] ) ) {  ?>
                <div id="gglstpvrfctn_wrapper_question">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'Secret question : ', 'bws-google-2-step-verification' ); ?></th>
                            <td>
                                <fieldset>
                                    <!-- Edit and Cancel buttons -->
                                    <button id="gglstpvrfctn-update-question-data" class="gglstpvrfctn-link-button" name="gglstpvrfctn-update-quesiton-data">
		                                <?php _e( 'Edit question data', 'bws-google-2-step-verification' ); ?>
                                    </button>
                                    <button style="display: none;" id="gglstpvrfctn-cancel-question-data" class="gglstpvrfctn-link-button" name="gglstpvrfctn-cancel-quesiton-data">
		                                <?php _e( 'Cancel', 'bws-google-2-step-verification' ); ?>
                                    </button>

                                    <!-- question and answer inputs -->
                                    <div id="gglstpvrfctn-question-data" style="display: none;">

                                        <label><?php _e( 'Enter your secret question :', 'bws-google-2-step-verification' )?><br>
                                            <input type="text" id="gglstpvrfctn-secret-question" value="<?php echo $question_data['question'] ?>">
                                        </label><br>

                                        <label><?php _e( 'Enter answer for secret question :', 'bws-google-2-step-verification' )?><br>
                                            <input type="password" id="gglstpvrfctn-secret-answer" value="<?php echo $question_data['answer'] ?>">
                                            <span toggle="#password-field" class="dashicons dashicons-hidden gglstpvrfctn-toggle-password"></span>
                                        </label><br>

                                        <div>
                                            <button class="button" id="gglstpvrfctn-save-question-data"><?php _e( 'Save', 'bws-google-2-step-verification' )?></button>
                                            <span id="gglstpvrfctn-question-notice-save"></span>
                                        </div>

                                    </div>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php }?>
		</div><!-- #gglstpvrfctn-settings-wrapper -->
	<?php }
}

/* Adding "2-Step Verification" block to the Edit User page */
if ( ! function_exists( 'gglstpvrfctn_edit_user_profile' ) ) {
	function gglstpvrfctn_edit_user_profile() {
		global $gglstpvrfctn_options, $gglstpvrfctn_enabled_methods, $current_user;

		$user_id = isset( $_REQUEST['user_id'] ) ? intval( $_REQUEST['user_id'] ) : $current_user->ID;
		$user = ( $user_id == $current_user->ID ) ? $current_user : get_userdata( $user_id );

		/* If verification is disabled */
		if ( ! array_intersect( ( array )$user->roles, $gglstpvrfctn_options['enabled_roles'] ) ) {
			return;
		}

		$user_options = gglstpvrfctn_get_user_options( $user_id );
		$enabled = isset( $user_options['enabled'] ) ? !! $user_options['enabled'] : false; ?>

		<h2><?php _e( '2-Step Verification', 'bws-google-2-step-verification' ); ?></h2>
		<table class="form-table">
			<tr>
				<th>
					<?php _e( 'Verification', 'bws-google-2-step-verification' ); ?>
				</th>
				<td>
					<label>
						<input type="checkbox" class="gglstpvrfctn-enabled  bws_option_affect" name="gglstpvrfctn-enabled" id="gglstpvrfctn-enabled" data-affect-show="#gglstpvrfctn-settings-wrapper" value="1" <?php checked( $enabled ); ?> >
						<span class="bws_info">
							<?php _e( 'Enable if you want the user to use 2-Step Verification.', 'bws-google-2-step-verification' ); ?>
						</span>
					</label>
				</td>
			</tr>
		</table>
		<div id="gglstpvrfctn-settings-wrapper">
			<table class="form-table">
				<tr>
					<th><?php _e( 'Verification Methods', 'bws-google-2-step-verification' ); ?></th>
					<td>
						<fieldset>
							<?php if ( isset( $gglstpvrfctn_enabled_methods['email'] ) ) { ?>
								<label>
									<input type="checkbox" class="gglstpvrfctn-methods" name="gglstpvrfctn_email" data-method="email" value="1" <?php checked( ! empty( $user_options[ 'email' ] ) ); ?> >
									<span><?php _e( 'Email', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
							<?php }
							if ( isset( $gglstpvrfctn_enabled_methods['sms'] ) ) { ?>
								<label>
									<input type="checkbox" class="gglstpvrfctn-methods bws_option_affect" name="gglstpvrfctn_sms" data-affect-show="#gglstpvrfctn-phone-label" data-method="sms" value="1" <?php checked( ! empty( $user_options[ 'sms' ] ) ); ?> >
									<span><?php _e( 'SMS code', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
								<label id="gglstpvrfctn-phone-label">
									<span><?php _e( 'Phone number', 'bws-google-2-step-verification' ); ?></span><br>
									<input type="tel" class="gglstpvrfctn-userphone" name="gglstpvrfctn_phone" required="required" placeholder="+1234567890" value="<?php if ( ! empty( $user_options[ 'phone' ] ) ) {  echo $user_options[ 'phone' ]; } ?>">
									<span class="gglstpvrfctn-error"><?php _e( 'A valid phone number is required', 'bws-google-2-step-verification' ); ?></span>
								</label><br>
							<?php } ?>
						</fieldset>
					</td>
				</tr>
			</table>
		</div><!-- #gglstpvrfctn-settings-wrapper -->
	<?php }
}

/* updating user information */
if ( ! function_exists( 'gglstpvrfctn_personal_options_update' ) ) {
	function gglstpvrfctn_personal_options_update() {
		global $gglstpvrfctn_options, $gglstpvrfctn_enabled_methods, $current_user;

		/* If verification is disabled */
		if ( ! array_intersect( ( array )$current_user->roles, $gglstpvrfctn_options['enabled_roles'] ) ) {
			return;
		}

		$is_question_data_empty = gglstpvrfctn_get_question_data( $current_user->ID );
		$user_secret = gglstpvrfctn_get_user_secret( $current_user->ID );

		if ( isset( $_POST['gglstpvrfctn-check-code'] ) ) {
			$secret = sanitize_text_field( $_POST['gglstpvrfctn-check-code'] );
			$user_code = trim( sanitize_text_field( str_replace( " ", "", $_POST['gglstpvrfctn-code-test'] ) ) );
			if ( gglstpvrfctn_check_authenticator_code( $current_user->ID, $user_code, $secret ) ) {
				$user_secret = $secret;
				update_user_meta( $current_user->ID, 'gglstpvrfctn_user_secret', gglstpvrfctn_encode_string( $secret ) );
			}
		}

		$user_options = gglstpvrfctn_get_user_options( $current_user->ID );

		if ( isset( $_POST['gglstpvrfctn-generate-backup-codes'] ) ) {
			$backup_codes = gglstpvrfctn_generate_backup_codes();
		} else {
			$backup_codes = get_user_meta( $current_user->ID, 'gglstpvrfctn_backup_code' );
		}

		if ( isset( $_POST['gglstpvrfctn-get-new-secret'] ) ) {
			delete_user_meta( $current_user->ID, 'gglstpvrfctn_user_secret' );
		}

		$methods_count = 0;
		foreach ( $gglstpvrfctn_enabled_methods as $method ) {
			if ( isset( $_REQUEST["gglstpvrfctn_{$method}"] ) ) {
				$user_options[ $method ] = 1;
				$methods_count++;
			} else {
				$user_options[ $method ] = 0;
			}
		}

		if (
			empty( $methods_count ) ||
			! (
				( isset( $gglstpvrfctn_enabled_methods['email'] ) && ! empty( $user_options['email'] ) ) ||
				( isset( $gglstpvrfctn_enabled_methods['authenticator'] ) && ! empty( $user_options['authenticator'] ) && ! empty( $user_secret ) ) ||
				( isset( $gglstpvrfctn_enabled_methods['backup_code'] ) && ! empty( $user_options['backup_code'] ) && ! empty( $backup_codes ) ) ||
				( isset( $gglstpvrfctn_enabled_methods['sms'] ) && ! empty( $user_options['sms'] ) ) ||
				( isset( $gglstpvrfctn_enabled_methods['question'] ) && isset( $user_options['question'] ) && ! $is_question_data_empty )
			)
		) {
			$user_options['enabled'] = 0;
		} else {
			$user_options['enabled'] = isset( $_REQUEST["gglstpvrfctn-enabled"] ) ? 1 : 0;
		}

		if ( ! empty( $gglstpvrfctn_options['notification_fail'] ) ) {
			$user_options['notification_fail'] = isset( $_REQUEST["gglstpvrfctn_notification_fail"] ) ? 1 : 0;
		}
		if ( isset( $_POST['gglstpvrfctn_phone'] ) ) {
			$user_options['phone'] = sanitize_text_field( $_POST['gglstpvrfctn_phone'] );
		}
		update_user_meta( $current_user->ID, 'gglstpvrfctn_user_options', json_encode( $user_options ) );
	}
}

/* updating user information */
if ( ! function_exists( 'gglstpvrfctn_edit_user_profile_update' ) ) {
	function gglstpvrfctn_edit_user_profile_update() {
		global $gglstpvrfctn_options, $current_user;

		$user_id = isset( $_REQUEST['user_id'] ) ? intval( $_REQUEST['user_id'] ) : $current_user->ID;
		$user = ( $user_id == $current_user->ID ) ? $current_user : get_userdata( $user_id );

		/* If verification is disabled */
		if ( ! array_intersect( ( array )$user->roles, $gglstpvrfctn_options['enabled_roles'] ) ) {
			return;
		}

		$user_options = gglstpvrfctn_get_user_options( $user_id );

		$user_options['enabled'] = isset( $_REQUEST['gglstpvrfctn-enabled'] ) ? 1 : 0;
		$user_options['email'] = isset( $_REQUEST["gglstpvrfctn_email"] ) ? 1 : 0;
		$user_options['sms'] = isset( $_REQUEST["gglstpvrfctn_sms"] ) ? 1 : 0;
		$user_options['phone'] = sanitize_text_field( $_REQUEST['gglstpvrfctn_phone'] );

		update_user_meta( $user_id, 'gglstpvrfctn_user_options', json_encode( $user_options ) );
	}
}

/**
 * Check secret question data ( question, answer )
 * @since 1.0.5
 * @param		int		$user_id
 *
 * @return		bool	return true if question data( question, answer ) is set
 */
if ( ! function_exists( 'gglstpvrfctn_get_question_data' ) ) {
	function gglstpvrfctn_get_question_data( $user_id ) {

		$user_meta_question_data = get_user_meta( $user_id, 'gglstpvrfctn_secret_question_data', true );
	    $question_data = unserialize( $user_meta_question_data );

	    if ( empty( $question_data['question'] ) || empty( $question_data['answer'] ) ) {
	        return true;
        }

	    return false;
	}
}

/* Adding errors on profile update */
if ( ! function_exists( 'gglstpvrfctn_user_profile_update_errors' ) ) {
	function gglstpvrfctn_user_profile_update_errors( $errors, $update = null, $user = null ) {
		global $gglstpvrfctn_enabled_methods, $pagenow, $current_user;

		if ( 'profile.php' == $pagenow ) {

		/* Updating personal settings */
			$user_id = $current_user->ID;

			$user_secret = gglstpvrfctn_get_user_secret( $user_id );
			$backup_codes = get_user_meta( $user_id, 'gglstpvrfctn_backup_code' );

			$is_question_data_empty = gglstpvrfctn_get_question_data( $user_id );

			$user_methods = array();
			foreach ( $gglstpvrfctn_enabled_methods as $method ) {
				if ( isset( $_REQUEST["gglstpvrfctn_{$method}"] ) ) {
					$user_methods[ $method ] = $method;
				}
			}

			if ( isset( $_REQUEST["gglstpvrfctn-enabled"] ) ) {
			/* 2-Step verification is set to be enabled */
				if ( empty( $user_methods ) ) {
					$message = sprintf(
						'%s %s',
						__( 'You should enable and configure at least one verification method.', 'bws-google-2-step-verification' ),
						__( '2-Step Verification has been disabled.', 'bws-google-2-step-verification' )
					);
					$errors->add( 'gglstpvrfctn_no_methods', $message );
				} else {
					if ( ! (
						( isset( $gglstpvrfctn_enabled_methods['email'] ) && isset( $user_methods['email'] ) ) ||
						( isset( $gglstpvrfctn_enabled_methods['authenticator'] ) && isset( $user_methods['authenticator'] ) && ! empty( $user_secret ) ) ||
						( isset( $gglstpvrfctn_enabled_methods['backup_code'] ) && isset( $user_methods['backup_code'] ) && ! empty( $backup_codes ) ) ||
						( isset( $gglstpvrfctn_enabled_methods['sms'] ) && isset( $user_methods['sms'] ) ) ||
                        ( isset( $gglstpvrfctn_enabled_methods['question'] ) && isset( $user_methods['question'] ) && ! $is_question_data_empty )
						) ) {
					/* No method was set properly */
						$message = sprintf(
							'%s %s',
							__( 'At least one enabled verification method should be configured properly.', 'bws-google-2-step-verification' ),
							__( '2-Step Verification has been disabled.', 'bws-google-2-step-verification' )
						);
						$errors->add( 'gglstpvrfctn_no_proper_methods', $message );
					}
				}
			}
		}
	}
}

/* add help tab  */
if ( ! function_exists( 'gglstpvrfctn_add_tabs' ) ) {
	function gglstpvrfctn_add_tabs() {
		$screen = get_current_screen();
		$args = array(
			'id' 			=> 'gglstpvrfctn',
			'section' 		=> '115000850886'
		);
		bws_help_tab( $screen, $args );
	}
}

if ( ! function_exists ( 'gglstpvrfctn_links' ) ) {
	function gglstpvrfctn_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() && ! is_plugin_active( 'bws-google-2-step-verification-pro/bws-google-2-step-verification-pro.php' ) ) {
				$links[]	=	'<a href="admin.php?page=google-2-step-verification.php">' . __( 'Settings', 'bws-google-2-step-verification' ) . '</a>';
			}
			$links[]	=	'<a href="https://support.bestwebsoft.com/hc/en-us/sections/115000850886" target="_blank">' . __( 'FAQ', 'bws-google-2-step-verification' ) . '</a>';
			$links[]	=	'<a href="https://support.bestwebsoft.com">' . __( 'Support', 'bws-google-2-step-verification' ) . '</a>';
		}
		return $links;
	}
}

/* Function creates other links on plugins page. */
if ( ! function_exists( 'gglstpvrfctn_action_links' ) ) {
	function gglstpvrfctn_action_links( $links, $file ) {
		/* Static so we don't call plugin_basename on every plugin row. */
		static $this_plugin;
		if ( ! $this_plugin ) {
			$this_plugin = plugin_basename( __FILE__ );
		}

		if ( ! is_network_admin() && $file == $this_plugin ) {
			$settings_link = '<a href="admin.php?page=google-2-step-verification.php">' . __( 'Settings', 'bws-google-2-step-verification' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}
}

if ( ! function_exists( 'gglstpvrfctn_get_sms_response' ) ) {
	function gglstpvrfctn_get_sms_response() {
		check_ajax_referer( 'gglstpvrfctn_login_script', 'gglstpvrfctn_ajax_nonce' );
		if (  ! empty( $_POST['gglstpvrfctn_login'] ) ) {
			$login = sanitize_user( $_POST['gglstpvrfctn_login'] );
			
			$user = get_user_by( 'login', $login );
			update_user_meta( $user->ID, 'gglstpvrfctn_sms_check', 'true' );
		}
	}
}

if ( ! function_exists( 'gglstpvrfctn_update_secret_question' ) ) {
	function gglstpvrfctn_update_secret_question() {
	    global $current_user;

	    if ( empty( $_POST['secret_question'] ) ) {
	        return false;
        }

	    $question_prepare = sanitize_text_field( $_POST['secret_question'] );
	    $answer_prepare = sanitize_text_field( $_POST['secret_answer'] );

		$data = serialize( array( 'question' => $question_prepare, 'answer' => $answer_prepare ) );

	    /* save or update secret question data */
		$result = update_user_meta( $current_user->ID, 'gglstpvrfctn_secret_question_data', $data );

		echo $result ? true : false; /* echo 'saved' - 1, or 'not saved - incorrect data' - 0 */

		wp_die();
	}
}

if ( ! function_exists( 'gglstpvrfctn_get_secret_question' ) ) {
    function gglstpvrfctn_get_secret_question() {

        if ( empty( $_POST['gglstpvrfctn_login'] ) ){
           return 'empty data';
        }

        $user = get_user_by( 'login', sanitize_user( $_POST['gglstpvrfctn_login'] ) );
	    $result = unserialize( get_user_meta( $user->ID, 'gglstpvrfctn_secret_question_data', true ) );

	    $question = ( $result['question'] ) ? '<p>' . _e( 'This is your question : ', 'bws-google-2-step-verification' ) . '</p><p>' . $result['question'] . '</p>' : _e( 'You have empty secret pass', 'bws-google-2-step-verification' );

	    echo $question;
        wp_die();
    }
}

/* Default reset password form - output */
if ( ! function_exists( 'gglstpvrfctn_reset_password_form' ) ) {
	function gglstpvrfctn_reset_password_form() {
		gglstpvrfctn_login_enqueue_scripts();
		gglstpvrfctn_login_form(); ?>
        <input type="hidden" name="reset_pass" value="submit">
	<?php }
}

/**
 *  Verify_code for reset password form
 *
 *  @param	int	    $user_id
 *  @param	string	$answer
 *
 * @return bool     $allow
 */
if ( ! function_exists( 'gglstpvrfctn_allow_password_reset' ) ) {
	function gglstpvrfctn_allow_password_reset( $allow ) {

		$login = sanitize_user( $_POST['user_login'] );
		$verification_code = trim( wp_unslash( sanitize_text_field( $_POST['gglstpvrfctn-code'] ) ) );

		$user = get_user_by( 'login', $login );

		if ( ! $user && strpos( $login, '@' ) ) {
			$user = get_user_by( 'email', $login );
		}

		$allow = gglstpvrfctn_verify_code( $user->ID, $verification_code );

		return $allow;
	}
}

/* Check code in registration form */
if ( ! function_exists( 'gglstpvrfctn_register_check' ) ) {
	function gglstpvrfctn_register_check( $allow ) {
		global $gglstpvrfctn_options;

		if ( gglstpvrfctn_is_woocommerce_page() )
			return $allow;

		$login = sanitize_user( $_POST['user_login'] );
		$email = sanitize_email( $_POST['user_email'] );

		if ( empty( $allow->errors ) ) {
			$allow->add( 'gglstpvrfctn_errors', 'Failed 2-Step verification' );
		}
		
		return $allow;
	}
}

if ( ! function_exists( 'gglstpvrfctn_is_woocommerce_page' ) ) {
	function gglstpvrfctn_is_woocommerce_page() {
		$traces = debug_backtrace();

		foreach ( $traces as $trace ) {
			if ( isset( $trace['file'] ) && false !== strpos( $trace['file'], 'woocommerce' ) ) {
				return true;
			}
		}

		return false;
	}
}

/* Function for delete options */
if ( ! function_exists( 'gglstpvrfctn_delete_options' ) ) {
	function gglstpvrfctn_delete_options() {
		global $wpdb;

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$all_plugins = get_plugins();

		if ( ! array_key_exists( 'bws-google-2-step-verification-pro/bws-google-2-step-verification-pro.php', $all_plugins ) ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				$old_blog = $wpdb->blogid;
				/* Get all blog ids */
				$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					delete_option( 'gglstpvrfctn_options' );
				}
				delete_site_option( 'gglstpvrfctn_options' );
				delete_site_option( 'gglstpvrfctn_key' );
				switch_to_blog( $old_blog );
			} else {
				delete_option( 'gglstpvrfctn_options' );
				delete_option( 'gglstpvrfctn_key' );
			}

			$metafields = array(
				'user_secret',
				'user_options',
				'backup_code',
				'email_code',
				'email_init_time',
				'hide_profile_banner',
				'hide_settings_banner'
			);

			foreach ( $metafields as $metafield ) {
				delete_metadata( 'user', 1, "gglstpvrfctn_{$metafield}", false, true );
			}
		}

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

if ( ! function_exists( 'gglstpvrfctn_set_email_verification' ) ) {
	function gglstpvrfctn_set_email_verification( $user_id ) {
		global $gglstpvrfctn_options;		
		if ( 1 == $gglstpvrfctn_options['default_email_method'] ) {
			$user_options = array();
			$user_options['enabled'] = 1;
			$user_options['email']   = 1;
			
			update_user_meta( $user_id, 'gglstpvrfctn_user_options', json_encode($user_options) );
		}
	}
}

register_activation_hook( __FILE__, 'gglstpvrfctn_plugin_activate' );

/* Calling a function add administrative menu. */
add_action( 'admin_menu', 'gglstpvrfctn_admin_menu' );
add_action( 'plugins_loaded', 'gglstpvrfctn_plugins_loaded' );
add_action( 'init', 'gglstpvrfctn_init' );
add_action( 'admin_init', 'gglstpvrfctn_admin_init' );
add_action( 'admin_head', 'gglstpvrfctn_admin_head' );
/* Adding stylesheets */
add_action( 'admin_enqueue_scripts', 'gglstpvrfctn_admin_enqueue_scripts' );

/* Adding banner */
add_action( 'admin_notices', 'gglstpvrfctn_plugin_banner' );
add_action( 'network_admin_admin_notices', 'gglstpvrfctn_plugin_banner' );

/* Additional links on the plugin page */
add_filter( 'plugin_action_links', 'gglstpvrfctn_action_links', 10, 2 );
add_filter( 'network_admin_plugin_action_links', 'gglstpvrfctn_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'gglstpvrfctn_links', 10, 2 );

/* Set verification email by default */
add_action( 'user_register', 'gglstpvrfctn_set_email_verification' );

/* Get new secret on user personal settings page */
add_action( 'wp_ajax_gglstpvrfctn_get_new_secret', 'gglstpvrfctn_get_new_secret' );
/* Test authenticator code for new secret on user personal settings page */
add_action( 'wp_ajax_gglstpvrfctn_test_secret', 'gglstpvrfctn_test_secret' );
/* Generate new backup codes */
add_action( 'wp_ajax_gglstpvrfctn_get_new_backup_codes', 'gglstpvrfctn_get_new_backup_codes' );
/* Check if verification is enabled for specified user from the login page */
add_action( 'wp_ajax_gglstpvrfctn_check_verification_options', 'gglstpvrfctn_check_verification_options' );
add_action( 'wp_ajax_nopriv_gglstpvrfctn_check_verification_options', 'gglstpvrfctn_check_verification_options' );
/* Send new email verification code */
add_action( 'wp_ajax_gglstpvrfctn_request_email_code', 'gglstpvrfctn_request_email_code' );
add_action( 'wp_ajax_nopriv_gglstpvrfctn_request_email_code', 'gglstpvrfctn_request_email_code' );
/* Send new sms verification code */
add_action( 'wp_ajax_gglstpvrfctn_request_sms_code', 'gglstpvrfctn_request_sms_code' );
add_action( 'wp_ajax_nopriv_gglstpvrfctn_request_sms_code', 'gglstpvrfctn_request_sms_code' );
add_action( 'wp_ajax_gglstpvrfctn_get_sms_response', 'gglstpvrfctn_get_sms_response' );
add_action( 'wp_ajax_nopriv_gglstpvrfctn_get_sms_response', 'gglstpvrfctn_get_sms_response' );
/* Hide banner with notice using AJAX */
add_action( 'wp_ajax_gglstpvrfctn_hide_settings_notice', 'gglstpvrfctn_hide_settings_notice' );

add_action( 'wp_ajax_gglstpvrfctn_set_question_data', 'gglstpvrfctn_update_secret_question' );
add_action( 'wp_ajax_gglstpvrfctn_get_secret_question', 'gglstpvrfctn_get_secret_question' );
add_action( 'wp_ajax_nopriv_gglstpvrfctn_get_secret_question', 'gglstpvrfctn_get_secret_question' );
