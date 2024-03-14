<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdulbReviewAsk' ) ) {
/**
 * Class to handle plugin review ask
 *
 * @since 2.0.15
 */
class ewdulbReviewAsk {

	public function __construct() {
		
		add_action( 'admin_notices', array( $this, 'maybe_add_review_ask' ) );

		add_action( 'wp_ajax_ulb_hide_review_ask', array( $this, 'hide_review_ask' ) );
		add_action( 'wp_ajax_ulb_send_feedback', array( $this, 'send_feedback' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_review_ask_scripts' ) );
	}

	public function maybe_add_review_ask() { 
		
		$ask_review_time = get_option( 'ulb-review-ask-time' );

		$install_time = get_option( 'ulb-installation-time' );
		if ( ! $install_time ) { update_option( 'ulb-installation-time', time() ); $install_time = time(); }

		$ask_review_time = $ask_review_time != '' ? $ask_review_time : $install_time + 3600*24*4;
		
		if ( $ask_review_time < time() and $install_time != '' and $install_time < time() - 3600*24*4 ) {
			
			global $pagenow;

			if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' ) { ?>
	
				<div class='notice notice-info is-dismissible ulb-main-dashboard-review-ask' style='display:none'>
					<div class='ulb-review-ask-plugin-icon'></div>
					<div class='ulb-review-ask-text'>
						<p class='ulb-review-ask-starting-text'>Enjoying using the Ultimate Lightbox?</p>
						<p class='ulb-review-ask-feedback-text ulb-hidden'>Help us make the plugin better! Please take a minute to rate the plugin. Thanks!</p>
						<p class='ulb-review-ask-review-text ulb-hidden'>Please let us know what we could do to make the plugin better!<br /><span>(If you would like a response, please include your email address.)</span></p>
						<p class='ulb-review-ask-thank-you-text ulb-hidden'>Thank you for taking the time to help us!</p>
					</div>
					<div class='ulb-review-ask-actions'>
						<div class='ulb-review-ask-action ulb-review-ask-not-really ulb-review-ask-white'>Not Really</div>
						<div class='ulb-review-ask-action ulb-review-ask-yes ulb-review-ask-green'>Yes!</div>
						<div class='ulb-review-ask-action ulb-review-ask-no-thanks ulb-review-ask-white ulb-hidden'>No Thanks</div>
						<a href='https://wordpress.org/support/plugin/ultimate-lightbox/reviews/' target='_blank'>
							<div class='ulb-review-ask-action ulb-review-ask-review ulb-review-ask-green ulb-hidden'>OK, Sure</div>
						</a>
					</div>
					<div class='ulb-review-ask-feedback-form ulb-hidden'>
						<div class='ulb-review-ask-feedback-explanation'>
							<textarea></textarea>
							<br>
							<input type="email" name="feedback_email_address" placeholder="<?php _e('Email Address', 'ultimate-lightbox'); ?>">
						</div>
						<div class='ulb-review-ask-send-feedback ulb-review-ask-action ulb-review-ask-green'>Send Feedback</div>
					</div>
					<div class='ulb-clear'></div>
				</div>

			<?php
			}
		}
		else {
			wp_dequeue_script( 'ulb-review-ask-js' );
			wp_dequeue_style( 'ulb-review-ask-css' );
		}
	}

	public function enqueue_review_ask_scripts() {

		wp_enqueue_style( 'ulb-review-ask-css', EWD_ULB_PLUGIN_URL . '/assets/css/dashboard-review-ask.css' );
		wp_enqueue_script( 'ulb-review-ask-js', EWD_ULB_PLUGIN_URL . '/assets/js/dashboard-review-ask.js', array( 'jquery' ), EWD_ULB_VERSION, true  );

		wp_localize_script(
			'ulb-review-ask-js',
			'ewd_ulb_review_ask',
			array(
				'nonce' => wp_create_nonce( 'ewd-ulb-review-ask-js' )
			)
		);
	}

	public function hide_review_ask() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-ulb-review-ask-js', 'nonce' ) ) {
			
			ewdulbHelper::admin_nopriv_ajax();
		}

		$ask_review_time = sanitize_text_field($_POST['ask_review_time']);

    	if ( get_option( 'ulb-review-ask-time' ) < time() + 3600*24 * $ask_review_time ) {
    		update_option( 'ulb-review-ask-time', time() + 3600*24 * $ask_review_time );
    	}

    	die();
	}

	public function send_feedback() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-ulb-review-ask-js', 'nonce' ) ) {
			
			ewdulbHelper::admin_nopriv_ajax();
		}
		
		$headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
	    $feedback = sanitize_text_field($_POST['feedback']);
 		$feedback .= '<br /><br />Email Address: ';
    	$feedback .= sanitize_text_field($_POST['email_address']);

    	wp_mail('contact@etoilewebdesign.com', 'ULB Feedback - Dashboard Form', $feedback, $headers);

    	die();
	} 
}

}