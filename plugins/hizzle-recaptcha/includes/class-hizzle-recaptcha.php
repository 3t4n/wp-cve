<?php
/**
 * Contains the main plugin class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin Class.
 *
 */
class Hizzle_reCAPTCHA {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public static $version = '1.0.3';

	/**
	 * Loaded integrations.
	 *
	 * @var Hizzle_reCAPTCHA_Integration[]
	 */
	public static $integrations = array();

	/**
	 * Load scripts.
	 *
	 * @var bool
	 */
	public static $load_scripts = false;

	/**
	 * Whether or not the current request has been validated.
	 *
	 * @var bool
	 */
	public static $is_valid = false;

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_filter( 'plugin_action_links_hizzle-recaptcha/plugin.php', array( $this, 'link_to_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
		add_action( 'wp_footer', array( $this, 'maybe_add_scripts' ), 11 );
		add_action( 'login_footer', array( $this, 'maybe_add_scripts' ), 11 );
		add_action( 'init', array( $this, 'load_integrations' ), 0 );
	}

	/**
	 * Links to the settings page.
	 *
	 * @param array $actions
	 */
	public function link_to_settings( $actions ) {

		$actions[] = sprintf(
			'<a href="%s" style="color: #33691e;">%s</a>',
			esc_url( add_query_arg( 'page', 'hizzle-recaptcha', admin_url( 'options-general.php' ) ) ),
			__( 'Settings', 'hizzle-recaptcha' )
		);

		return $actions;

	}

	/**
	 * Checks if we should load the integrations.
	 *
	 * @since 1.0.0
	 */
	public function load_integrations() {

		// Ensure that captcha is set-up correctly.
		if ( ! $this->show_captcha() ) {
			return;
		}

		// Load the base integrations class.
		require_once plugin_dir_path( __FILE__ ) . 'integrations/base.php';

		// Load a list of available enabled integrations.
		$available_integrations = $this->get_available_integrations();
		foreach ( $this->get_enabled_integrations() as $integration ) {

			// Ensure that it is available.
			if ( isset( $available_integrations[ $integration ] ) ) {
				require_once $available_integrations[ $integration ]['file'];
				self::$integrations[ $integration ] = new $available_integrations[ $integration ]['class']();
			}
		}

	}

	/**
	 * Loads the recaptcha code if we're displaying it on this page.
	 *
	 * @since 1.0.0
	 */
	public function maybe_add_scripts() {
		if ( self::$load_scripts && $this->show_captcha() ) {

			if ( 'recaptcha' === hizzle_recaptcha_get_option( 'load_from' ) ) {
				$url = apply_filters( 'hizzle_recaptcha_api_url', 'https://www.recaptcha.net/recaptcha/api.js' );
			} else {
				$url = apply_filters( 'hizzle_recaptcha_api_url', 'https://www.google.com/recaptcha/api.js' );
			}

			wp_enqueue_script( 'recaptcha', $url, array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}
	}

	/**
	 * Registers the settings menu.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_menu() {
		add_options_page(
			__( 'reCAPTCHA by Hizzle', 'hizzle-recaptcha' ),
			__( 'reCAPTCHA by Hizzle', 'hizzle-recaptcha' ),
			'manage_options',
			'hizzle-recaptcha',
			array( $this, 'display_settings_page' )
		);
	}

	/**
	 * Displays the settings menu.
	 *
	 * @since 1.0.0
	 */
	public function display_settings_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$saved_settings         = $this->maybe_save_settings();
		$enabled_integrations   = $this->get_enabled_integrations();
		$available_integrations = wp_list_pluck( $this->get_available_integrations(), 'name' );
		$settings               = array(
			'site_key'       => array(
				'type'    => 'text',
				'label'   => __( 'Site Key', 'hizzle-recaptcha' ),
				'default' => '',
			),
			'secret_key'     => array(
				'type'    => 'text',
				'label'   => __( 'Secret Key', 'hizzle-recaptcha' ),
				'default' => '',
			),
			'load_from'      => array(
				'type'    => 'select',
				'label'   => __( 'Load from', 'hizzle-recaptcha' ),
				'options' => array(
					'google'    => 'google.com',
					'recaptcha' => 'recaptcha.net',
				),
				'default' => 'google',
				'desc'    => __( 'Google is the default, but you can use recaptcha.net if Google is blocked in your country.', 'hizzle-recaptcha' ),
			),
			'hide_logged_in' => array(
				'type'    => 'checkbox',
				'label'   => __( 'Hide from logged in users', 'hizzle-recaptcha' ),
				'label2'  => __( 'If checked, logged in users will not see the reCAPTCHA checkbox', 'hizzle-recaptcha' ),
				'default' => '',
			),
		);

		include plugin_dir_path( __FILE__ ) . 'settings.php';
	}

	/**
	 * Displays the settings menu.
	 *
	 * @since 1.0.0
	 */
	protected function maybe_save_settings() {

		if ( empty( $_POST['hizzle_recaptcha'] ) ) {
			return '';
		}

		if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_POST['hizzle-recaptcha'], 'hizzle-recaptcha' ) ) {
			return false;
		}

		return update_option( 'hizzle_recaptcha', wp_kses_post_deep( $_POST['hizzle_recaptcha'] ) );
	}

	/**
	 * Retrieves an array of enabled integrations.
	 *
	 * @since 1.0.0
	 */
	protected function get_enabled_integrations() {
		$options = get_option( 'hizzle_recaptcha' );

		if ( empty( $options ) ) {
			return array( 'registration', 'resetpass', 'comment' );
		}

		return empty( $options['enabled_integrations'] ) ? array() : $options['enabled_integrations'];
	}

	/**
	 * Retrieves an array of available integrations.
	 *
	 * @since 1.0.0
	 */
	protected function get_available_integrations() {

		$integrations = apply_filters(
			'hizzle_recaptcha_available_integrations',
			array(
				'login'            => array(
					'name'  => __( 'Login Form', 'hizzle-recaptcha' ),
					'class' => 'Hizzle_reCAPTCHA_Login_Integration',
					'file'  => plugin_dir_path( __FILE__ ) . 'integrations/login.php',
				),
				'registration'     => array(
					'name'  => __( 'Registration Form', 'hizzle-recaptcha' ),
					'class' => 'Hizzle_reCAPTCHA_Registration_Integration',
					'file'  => plugin_dir_path( __FILE__ ) . 'integrations/registration.php',
				),
				'resetpass'        => array(
					'name'  => __( 'Reset Password Form', 'hizzle-recaptcha' ),
					'class' => 'Hizzle_reCAPTCHA_Resetpass_Integration',
					'file'  => plugin_dir_path( __FILE__ ) . 'integrations/resetpass.php',
				),
				'lostpassword'     => array(
					'name'  => __( 'Lost Password Form', 'hizzle-recaptcha' ),
					'class' => 'Hizzle_reCAPTCHA_Lost_Password_Integration',
					'file'  => plugin_dir_path( __FILE__ ) . 'integrations/lostpassword.php',
				),
				'comment'          => array(
					'name'  => __( 'Comment Form', 'hizzle-recaptcha' ),
					'class' => 'Hizzle_reCAPTCHA_Comment_Integration',
					'file'  => plugin_dir_path( __FILE__ ) . 'integrations/comment.php',
				),
				'woocommerce'      => array(
					'name'      => __( 'WooCommerce Checkout', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_WooCommerce_Integration',
					'installed' => function_exists( 'WC' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/woocommerce.php',
				),
				'noptin'           => array(
					'name'      => __( 'Noptin Newsletter Forms', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_Noptin_Integration',
					'installed' => function_exists( 'noptin' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/noptin.php',
				),
				'bbpress-reply'    => array(
					'name'      => __( 'New Reply (bbPress)', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_bbPress_Reply_Integration',
					'installed' => function_exists( 'bbpress' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/bbpress-reply.php',
				),
				'bbpress-topic'    => array(
					'name'      => __( 'New Topic (bbPress)', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_bbPress_Topic_Integration',
					'installed' => function_exists( 'bbpress' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/bbpress-topic.php',
				),
				'buddypress'       => array(
					'name'      => __( 'New Topic (BuddyPress)', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_BuddyPress_Integration',
					'installed' => function_exists( 'buddypress' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/buddypress.php',
				),
				'cf7'              => array(
					'name'      => __( 'Contact Form 7', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_CF7_Integration',
					'installed' => function_exists( 'wpcf7' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/cf7.php',
				),
				'mailchimp'        => array(
					'name'      => __( 'Mailchimp for WordPress', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_Mailchimp_Integration',
					'installed' => defined( 'MC4WP_VERSION' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/mailchimp.php',
				),
				'wpforms'          => array(
					'name'      => __( 'WPForms', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_WPForms_Integration',
					'installed' => function_exists( 'wpforms' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/wpforms.php',
				),
				'wpforo_new_topic' => array(
					'name'      => __( 'New Topic (wpForo)', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_WPforo_Topic_Integration',
					'installed' => function_exists( 'WPF' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/wpforo_new_topic.php',
				),
				'wpforo_reply'     => array(
					'name'      => __( 'New Reply (wpForo)', 'hizzle-recaptcha' ),
					'class'     => 'Hizzle_reCAPTCHA_WPforo_Reply_Integration',
					'installed' => function_exists( 'WPF' ),
					'file'      => plugin_dir_path( __FILE__ ) . 'integrations/wpforo_reply.php',
				),
			)
		);

		foreach ( $integrations as $key => $data ) {
			if ( isset( $data['installed'] ) && false === $data['installed'] ) {
				unset( $integrations[ $key ] );
			}
		}

		return $integrations;
	}

	/**
	 * Checks if we should show a captcha box.
	 *
	 * @since 1.0.0
	 */
	public function show_captcha() {
		$site_key   = hizzle_recaptcha_get_option( 'site_key' );
		$secret_key = hizzle_recaptcha_get_option( 'secret_key' );

		if ( empty( $site_key ) || empty( $secret_key ) ) {
			return false;
		}

		$hide_logged = hizzle_recaptcha_get_option( 'hide_logged_in', false );
		$should_show = 0 === get_current_user_id() || ! $hide_logged;

		return apply_filters( 'hizzle_recaptcha_show_captcha', $should_show );
	}

}

$GLOBALS['hizzle_recaptcha'] = new Hizzle_reCAPTCHA();
