<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdotpReviewAsk' ) ) {
/**
 * Class to handle plugin review ask
 *
 * @since 3.0.0
 */
class ewdotpReviewAsk {

	public function __construct() {
		
		add_action( 'admin_notices', array( $this, 'maybe_add_review_ask' ) );

		add_action( 'wp_ajax_ewd_otp_hide_review_ask', array( $this, 'hide_review_ask' ) );
		add_action( 'wp_ajax_ewd_otp_send_feedback', array( $this, 'send_feedback' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_review_ask_scripts' ) );
	}

	public function maybe_add_review_ask() { 
		
		$ask_review_time = get_option( 'ewd-otp-review-ask-time' );

		$install_time = get_option( 'ewd-otp-installation-time' );
		if ( ! $install_time ) { update_option( 'ewd-otp-installation-time', time() ); $install_time = time(); }

		$ask_review_time = $ask_review_time != '' ? $ask_review_time : $install_time + 3600*24*4;
		
		if ( $ask_review_time < time() and $install_time != '' and $install_time < time() - 3600*24*4 ) {
			
			global $pagenow;

			if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' ) { ?>
	
				<div class='notice notice-info is-dismissible ewd-otp-main-dashboard-review-ask' style='display:none'>
					<div class='ewd-otp-review-ask-plugin-icon'></div>
					<div class='ewd-otp-review-ask-text'>
						<p class='ewd-otp-review-ask-starting-text'>Enjoying using the Order Tracking?</p>
						<p class='ewd-otp-review-ask-feedback-text ewd-otp-hidden'>Help us make the plugin better! Please take a minute to rate the plugin. Thanks!</p>
						<p class='ewd-otp-review-ask-review-text ewd-otp-hidden'>Please let us know what we could do to make the plugin better!<br /><span>(If you would like a response, please include your email address.)</span></p>
						<p class='ewd-otp-review-ask-thank-you-text ewd-otp-hidden'>Thank you for taking the time to help us!</p>
					</div>
					<div class='ewd-otp-review-ask-actions'>
						<div class='ewd-otp-review-ask-action ewd-otp-review-ask-not-really ewd-otp-review-ask-white'>Not Really</div>
						<div class='ewd-otp-review-ask-action ewd-otp-review-ask-yes ewd-otp-review-ask-green'>Yes!</div>
						<div class='ewd-otp-review-ask-action ewd-otp-review-ask-no-thanks ewd-otp-review-ask-white ewd-otp-hidden'>No Thanks</div>
						<a href='https://wordpress.org/support/plugin/order-tracking/reviews/' target='_blank'>
							<div class='ewd-otp-review-ask-action ewd-otp-review-ask-review ewd-otp-review-ask-green ewd-otp-hidden'>OK, Sure</div>
						</a>
					</div>
					<div class='ewd-otp-review-ask-feedback-form ewd-otp-hidden'>
						<div class='ewd-otp-review-ask-feedback-explanation'>
							<textarea></textarea>
							<br>
							<input type="email" name="feedback_email_address" placeholder="<?php _e('Email Address', 'order-tracking'); ?>">
						</div>
						<div class='ewd-otp-review-ask-send-feedback ewd-otp-review-ask-action ewd-otp-review-ask-green'>Send Feedback</div>
					</div>
					<div class='ewd-otp-clear'></div>
				</div>

			<?php
			}
		}
		else {
			wp_dequeue_script( 'ewd-otp-review-ask-js' );
			wp_dequeue_style( 'ewd-otp-review-ask-css' );
		}
	}

	public function enqueue_review_ask_scripts() {

		wp_enqueue_style( 'ewd-otp-review-ask-css', EWD_OTP_PLUGIN_URL . '/assets/css/dashboard-review-ask.css' );
		wp_enqueue_script( 'ewd-otp-review-ask-js', EWD_OTP_PLUGIN_URL . '/assets/js/dashboard-review-ask.js', array( 'jquery' ), EWD_OTP_VERSION, true  );

		wp_localize_script(
			'ewd-otp-review-ask-js',
			'ewd_otp_review_ask',
			array(
				'nonce' => wp_create_nonce( 'ewd-otp-review-ask-js' )
			)
		);
	}

	public function hide_review_ask() {
		global $ewd_otp_controller;

		// Authenticate request
		if (
			! check_ajax_referer( 'ewd-otp-review-ask-js', 'nonce' )
			or ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdotpHelper::admin_nopriv_ajax();
		}

		$ask_review_time = intval( $_POST['ask_review_time'] );

		if ( get_option( 'ewd-otp-review-ask-time' ) < time() + 3600*24 * $ask_review_time ) {
			update_option( 'ewd-otp-review-ask-time', time() + 3600*24 * $ask_review_time );
		}

		die();
	}

	public function send_feedback() {
		global $ewd_otp_controller;

		// Authenticate request
		if (
			! check_ajax_referer( 'ewd-otp-review-ask-js', 'nonce' )
			or ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdotpHelper::admin_nopriv_ajax();
		}

		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
		$feedback = sanitize_text_field( $_POST['feedback'] );
		$feedback .= '<br /><br />Email Address: ';
		$feedback .= sanitize_email( $_POST['email_address'] );

		wp_mail('contact@etoilewebdesign.com', 'OTP Feedback - Dashboard Form', $feedback, $headers);

		die();
	} 
}

}