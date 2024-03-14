<?php

/**
 * The standard WP login/registration-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0.9
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wp-login-registration
 */

/**
 * The woocommerce-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the woocommerce-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wp-login-registration
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_WP {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.0.9
	 * @access   private
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 * @since    1.0.9
	 * @access   private
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.9
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Maybe add fathom analytics script.
		add_action( 'login_footer', [ $this, 'fac_maybe_add_fathom_script' ] );

		// Login form.
		$this->fac_check_login_form();
		add_action( 'login_footer', [ $this, 'fac_login_footer' ] );

		// Registration form.
		$this->fac_check_registration_form();
		add_action( 'login_footer', [ $this, 'fac_registration_footer' ] );

		// Lost password form.
		$this->fac_check_lost_password_form();
		add_action( 'login_footer', [ $this, 'fac_lost_password_footer' ] );

	}

	/**
	 * Check event id of the login form.
	 *
	 * @since    1.0.9
	 */
	public function fac_check_login_form() {
		global $fac4wp_options;
		$update      = 0;
		$option_name = 'fac_options';
		$option      = (array) get_option( $option_name, [] );
		if ( $fac4wp_options['integrate-wp-login'] ) {
			if ( ! isset( $option['wp_login_event_id'] ) || empty( $option['wp_login_event_id'] ) ) {
				$event_title  = apply_filters( 'fac_login_event_title', __( 'WP Login', 'fathom-analytics-conversions' ) );
				$new_event_id = fac_add_new_fathom_event( $event_title );
				//$new_event_id = 'IN6NIAKX';
				if ( ! empty( $new_event_id ) ) {
					$option['wp_login_event_id'] = $new_event_id;
					$update                      = 1;
				}
			}
		}
		if ( $update ) {
			update_option( $option_name, $option );
		}

	}

	/**
	 * Check event id of the registration form.
	 *
	 * @since    1.0.9
	 */
	public function fac_check_registration_form() {
		global $fac4wp_options;
		$update      = 0;
		$option_name = 'fac_options';
		$option      = (array) get_option( $option_name, [] );
		if ( $fac4wp_options['integrate-wp-registration'] ) {
			if ( ! isset( $option['wp_registration_event_id'] ) || empty( $option['wp_registration_event_id'] ) ) {
				$event_title  = apply_filters( 'fac_registration_event_title', __( 'WP Registration', 'fathom-analytics-conversions' ) );
				$new_event_id = fac_add_new_fathom_event( $event_title );
				//$new_event_id = 'IN6NIAKX';
				if ( ! empty( $new_event_id ) ) {
					$option['wp_registration_event_id'] = $new_event_id;
					$update                             = 1;
				}
			}
		}
		if ( $update ) {
			update_option( $option_name, $option );
		}

	}

	/**
	 * Check event id of the lost password form.
	 *
	 * @since    1.0.9
	 */
	public function fac_check_lost_password_form() {
		global $fac4wp_options;
		$update      = 0;
		$option_name = 'fac_options';
		$option      = (array) get_option( $option_name, [] );
		if ( $fac4wp_options['integrate-wp-lost-password'] ) {
			if ( ! isset( $option['wp_lost_password_event_id'] ) || empty( $option['wp_lost_password_event_id'] ) ) {
				$event_title  = apply_filters( 'fac_lost_password_event_title', __( 'WP Lost Password', 'fathom-analytics-conversions' ) );
				$new_event_id = fac_add_new_fathom_event( $event_title );
				//$new_event_id = 'IN6NIAKX';
				if ( ! empty( $new_event_id ) ) {
					$option['wp_lost_password_event_id'] = $new_event_id;
					$update                              = 1;
				}
			}
		}
		if ( $update ) {
			update_option( $option_name, $option );
		}

	}

	/**
	 * Maybe add the Fathom JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_maybe_add_fathom_script() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-login'] || $fac4wp_options['integrate-wp-registration'] || $fac4wp_options['integrate-wp-lost-password'] ) {
			if ( $fac4wp_options['fac_fathom_analytics_is_active'] && function_exists( 'fathom_print_js_snippet' ) ) {
				fathom_print_js_snippet();
			}
		}
	}

	/**
	 * Add the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_login_footer() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-login'] ) {
			$option = (array) get_option( 'fac_options', [] );
			if ( ! empty( $option['wp_login_event_id'] ) ) {
				$fac_content = '
<!-- Fathom Analytics Conversions -->
<script data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				$fac_content .= '
	window.addEventListener("load", (event) => {
		const login_form = document.getElementById("loginform");
		if(login_form) {
			login_form.addEventListener("submit", () => {
                fathom.trackGoal("' . $option['wp_login_event_id'] . '", 0);
            });
        }
	});';
				$fac_content .= '
</script>
<!-- END Fathom Analytics Conversions -->
';
				echo $fac_content;
			}
		}
	}

	/**
	 * Add the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_registration_footer() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-registration'] ) {
			$option = (array) get_option( 'fac_options', [] );
			if ( ! empty( $option['wp_registration_event_id'] ) ) {
				$fac_content = '
<!-- Fathom Analytics Conversions -->
<script data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				$fac_content .= '
	window.addEventListener("load", (event) => {
		const register_form = document.getElementById("registerform");
		if(register_form) {
			register_form.addEventListener("submit", () => {
                fathom.trackGoal("' . $option['wp_registration_event_id'] . '", 0);
            });
        }
	});';
				$fac_content .= '
</script>
<!-- END Fathom Analytics Conversions -->
';
				echo $fac_content;
			}
		}
	}

	/**
	 * Add the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.9
	 */
	public function fac_lost_password_footer() {
		global $fac4wp_options;
		if ( ! function_exists( 'is_fac_fathom_analytic_active' ) || ! is_fac_fathom_analytic_active() ) {
			return;
		}
		if ( $fac4wp_options['integrate-wp-lost-password'] ) {
			$option = (array) get_option( 'fac_options', [] );
			if ( ! empty( $option['wp_lost_password_event_id'] ) ) {
				$fac_content = '
<!-- Fathom Analytics Conversions -->
<script data-cfasync="false" data-pagespeed-no-defer type="text/javascript">';
				$fac_content .= '
	window.addEventListener("load", (event) => {
		const lost_password_form = document.getElementById("lostpasswordform");
		if(lost_password_form) {
			lost_password_form.addEventListener("submit", () => {
                fathom.trackGoal("' . $option['wp_lost_password_event_id'] . '", 0);
            });
        }
	});';
				$fac_content .= '
</script>
<!-- END Fathom Analytics Conversions -->
';
				echo $fac_content;
			}
		}
	}

}
