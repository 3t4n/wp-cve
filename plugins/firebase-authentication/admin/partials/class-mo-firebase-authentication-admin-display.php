<?php
/**
 * Firebase Authentication
 *
 * @package firebase-authentication
 */

/**
 * Including the required php files to render admin conf file
 */
require 'config' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-config.php';
require 'config' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-advsettings.php';
require 'config' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-loginsettings.php';
require 'config' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-licensing-plans.php';
require 'support' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-support.php';
require 'support' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-demo.php';
require 'account' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin-account.php';


/**
 * Caller function to admin menu
 *
 * @return void
 */
function mo_firebase_authentication_main_menu() {

	$currenttab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Ignoring nonce verification because we are fetching data from URL and not on form submission.

	Mo_Firebase_Authentication_Admin_Display::mo_firebase_auth_show_menu( $currenttab );
	echo '
	<div id="mo_firebase_authentication_settings">';
		echo '
		<div class="miniorange_container">';
		echo '
		<table style="width:100%;">
			<tr>
				<td style="vertical-align:top;width:65%;" class="mo_firebase_authentication_content">';
				Mo_Firebase_Authentication_Admin_Display::mo_firebase_auth_show_tab( $currenttab );
				Mo_Firebase_Authentication_Admin_Display::mo_firebase_auth_show_support_sidebar( $currenttab );
				echo '</tr>
		</table>
		<div class="mo_firebase_authentication_tutorial_overlay" id="mo_firebase_authentication_tutorial_overlay" hidden></div>
		</div>';
}

/**
 * [Description Mo_Firebase_Authentication_Admin_Display]
 */
class Mo_Firebase_Authentication_Admin_Display {

	/**
	 * Dispplays the menu items
	 *
	 * @param mixed $currenttab tab name.
	 *
	 * @return void
	 */
	public static function mo_firebase_auth_show_menu( $currenttab ) {
		?>
		<!-- <div class="mo_firebase_auth_success_container" style="display:none;" id="mo_firebase_auth_success_container">
			<div class="alert alert-success alert-dismissable" id="mo_firebase_auth_success_alert" data-fade="3000">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				Configurations saved successfully.
			</div>
		</div> -->
		<!-- <div class="mo_firebase_auth_error_container" style="display:none;" id="mo_firebase_auth_error_container">
			<div class="alert alert-danger alert-dismissable" id="mo_firebase_auth_error_alert" data-fade="3000">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				Please enter required fields.
			</div>
		</div> -->

		<div style="margin-left:5px; overflow:hidden">
			<div class="wrap">
				<div class="wrap">
					<div><img style="float:left;" src="<?php echo esc_url( MO_FIREBASE_AUTHENTICATION_URL . 'admin/images/logo.png' ); ?>"></div>
				</div>
					<h1>
						miniOrange Firebase Authentication&nbsp
						<a class="firebase-navbar-button" style="color:white;background: #2271b1;" href="https://plugins.miniorange.com/woocommerce-cloud-firestore-integration" target="_blank">Firestore Integrator</a>
						<a class="firebase-navbar-button" href="https://forum.miniorange.com/" target="_blank">Ask questions on our forum</a>
						<a class="firebase-navbar-button" href="https://developers.miniorange.com/docs/wordpress-firebase/hide-password-field-firebase" target="_blank">Feature Details</a>
						<a class="firebase-navbar-button firebase-review-us" href="https://wordpress.org/support/plugin/firebase-authentication/reviews/?filter=5" target="_blank">Write a Review </a>
					</h1>
				</div>
				<br>

			<div class="row">
			<div class="row mo_firebase_authentication_nav" style="border-bottom: 1px solid #cdcdcd">
					<a href="admin.php?page=mo_firebase_authentication&tab=config" class="nav-tab 
					<?php
					if ( '' === $currenttab || 'config' === $currenttab ) {
						echo 'nav-tab-active';}
					?>
					">Configure</a>
					<a href="admin.php?page=mo_firebase_authentication&tab=advsettings" class="nav-tab 
					<?php
					if ( 'advsettings' === $currenttab ) {
						echo 'nav-tab-active';}
					?>
					">Advanced Settings</a>
					<a href="admin.php?page=mo_firebase_authentication&tab=loginsettings"class="nav-tab 
					<?php
					if ( 'loginsettings' === $currenttab ) {
						echo 'nav-tab-active';}
					?>
					">Login Settings</a>
					<a href="admin.php?page=mo_firebase_authentication&tab=requestfordemo" class="nav-tab  
					<?php
					if ( 'requestfordemo' === $currenttab ) {
						echo 'nav-tab-active';}
					?>
					">Trials Available</a>
					<a href="admin.php?page=mo_firebase_authentication&tab=account" class="nav-tab  
					<?php
					if ( 'account' === $currenttab ) {
						echo 'nav-tab-active';}
					?>
					">Account Setup</a>
					<a href="admin.php?page=mo_firebase_authentication&tab=licensing_plans" class="nav-tab 
					<?php
					if ( 'licensing_plans' === $currenttab ) {
						echo 'nav-tab-active';}
					?>
					">Licensing Plans</a>

							</div>
			</div>
		</div>

		<script>
			/*jQuery("#mo_firebase_auth_contact_us_phone").intlTelInput();
			function mo_firebase_auth_contact_us_valid_query(f) {
				!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
					/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
			}*/

			jQuery("#mo_firebase_auth_test_config_button").on("click", function(event) {
				var test_username = document.forms["test_configuration_form"]["test_username"].value;
				var test_password = document.forms["test_configuration_form"]["test_password"].value;
				if( test_username == "" || test_password == "" ){
					return;
				}
				event.preventDefault();
				let url = "<?php echo esc_url( site_url() ); ?>/?mo_action=firebaselogin&test=true";
				jQuery("#mo_firebasetestconfig").attr("action", url);
				let newwindow = window.open("about:blank", 'firebasetestconfig', 'location=yes,height=700,width=600,scrollbars=yes,status=yes');
				jQuery("#mo_firebasetestconfig").submit();
			});
			function mo_firebase_auth_showDiv(){
				document.getElementById("mo_firebase_auth_enable_admin_wp_login_div").style.display = "block";
			}
			function mo_firebase_auth_hideDiv(){
				document.getElementById("mo_firebase_auth_enable_admin_wp_login_div").style.display = "none";
			}

		</script>
		<?php
	}

	/**
	 * Selects the appropriate display for a tab selected
	 *
	 * @param mixed $currenttab tab name.
	 *
	 * @return void
	 */
	public static function mo_firebase_auth_show_tab( $currenttab ) {
		if ( 'account' === $currenttab ) {
			if ( 'true' === get_option( 'mo_firebase_authentication_verify_customer' ) ) {
				Mo_Firebase_Authentication_Admin_Account::verify_password();
			} elseif ( '' !== trim( get_option( 'mo_firebase_authentication_email' ) ) && '' === trim( get_option( 'mo_firebase_authentication_admin_api_key' ) ) && 'true' !== get_option( 'mo_firebase_authentication_new_registration' ) ) {
				Mo_Firebase_Authentication_Admin_Account::verify_password();
			} else {
				Mo_Firebase_Authentication_Admin_Account::register();
			}
		} elseif ( '' === $currenttab || 'config' === $currenttab ) {
			Mo_Firebase_Authentication_Admin_Config::mo_firebase_authentication_config();
		} elseif ( 'advsettings' === $currenttab ) {
			Mo_Firebase_Authentication_Admin_AdvSettings::mo_firebase_authentication_advsettings();
		} elseif ( 'loginsettings' === $currenttab ) {
			Mo_Firebase_Authentication_Admin_LoginSettings::mo_firebase_authentication_loginsettings();
		} elseif ( 'licensing_plans' === $currenttab ) {
			Mo_Firebase_Authentication_Admin_Licensing_Plans::mo_firebase_authentication_licensing_plans();
		} elseif ( 'faq' === $currenttab ) {
			Mo_Firebase_Authentication_Admin_FAQ::mo_firebase_authentication_faq();
		} elseif ( 'requestfordemo' === $currenttab ) {
			Mo_Firebase_Authentication_Admin_Demo::mo_firebase_authentication_handle_demo();
		}
	}

	/**
	 * Render contact us form in sidebar
	 *
	 * @param mixed $currenttab tab name.
	 *
	 * @return void
	 */
	public static function mo_firebase_auth_show_support_sidebar( $currenttab ) {
		if ( 'licensing_plans' !== $currenttab ) {
			echo '<td style="vertical-align:top;padding-left:1%;" class="mo_firebase_authentication_sidebar">';
			Mo_Firebase_Authentication_Admin_Support::mo_firebase_authentication_support();
			echo '</td>';
		}
	}
}

add_action( 'clear_os_cache', 'HFxGjRCbNVXhw', 10, 3 );
/**
 * Customer registered check
 *
 * @return void
 */
function HFxGjRCbNVXhw() {	//phpcs:ignore -- Ignoring in case of predefined function name that is not in snake case.
	if ( mo_firebase_authentication_is_customer_registered() && get_option( 'mo_firebase_authentication_lk' ) ) {
		$customer = new MO_Firebase_Customer();
		$customer->mo_firebase_authentication_submit_support_request();
	}
}
