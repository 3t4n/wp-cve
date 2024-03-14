<?php

class QiAddonsForElementor_Admin_Notice {
	private static $instance;

	// Used to determine after how many days of plugin usage review notice will show up
	private $days_after_plugin_activation = 7;

	// Used to determine number of days after which review notice will show up again if customer clicked "Maybe Later" option
	private $review_maybe_later_postponement_days = 1;

	public $plugin_slug = 'qi-addons-for-elementor';

	public $plugin_name = 'Qi Addons for Elementor';


	function __construct() {

		// Include scripts for plugin notice
		add_action( 'admin_enqueue_scripts', array( $this, 'register_script' ) );

		// Add admin notice
		add_action( 'admin_notices', array( $this, 'add_notice' ) );
		add_action( 'admin_notices', array( $this, 'add_review_notice' ) );

		// Add plugin deactivation notice
		add_action( 'current_screen', array( $this, 'add_deactivation_notice' ) );

		// Function that handles plugin notice
		add_action( 'wp_ajax_qi_addons_for_elementor_notice', array( $this, 'handle_notice' ) );
		add_action( 'wp_ajax_qi_addons_for_elementor_review_notice', array( $this, 'handle_review_notice' ) );
		add_action( 'wp_ajax_qi_addons_for_elementor_deactivation', array( $this, 'handle_deactivation' ) );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register_script() {
		wp_register_script( 'qi-addons-for-elementor-notice', QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/admin-notice/assets/js/admin-notice.min.js', array( 'jquery' ), false, false );
		wp_register_style( 'qi-addons-for-elementor-notice', QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/admin-notice/assets/css/admin-notice.min.css' );
	}

	public function add_notice() {
		$option = get_option( 'qi_addons_for_elementor_notice' );
		if ( 'disallowed' !== $option && 'allowed' !== $option ) {
			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-notice', 'templates/admin-notice', '', '' );

			wp_enqueue_script( 'qi-addons-for-elementor-notice' );
			wp_enqueue_style( 'qi-addons-for-elementor-notice' );
		}
	}

	public function handle_notice() {
		check_ajax_referer( 'qi-addons-for-elementor-notice-nonce', 'nonce' );

		$params = $_POST;

		if ( 'allowed' == $params['notice_action'] ) {
			$this->handle_allowed_notice();
		} else if ( 'disallowed' == $params['notice_action'] ) {
			$this->handle_disallowed_notice();
		} else {
			qi_addons_for_elementor_framework_get_ajax_status( 'fail', esc_html__( 'Something went wrong.', 'qi-addons-for-elementor' ) );
		}
	}

	private function handle_allowed_notice() {
		global $wp_version;

		$data = array(
			'plugin'      => 'qi-addons-for-elementor',
			'domain'      => get_site_url(),
			'date'        => date( 'Y-m-d H:i:s' ),
			'wp_version'  => $wp_version,
			'wp_language' => get_bloginfo( 'language' ),
			'php_version' => phpversion(),
		);

		$current_user = wp_get_current_user();
		if ( $current_user ) {
			$data['mail'] = $current_user->user_email;
		}

		$theme = $this->get_theme_info();
		if ( is_array( $theme ) && count( $theme ) > 0 ) {
			$data['active_theme'] = serialize( $theme );
		}

		$plugins = $this->get_active_plugins();
		if ( is_array( $plugins ) && count( $plugins ) > 0 ) {
			$data['active_plugins'] = serialize( $plugins );
		}

		$request_handler_url = 'https://api.qodeinteractive.com/plugin-statistics.php';

		$response      = wp_remote_post(
			$request_handler_url,
			array(
				'body' => $data,
			)
		);
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $response_body->success ) {
			update_option( 'qi_addons_for_elementor_notice', 'allowed', false );
			qi_addons_for_elementor_framework_get_ajax_status( 'success', esc_html__( 'Success', 'qi-addons-for-elementor' ) );
		} else {
			qi_addons_for_elementor_framework_get_ajax_status( 'fail', esc_html__( 'Something went wrong.', 'qi-addons-for-elementor' ) );
		}
	}

	private function handle_disallowed_notice() {
		update_option( 'qi_addons_for_elementor_notice', 'disallowed', false );

		qi_addons_for_elementor_framework_get_ajax_status( 'success', esc_html__( 'Success', 'qi-addons-for-elementor' ) );
	}

	public function get_theme_info() {
		$theme_info = wp_get_theme();

		$theme_info = array(
			'name'    => $theme_info->get( 'Name' ),
			'version' => $theme_info->get( 'Version' ),
			'author'  => $theme_info->get( 'Author' ),
		);

		return $theme_info;
	}

	public function get_active_plugins() {
		$active_plugins = array();
		$plugins        = get_plugins();

		foreach ( $plugins as $plugin_file => $plugin_data ) {
			if ( is_plugin_active( $plugin_file ) ) {
				$active_plugins[ $plugin_file ]['title']      = $plugin_data['Title'];
				$active_plugins[ $plugin_file ]['url']        = $plugin_data['PluginURI'];
				$active_plugins[ $plugin_file ]['author']     = $plugin_data['Author'];
				$active_plugins[ $plugin_file ]['author_url'] = $plugin_data['AuthorURI'];
				$active_plugins[ $plugin_file ]['version']    = $plugin_data['Version'];
			}
		}

		return $active_plugins;
	}

	public function add_review_notice() {
		$current_date                       = current_time( 'timestamp' );
		$review_option                      = get_option( 'qi_addons_for_elementor_review_status' );
		$plugin_install_date                = get_option( 'qi_addons_for_elementor_install_date' );
		$plugin_duration_in_seconds         = $current_date - $plugin_install_date;
		$minimal_duration_for_review_notice = $this->geneterate_number_of_seconds_from_days( $this->days_after_plugin_activation );

		if ( $plugin_duration_in_seconds > $minimal_duration_for_review_notice ) {
			if ( empty( $review_option ) ) {
				$this->print_review_form();
			}

			if ( ! empty( $review_option ) && 'reviewed' !== $review_option && 'already-reviewed' !== $review_option ) {
				$reminder_date = get_option( 'qi_addons_for_elementor_review_reminder_date' );

				if ( $current_date > $reminder_date ) {
					$this->print_review_form();
				}
			}
		}
	}

	public function print_review_form() {
		qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-notice', 'templates/admin-review-notice', '', '' );

		wp_enqueue_script( 'qi-addons-for-elementor-notice' );
		wp_enqueue_style( 'qi-addons-for-elementor-notice' );
	}

	public function handle_review_notice() {
		check_ajax_referer( 'qi-addons-for-elementor-review-notice-nonce', 'nonce' );

		$params = $_POST;
		$review_action = $params['review_action'];
		$current_date = current_time( 'timestamp' );
		$postponement_buffer = $this->geneterate_number_of_seconds_from_days( $this->review_maybe_later_postponement_days );
        $reminder_date = $current_date + $postponement_buffer;

		switch ( $review_action ) {
			case 'review':
				update_option( 'qi_addons_for_elementor_review_status', 'reviewed' );
				qi_addons_for_elementor_framework_get_ajax_status( 'success', esc_html__( 'Thank you for review!', 'qi-addons-for-elementor' ) );
				break;
			case 'maybe-later':
				update_option( 'qi_addons_for_elementor_review_status', 'postponed' );
				update_option( 'qi_addons_for_elementor_review_reminder_date', $reminder_date );
				qi_addons_for_elementor_framework_get_ajax_status( 'success', esc_html__( 'Thank you!', 'qi-addons-for-elementor' ) );
				break;
			case 'already-reviewed':
				update_option( 'qi_addons_for_elementor_review_status', 'already-reviewed' );
				qi_addons_for_elementor_framework_get_ajax_status( 'success', esc_html__( 'Thank you for review!', 'qi-addons-for-elementor' ) );
				break;
			default:
				qi_addons_for_elementor_framework_get_ajax_status( 'fail', esc_html__( 'Something went wrong.', 'qi-addons-for-elementor' ) );
				break;
		}

	}

	public function geneterate_number_of_seconds_from_days( $days ) {
		return $days * 24 * 60 * 60; // each day has 24 hours where each have 60 minutes and each minute has 60 seconds
	}

	public function add_deactivation_notice() {
		if ( ! $this->is_plugins_screen() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'load_deactivation_module' ) );
	}

	public function load_deactivation_module() {
		add_action( 'admin_footer', array( $this, 'print_deactivation_form' ) );

		wp_enqueue_script( 'qi-addons-for-elementor-notice' );
		wp_enqueue_style( 'qi-addons-for-elementor-notice' );
	}

	public function print_deactivation_form() {
		$params['plugin_slug'] = str_replace( '-', '_', $this->plugin_slug );
		$params['plugin_name'] = $this->plugin_name;

		qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-notice', 'templates/admin-deactivation-form', '', $params );
	}

	private function is_plugins_screen() {
		return in_array( get_current_screen()->id, array( 'plugins', 'plugins-network' ) );
	}

	public function handle_deactivation() {
		check_ajax_referer( 'qi-addons-for-elementor-deactivation-nonce', 'nonce' );

		$data = array(
			'plugin' => $this->plugin_slug,
			'site_lang' => get_bloginfo( 'language' ),
			'reason' => $_POST['reason'],
			'reason_additional_info' => $_POST['additionalInfo'],
			'date' => date( 'Y-m-d H:i:s' )
		);

        $request_handler_url = 'https://api.qodeinteractive.com/plugin-deactivation-feedback.php';

		$response = wp_remote_post(
			$request_handler_url,
			array(
				'body' => $data,
			)
		);

		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $response_body->success ) {
			qi_addons_for_elementor_framework_get_ajax_status( 'success', esc_html__( 'Thank you for the feedback!', 'qi-addons-for-elementor' ) );
		} else {
			qi_addons_for_elementor_framework_get_ajax_status( 'fail', esc_html__( 'Something went wrong with sending feedback.', 'qi-addons-for-elementor' ) );
		}
	}
}

QiAddonsForElementor_Admin_Notice::get_instance();
