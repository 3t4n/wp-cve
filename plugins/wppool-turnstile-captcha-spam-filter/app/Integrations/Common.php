<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile\Integrations;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages general turnstile integrations.
 *
 * @since 1.0.1
 */
class Common {



	/**
	 * Class constructor.
	 *
	 * @since 1.0.1
	 */
	public function __construct()
	{
		add_action( 'init', [ $this, 'initialize' ] );
	}

	/**
	 * The Array of Allowed Tags
	 *
	 * @var array
	 */
	private $allowed_tags;

	/**
	 * The Placement of ECT Widget
	 *
	 * @var string
	 */
	private $ect_placement;


	/**
	 * Initializes plugins core.
	 *
	 * @since 1.0.0
	 */
	public function initialize()
	{
		$this->display_verifier();
		$this->enqueue_assets();
	}

	/**
	 * Performs the render & verify operations in the frontend.
	 *
	 * @return void
	 */
	public function display_verifier()
	{
		$cfActivate  = ! is_user_logged_in();
		$authorized_user = current_user_can( 'manage_options' );
		$site_key    = wp_turnstile()->settings->get( 'site_key' );
		$secret_key  = wp_turnstile()->settings->get( 'secret_key' );

		if ($cfActivate && ! empty( $site_key ) && ! empty( $secret_key )) {
			add_action( EASY_CLOUDFLARE_TURNSTILE_PREFIX . 'display_list', [ $this, 'render_list' ] );
			add_action( EASY_CLOUDFLARE_TURNSTILE_PREFIX . 'verify_list', [ $this, 'verify_list' ] );

			foreach (apply_filters( 'easy_cloudflare_turnstile_render_list', $this->render_list() ) as $context) {
				add_action( $context, [ $this, 'enqueueApiScript' ] );
				add_action( $context, [ $this, 'render' ], 10, 1 );
			}
			foreach (apply_filters( 'easy_cloudflare_turnstile_verify_list', $this->verify_list() ) as $verify) {
				add_action( $verify, [ $this, 'verify' ] );
			}
		} else if ($authorized_user && ! empty( $site_key ) && ! empty( $secret_key )) {
			foreach (apply_filters( 'easy_cloudflare_turnstile_render_list', $this->render_list() ) as $context) {
				add_action( $context, [ $this, 'enqueueApiScript' ] );
				add_action( $context, [ $this, 'render' ], 10, 1 );
			}
			foreach (apply_filters( 'easy_cloudflare_turnstile_verify_list', $this->verify_list() ) as $verify) {
				add_action( $verify, [ $this, 'verify' ] );
			}
		}
	}

	/**
	 * Customized wrapper function for error response.
	 *
	 * @param string $msg Response message to display.
	 *
	 * @return void
	 */
	private function terminate_request( $msg )
	{
		$error               = __( 'Error', 'wppool-turnstile' );
		$verification_failed = __( 'verification failed', 'wppool-turnstile' );

		wp_die(
			sprintf(
				'<p><strong>%s:</strong> Cloudflare Turnstile %s. %s</p>',
				esc_html( $error ),
				esc_html( $verification_failed ),
				esc_html( $msg )
			),
			'Forbidden by Turnstile',
			[
				'response'  => 403,
				'back_link' => 1,
			]
		);
	}

	/**
	 * Enqueue plugin assets.
	 *
	 * @since 1.0.0
	 */
	private function enqueue_assets()
	{
		wp_enqueue_script(
			'wppool-turnstile-cb',
			EASY_CLOUDFLARE_TURNSTILE_URL . 'assets/js/wppool-turnstile-cb.js',
			[ 'jquery' ],
			EASY_CLOUDFLARE_TURNSTILE_VERSION,
			true
		);

		wp_localize_script(
			'wppool-turnstile-cb',
			'WP_TURNSTILE_OBJ',
			[ 'CF_SITE_KEY' => wp_turnstile()->settings->get( 'site_key' ) ]
		);
	}

	/**
	 * Renders possible checking entry list.
	 *
	 * @param array $list The entry list.
	 * @since 1.0.0
	 *
	 * @return array|string[]
	 */
	public function render_list( $list = [] )
	{
		$wp_fields = wp_turnstile()->integrations->field( 'wordpress' );
		$wc_fields = wp_turnstile()->integrations->field( 'woocommerce' );
		$bbpress_fields = wp_turnstile()->integrations->field( 'bbpress' );

		$wordpress   = wp_turnstile()->integrations->get( 'wordpress' );
		$woocommerce = wp_turnstile()->integrations->get( 'woocommerce' );
		$buddypress  = wp_turnstile()->integrations->get( 'buddypress' );
		$bbpress  = wp_turnstile()->integrations->get( 'bbpress' );
		$bbpress  = wp_turnstile()->integrations->get( 'bbpress' );
		$list = [];

		if (wp_validate_boolean( $wordpress )) {
			if (in_array( 'wordpress_login', $wp_fields, true )) {
				$list[] = 'login_form';
			}

			if (in_array( 'wordpress_register', $wp_fields, true )) {
				$list[] = 'register_form';
				$list[] = 'woocommerce_register_form';
			}
			if (in_array( 'wordpress_reset_password', $wp_fields, true )) {
				$list[] = 'lostpassword_form';
			}
			if (in_array( 'wordpress_comment', $wp_fields, true )) {
				$list[] = 'comment_form_after_fields';
			}
		}

		if (wp_validate_boolean( $woocommerce )) {
			if (in_array( 'my_account_login', $wc_fields, true )) {
				$list[] = 'woocommerce_login_form';
			}

			if (in_array( 'wc_lost_password', $wc_fields, true )) {
				$list[] = 'woocommerce_lostpassword_form';
			}

			if (in_array( 'wc_checkout', $wc_fields )) {
				$this->ect_placement = get_option( 'ect_placement' );
				$this->ect_placement = ( isset( $this->ect_placement ) && is_array( $this->ect_placement ) ) ? sanitize_text_field( $this->ect_placement['woocommerce'] ) : '';
				if ($this->ect_placement) {
					if ('before_billing' === $this->ect_placement) {
						$list[] = 'woocommerce_before_checkout_billing_form';
					} elseif ('after_billing' === $this->ect_placement) {
						$list[] = 'woocommerce_after_checkout_billing_form';
					} elseif ('before_payment' === $this->ect_placement) {
						$list[] = 'woocommerce_review_order_before_payment';
					} elseif ('after_payment' === $this->ect_placement) {
						$list[] = 'woocommerce_review_order_after_payment';
					} elseif ('before_pay' === $this->ect_placement) {
						$list[] = 'woocommerce_review_order_before_submit';
					}
				} else {
					$list[] = 'woocommerce_before_checkout_billing_form';
				}
			}
		}

		if (wp_validate_boolean( $buddypress )) {
			$list[] = 'bp_after_signup_profile_fields';
		}

		if (wp_validate_boolean( $bbpress )) {
			if (in_array( 'bbpress_topic', $bbpress_fields, true )) {
				$list[] = 'bbp_theme_before_topic_form_submit_wrapper';
			}

			if (in_array( 'bbpress_reply', $bbpress_fields, true )) {
				$list[] = 'bbp_theme_before_reply_form_submit_wrapper';
			}
		}

		return $list;
	}

	/**
	 * Possible entries list to verify.
	 *
	 * @param array|null $list The list.
	 * @return array|string[]
	 */
	public function verify_list( $list = [] )
	{
		$wp_fields = wp_turnstile()->integrations->field( 'wordpress' );
		$wc_fields = wp_turnstile()->integrations->field( 'woocommerce' );
		$bbpress_fields = wp_turnstile()->integrations->field( 'bbpress' );

		$wordpress   = wp_turnstile()->integrations->get( 'wordpress' );
		$woocommerce = wp_turnstile()->integrations->get( 'woocommerce' );
		$buddypress  = wp_turnstile()->integrations->get( 'buddypress' );
		$bbpress  = wp_turnstile()->integrations->get( 'bbpress' );

		$list = [];

		if (wp_validate_boolean( $wordpress )) {
			if (in_array( 'wordpress_register', $wp_fields, true )) {
				$list[] = 'registration_errors';
			}
			if (in_array( 'wordpress_reset_password', $wp_fields, true )) {
				$list[] = 'lostpassword_post';
			}
			if (in_array( 'wordpress_comment', $wp_fields, true )) {
				$list[] = 'preprocess_comment';
			}
		}

		if (wp_validate_boolean( $wordpress ) && ! wp_validate_boolean( $woocommerce )) {
			if (in_array( 'wordpress_login', $wp_fields, true )) {
				$list[] = 'authenticate';
			}
		} elseif (wp_validate_boolean( $wordpress ) && wp_validate_boolean( $woocommerce )) {
			if (in_array( 'my_account_login', $wc_fields, true ) && in_array( 'wordpress_login', $wp_fields, true )) {
				$list[] = 'authenticate';
			}
		}

		if (wp_validate_boolean( $woocommerce )) {
			if (in_array( 'wc_lost_password', $wc_fields, true )) {
				$list[] = 'woocommerce_register_post';
			}

			if (in_array( 'wc_checkout', $wc_fields, true )) {
				$list[] = 'woocommerce_checkout_process';
			}
		}

		if (wp_validate_boolean( $buddypress )) {
			$list[] = 'bp_signup_validate';
		}

		if (wp_validate_boolean( $bbpress )) {
			if (in_array( 'bbpress_topic', $bbpress_fields, true )) {
				$list[] = 'bbp_new_topic_pre_extras';
			}

			if (in_array( 'bbpress_reply', $bbpress_fields, true )) {
				$list[] = 'bbp_new_reply_pre_extras';
			}
		}

		return $list;
	}


	/**
	 * Enqueue CF api script.
	 *
	 * @since 1.0.0
	 */
	public function enqueueApiScript()
	{
		wp_enqueue_script(
			'wppool-turnstile-api',
			wp_turnstile()->challenges_url,
			[],
			wp_turnstile()->api_version,
			true
		);

		wp_enqueue_style(
			'wppool-turnstile-admin',
			EASY_CLOUDFLARE_TURNSTILE_URL . 'assets/css/integration.css',
			[],
			EASY_CLOUDFLARE_TURNSTILE_VERSION,
			'all'
		);
	}

	/**
	 * Retrieves client connecting IP.
	 *
	 * @since 1.0.0
	 */
	public function get_connecting_ip()
	{
		return isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? sanitize_text_field( $_SERVER['HTTP_CF_CONNECTING_IP'] ) : sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
	}

	/**
	 * Render the turnstile container on the frontend.
	 *
	 * @since 1.0.0
	 */
	public function render()
	{
		$button_access = wp_validate_boolean( wp_turnstile()->settings->get( 'button_access', 'false' ) );
		$html = '<div class="ect-turnstile-container" id="wppool-turnstile-container" data-theme="' .
			esc_attr( wp_turnstile()->settings->get( 'theme', 'light' ) ) . '" data-submit-button="' .
			esc_attr( $button_access ) . '" data-action="wppool-turnstile-container" data-size="normal"></div>';

		$html .= '<style>
				.submit input[type="submit"],
				input[name="wc_reset_password"] + button.woocommerce-Button,
				.woocommerce button[type="submit"], .bbp-form button[type="submit"]{
					pointer-events: none;
					opacity: .5;
				}
			</style>';

		$this->allowed_tags = wp_kses_allowed_html( 'post' );
		$this->allowed_tags['style'] = $this->allowed_tags;
		echo wp_kses( $html, $this->allowed_tags );
	}
	/**
	 * Returns error message based on the response error code.
	 *
	 * @since 1.0.0
	 * @param string|null $code The response error code.
	 *
	 * @return string
	 */
	public function get_error_message( $code )
	{
		switch ($code) {
			case 'missing-input-secret':
				return __( 'The secret parameter is missing.', 'wppool-turnstile' );

			case 'missing-input-response':
				return __( 'The response parameter is missing.', 'wppool-turnstile' );

			case 'invalid-input-secret':
				return __( 'The secret parameter is invalid or malformed.', 'wppool-turnstile' );

			case 'invalid-input-response':
				return __( 'The response parameter is invalid or malformed.', 'wppool-turnstile' );

			case 'bad-request':
				return __( 'The request is invalid or malformed.', 'wppool-turnstile' );

			case 'timeout-or-duplicate':
				return __( 'The response is no longer valid: either is too old or has been used previously.', 'wppool-turnstile' );

			default:
				return __( 'Unknown error.', 'wppool-turnstile' );
		}
	}

	/**
	 * Performs verify the received token.
	 *
	 * @since 1.0.0
	 * @param mixed $input Request input.
	 *
	 * @return mixed|void
	 */
	public function verify( $input ) { 		if (!empty($_POST)) { // phpcs:ignore
			$token     = strval( filter_input( INPUT_POST, 'cf-turnstile-response', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) );
			$response  = wp_turnstile()->helpers->validate_turnstile( $token );
			$error_message = wp_turnstile()->settings->get( 'error_msg', __( 'Please verify you are human', 'wppool-turnstile' ) );
			return ! wp_validate_boolean( $response['success'] ) ? $this->terminate_request( esc_attr( $error_message ) ) : $input;
	}
	}
}