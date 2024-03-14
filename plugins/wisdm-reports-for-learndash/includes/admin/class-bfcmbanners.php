<?php
/**
 * Report Export Form/AJAX request Submission.
 *
 * @package learndash-reports-by-wisdmlabs.
 */

namespace bfcm_banner {

	/**
	 * Class to handle ___
	 */
	class BfcmBanners {

		/**
		 * Instance of this class.
		 *
		 * @since 1.4.1.
		 *
		 * @var object
		 */
		protected static $instance = null;
		/**
		 * Initialization.
		 */
		public function __construct() {

			add_action( 'admin_notices', array( $this, 'wrld_banner_logic' ) );

		}

		/**
		 * Returns an instance of this class.
		 *
		 * @since 1.4.1
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * This function handles the banner display logic
		 *
		 * @since 1.4.1
		 */
		public function wrld_banner_logic() {
			global $current_user;
			if ( ( in_array( 'wdm_instructor', $current_user->roles, true ) || in_array( 'group_leader', $current_user->roles, true ) ) && ! in_array( 'administrator', $current_user->roles, true ) ) {
				return;
			}
			$get_data = filter_var_array( $_GET, FILTER_UNSAFE_RAW ); // phpcs:ignore WordPress.Security.NonceVerification
			if ( isset( $get_data['wdm_banner_type'] ) ) {
				if ( 'pre' === $get_data['wdm_banner_type'] ) {
					update_option( 'wdm_pre_bfcm_dismiss', true );
				};

				if ( 'upgrade' === $get_data['wdm_banner_type'] ) {
					update_option( 'wrld_upgrade_bfcm_dismiss', true );
				};

				if ( 'lifetime' === $get_data['wdm_banner_type'] ) {
					update_option( 'wrld_lifetime_bfcm_dismiss', true );
				};
			}

			$date_from_user = gmdate( 'Y-m-d' );

			// Spinner banner per BFCM.
			if ( ! get_option( 'wdm_pre_bfcm_dismiss' ) ) {
				$start_date = '2022-11-04';
				$end_date   = '2022-11-23';
				$is_pre     = $this->check_in_range( $start_date, $end_date, $date_from_user );
				if ( $is_pre ) {
					$this->wrld_bfcm_black_friday();
				}
			}

				$start_date = '2022-11-24';
				$end_date   = '2022-11-30';
				$is_during  = $this->check_in_range( $start_date, $end_date, $date_from_user );
			if ( $is_during ) {
				$validity = get_option( 'edd_learndash-reports-pro_license_status', false );
				if ( 'valid' !== $validity && defined( 'WRLD_PLUGIN_VERSION' ) ) {// plugin activation check.
					if ( ! get_option( 'wrld_upgrade_bfcm_dismiss' ) ) {
						$this->wrld_bfcm_during();
					}
				} else {
					if ( ! get_option( 'wrld_lifetime_bfcm_dismiss' ) ) {
						$license_key = trim( get_option( 'edd_learndash-reports-pro_license_key' ) );
						$key_data    = get_option( 'edd_learndash-reports-pro_' . $license_key . '_data', array() );
						if ( ! empty( $key_data ) ) {
							$expiration_date = $key_data->expires;
							if ( 'lifetime' !== $expiration_date ) {
								$this->wrld_bfcm_during_lifetime();
							}
						} else {
							$this->wrld_bfcm_during_lifetime();
						}
					}
				}
			}

		}

		/**
		 * Helper function that checks the specified date is in a given range or not.
		 *
		 * @param date $start_date This is the start date of the pre BFCM Sale.
		 * @param date $end_date This is the end date of the pre BFCM Sale.
		 * @param date $date_from_user This is the referenced current date of the system.
		 *
		 * @since 1.4.1
		 */
		public function check_in_range( $start_date, $end_date, $date_from_user ) {
			// Convert to timestamp.
			$start_ts = strtotime( $start_date );
			$end_ts   = strtotime( $end_date );
			$user_ts  = strtotime( $date_from_user );

			// Check that user date is between start & end.
			return ( ( $user_ts >= $start_ts ) && ( $user_ts <= $end_ts ) );
		}

		/**
		 * Returns html bfcm content.
		 *
		 * @since 1.4.1
		 */
		public function wrld_bfcm_black_friday() {
			global $wp;
			$current_url = get_permalink( add_query_arg( array(), $wp->request ) );
			if (is_rtl()) {
				wp_enqueue_style( 'wrld_bfcm_style', WRLD_REPORTS_SITE_URL . '/assets/css/bfcm.rtl.css', array(), WRLD_PLUGIN_VERSION );
			} else {
				wp_enqueue_style( 'wrld_bfcm_style', WRLD_REPORTS_SITE_URL . '/assets/css/bfcm.css', array(), WRLD_PLUGIN_VERSION );
			}

			$banner_head       = __( 'Play a game with WisdmLabs to get ahead of the black friday sale', 'learndash-reports-by-wisdmlabs' );
			$banner_message    = __( 'Spin the Wheel to Win Free Access or discount on our Premium WordPress and LearnDash Product', 'learndash-reports-by-wisdmlabs' );
			$button_text       = __( 'SPIN AND WIN', 'learndash-reports-by-wisdmlabs' );
			$link              = 'https://wisdmlabs.com/wisdm-black-friday-giveaway?utm_source=giveaway&utm_medium=spinthewheel&utm_campaign=bfcm22';
			$wisdm_logo        = WRLD_REPORTS_SITE_URL . '/assets/images/logo-bfcm-wsdm.png';
			$background        = WRLD_REPORTS_SITE_URL . '/assets/images/spin_wheel.png';
			$dismiss_attribute = add_query_arg( array( 'wdm_banner_type' => 'pre' ), $current_url );
			include WRLD_REPORTS_PATH . '/includes/templates/bfcm-banner.php';
		}

		/**
		 * Returns html of banners.
		 *
		 * @since 1.4.1
		 */
		public function wrld_bfcm_during() {
			global $wp;
			$current_url = get_permalink( add_query_arg( array(), $wp->request ) );
			if (is_rtl()) {
				wp_enqueue_style( 'wrld_bfcm_style', WRLD_REPORTS_SITE_URL . '/assets/css/bfcm.rtl.css', array(), WRLD_PLUGIN_VERSION );
			} else {
				wp_enqueue_style( 'wrld_bfcm_style', WRLD_REPORTS_SITE_URL . '/assets/css/bfcm.css', array(), WRLD_PLUGIN_VERSION );
			}
			$banner_head          = __( 'Save BIG on both Money and Time this Black Friday Cyber Monday Season! ', 'learndash-reports-by-wisdmlabs' );
			$banner_message       = __( 'Bridge the gap with a complete Reporting Solution and grow your e-learning business', 'learndash-reports-by-wisdmlabs' );
			$banner_message_addon = __( 'Get up to 70% off on WISDM Reports for LearnDash Pro', 'learndash-reports-by-wisdmlabs' );
			$button_text          = __( 'UPGRADE NOW', 'learndash-reports-by-wisdmlabs' );
			$link                 = 'https://wisdmlabs.com/reports-for-learndash/?utm_source=freeplugin&utm_medium=banner&utm_campaign=bfcm22';
			$wisdm_logo           = WRLD_REPORTS_SITE_URL . '/assets/images/logo-bfcm-wsdm.png';
			$background           = WRLD_REPORTS_SITE_URL . '/assets/images/bfcm-right-during.png';
			$dismiss_attribute    = add_query_arg( array( 'wdm_banner_type' => 'upgrade' ), $current_url );
			include WRLD_REPORTS_PATH . '/includes/templates/bfcm-banner.php';
		}

		/**
		 * Returns html of banners.
		 *
		 * @since 1.4.1
		 */
		public function wrld_bfcm_during_lifetime() {
			global $wp;
			$current_url = get_permalink( add_query_arg( array(), $wp->request ) );
			if (is_rtl()) {
				wp_enqueue_style( 'wrld_bfcm_style', WRLD_REPORTS_SITE_URL . '/assets/css/bfcm.rtl.css', array(), WRLD_PLUGIN_VERSION );
			} else {
				wp_enqueue_style( 'wrld_bfcm_style', WRLD_REPORTS_SITE_URL . '/assets/css/bfcm.css', array(), WRLD_PLUGIN_VERSION );
			}
			$banner_head          = __( 'Save BIG on both Money and Time this Black Friday Cyber Monday Season! ', 'learndash-reports-by-wisdmlabs' );
			$banner_message       = __( 'Bridge the gap with a complete Reporting Solution and grow your e-learning business', 'learndash-reports-by-wisdmlabs' );
			$banner_message_addon = __(
				'Get up to 70% off on WISDM Reports for LearnDash Pro Lifetime Pack
            ',
				'learndash-reports-by-wisdmlabs'
			);
			$button_text          = __( 'Upgrade to Lifetime', 'learndash-reports-by-wisdmlabs' );
			$link                 = 'https://wisdmlabs.com/reports-for-learndash/?utm_source=proplugin&utm_medium=banner&utm_campaign=bfcm22';
			$wisdm_logo           = WRLD_REPORTS_SITE_URL . '/assets/images/logo-bfcm-wsdm.png';
			$banner_height        = '200px';
			$background           = WRLD_REPORTS_SITE_URL . '/assets/images/bfcm-right-during.png';
			$dismiss_attribute    = add_query_arg( array( 'wdm_banner_type' => 'lifetime' ), $current_url );
			include WRLD_REPORTS_PATH . '/includes/templates/bfcm-banner.php';
		}

	}
	BfcmBanners::get_instance();
}
