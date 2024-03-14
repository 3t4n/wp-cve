<?php
defined( 'ABSPATH' ) || die();
require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-helper.php';

class STLSR_Setting {
	public static function add_action_links( $links ) {
		$settings_link = ( '<a href="' . esc_url( admin_url( 'options-general.php?page=stlsr_settings' ) ) . '">' . esc_html__( 'Settings', 'login-security-recaptcha' ) . '</a>' );
		array_unshift( $links, $settings_link );

		if ( ! class_exists( 'STLSP_Login_Security_Pro' ) ) {
			$premium_link = ( '<a target="_blank" style="font-weight: bold;" href="' . esc_url( STLSR_Helper::get_pro_url() ) . '">' . esc_html__( 'Get Premium', 'login-security-recaptcha' ) . '</a>' );
			array_unshift( $links, $premium_link );
		}

		return $links;
	}

	public static function redirect() {
		if ( get_option( 'stlsr_redirect_to_settings', false ) ) {
			delete_option( 'stlsr_redirect_to_settings' );
			?>
			<div class="updated notice notice-success is-dismissible">
				<p>
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %s: Settings page link. */
							__( 'To get started with Login Security Captcha, visit our <a href="%s" target="_blank">settings page</a>.', 'login-security-recaptcha' ),
							esc_url( admin_url( 'options-general.php?page=stlsr_settings' ) )
						),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					);
					?>
				</p>
				<p>
					<a class="button" href="<?php echo esc_url( admin_url( 'options-general.php?page=stlsr_settings' ) ); ?>" target="_blank">
						<?php esc_html_e( 'Login Security Captcha Settings', 'login-security-recaptcha' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}

	public static function save_captcha() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}

		if ( ! isset( $_POST['save-captcha'] ) || ! wp_verify_nonce( $_POST['save-captcha'], 'save-captcha' ) ) {
			die();
		}

		$grecaptcha_v2_default = STLSR_Helper::grecaptcha_v2_default();

		$grecaptcha_v2_site_key   = isset( $_POST['grecaptcha_v2_site_key'] ) ? sanitize_text_field( $_POST['grecaptcha_v2_site_key'] ) : '';
		$grecaptcha_v2_secret_key = isset( $_POST['grecaptcha_v2_secret_key'] ) ? sanitize_text_field( $_POST['grecaptcha_v2_secret_key'] ) : '';
		$grecaptcha_v2_theme      = isset( $_POST['grecaptcha_v2_theme'] ) ? sanitize_text_field( $_POST['grecaptcha_v2_theme'] ) : $grecaptcha_v2_default['theme'];

		$grecaptcha_v3_default = STLSR_Helper::grecaptcha_v3_default();

		$grecaptcha_v3_site_key   = isset( $_POST['grecaptcha_v3_site_key'] ) ? sanitize_text_field( $_POST['grecaptcha_v3_site_key'] ) : '';
		$grecaptcha_v3_secret_key = isset( $_POST['grecaptcha_v3_secret_key'] ) ? sanitize_text_field( $_POST['grecaptcha_v3_secret_key'] ) : '';
		$grecaptcha_v3_score      = isset( $_POST['grecaptcha_v3_score'] ) ? sanitize_text_field( $_POST['grecaptcha_v3_score'] ) : $grecaptcha_v3_default['score'];
		$grecaptcha_v3_badge      = isset( $_POST['grecaptcha_v3_badge'] ) ? sanitize_text_field( $_POST['grecaptcha_v3_badge'] ) : $grecaptcha_v3_default['badge'];
		$grecaptcha_v3_onaction   = isset( $_POST['grecaptcha_v3_onaction'] ) ? (bool) $_POST['grecaptcha_v3_onaction'] : false;

		$cf_turnstile_default = STLSR_Helper::cf_turnstile_default();

		$cf_turnstile_site_key    = isset( $_POST['cf_turnstile_site_key'] ) ? sanitize_text_field( $_POST['cf_turnstile_site_key'] ) : '';
		$cf_turnstile_secret_key  = isset( $_POST['cf_turnstile_secret_key'] ) ? sanitize_text_field( $_POST['cf_turnstile_secret_key'] ) : '';
		$cf_turnstile_theme       = isset( $_POST['cf_turnstile_theme'] ) ? sanitize_text_field( $_POST['cf_turnstile_theme'] ) : $cf_turnstile_default['theme'];
		$cf_turnstile_size        = isset( $_POST['cf_turnstile_size'] ) ? sanitize_text_field( $_POST['cf_turnstile_size'] ) : $cf_turnstile_default['size'];
		$cf_turnstile_disable_btn = isset( $_POST['cf_turnstile_disable_btn'] ) ? (bool) $_POST['cf_turnstile_disable_btn'] : false;

		$capt_login_enable = isset( $_POST['capt_login_enable'] ) ? (bool) $_POST['capt_login_enable'] : false;
		$capt_login        = isset( $_POST['capt_login'] ) ? sanitize_text_field( $_POST['capt_login'] ) : '';

		$capt_lostpassword_enable = isset( $_POST['capt_lostpassword_enable'] ) ? (bool) $_POST['capt_lostpassword_enable'] : false;
		$capt_lostpassword        = isset( $_POST['capt_lostpassword'] ) ? sanitize_text_field( $_POST['capt_lostpassword'] ) : '';

		$capt_register_enable = isset( $_POST['capt_register_enable'] ) ? (bool) $_POST['capt_register_enable'] : false;
		$capt_register        = isset( $_POST['capt_register'] ) ? sanitize_text_field( $_POST['capt_register'] ) : '';

		$capt_comment_default = STLSR_Helper::capt_comment_default();

		$capt_comment_enable    = isset( $_POST['capt_comment_enable'] ) ? (bool) $_POST['capt_comment_enable'] : false;
		$capt_comment           = isset( $_POST['capt_comment'] ) ? sanitize_text_field( $_POST['capt_comment'] ) : '';
		$capt_comment_logged_in = isset( $_POST['capt_comment_logged_in'] ) ? (bool) $_POST['capt_comment_logged_in'] : false;

		$errors = array();

		if ( ! in_array( $grecaptcha_v2_theme, array_keys( STLSR_Helper::grecaptcha_v2_themes() ), true ) ) {
			$grecaptcha_v2_theme = $grecaptcha_v2_default['theme'];
		}

		if ( ! in_array( $grecaptcha_v3_score, array_keys( STLSR_Helper::grecaptcha_v3_scores() ), true ) ) {
			$grecaptcha_v3_score = $grecaptcha_v3_default['score'];
		}

		if ( ! in_array( $grecaptcha_v3_badge, array_keys( STLSR_Helper::grecaptcha_v3_badges() ), true ) ) {
			$grecaptcha_v3_badge = $grecaptcha_v3_default['badge'];
		}

		if ( ! in_array( $cf_turnstile_theme, array_keys( STLSR_Helper::cf_turnstile_themes() ), true ) ) {
			$cf_turnstile_theme = $cf_turnstile_default['theme'];
		}

		if ( ! in_array( $cf_turnstile_size, array_keys( STLSR_Helper::cf_turnstile_sizes() ), true ) ) {
			$cf_turnstile_size = $cf_turnstile_default['size'];
		}

		$captcha_list_keys = array_keys( STLSR_Helper::captcha_list() );

		update_option(
			'stlsr_google_recaptcha_v2',
			array(
				'site_key'   => $grecaptcha_v2_site_key,
				'secret_key' => $grecaptcha_v2_secret_key,
				'theme'      => $grecaptcha_v2_theme,
			),
			true
		);

		update_option(
			'stlsr_google_recaptcha_v3',
			array(
				'site_key'   => $grecaptcha_v3_site_key,
				'secret_key' => $grecaptcha_v3_secret_key,
				'score'      => $grecaptcha_v3_score,
				'badge'      => $grecaptcha_v3_badge,
				'onaction'   => $grecaptcha_v3_onaction,
			),
			true
		);

		update_option(
			'stlsr_cf_turnstile',
			array(
				'site_key'    => $cf_turnstile_site_key,
				'secret_key'  => $cf_turnstile_secret_key,
				'theme'       => $cf_turnstile_theme,
				'size'        => $cf_turnstile_size,
				'disable_btn' => $cf_turnstile_disable_btn,
			),
			true
		);

		if ( $capt_login_enable && ! in_array( $capt_login, $captcha_list_keys, true ) ) {
			$errors['capt_login'] = esc_html__( 'Please select valid captcha.', 'login-security-recaptcha' );
		} else {
			update_option(
				'stlsr_login_captcha',
				array(
					'enable'  => $capt_login_enable,
					'captcha' => $capt_login,
				),
				true
			);
		}

		if ( $capt_lostpassword_enable && ! in_array( $capt_lostpassword, $captcha_list_keys, true ) ) {
			$errors['capt_lostpassword'] = esc_html__( 'Please select valid captcha.', 'login-security-recaptcha' );
		} else {
			update_option(
				'stlsr_lostpassword_captcha',
				array(
					'enable'  => $capt_lostpassword_enable,
					'captcha' => $capt_lostpassword,
				),
				true
			);
		}

		if ( $capt_register_enable && ! in_array( $capt_register, $captcha_list_keys, true ) ) {
			$errors['capt_register'] = esc_html__( 'Please select valid captcha.', 'login-security-recaptcha' );
		} else {
			update_option(
				'stlsr_register_captcha',
				array(
					'enable'  => $capt_register_enable,
					'captcha' => $capt_register,
				),
				true
			);
		}

		if ( $capt_comment_enable && ! in_array( $capt_comment, $captcha_list_keys, true ) ) {
			$errors['capt_comment'] = esc_html__( 'Please select valid captcha.', 'login-security-recaptcha' );
		} else {
			update_option(
				'stlsr_comment_captcha',
				array(
					'enable'    => $capt_comment_enable,
					'captcha'   => $capt_comment,
					'logged_in' => $capt_comment_logged_in,
				),
				true
			);
		}

		if ( count( $errors ) < 1 ) {
			wp_send_json_success( array( 'message' => esc_html__( 'Setting saved.', 'login-security-recaptcha' ) ) );
		}

		wp_send_json_error( $errors );
	}

	public static function reset_plugin() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}

		if ( ! isset( $_POST['reset-plugin'] ) || ! wp_verify_nonce( $_POST['reset-plugin'], 'reset-plugin' ) ) {
			die();
		}

		delete_option( 'stlsr_google_recaptcha_v2' );
		delete_option( 'stlsr_google_recaptcha_v3' );
		delete_option( 'stlsr_cf_turnstile' );
		delete_option( 'stlsr_login_captcha' );
		delete_option( 'stlsr_lostpassword_captcha' );
		delete_option( 'stlsr_register_captcha' );
		delete_option( 'stlsr_comment_captcha' );
		delete_option( 'stlsr_error_logs' );
		delete_option( 'stlsr_redirect_to_settings' );

		wp_send_json_success( array( 'message' => esc_html__( 'The plugin has been reset to its default state.', 'login-security-recaptcha' ) ) );
	}

	public static function clear_error_logs() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}

		if ( ! isset( $_POST['clear-error-logs'] ) || ! wp_verify_nonce( $_POST['clear-error-logs'], 'clear-error-logs' ) ) {
			die();
		}

		update_option( 'stlsr_error_logs', array(), true );

		wp_send_json_success( array( 'message' => esc_html__( 'The error logs have been cleared successfully.', 'login-security-recaptcha' ) ) );
	}
}
