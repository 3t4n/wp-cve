<?php //phpcs:ignore WPShield_Standard.Security.DisallowBrandAndImproperPluginName.ImproperPluginName, WordPress.Files.FileName.NotHyphenatedLowercase, WordPress.Files.FileName.InvalidClassFileName  -- Could not change its file name as it would create issue for the customers while updating the plugin.
//phpcs:ignore
/**
 * Plugin Name: Multi Factor Authentication
 * Plugin URI: https://miniorange.com
 * Description: This is a simple 2FA plugin that provides various two-factor authentication methods as an additional layer of security after the default WordPress login. We Support Google/Authy/LastPass Authenticator and Security Questions(KBA) for unlimited admin users in the free version of the plugin.
 * Version: 1.4.1
 * Author: miniOrange
 * Author URI: https://miniorange.com
 * License: MIT/Expat
 *
 * @package miniorange-login-security
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	define( 'MO_HOST_NAME', 'https://login.xecurify.com' );
	define( 'MO2F_VERSION', '1.4.1' );
	define( 'MO2F_TEST_MODE', false );
	global $main_dir;
	$main_dir = plugin_dir_url( __FILE__ );
if ( ! class_exists( 'Miniorange_TwoFactor' ) ) {
	/**
	 * Includes all the hooks and actions in the main plugin file.
	 */
	class Miniorange_TwoFactor {

		/**
		 * Constructor.
		 */
		public function __construct() {
			register_deactivation_hook( __FILE__, array( $this, 'momls_wpns_deactivate' ) );
			register_activation_hook( __FILE__, array( $this, 'momls_wpns_activate' ) );
			add_action( 'admin_menu', array( $this, 'momls_wpns_widget_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'momls_wpns_settings_style' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'momls_wpns_settings_script' ) );
			add_action( 'wpns_momls_show_message', array( $this, 'momls_show_message' ), 1, 2 );
			add_action( 'wp_footer', array( $this, 'momls_footer_link' ), 100 );

			add_action( 'admin_init', array( $this, 'momls_reset_save_settings' ) );
			add_filter( 'manage_users_columns', array( $this, 'momls_mapped_email_column' ) );
			add_action( 'manage_users_custom_column', array( $this, 'momls_mapped_email_column_content' ), 10, 3 );

			$actions = add_filter( 'user_row_actions', array( $this, 'momls_reset_users' ), 10, 2 );
			add_action( 'admin_footer', array( $this, 'momls_feedback_request' ) );
			if ( get_site_option( 'mo2f_disable_file_editing' ) ) {
				define( 'DISALLOW_FILE_EDIT', true );
			}
			$this->momls_includes();

		}
		/**
		 * As on plugins.php page not in the plugin.
		 *
		 * @return void
		 */
		public function momls_feedback_request() {
			if ( isset( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( esc_url_raw( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
				return;
			}
			global $mo2f_dir_name;

			$email = get_site_option( 'mo2f_email' );
			if ( empty( $email ) ) {
				$user  = wp_get_current_user();
				$email = $user->user_email;
			}
			$imagepath = plugins_url( '/includes/images/', __FILE__ );
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );
			wp_enqueue_style( 'momls_wpns_admin_plugins_page_style', plugins_url( '/includes/css/style_settings.min.css', __FILE__ ), array(), MO2F_VERSION );

			include $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'feedback-form.php';

		}

		/**
		 * Add submenu options in the miniOrange plugin menu.
		 *
		 * @return void
		 */
		public function momls_wpns_widget_menu() {
			$user         = wp_get_current_user();
			$user_id      = wp_get_current_user()->ID;
			$onprem_admin = get_site_option( 'mo2f_onprem_admin' );
			$roles        = (array) $user->roles;
			$flag         = 0;
			foreach ( $roles as $role ) {
				if ( get_site_option( 'mo2fa_' . $role ) === '1' ) {
					$flag = 1;
				}
			}

			$is_2fa_enabled = ( ( $flag ) || ( $user_id === $onprem_admin ) );

			if ( $is_2fa_enabled ) {
				$menu_slug = 'mo_2fa_two_fa';
				add_menu_page( 'miniOrange 2-Factor', 'Multi-factor Authentication', 'administrator', $menu_slug, array( $this, 'momls_wpns' ), plugin_dir_url( __FILE__ ) . 'includes/images/miniorange_icon.png' );
			} else {
				$menu_slug = 'mo_2fa_dashboard';
			}

			if ( get_site_option( 'is_onprem' ) ) {
				if ( $is_2fa_enabled ) {

					add_submenu_page( $menu_slug, 'miniOrange 2-Factor', 'Two Factor', 'read', 'mo_2fa_two_fa', array( $this, 'momls_wpns' ), 1 );
				}
			} else {
				add_submenu_page( $menu_slug, 'miniOrange 2-Factor', 'Two Factor', 'administrator', 'mo_2fa_two_fa', array( $this, 'momls_wpns' ), 2 );
			}

			add_submenu_page( $menu_slug, 'miniOrange 2-Factor', 'Troubleshooting', 'administrator', 'mo_2fa_troubleshooting', array( $this, 'momls_wpns' ), 10 );
			add_submenu_page( $menu_slug, 'miniOrange 2-Factor', 'Account', 'administrator', 'mo_2fa_account', array( $this, 'momls_wpns' ), 11 );
			$mo2fa_hook_page = add_users_page( 'Reset 2nd Factor', null, 'manage_options', 'reset', array( $this, 'momls_reset_2fa_for_users_by_admin' ), 66 );

		}
		/**
		 * Adding some options and calling functions after activation.
		 *
		 * @return void
		 */
		public function momls_wpns() {
			global $momlsdb_queries;
			$momlsdb_queries->momls_plugin_activate();
			include 'controllers/main-controller.php';
		}
		/**
		 * Settings options and calling required functions after register activation hook.
		 *
		 * @return void
		 */
		public function momls_wpns_activate() {
			global $wpns_db_queries,$momlsdb_queries;
			$user_id = wp_get_current_user()->ID;
			$wpns_db_queries->momls_plugin_activate();
			$momlsdb_queries->momls_plugin_activate();
			add_site_option( 'mo2f_activate_plugin', 1 );
			add_site_option( 'mo2f_login_policy', 1 );
			add_site_option( 'mo2f_is_NC', 1 );
			add_site_option( 'mo2f_is_NNC', 1 );
			add_site_option( 'mo2f_number_of_transactions', 1 );
			add_site_option( 'mo2f_set_transactions', 0 );
			add_site_option( 'mo2f_enable_forgotphone', 1 );
			add_site_option( 'mo2f_enable_2fa_for_users', 1 );
			add_site_option( 'mo2f_enable_2fa_prompt_on_login_page', 0 );
			add_site_option( 'mo2f_enable_xmlrpc', 0 );
			add_site_option( 'mo2fa_administrator', 1 );
			add_site_option( 'mo2f_custom_plugin_name', 'miniOrange 2-Factor' );
			add_action( 'momls_auth_show_success_message', array( $this, 'momls_auth_show_success_message' ), 10, 1 );
			add_action( 'momls_auth_show_error_message', array( $this, 'momls_auth_show_error_message' ), 10, 1 );
			add_site_option( 'mo2f_onprem_admin', $user_id );

		}

		/**
		 * Settings options and calling required functions after register dectivation hook.
		 *
		 * @return void
		 */
		public function momls_wpns_deactivate() {
			global $momls_wpns_utility;
			if ( ! $momls_wpns_utility->momls_check_empty_or_null( get_site_option( 'momls_wpns_registration_status' ) ) ) {
				delete_site_option( 'mo2f_email' );
			}
			update_site_option( 'mo2f_activate_plugin', 1 );
			delete_site_option( 'mo2f_customerKey' );
			delete_site_option( 'Momls_Api_key' );
			delete_site_option( 'mo2f_customer_token' );
			delete_site_option( 'momls_wpns_transactionId' );
			delete_site_option( 'momls_wpns_registration_status' );

			$two_fa_settings = new Momls_Miniorange_Authentication();
			$two_fa_settings->momls_auth_deactivate();
		}
		/**
		 * Including css files on 2fa dashboard.
		 *
		 * @param int $hook - Hook suffix for the current admin page.
		 * @return void
		 */
		public function momls_wpns_settings_style( $hook ) {
			if ( strpos( $hook, 'page_mo_2fa' ) ) {
				wp_enqueue_style( 'momls_wpns_admin_settings_style', plugins_url( 'includes/css/style_settings.min.css', __FILE__ ), array(), MO2F_VERSION );
				wp_enqueue_style( 'momls_wpns_admin_settings_datatable_style', plugins_url( 'includes/css/jquery.dataTables.min.css', __FILE__ ), array(), MO2F_VERSION );
				wp_enqueue_style( 'momls_wpns_button_settings_style', plugins_url( 'includes/css/button_styles.min.css', __FILE__ ), array(), MO2F_VERSION );
			}

		}
		/**
		 * Including javascript files on 2fa dashboard.
		 *
		 * @param int $hook - Hook suffix for the current admin page.
		 * @return void
		 */
		public function momls_wpns_settings_script( $hook ) {
			wp_enqueue_script( 'momls_wpns_admin_settings_script', plugins_url( 'includes/js/settings_page.min.js', __FILE__ ), array( 'jquery' ), MO2F_VERSION, false );
			if ( strpos( $hook, 'page_mo_2fa' ) ) {

				wp_enqueue_script( 'momls_wpns_admin_datatable_script', plugins_url( 'includes/js/jquery.dataTables.min.js', __FILE__ ), array( 'jquery' ), MO2F_VERSION, false );
				wp_enqueue_script( 'momls_wpns_qrcode_script', plugins_url( '/includes/jquery-qrcode/jquery-qrcode.min.js', __FILE__ ), array(), MO2F_VERSION, false );
				wp_enqueue_script( 'momls_wpns_min_qrcode_script', plugins_url( '/includes/jquery-qrcode/jquery-qrcode.min.js', __FILE__ ), array(), MO2F_VERSION, false );
			}
		}
		/**
		 * Show nofications on admin dashboard depend on type i.e. sucess, error and notice.
		 *
		 * @param string $content - message to be shown on dashboard.
		 * @param string $type - type of message to be shown.
		 * @return void
		 */
		public function momls_show_message( $content, $type ) {
			if ( 'CUSTOM_MESSAGE' === $type ) {
				echo "<div class='overlay_not_JQ_success' id='pop_up_success'><p class='popup_text_not_JQ'>" . wp_kses_post( $content ) . '</p> </div>';
				?>
				<script type="text/javascript">
				setTimeout(function () {
				var element = document.getElementById("pop_up_success");
					element.classList.toggle("overlay_not_JQ_success");
					element.innerHTML = "";
				}, 4000);					
				</script>
				<?php
			}
			if ( 'NOTICE' === $type ) {
				echo "<div class='overlay_not_JQ_error' id='pop_up_error'><p class='popup_text_not_JQ'>" . wp_kses_post( $content ) . '</p> </div>';
				?>
				<script type="text/javascript">
				setTimeout(function () {
					var element = document.getElementById("pop_up_error");
						element.classList.toggle("overlay_not_JQ_error");
						element.innerHTML = "";
					}, 4000);						
				</script>
				<?php
			}
			if ( 'ERROR' === $type ) {
				echo "<div class='overlay_not_JQ_error' id='pop_up_error'><p class='popup_text_not_JQ'>" . wp_kses_post( $content ) . '</p> </div>';
				?>
				<script type="text/javascript">
				setTimeout(function () {
					var element = document.getElementById("pop_up_error");
					element.classList.toggle("overlay_not_JQ_error");
					element.innerHTML = "";
					}, 4000);		
				</script>
				<?php
			}
			if ( 'SUCCESS' === $type ) {

				echo "<div class='overlay_not_JQ_success' id='pop_up_success'><p class='popup_text_not_JQ'>" . wp_kses_post( $content ) . '</p> </div>';
				?>
					<script type="text/javascript">
					setTimeout(function () {
					var element = document.getElementById("pop_up_success");
						element.classList.toggle("overlay_not_JQ_success");
						element.innerHTML = "";
					}, 4000);							
					</script>
				<?php
			}
		}
		/**
		 * Function footer link.
		 *
		 * @return void
		 */
		public function momls_footer_link() {
			echo wp_kses(
				Momls_Wpns_Constants::FOOTER_LINK,
				array(
					'a' => array(
						'style' => array(),
						'href'  => array(),
					),
				)
			);
		}

		/**
		 * Includes all the required handler and controller files.
		 *
		 * @return void
		 */
		public function momls_includes() {
			require 'database/class-momls-wpns-db.php';
			require 'database/class-momls-db.php';
			require 'api/class-momls-customer-setup.php';
			require 'api/class-momls-miniorange-rba-attributes.php';
			require 'api/class-momls-two-factor-setup.php';
			require 'handler/class-momls-feedback-handler.php';
			require 'handler/twofa/setup-twofa.php';
			require 'handler/twofa/class-momls-miniorange-authentication.php';
			require 'handler/twofa/class-momls-utility.php';
			require 'handler/twofa/class-momls-constants.php';
			require 'helper/class-momls-curl.php';
			require 'helper/class-momls-wpns-constants.php';
			require 'helper/class-momls-wpns-messages.php';
			require 'controllers/twofa/class-mo-mls-ajax.php';
			require 'helper/class-momls-wpns-utility.php';
		}
		/**
		 * Handle reset users functionality from user's profile section.
		 *
		 * @param string[] $actions - An array of action links to be displayed.
		 * @param object   $user_object - object for the currently listed user.
		 * @return string[]
		 */
		public function momls_reset_users( $actions, $user_object ) {
			if ( current_user_can( 'administrator', $user_object->ID ) && get_user_meta( $user_object->ID, 'currentMethod', true ) ) {
				if ( get_current_user_id() !== $user_object->ID ) {
					$actions['momls_reset_users'] = "<a class='momls_reset_users' href='" . admin_url( "users.php?page=reset&action=reset_edit&amp;user=$user_object->ID" ) . "'>" . __( 'Reset 2 Factor', 'cgc_ub' ) . '</a>';
				}
			}
			return $actions;

		}

		/**
		 * Add column in user profile section of WordPress.
		 *
		 * @param string[] $columns - The column header labels keyed by column ID.
		 * @return string[]
		 */
		public function momls_mapped_email_column( $columns ) {
			$columns['current_method'] = '2FA Method';
			return $columns;
		}

		/**
		 * Users page to reset 2FA for specific user
		 *
		 * @return void
		 */
		public function momls_reset_2fa_for_users_by_admin() {
			$nonce = wp_create_nonce( 'ResetTwoFnonce' );
			if ( ! isset( $_GET['mo2f_reset-2fa'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['mo2f_reset-2fa'] ) ), 'reset_edit' ) ) {
				wp_send_json( 'ERROR' );
			}
			if ( isset( $_GET['action'] ) && sanitize_text_field( wp_unslash( $_GET['action'] ) ) === 'reset_edit' ) {
				$user_id   = isset( $_GET['user_id'] ) ? sanitize_text_field( wp_unslash( $_GET['user_id'] ) ) : '';
				$user_info = get_userdata( $user_id );
				if ( is_numeric( $user_id ) ) {
					?>
					<form method="post" name="reset2fa" id="reset2fa" action="<?php echo esc_url( 'users.php' ); ?>">						
						<div class="wrap">
						<h1>Reset 2nd Factor</h1>
						<p>You have specified this user for reset:</p>
						<ul>
						<li>ID #<?php echo esc_html( $user_info->ID ); ?>: <?php echo esc_html( $user_info->user_login ); ?></li> 
						</ul>
							<input type="hidden" name="userid" value="
							<?php
							echo esc_attr( $user_id );
							?>
							">
							<input type="hidden" name="miniorange_reset_2fa_option" value="mo_reset_2fa">
							<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>">
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Confirm Reset" ></p>
						</div>
					</form>
					<?php
				}
			}
		}
		/**
		 * Function to save settings on 2FA reset.
		 *
		 * @return void
		 */
		public function momls_reset_save_settings() {
			if ( isset( $_POST['miniorange_reset_2fa_option'] ) && sanitize_text_field( wp_unslash( $_POST['miniorange_reset_2fa_option'] ) ) === 'mo_reset_2fa' ) {
				$nonce = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : null;
				if ( ! wp_verify_nonce( $nonce, 'ResetTwoFnonce' ) ) {

					return;
				}
				$user_id = isset( $_POST['userid'] ) && ! empty( $_POST['userid'] ) ? sanitize_text_field( wp_unslash( $_POST['userid'] ) ) : '';
				if ( ! empty( $user_id ) ) {
					if ( current_user_can( 'edit_user' ) ) {
						delete_user_meta( $user_id, 'currentMethod' );
					}
					delete_user_meta( $user_id, 'mo2f_kba_challenge' );
					delete_user_meta( $user_id, 'mo2f_2FA_method_to_configure' );
					delete_user_meta( $user_id, 'Security Questions' );
					delete_user_meta( $user_id, 'kba_questions_user' );
					delete_user_meta( $user_id, 'mo2f_2FA_method_to_test' );
				}
			}
			if ( isset( $_POST['mo_mfa_remove_account'] ) && 'momls_wpns_reset_account' === $_POST['mo_mfa_remove_account'] ) {
				delete_site_option( 'mo2f_customerKey' );
				delete_site_option( 'Momls_Api_key' );
				delete_site_option( 'mo2f_customer_token' );
				delete_site_option( 'mo2f_app_secret' );
				delete_site_option( 'momls_wpns_enable_log_requests' );
				delete_site_option( 'mo2f_miniorange_admin' );
				delete_site_option( 'mo_2factor_admin_registration_status' );
			}
		}
		/**
		 * Get mapped user profile column
		 *
		 * @param string $value Row value to be shown.
		 * @param string $column_name Column name.
		 * @param  int    $user_id User ID of the details to be shown.
		 * @return string
		 */
		public function momls_mapped_email_column_content( $value, $column_name, $user_id ) {
			$user = get_userdata( $user_id );
			if ( get_site_option( 'is_onprem' ) ) {
				$current_method = get_user_meta( $user->ID, 'currentMethod', true );
				if ( ! $current_method ) {
					$current_method = 'Not Registered for 2FA';
				}
			} else {
				global $momlsdb_queries;
				$current_method = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', $user->ID );
				if ( ! $current_method ) {
					$current_method = 'Not Registered for 2FA';
				}
			}

			if ( 'current_method' === $column_name ) {
				return $current_method;
			}
			return $value;
		}

	}

	new Miniorange_TwoFactor();
}
