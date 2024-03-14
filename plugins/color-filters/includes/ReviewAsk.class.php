<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewduwcfReviewAsk' ) ) {
/**
 * Class to handle plugin review ask
 *
 * @since 3.0.0
 */
class ewduwcfReviewAsk {

	public function __construct() {
		
		add_action( 'admin_notices', array( $this, 'maybe_add_review_ask' ) );

		add_action( 'wp_ajax_ewd_uwcf_hide_review_ask', array( $this, 'hide_review_ask' ) );
		add_action( 'wp_ajax_ewd_uwcf_send_feedback', array( $this, 'send_feedback' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_review_ask_scripts' ) );
	}

	public function maybe_add_review_ask() { 
		
		$ask_review_time = get_option( 'ewd-uwcf-review-ask-time' );

		$install_time = get_option( 'ewd-uwcf-installation-time' );
		if ( ! $install_time ) { update_option( 'ewd-uwcf-installation-time', time() ); $install_time = time(); }

		$ask_review_time = $ask_review_time != '' ? $ask_review_time : $install_time + 3600*24*4;
		
		if ( $ask_review_time < time() and $install_time != '' and $install_time < time() - 3600*24*4 ) {
			
			global $pagenow;

			if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' ) { ?>
	
				<div class='notice notice-info is-dismissible ewd-uwcf-main-dashboard-review-ask' style='display:none'>
					<div class='ewd-uwcf-review-ask-plugin-icon'></div>
					<div class='ewd-uwcf-review-ask-text'>
						<p class='ewd-uwcf-review-ask-starting-text'>Enjoying using the Ultimate WooCommerce Filters?</p>
						<p class='ewd-uwcf-review-ask-feedback-text ewd-uwcf-hidden'>Help us make the plugin better! Please take a minute to rate the plugin. Thanks!</p>
						<p class='ewd-uwcf-review-ask-review-text ewd-uwcf-hidden'>Please let us know what we could do to make the plugin better!<br /><span>(If you would like a response, please include your email address.)</span></p>
						<p class='ewd-uwcf-review-ask-thank-you-text ewd-uwcf-hidden'>Thank you for taking the time to help us!</p>
					</div>
					<div class='ewd-uwcf-review-ask-actions'>
						<div class='ewd-uwcf-review-ask-action ewd-uwcf-review-ask-not-really ewd-uwcf-review-ask-white'>Not Really</div>
						<div class='ewd-uwcf-review-ask-action ewd-uwcf-review-ask-yes ewd-uwcf-review-ask-green'>Yes!</div>
						<div class='ewd-uwcf-review-ask-action ewd-uwcf-review-ask-no-thanks ewd-uwcf-review-ask-white ewd-uwcf-hidden'>No Thanks</div>
						<a href='https://wordpress.org/support/plugin/color-filters/reviews/' target='_blank'>
							<div class='ewd-uwcf-review-ask-action ewd-uwcf-review-ask-review ewd-uwcf-review-ask-green ewd-uwcf-hidden'>OK, Sure</div>
						</a>
					</div>
					<div class='ewd-uwcf-review-ask-feedback-form ewd-uwcf-hidden'>
						<div class='ewd-uwcf-review-ask-feedback-explanation'>
							<textarea></textarea>
							<br>
							<input type="email" name="feedback_email_address" placeholder="<?php _e('Email Address', 'color-filters'); ?>">
						</div>
						<div class='ewd-uwcf-review-ask-send-feedback ewd-uwcf-review-ask-action ewd-uwcf-review-ask-green'>Send Feedback</div>
					</div>
					<div class='ewd-uwcf-clear'></div>
				</div>

			<?php
			}
		}
		else {
			wp_dequeue_script( 'ewd-uwcf-review-ask-js' );
			wp_dequeue_style( 'ewd-uwcf-review-ask-css' );
		}
	}

	public function enqueue_review_ask_scripts() {

		wp_enqueue_style( 'ewd-uwcf-review-ask-css', EWD_UWCF_PLUGIN_URL . '/assets/css/dashboard-review-ask.css' );
		wp_enqueue_script( 'ewd-uwcf-review-ask-js', EWD_UWCF_PLUGIN_URL . '/assets/js/dashboard-review-ask.js', array( 'jquery' ), EWD_UWCF_VERSION, true  );

		wp_localize_script(
			'ewd-uwcf-review-ask-js',
			'ewd_uwcf_review_ask',
			array(
				'nonce' => wp_create_nonce( 'ewd-uwcf-review-ask-js' )
			)
		);
	}

	public function hide_review_ask() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-review-ask-js', 'nonce' ) || ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}

		$ask_review_time = sanitize_text_field($_POST['ask_review_time']);

		if ( get_option( 'ewd-uwcf-review-ask-time' ) < time() + 3600*24 * $ask_review_time ) {
			update_option( 'ewd-uwcf-review-ask-time', time() + 3600*24 * $ask_review_time );
		}

		die();
	}

	public function send_feedback() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-review-ask-js', 'nonce' ) || ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}
		
		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
		$feedback = sanitize_text_field( $_POST['feedback'] );
		$feedback .= '<br /><br />Email Address: ';
		$feedback .= sanitize_email( $_POST['email_address'] );

		wp_mail('contact@etoilewebdesign.com', 'UWCF Feedback - Dashboard Form', $feedback, $headers);

		die();
	} 
}

}