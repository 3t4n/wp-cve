<?php
/**
 * This file contains the class time spent onboarding functions.
 *
 * @package learndash-reports-by-wisdmlabs
 */

namespace WisdmReportsLearndash;

/**
 * Learner Time spent onboarding logic
 *
 * @since 1.4.1
 */
class Time_Spent_Onboarding {

	/**
	 * Description message
	 *
	 * @var $instance comment about this variable.
	 *
	 * This is single instance of a class.
	 */
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
		add_action( 'init', array( $this, 'wrld_time_spent_onboarding_dismiss_logic' ) );
		add_action( 'admin_notices', array( $this, 'wrld_time_spent_onboarding_logic' ) );
		// add_action( 'init', array( $this, 'wrld_course_progress_onboarding_dismiss_logic' ) );
		// add_action( 'admin_notices', array( $this, 'wrld_course_progress_onboarding_logic' ) );
	}

	/**
	 * Returns html of banners.
	 *
	 * @since 1.4.1
	 */
	public function wrld_time_spent_onboarding_logic() {
       
		if ( ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			return; // pro version is not active.
		}

		$plugin_first_activation = true;//get_option( 'wrld_free_plugin_first_activated', false );

		if ( ! $plugin_first_activation ) {
			return;
		}
		$wrld_page = get_option( 'ldrp_reporting_page', false );

		if ( empty( $wrld_page ) || 'publish' !== get_post_status( $wrld_page ) ) {
			return;
		}
        
        $full_url = home_url()."/wp-admin/admin.php?page=wrld-settings&dts_am=true#wlrd-dashboard-time-settings";

		global $wpdb;

		$table_name = $wpdb->prefix . 'ld_time_entries';
		$query = "SELECT COUNT(*) FROM $table_name";

		$count = $wpdb->get_var($query);

		if ($count == 0) {
			update_option( 'migrated_course_time_access_data', time() );
			update_option( 'wrld_time_spent_admin_modal_dismiss', true );
		} else {
			if ( ! get_option( 'migrated_course_time_access_data', false ) ) {
				$this->show_onboarding_notice( $full_url );
			}
			if ( ! get_option( 'wrld_time_spent_admin_modal_dismiss' ) ) {
				$this->show_onboarding_modal( $full_url );
			}
		}

		
	}


	
	/**
	 * Returns html of banners.
	 *
	 * @since 1.4.1
	 */
	public function wrld_time_spent_onboarding_dismiss_logic() {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			$get_data = filter_var_array( $_GET, FILTER_UNSAFE_RAW ); // phpcs:ignore WordPress.Security.NonceVerification
			if ( isset( $get_data['dts_am'] ) ) {
				if ( $get_data['dts_am'] ) {
					update_option( 'wrld_time_spent_admin_modal_dismiss', true );
				};
			}
		}
	}

	
	/**
	 * Helper function to shoe onboarding notice.
	 *
	 * @param string $page_link This is the start date of the pre BFCM Sale.
	 *
	 * @since 1.4.1
	 */
	public function show_marketing_notice() {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			global $wp;
			$current_url = get_permalink( add_query_arg( array(), $wp->request ) );
			wp_enqueue_style( 'wrld_Time_Spent_Onboarding_style', WRLD_REPORTS_SITE_URL . '/assets/css/time-spent-onboarding.css', array(), WRLD_PLUGIN_VERSION );
			$button_text       = __( 'UPGRADE NOW', 'learndash-reports-by-wisdmlabs' );
			$link              = 'https://wisdmlabs.com/checkout/?edd_action=add_to_cart&download_id=707478&edd_options%5Bprice_id%5D=7&discount=upgradetopro&utm_source=wrld&utm_medium=wrld_update_banner&utm_campaign=wrld_in_plugin_settings_tab';
			$wisdm_logo        = WRLD_REPORTS_SITE_URL . '/assets/images/logo-bfcm-wsdm.png';
			$dismiss_attribute = add_query_arg(
				array(
					'wdm_banner_type'       => 'upgrade',
					'wrld_marketing_notice' => true,
				),
				$current_url
			);
			include WRLD_REPORTS_PATH . '/includes/templates/admin-notice-free-users.php';
		}
	}



	/**
	 * Helper function to shoe onboarding notice.
	 *
	 * @param string $page_link This is the start date of the pre BFCM Sale.
	 *
	 * @since 1.4.1
	 */
	public function show_onboarding_notice( $page_link ) {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {

			global $wp;
			$current_url = get_permalink( add_query_arg( array(), $wp->request ) );
			wp_enqueue_style( 'wrld_Time_Spent_Onboarding_style', WRLD_REPORTS_SITE_URL . '/assets/css/time-spent-onboarding.css', array(), WRLD_PLUGIN_VERSION );
			$banner_head          = __( 'Enhanced Course Time Spent Visualizations ', 'learndash-reports-by-wisdmlabs' );
			$banner_message       = __( 'We have enhanced the Time Spent on Course reports with improved visualizations, enabling a quick and intuitive understanding of the time spent by learners ', 'learndash-reports-by-wisdmlabs' );
			$banner_message_addon = __(
				'Update your Reports dashboard now!
			',
				'learndash-reports-by-wisdmlabs'
			);
			$button_text          = __( 'UPGRADE NOW', 'learndash-reports-by-wisdmlabs' );
			$link                 = 'https://wisdmlabs.com/reports-for-learndash/?utm_source=freeplugin&utm_medium=banner&utm_campaign=bfcm22';
			$wisdm_logo           = WRLD_REPORTS_SITE_URL . '/assets/images/logo-bfcm-wsdm.png';
			$background           = WRLD_REPORTS_SITE_URL . '/assets/images/bfcm-right-during.png';
			$dismiss_attribute    = add_query_arg( array( 'wdm_banner_type' => 'upgrade' ), $current_url );
			include WRLD_REPORTS_PATH . '/includes/templates/time-spent-onboarding.php';
		}
	}

	/**
	 * Helper function to shoe onboarding modal.
	 *
	 * @param string $page_link This is the start date of the pre BFCM Sale.
	 *
	 * @since 1.4.1
	 */
	public function show_onboarding_modal( $page_link ) {

		$screen = get_current_screen();

		if ( ! empty( $screen ) && 'plugins' !== $screen->base && 'update' !== $screen->base ) {
			return; // not on admin plugins page.
		}

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			// show modal asking for update.

			wp_enqueue_style( 'wrld_time_spent_modal', WRLD_REPORTS_SITE_URL . '/assets/css/wrld-modal-time-spent.css', array(), WRLD_PLUGIN_VERSION );
			wp_enqueue_script( 'wrld_modal_ts_script', WRLD_REPORTS_SITE_URL . '/assets/js/wrld-modal-time-spent.js', array( 'jquery' ), WRLD_PLUGIN_VERSION );
			$modal_head = __(
				'Enhanced Course Time Spent Visualization
			',
				'learndash-reports-by-wisdmlabs'
			);

			$info_url          = '#';
			$modal_action_text = __( 'Got It!', 'learndash-reports-by-wisdmlabs' );
			$action_close      = 'update-pro';
			$wp_nonce          = wp_create_nonce( 'reports-firrst-install-modal' );
			$dismiss_link      = add_query_arg( array( 'dts_am' => true ) );

			include_once WRLD_REPORTS_PATH . '/includes/templates/admin-modal-time-spent.php';
		}
	}

}

\WisdmReportsLearndash\Time_Spent_Onboarding::instance();
