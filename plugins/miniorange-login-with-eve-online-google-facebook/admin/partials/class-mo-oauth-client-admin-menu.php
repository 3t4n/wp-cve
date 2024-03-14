<?php
/**
 * Admin Menu
 *
 * @package    admin-menu
 * @author     miniOrange <info@miniorange.com>
 * @license    MIT/Expat
 * @link       https://miniorange.com
 */

/**
 * Adding required files.
 */

require 'class-mo-oauth-client-admin-utils.php';
require 'account' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-admin-account.php';
require 'apps' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-apps.php';
require 'licensing' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-license.php';
require 'support' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-support.php';
require 'guides' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-attribute-mapping.php';
require 'demo' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-demo.php';
require 'troubleshoot' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-troubleshoot.php';
require 'addons' . DIRECTORY_SEPARATOR . 'class-mo-oauth-client-addons.php';

/**
 * Initialize CSS files
 *
 * @param mixed $hook WordPress hook.
 */
function mooauth_client_plugin_settings_style( $hook ) {
	if ( 'toplevel_page_mo_oauth_settings' !== $hook ) {
		return;
	}
	wp_enqueue_style( 'mo_oauth_admin_style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/admin.min.css', array(), MO_OAUTH_CSS_JS_VERSION );
	wp_enqueue_style( 'mo_oauth_admin_settings_style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/style_settings.min.css', array(), MO_OAUTH_CSS_JS_VERSION );
	wp_enqueue_style( 'mo_oauth_admin_settings_font_awesome', plugin_dir_url( dirname( __FILE__ ) ) . 'css/font-awesome.min.css', array(), '4.7.0' );
	wp_enqueue_style( 'mo_oauth_admin_settings_phone_style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/phone.min.css', array(), '0.0.2' );
	wp_enqueue_style( 'mo_oauth_admin_settings_datatable_style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/jquery.dataTables.min.css', array(), '3.6.0' );
	wp_enqueue_style( 'mo_oauth_admin_settings_inteltelinput_style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/intlTelInput.min.css', array(), '17.0.19' );
	wp_enqueue_style( 'mo_oauth_admin_settings_jquery_ui_style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/jquery-ui.min.css', array(), '1.12.1' );
	wp_enqueue_style( 'mo_oauth_admin_settings_overall_font_style', plugin_dir_url( dirname( __FILE__ ) ) . 'css/fontNunito.min.css', array(), '1.0.0' );
}

/**
 * Initialize JS files
 *
 * @param mixed $hook WordPress hook.
 */
function mooauth_client_plugin_settings_script( $hook ) {
	if ( 'toplevel_page_mo_oauth_settings' !== $hook ) {
		return;
	}
	wp_enqueue_script( 'mo_oauth_admin_script', plugin_dir_url( dirname( __FILE__ ) ) . 'js/admin.min.js', array(), $ver = MO_OAUTH_CSS_JS_VERSION, false );
	wp_enqueue_script( 'mo_oauth_admin_settings_script', plugin_dir_url( dirname( __FILE__ ) ) . 'js/settings.min.js', array(), $ver = MO_OAUTH_CSS_JS_VERSION, false );
	wp_enqueue_script( 'mo_oauth_admin_settings_phone_script', plugin_dir_url( dirname( __FILE__ ) ) . 'js/phone.min.js', array(), $ver = '0.8.3', false );
	wp_enqueue_script( 'mo_oauth_admin_settings_datatable_script', plugin_dir_url( dirname( __FILE__ ) ) . 'js/jquery.dataTables.min.js', array(), $ver = '1.10.20', false );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'mo_oauth_admin_settings_jquery-ui3', includes_url() . 'js/jquery/ui/datepicker.min.js', array(), $ver = false, false );
	wp_enqueue_script( 'mo_oauth_admin_settings_inteltelinput', plugin_dir_url( dirname( __FILE__ ) ) . 'js/intlTelInput.min.js', array(), $ver = '13.0.4', false );
}

/**
 * Display Main Menu
 */
function mooauth_client_main_menu() {
	$today      = gmdate( 'Y-m-d H:i:s' );
	$currenttab = '';
	if ( isset( $_GET['tab'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
		$currenttab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
	}
	MO_OAuth_Client_Admin_Utils::curl_extension_check();
	MO_OAuth_Client_Admin_Menu::show_menu( $currenttab );
	echo '<div id="mo_oauth_settings">';
	if ( get_option( 'mo_oauth_client_show_rest_api_message' ) ) {
		MO_OAuth_Client_Admin_Menu::show_rest_api_secure_message();
	}
		echo '
		<div class="miniorange_container">';

		echo '<table style="width:100%;">
			<tr>
				<td style="vertical-align:top;width:65%;" class="mo_oauth_content">';

				MO_OAuth_Client_Admin_Menu::show_tab( $currenttab );

				MO_OAuth_Client_Admin_Menu::show_support_sidebar( $currenttab );
				echo '</tr>
				</table>
				<div class="mo_tutorial_overlay" id="mo_tutorial_overlay" hidden></div>
		</div>';
}


/**
 * Migrate Customers
 *
 * @return true|false
 */
function mooauth_migrate_customers() {
	if ( get_option( 'mo_oauth_client_admin_customer_key' ) > 138200 ) {
		return true;
	} else {
		return false;
	}}

/**
 * [Display data based on different tabs]
 */
class MO_OAuth_Client_Admin_Menu {

	/**
	 * Delete log file
	 */
	public static function logfile_delete() {

		$mo_file_path1 = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . get_option( 'mo_oauth_debug' ) . '.log';
		if ( file_exists( $mo_file_path1 ) ) {
			unlink( $mo_file_path1 );
		}

	}

	/**
	 * Show Menu
	 *
	 * @param mixed $currenttab current tab the user has clicked.
	 */
	public static function show_menu( $currenttab ) {

		if ( get_option( 'mo_debug_check' ) ) {
			update_option( 'mo_debug_check', 0 );
		}

		$log_file_path = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . get_option( 'mo_oauth_debug' ) . '.log';

		$mo_log_enable = get_option( 'mo_debug_enable' );

		$mo_oauth_debug = get_option( 'mo_oauth_debug' );

		if ( 'on' === $mo_log_enable ) {
			$key            = 604800;
			$mo_debug_times = get_option( 'mo_debug_time' );
			$mo_curr_time   = time();

			$mo_oauth_var = (int) ( ( $mo_curr_time - $mo_debug_times ) / ( $key ) );
			if ( $mo_oauth_var >= 1 ) {
				update_option( 'mo_debug_time', $mo_debug_times + ( $mo_oauth_var * $key ) );
				update_option( 'mo_debug_enable', 0 );

				self::logfile_delete();
				delete_option( 'mo_oauth_debug' );
			}
		} else {
			self::logfile_delete();
			delete_option( 'mo_oauth_debug' );
		}

		if ( ( 'on' === $mo_log_enable && ! $mo_oauth_debug ) || ( 'on' === $mo_log_enable && ( ! file_exists( $log_file_path ) ) ) ) {

				update_option( 'mo_oauth_debug', 'mo_oauth_debug' . uniqid() );
				$mo_oauth_debugs = get_option( 'mo_oauth_debug' );
				$mo_file_addr2   = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . $mo_oauth_debugs . '.log';
				$mo_debug_file   = fopen( $mo_file_addr2, 'w' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen -- Using fopen to open exiting debug log file.
				chmod( $mo_file_addr2, 0644 );
				update_option( 'mo_debug_check', 1 );
				MOOAuth_Debug::mo_oauth_log( '' );
				update_option( 'mo_debug_check', 0 );
		}

		if ( 'licensing' !== $currenttab ) { ?>
		<div class="mo_oauth_plugin_body">
	<div class="container mo_oauth_free_plugin_container">
	<div class="wrap">
	<div><img style="float:left; margin-right:1em;"
			src="<?php echo esc_attr( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/mini.png"></div></div>

<?php } ?>
<div class="mo_oauth_wrap" style="display:flex; justify-content: space-between">
		<?php if ( 'licensing' !== $currenttab ) { ?>
	<div class="mo_oauth_plugin_heading">
		<h1 class="mo_oauth_h1" style="color:#191E23;font-size:1.60rem;"><b>miniOrange OAuth Single Sign On</b>
		</h1>
		</div>
		<div class="justify-content-end mo_oauth_wrapper">
			<h1 class="mo_oauth_h1">
			<a id="license_upgrade" class="mo-add-new-hover mo_premium-plans-btn mo_oauth_header_link"
			href="<?php echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr( add_query_arg( array( 'tab' => 'licensing' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>">
			<img class="mo_oauth_header_link_image" style="margin: 0px;" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/prem.png" alt="miniOrange Premium Plans Logo">
			<?php esc_html_e( 'Premium Plans', 'miniorange-login-with-eve-online-google-facebook' ); ?></a>
			<a id="faq_button_id" class="mo_generic-btns-on-top mo_oauth_header_link"
			href="<?php echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr( add_query_arg( array( 'tab' => 'troubleshoot' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>"><span>
			<img class="mo_oauth_header_link_image" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/troubleshooting.png' ); ?>" alt="miniOrange Troubleshooting Logo"><?php esc_html_e( 'Troubleshooting', 'miniorange-login-with-eve-online-google-facebook' ); ?></span></a>
			<a id="form_button_id" class="mo_generic-btns-on-top mo_oauth_header_link" href="https://wordpress.org/support/plugin/miniorange-login-with-eve-online-google-facebook/"
			target="_blank"><span>
			<img class="mo_oauth_header_link_image" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) ); ?>/images/ask-questions.png"
				alt="miniOrange Ask Questions Logo">
			<?php esc_html_e( 'Ask questions on our forum', 'miniorange-login-with-eve-online-google-facebook' ); ?></span></a>
			<a id="features_button_id " class="mo_generic-btns-on-top mo_oauth_header_link"
			href="https://developers.miniorange.com/docs/oauth/wordpress/client" target="_blank"><span>
			<img class="mo_oauth_header_link_image" src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/images/feature-details.png' ); ?>"
				alt="miniOrange Feature Details Logo"><?php esc_html_e( 'Feature Details', 'miniorange-login-with-eve-online-google-facebook' ); ?></span></a>
	</h1>
	</div>
	<?php } if ( 'licensing' === $currenttab ) { ?>
	<div class="mo_license_heading" style="display: flex;padding-bottom:7px;padding-top: 35px;width: 100%;gap :19em"
		id="nav-container">
		<div>
			<a 
				href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'default' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ); ?>">
				<button id="Back-To-Plugin-Configuration" type="button" value="Back-To-Plugin-Configuration"
					class="button button-primary button-large"
					style="position:absolute;left:10px;background-color: #093553;">
					<span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span>
					Plugin Configuration
				</button>
			</a>
		</div>
		<div style="display:block;text-align:center;margin: 10px;">
			<h2 style="font-size:22px;text-align: center;float: left"><b>miniOrange OAuth & OIDC Single Sign-On</b></h2>
		</div>
	</div>
			<?php
	}
	?>
</div>
</div>
<style>
.mo-add-new-hover:hover {
	color: white !important;
}
</style>
		<?php if ( 'licensing' !== $currenttab ) { ?>
<div id="tab">
	<h2 class="nav-tab-wrapper mo_oauth_nav_tab_wrapper">
		<a id="tab-config" href="admin.php?page=mo_oauth_settings&tab=config"
			class="nav-tab mo_oauth_nav-tab anglebg 
			<?php
			if ( 'config' === $currenttab ) {
				echo 'nav-tab-active mo_oauth_nav-tab-active';}
			?>
			">
					<?php esc_html_e( 'Configure OAuth', 'miniorange-login-with-eve-online-google-facebook' ); ?>
		</a>
		<a id="tab-attrmapping" href="admin.php?page=mo_oauth_settings&tab=attributemapping"
			class="nav-tab mo_oauth_nav-tab 
			<?php
			if ( 'attributemapping' === $currenttab ) {
				echo 'nav-tab-active mo_oauth_nav-tab-active';}
			?>
			">
					<?php esc_html_e( 'Attribute/Role Mapping', 'miniorange-login-with-eve-online-google-facebook' ); ?>
		</a>
		<a id="tab-signinsettings" href="admin.php?page=mo_oauth_settings&tab=signinsettings"
			class="nav-tab mo_oauth_nav-tab 
			<?php
			if ( 'signinsettings' === $currenttab ) {
				echo 'nav-tab-active mo_oauth_nav-tab-active';}
			?>
			">
					<?php esc_html_e( 'Login Settings', 'miniorange-login-with-eve-online-google-facebook' ); ?>
		</a>
		<a id="tab-customization" href="admin.php?page=mo_oauth_settings&tab=customization"
			class="nav-tab mo_oauth_nav-tab  
			<?php
			if ( 'customization' === $currenttab ) {
				echo 'nav-tab-active mo_oauth_nav-tab-active';}
			?>
			">
					<?php esc_html_e( 'Login Button Customization', 'miniorange-login-with-eve-online-google-facebook' ); ?>
		</a>
		<a id="tab-requestdemo" href="admin.php?page=mo_oauth_settings&tab=requestfordemo"
			class="nav-tab mo_oauth_nav-tab 
			<?php
			if ( 'requestfordemo' === $currenttab ) {
				echo 'nav-tab-active mo_oauth_nav-tab-active';}
			?>
			">
					<?php esc_html_e( 'Trials Available', 'miniorange-login-with-eve-online-google-facebook' ); ?>
		</a>
		<a id="tab-acc-setup" href="admin.php?page=mo_oauth_settings&tab=account"
			class="nav-tab mo_oauth_nav-tab 
			<?php
			if ( 'account' === $currenttab ) {
				echo 'nav-tab-active mo_oauth_nav-tab-active';}
			?>
			">
					<?php esc_html_e( 'Account Setup', 'miniorange-login-with-eve-online-google-facebook' ); ?>
		</a>
		<a id="tab-addons" href="admin.php?page=mo_oauth_settings&tab=addons"
			class="nav-tab mo_oauth_nav-tab 
			<?php
			if ( 'addons' === $currenttab ) {
				echo 'nav-tab-active mo_oauth_nav-tab-active';}
			?>
			">
					<?php esc_html_e( 'Add-ons', 'miniorange-login-with-eve-online-google-facebook' ); ?>
		</a>
	</h2>
	<hr class="mo-divider">
	<br>
</div>
			<?php
		}

	}
	/**
	 * Show REST API not protected alert
	 */
	public static function show_rest_api_secure_message() {
		if ( get_option( 'mo_oauth_client_show_rest_api_message' ) ) {
			$currenttab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
			if ( 'licensing' !== $currenttab ) {
				?>
				<form name="f" method="post" action="" id="mo_oauth_client_rest_api_form">
							<?php wp_nonce_field( 'mo_oauth_client_rest_api_form', 'mo_oauth_client_rest_api_form_field' ); ?>
					<input type="hidden" name="option" value="mo_oauth_client_rest_api_message" />
					<div class="notice notice-info" style="padding-right: 38px;position: relative;border-left-color:red;">
						<h4><i class="fa fa-exclamation-triangle" style="font-size:20px;color:red;"></i>&nbsp;&nbsp;
							<b>Security Alert: </b> Looks like your WP REST APIs are not protected from public access. WP REST APIs
							should be protected and allowed only for authorized access. You can <a
								href="https://wordpress.org/plugins/wp-rest-api-authentication/" target="_blank">click here</a> to know
							how it can be handled.
						</h4>
						<button type="button" class="notice-dismiss" id="mo_oauth_client_rest_api_button"><span
								class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>
				</form>
				<script>
				jQuery("#mo_oauth_client_rest_api_button").click(function() {
					jQuery("#mo_oauth_client_rest_api_form").submit();
				});
				</script>
				<?php
			}
		}
	}

	/**
	 * Show IDP link
	 *
	 * @param mixed $currenttab current tab user is viewing.
	 */
	public static function show_idp_link( $currenttab ) {
		if ( ( ! get_option( 'mo_oauth_client_show_mo_server_message' ) ) ) {
			?>
<form name="f" method="post" action="" id="mo_oauth_client_mo_server_form">
			<?php wp_nonce_field( 'mo_oauth_mo_server_message_form', 'mo_oauth_mo_server_message_form_field' ); ?>
	<input type="hidden" name="option" value="mo_oauth_client_mo_server_message" />
	<div class="notice notice-info" style="padding-right: 38px;position: relative;">
		<h4><?php esc_html_e( 'Looking for a User Storage/OAuth Server? We have a B2C Service(Cloud IDP) which can scale to hundreds of millions of consumer identities. You can', 'miniorange-login-with-eve-online-google-facebook' ); ?> <a href="https://idp.miniorange.com/b2c-pricing" target="_blank" rel="noopener"><?php esc_html_e( 'click here', 'miniorange-login-with-eve-online-google-facebook' ); ?></a> <?php esc_html_e( 'to find more about it.', 'miniorange-login-with-eve-online-google-facebook' ); ?></h4>
		<button type="button" class="notice-dismiss" id="mo_oauth_client_mo_server"><span
				class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
</form>
<script>
jQuery("#mo_oauth_client_mo_server").click(function() {
	jQuery("#mo_oauth_client_mo_server_form").submit();
});
</script>
			<?php
		}
		self::mo_oauth_client_check_action_messages();
	}


	/**
	 * Handle admin notices
	 */
	public static function mo_oauth_client_check_action_messages() {
		$notices = get_option( 'mo_oauth_client_notice_messages' );

		if ( empty( $notices ) ) {
			return;
		}
		foreach ( $notices as $key => $notice ) {
			echo '<div class="notice notice-info" style="padding-right: 38px;position: relative;"><h4>' . esc_attr( $notice ) . '</h4></div>';
		}
	}

	/**
	 * Handle views according to current tab.
	 *
	 * @param mixed $currenttab current tab user is viewing.
	 */
	public static function show_tab( $currenttab ) {
		if ( 'account' === $currenttab ) {
			if ( get_option( 'mo_oauth_client_verify_customer' ) === 'true' ) {
				MO_OAuth_Client_Admin_Account::verify_password();
			} elseif ( '' !== trim( get_option( 'mo_oauth_admin_email' ) ) && trim( get_option( 'mo_oauth_client_admin_api_key' ) ) === '' && 'true' !== get_option( 'mo_oauth_client_new_registration' ) ) {
				MO_OAuth_Client_Admin_Account::verify_password();
			} else {
				MO_OAuth_Client_Admin_Account::register();
			}
		} elseif ( 'customization' === $currenttab ) {
				MO_OAuth_Client_Apps::customization();
		} elseif ( 'signinsettings' === $currenttab ) {
			MO_OAuth_Client_Apps::sign_in_settings();
		} elseif ( 'licensing' === $currenttab ) {
			MO_OAuth_Client_License::show_licensing_page();
		} elseif ( 'requestfordemo' === $currenttab ) {
			MO_OAuth_Client_Demo::requestfordemo();
		} elseif ( 'addons' === $currenttab ) {
			MO_OAuth_Client_Addons::addons();
		} elseif ( 'attributemapping' === $currenttab ) {
			MO_OAuth_Client_Apps::attribute_role_mapping();
		} elseif ( 'troubleshoot' === $currenttab ) {
			MO_OAuth_Client_Troubleshoot::troubleshooting();
		} elseif ( '' === $currenttab ) {
			?>
	<a id="goregister" style="display:none;" href="<?php /*WordPress.Security.NonceVerification.Recommended, Ignoring nonce verification because we are fetching data from URL and not on form submission.*/ echo ! empty( $_SERVER['REQUEST_URI'] ) ? esc_attr( add_query_arg( array( 'tab' => 'config' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : ''; ?>">

	<script>
		location.href = jQuery('#goregister').attr('href');
	</script>
			<?php
		} else {
			MO_OAuth_Client_Apps::applist();
		}
	}

	/**
	 * Display Support Sidebar.
	 *
	 * @param mixed $currenttab current tab user is viewing.
	 */
	public static function show_support_sidebar( $currenttab ) {
		if ( 'licensing' !== $currenttab ) {
			echo '<td style="vertical-align:top;padding-left:1%;" class="mo_oauth_sidebar">';
			if ( 'attributemapping' === $currenttab ) {
				echo esc_html( MO_OAuth_Client_Attribute_Mapping::emit_attribute_table() );
			}
			echo esc_html( MO_OAuth_Client_Support::support() );
			echo '</td>';
		}
	}
}
?>
