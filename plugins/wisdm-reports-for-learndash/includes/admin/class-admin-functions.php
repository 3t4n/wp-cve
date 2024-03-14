<?php
/**
 * This file contains the class admin functions.
 *
 * @package learndash-reports-by-wisdmlabs
 */

namespace WisdmReportsLearndash;

class Admin_Functions {

	protected static $instance = null;

	/**
	 * This function has been used to create the static instance of the admin functions class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * This is the constructor for the class that will instantiate all processes related to the class.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * This method is used to add all the admin related actions and filter hooks.
	 */
	public function init_hooks() {
		add_action( 'wp_ajax_wrld_page_visit', array( $this, 'update_reporting_page_visit' ) );
		add_action( 'wp_ajax_wrld_gutenberg_block_visit', array( $this, 'wp_ajax_wrld_gutenberg_block_visit' ) );
		add_action( 'wp_ajax_wrld_notice_action', array( $this, 'wrld_notice_action' ) );
		add_action( 'init', array( $this, 'wrld_create_patterns_page' ), 99 );
		add_action( 'init', array( $this, 'wrld_create_student_patterns_page' ), 99 );
		// settings page ajax actions
		add_action( 'wp_ajax_wrld_skip_license_activation', array( $this, 'wrld_skip_license_activation' ) );
		add_action( 'wp_ajax_wrld_license_activate', array( $this, 'wrld_license_activate' ) );
		add_action( 'wp_ajax_wrld_license_deactivate', array( $this, 'wrld_license_deactivate' ) );
		add_action( 'wp_ajax_wrld_set_configuration', array( $this, 'wrld_set_configuration' ) );
		add_action( 'wp_ajax_wrld_update_settings', array( $this, 'wrld_update_settings' ) );
		add_action( 'wp_ajax_wrld_license_page_visit', array( $this, 'wrld_license_page_visit' ) );
		add_action( 'wp_ajax_wrld_exclude_settings_save', array( $this, 'wrld_exclude_settings_save' ) );
		add_action( 'wp_ajax_apply_time_tracking_settings', array( $this, 'apply_time_tracking_settings' ) );
		add_action( 'wp_ajax_wrld_exclude_load_users', array( $this, 'wrld_exclude_load_users' ) );
		// Welcome modal
		add_action( 'wp_ajax_wrld_welcome_modal_action', array( $this, 'wrld_welcome_modal_action' ) );
		add_action( 'wp_ajax_wrld_autoupdate_dashboard', array( $this, 'wrld_autoupdate_dashboard' ) );
		// add_action( 'admin_notices', array( $this, 'wrld_pattern_update_notice' ) );

		// helpscout
		add_action( 'wrld_helpscout_beacon', array( $this, 'wrld_add_beacon_helpscout_script' ), 99 );
		add_action( 'admin_init', array( $this, 'wrld_load_beacon_helpscout' ) );
	}

	public static function wp_ajax_wrld_gutenberg_block_visit(){
		$nonce = ! empty( $_REQUEST['wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wp_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'wrld-admin-settings' ) ) {
		    update_option( $_REQUEST['option_key'], true );
			\wp_send_json_success(
				array(
					'updated' => $_REQUEST['option_key'],
				)
			);
		}
		\wp_send_json_error();
	}

	public function wrld_pattern_update_notice() {
		if ( ! isset( $_GET['updated_pattern'] ) ) {
			return;
		}
		echo "<div class='notice notice-success'>
			<p>Dashboard Page successfully updated.</p>
		</div>";
	}

	public function wrld_autoupdate_dashboard() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => true,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}
		global $wrld_pattern;
		$reports_page = get_option( 'ldrp_reporting_page', false );
		$data         = array(
			'ID'           => $reports_page,
			'post_content' => $wrld_pattern,
		);
		wp_update_post( $data );
		wp_send_json_success(
			array(
				'url' => add_query_arg(
					array(
						'updated_pattern' => 1,
						'dla_op'          => true,
						'dla_oq'          => true,
					),
					get_permalink( $reports_page )
				),
			)
		);
		die();
	}

	/**
	 * This action is hooked to the ajax callback action 'wp_ajax_wrld_page_visit',
	 * on called verifies if the request is from the correct source & update teh value for the
	 * option 'wrld_reporting_page_visited'.
	 */
	public static function update_reporting_page_visit() {
		$nonce = ! empty( $_REQUEST['wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wp_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'reports-firrst-install-modal' ) ) {
			// update_option( 'wrld_reporting_page_visited', 2 );
			\wp_send_json_success();
		}
		\wp_send_json_error();
	}


	/**
	 * This action is hooked to the ajax callback action 'wp_ajax_wrld_notice_action',
	 * on called verifies if the request is from the correct source & update the value for the
	 * option 'wrld_last_skipped_on'.
	 */
	public static function wrld_notice_action() {
		$nonce = ! empty( $_REQUEST['wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wp_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'wrld-review-notice' ) ) {
			$user_action = sanitize_text_field( wp_unslash( $_REQUEST['user_action'] ) );
			switch ( $user_action ) {
				case 'wisdm-reports-for-ld-remind-later':
				case 'wisdm-reports-for-ld-post-review':
				case 'close':
					update_option( 'wrld_last_skipped_on', time() );
					break;
				default:
					update_option( 'ld-reports-review-dismissed', time() );
					break;
			}
			\wp_send_json_success();
		}
		\wp_send_json_error();
	}

	public function show_wisdm_reports_submenu() {
		?>
<div class="wrap">
	<h1><?php esc_html_e( 'Reports', 'learndash-reports-by-wisdmlabs' ); ?></h1>
		<?php
			$dashboard_id = get_option( 'ldrp_reporting_page', false );
		if ( ! $dashboard_id || 'publish' !== get_post_status( $dashboard_id ) ) {
			?>
	<p><?php esc_html_e( 'The page seems to have been deleted/removed. Please click on the link below to create the page manually.', 'learndash-reports-by-wisdmlabs' ); ?>
	</p>
	<a
		href="<?php echo esc_attr( add_query_arg( array( 'create_wisdm_reports' => true ) ) ); ?>"><?php esc_html_e( 'Create Reports Dashboard', 'learndash-reports-by-wisdmlabs' ); ?></a>
			<?php
		} else {
			?>
	<p><a class="primary-button primary-btn components-button is-primary"
			href="<?php echo esc_url( get_permalink( $dashboard_id ) ); ?>" target="_blank"
			style="background:#007cba;color:#fff;text-decoration:none;text-shadow:none;outline:1px solid transparent;padding:6px 12px;border-radius:2px;"><?php esc_html_e( 'Launch Reports Dashboard', 'learndash-reports-by-wisdmlabs' ); ?></a>
	</p>
			<?php
		}
		?>
</div>
		<?php
	}

	/**
	 * This function creates the page with the default WRLD pattern when a get parameter 'create_wisdm_reports' is set.
	 */
	public function wrld_create_patterns_page() {
		$new_page_creation = filter_input( INPUT_GET, 'create_wisdm_reports', FILTER_VALIDATE_BOOLEAN );
		$created_page      = get_option( 'ldrp_reporting_page', false );

		if ( $created_page && 'publish' !== get_post_status( $created_page ) ) {
			if ( ! empty( $new_page_creation ) ) {
				wrld_create_patterns_page( true );
				// add_action( 'admin_notices', '\WisdmReportsLearndash\Admin_Functions::report_page_creation_notice' );
			}
		} elseif ( false == $created_page ) {
			wrld_create_patterns_page( false );
				// add_action( 'admin_notices', '\WisdmReportsLearndash\Admin_Functions::report_page_creation_notice' );
		}
	}
	/**
	 * This function creates the page with the default WRLD pattern when a get parameter 'create_wisdm_reports' is set.
	 */
	public function wrld_create_student_patterns_page() {
		$new_page_creation = filter_input( INPUT_GET, 'create_student_reports', FILTER_VALIDATE_BOOLEAN );
		$created_page      = get_option( 'ldrp_student_page', false );

		if ( $created_page && 'publish' !== get_post_status( $created_page ) ) {
			if ( ! empty( $new_page_creation ) ) {
				wrld_create_student_patterns_page( true );
				// add_action( 'admin_notices', '\WisdmReportsLearndash\Admin_Functions::report_student_page_creation_notice' );
			}
		} elseif ( false == $created_page ) {
			wrld_create_student_patterns_page( false );
				// add_action( 'admin_notices', '\WisdmReportsLearndash\Admin_Functions::report_student_page_creation_notice' );
		}
	}

	/**
	 * The function checks if the plugin specified is installed on the website.
	 * - Currently works only for the WordPress single site.
	 *
	 * @param $plugin_slug plugin path and plugin file name, ex : learndash-reports-pro/learndash-reports-pro.php.
	 */
	public static function is_plugin_installed( $plugin_slug ) {
		if ( empty( $plugin_slug ) ) {
			return false;
		}

		// Check if needed functions exists - if not, require them
		if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins();
		return array_key_exists( $plugin_slug, $installed_plugins ) || in_array( $plugin_slug, $installed_plugins, true );
	}

	/**
	 * Shows admin a notice on the admin dashboard.
	 */
	public static function report_page_creation_notice() {
		echo '<div class="notice notice-success is-dismissible"><p>';
		echo esc_html__( 'Successfully created WISDM Reports For LearnDash page', 'learndash-reports-by-wisdmlabs' );
		echo '</p></div>';
	}

	/**
	 * Shows admin a notice on the admin dashboard.
	 */
	public static function report_student_page_creation_notice() {
		echo '<div class="notice notice-success is-dismissible"><p>';
		echo esc_html__( 'Successfully created WISDM Reports For LearnDash Student Quiz Reports page', 'learndash-reports-by-wisdmlabs' );
		echo '</p></div>';
	}

	/****************************************
	 * Actions for admin dashboard settings *
	 ****************************************/

	public static function wrld_skip_license_activation() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => true,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}
		$settings_data                            = get_option( 'wrld_settings', array() );
		$settings_data['skip-license-activation'] = true;
		update_option( 'wrld_settings', $settings_data );
		wp_send_json_success( array( 'success' => true ) );
	}

	public static function wrld_exclude_settings_save() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => true,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}

		$type  = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );
		$value = filter_input( INPUT_POST, 'value', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		update_option( 'exclude_' . $type, $value );

		wp_send_json_success( array( 'success' => true ) );
	}

	public static function apply_time_tracking_settings() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => true,
					'message' => __(
						'Nonce verification failed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}

		$status   = filter_input( INPUT_POST, 'status', FILTER_SANITIZE_STRING );
		$timer    = filter_input( INPUT_POST, 'timeout', FILTER_SANITIZE_NUMBER_INT );
		$message  = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );
		$btnlabel = filter_input( INPUT_POST, 'btnlabel', FILTER_SANITIZE_STRING );

		if ( ! empty( $timer ) ) {
			update_option( 'wrld_time_tracking_timer', $timer );
		}
		if ( ! empty( $message ) ) {
			update_option( 'wrld_time_tracking_message', $message );
		}
		if ( ! empty( $btnlabel ) ) {
			update_option( 'wrld_time_tracking_btnlabel', $btnlabel );
		}
		if ( ! empty( $status ) ) {
			$all_updates  = get_option( 'wrld_time_tracking_log', false );
			$current_time = current_time( 'timestamp' );
			if ( ! empty( $all_updates ) ) {
				$all_updates[] = $current_time;
			} else {
				$all_updates = array( $current_time );
			}
			update_option( 'wrld_time_tracking_status', $status );
			update_option( 'wrld_time_tracking_last_update', $current_time );
			update_option( 'wrld_time_tracking_log', $all_updates );
			wp_send_json_success(
				array(
					'success' => true,
					'time'    => date_i18n(
						'Y-m-d H:i:s',
						$current_time
					),
				)
			);
		}
		wp_send_json_success( array( 'success' => true ) );

	}

	public static function wrld_exclude_load_users() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => true,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}

		$page   = filter_input( INPUT_POST, 'page', FILTER_VALIDATE_INT );
		$search = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );

		if ( empty( $page ) ) {
			$page = 1;
		}

		$args = array(
			'number'         => 10,
			'paged'          => $page,
			'search_columns' => array(
				'user_login',
				'user_email',
				'user_nicename',
				'display_name',
			),
			'fields'         => array(
				'ID',
				'display_name',
			),
		);

		if ( ! empty( $search ) ) {
			$args['search'] = '*' . $search . '*';
		}

		$users = get_users( $args );
		if ( empty( $users ) ) {
			wp_send_json_error();
		}

		wp_send_json_success(
			array(
				'success' => true,
				'data'    => $users,
			)
		);
	}

	/**
	 * A function hooked to the ajax action wrld_license_activate,
	 * resposible for initiating the activation request for the
	 * reports pro plugin license key.
	 *
	 * @since 1.2.0
	 */
	public static function wrld_license_activate() {
		$str              = get_home_url();
		$site_url         = preg_replace( '#^https?://#', '', $str );
		$ldrp_plugin_data = array(
			'pluginShortName'   => 'WISDM Reports for LearnDash PRO',
			'pluginSlug'        => 'learndash-reports-pro',
			'itemId'            => 707478,
			'pluginVersion'     => LDRP_PLUGIN_VERSION,
			'pluginName'        => 'WISDM Reports for LearnDash PRO',
			'storeUrl'          => 'https://wisdmlabs.com/license-check/',
			'siteUrl'           => $site_url,
			'authorName'        => 'WisdmLabs',
			'pluginTextDomain'  => 'learndash-reports-pro',
			'baseFolderUrl'     => plugins_url( '/', __FILE__ ),
			'baseFolderDir'     => untrailingslashit( plugin_dir_path( __FILE__ ) ),
			'isTheme'           => false,
			'themeChangelogUrl' => 'https://wisdmlabs.com/elumine/documentation/change-log/',
			'mainFileName'      => 'learndash-reports-pro.php',
		);
		$nonce            = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$license_key      = isset( $_REQUEST['license_key'] ) ? sanitize_text_field( $_REQUEST['license_key'] ) : '';
		$nonce_valid      = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		$license_data     = '';

		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}

		if ( defined( 'LDRP_PLUGIN_VERSION' ) && ! empty( $license_key ) ) {
			$_POST['edd_learndash-reports-pro_license_key'] = $license_key;
			require_once LDRP_PLUGIN_DIR . 'licensing/class-wdm-license.php';
			require_once LDRP_PLUGIN_DIR . 'licensing/class-wdm-add-license-data.php';
			$license_manager = new \Licensing\WdmAddLicenseData( $ldrp_plugin_data );
			if ( method_exists( $license_manager, 'activate_license' ) ) {
				$license_manager->activate_license();
				$license_data = \Licensing\WdmGetLicenseData::get_data_from_db( $ldrp_plugin_data );
			} elseif ( method_exists( $license_manager, 'activateLicense' ) ) {
				$license_manager->activateLicense();
				$license_data = \Licensing\WdmGetLicenseData::getDataFromDb( $ldrp_plugin_data );
			} else {
				wp_send_json_error( array( 'status' => 'failed' ) );
			}
		}

		wp_send_json_success( array( 'license_data' => $license_data ) );
	}

	/**
	 * A function hooked to the ajax action wrld_license_deactivate,
	 * resposible for initiating the deactivation request for the
	 * reports pro plugin license key.
	 *
	 * @since 1.2.0
	 */
	public static function wrld_license_deactivate() {
		$str              = get_home_url();
		$site_url         = preg_replace( '#^https?://#', '', $str );
		$ldrp_plugin_data = array(
			'pluginShortName'   => 'WISDM Reports for LearnDash PRO',
			'pluginSlug'        => 'learndash-reports-pro',
			'itemId'            => 707478,
			'pluginVersion'     => LDRP_PLUGIN_VERSION,
			'pluginName'        => 'WISDM Reports for LearnDash PRO',
			'storeUrl'          => 'https://wisdmlabs.com/license-check/',
			'siteUrl'           => $site_url,
			'authorName'        => 'WisdmLabs',
			'pluginTextDomain'  => 'learndash-reports-pro',
			'baseFolderUrl'     => plugins_url( '/', __FILE__ ),
			'baseFolderDir'     => untrailingslashit( plugin_dir_path( __FILE__ ) ),
			'isTheme'           => false,
			'themeChangelogUrl' => 'https://wisdmlabs.com/elumine/documentation/change-log/',
			'mainFileName'      => 'learndash-reports-pro.php',
		);
		$nonce            = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid      = wp_verify_nonce( $nonce, 'wrld-admin-settings' );

		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}

		if ( defined( 'LDRP_PLUGIN_VERSION' ) ) {
			require_once LDRP_PLUGIN_DIR . 'licensing/class-wdm-license.php';
			require_once LDRP_PLUGIN_DIR . 'licensing/class-wdm-add-license-data.php';

			$license_manager = new \Licensing\WdmAddLicenseData( $ldrp_plugin_data );

			if ( method_exists( $license_manager, 'activate_license' ) ) {
				$license_manager->deactivate_license();
				$license_data = \Licensing\WdmGetLicenseData::get_data_from_db( $ldrp_plugin_data );
			} elseif ( method_exists( $license_manager, 'activateLicense' ) ) {
				$license_manager->deactivateLicense();
				$license_data = \Licensing\WdmGetLicenseData::getDataFromDb( $ldrp_plugin_data );
			} else {
				wp_send_json_error( array( 'status' => 'failed' ) );
			}
		}

		wp_send_json_success( array( 'license_data' => $license_data ) );
	}

	/**
	 * A function hooked to the ajax action wrld_set_configuration,
	 * resposible for setting up the configuration which enables inclusion
	 * of the reports page link in the primary menu.
	 *
	 * @since 1.2.0
	 */
	public static function wrld_set_configuration() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => true,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}
		$settings_data = get_option( 'wrld_settings', array() );
		if ( isset( $_REQUEST['add_in_menu'] ) ) {
			$settings_data['wrld-menu-config-setting'] = 'true' == sanitize_text_field( $_REQUEST['add_in_menu'] ) ? true : false;
		}
		if ( isset( $_REQUEST['add_student_in_menu'] ) ) {
			$settings_data['wrld-menu-student-setting'] = 'true' == sanitize_text_field( $_REQUEST['add_student_in_menu'] ) ? true : false;
		}
		update_option( 'wrld_settings', $settings_data );
		wp_send_json_success( array( 'success' => true ) );
	}

	/**
	 * A function hooked to the ajax action wrld_update_settings,
	 * resposible for setting up the configuration which enables inclusion
	 * of the reports page link in the primary menu.
	 *
	 * @since 1.2.0
	 */
	public static function wrld_update_settings() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}
		$settings_data = get_option( 'wrld_settings', array() );
		if ( isset( $_REQUEST['access_to_group_leader'] ) ) {
			$access_for_group_leader = 'true' == sanitize_text_field( $_REQUEST['access_to_group_leader'] ) ? true : false;
		}
		if ( isset( $_REQUEST['access_to_wdm_instructor'] ) ) {
			$access_for_wdm_instructor = 'true' == sanitize_text_field( $_REQUEST['access_to_wdm_instructor'] ) ? true : false;
		}
		if ( isset( $_REQUEST['add_in_menu'] ) ) {
			$settings_data['wrld-menu-config-setting'] = 'true' == sanitize_text_field( $_REQUEST['add_in_menu'] ) ? true : false;
		}
		if ( isset( $_REQUEST['add_student_in_menu'] ) ) {
			$settings_data['wrld-menu-student-setting'] = 'true' == sanitize_text_field( $_REQUEST['add_student_in_menu'] ) ? true : false;
		}

		if ( isset( $access_for_group_leader ) || isset( $access_for_wdm_instructor ) ) {
			$settings_data['dashboard-access-roles'] = array(
				'group_leader'   => $access_for_group_leader,
				'wdm_instructor' => $access_for_wdm_instructor,
			);
		}
		update_option( 'wrld_settings', $settings_data );
		wp_send_json_success( array( 'settings_data' => $settings_data ) );

	}


	public function wrld_license_page_visit() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-admin-settings' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}
		$page          = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		$wrld_pages    = array( 'wrld-dashboard-page', 'wrld-license-activation', 'wrld-other-plugins', 'wrld-help', 'wrld-settings' ,'wrld-whatsnew' , 'wrld-gutenbergblocks' );
		$settings_data = get_option( 'wrld_settings', array() );
		if ( in_array( $page, $wrld_pages, true ) ) {
			$settings_data['skip-license-activation'] = true;
		}
		update_option( 'wrld_settings', $settings_data );

		wp_send_json_success( array( 'settings_data' => $settings_data ) );
	}

	public static function wrld_welcome_modal_action() {
		$nonce       = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : '';
		$nonce_valid = wp_verify_nonce( $nonce, 'wrld-welcome-modal' );
		if ( ! $nonce_valid ) {
			wp_send_json_error(
				array(
					'success' => true,
					'message' => __(
						'Nonce verification fialed',
						'learndash-reports-by-wisdmlabs'
					),
				)
			);
		}
		if ( defined( 'LDRP_PLUGIN_VERSION' ) ) {
			update_option( 'wrld_visited_dashboard', 'pro' );
		} else {
			update_option( 'wrld_visited_dashboard', 'free' );
		}
	}

	/**
	 * Add the Helpscout Beacon script on the Reports Settings backend pages.
	 * Callback to action hook 'ldgr_helpscout_beacon'.
	 *
	 * @since 4.9.10
	 */
	public function wrld_add_beacon_helpscout_script() {
		?>
			<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});
</script><script type="text/javascript">window.Beacon('init', 'c4e8db5f-faeb-4368-a0b5-2d97be8457ba')</script>


		<?php

	}

		/**
		 * Calling function to the helpscout beacon.
		 * Callback to action hook 'wrld_helpscout_beacon'.
		 *
		 * @since 4.9.10
		 */
	public function wrld_load_beacon_helpscout() {
		global $pagenow, $typenow;

		// If CPT 'form' and edit.php page.
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && ( 'wrld-dashboard-page' === $_GET['page'] || 'wrld-license-activation' === $_GET['page'] || 'wrld-settings' === $_GET['page'] || 'wrld-other-plugins' === $_GET['page'] || 'wrld-help' === $_GET['page'] ) ) {
			/**
			 * Use the action to execute some code on the PEP backend page.
			 *
			 * @hooked add_beacon_helpscout_script - 10
			 */

			do_action( 'wrld_helpscout_beacon' );
		}
	}

}

\WisdmReportsLearndash\Admin_Functions::instance();
