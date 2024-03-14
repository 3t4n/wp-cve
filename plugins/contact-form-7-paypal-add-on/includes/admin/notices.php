<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Show admin notice.
 * @since 1.8
 * @return string or array
 */
add_action('admin_notices', 'cf7pp_admin_earnings_notice');
function cf7pp_admin_earnings_notice(){

	$cf7pp_show_earnings_notice = get_option('cf7pp_show_earnings_notice');
	if ( $cf7pp_show_earnings_notice == '-1' || (time() < $cf7pp_show_earnings_notice + DAY_IN_SECONDS * 14) ) return;

	$msg = '';

	$earnings = cf7pp_get_earnings_amount();
	$earnings = round($earnings, 0); 
	
	if ( $earnings <= 50 ) {
		if ( cf7pp_get_instalation_timestamp() < (time() - DAY_IN_SECONDS * 14) )  {
			$msg = __('You\'ve been using Contact Form 7 - PayPal & Stripe for a while now.', 'contact-form-7');
		}
	} else {
		$msg = sprintf(__('You\'ve made over $%s in earnings using Contact Form 7 - PayPal & Stripe.', 'contact-form-7'), $earnings);
	}

	if (empty($msg)) return;

	printf(
		'<div class="notice notice-success">
			<p><strong>%s</strong> %s</p>
			<p>%s</p>
			<p>%s</p>
			<p>
				<a class="button button-primary" href="https://wordpress.org/support/plugin/contact-form-7-paypal-add-on/reviews/?filter=5#new-post" target="_blank">%s</a>
				<a class="button button-secondary" href="%s">%s</a>
				<a class="button button-secondary" href="%s">%s</a>
			</p>
		</div>',
		__('Great job!', 'contact-form-7'),
		$msg,
		__('If you have a moment, please help us spread the word by leaving a 5-star review on WordPress.', 'contact-form-7'),
		__('- Thanks, Scott Paterson', 'contact-form-7'),
		__('Leave a review', 'contact-form-7'),
		add_query_arg('cf7pp_show_earnings_notice', 'later'),
		__('Maybe later', 'contact-form-7'),
		add_query_arg('cf7pp_show_earnings_notice', 'newer'),
		__('I already did', 'contact-form-7')
	);
}

/**
 * Handle "Maybe later" and "I already did" buttons.
 * @since 1.8
 */
add_action('init', 'cf7pp_show_earnings_notice');
function cf7pp_show_earnings_notice() {
	if ( !isset($_GET['cf7pp_show_earnings_notice']) ) return;

	switch ($_GET['cf7pp_show_earnings_notice']) {
		case 'later':
			update_option('cf7pp_show_earnings_notice', time());
			break;

		case 'newer':
			update_option('cf7pp_show_earnings_notice', '-1');
			break;
	}

	wp_redirect(remove_query_arg('cf7pp_show_earnings_notice'));
	die();
}

/**
 * Show admin notice for Stripe Connect.
 * @since 1.8
 */
add_action('admin_notices', 'cf7pp_admin_stripe_connect_notice');
function cf7pp_admin_stripe_connect_notice() {
	$options = cf7pp_free_options();
	
	if (isset($options['mode_stripe'])) {
		$mode = $options['mode_stripe'] == "2" ? 'live' : 'sandbox';
	} else {
		$mode = 'sandbox';
	}
	
	$acct_id_key = $mode == 'live' ? 'acct_id_live' : 'acct_id_test';

	if (!empty($options[$acct_id_key]) || !empty($options['stripe_connect_notice_dismissed']) ||
		( isset( $_GET['page'] ) && $_GET['page'] == 'cf7pp_admin_table' && isset( $_GET['tab'] ) && $_GET['tab'] == 5 ) ) return;

	$dismiss_url = add_query_arg('cf7pp_admin_stripe_connect_notice_dismiss', 1, admin_url());

	printf(
		'<div class="notice notice-error is-dismissible cf7pp-stripe-connect-notice" data-dismiss-url="%s">
			<p>%s</p>
			<p><a href="%s" class="stripe-connect-btn"><span>Connect with Stripe</span></a></p>
			<br />WPPlugin LLC is an offical Stripe Partner. Pay as you go pricing: 2%% per-transaction fee + Stripe fees.
		</div>',
		$dismiss_url,
		__('<b>Important</b> - \'Contact Form 7 - PayPal & Stripe Add-on\' now uses Stripe Connect.
		Stripe Connect improves security and allows for easier setup. <br /><br />If you use Stripe, please use Stripe Connect.
		Have questions: see the <a target="_blank" href="https://wpplugin.org/documentation/stripe-connect/">documentation</a>.', 'contact-form-7'),
		cf7pp_stripe_connect_url()
	);
}

/**
 * Dismiss admin notice for Stripe Connect.
 * @since 1.8
 */
add_action('admin_init', 'cf7pp_admin_stripe_connect_notice_dismiss');
function cf7pp_admin_stripe_connect_notice_dismiss() {
	$dismiss_option = filter_input(INPUT_GET, 'cf7pp_admin_stripe_connect_notice_dismiss', FILTER_SANITIZE_NUMBER_INT);
	if (!empty($dismiss_option)) {
		$options = cf7pp_free_options();
		$options['stripe_connect_notice_dismissed'] = 1;
		cf7pp_free_options_update( $options );
		exit;
	}
}










function cf7pp_paypal_commerce_onboarding_url() {
	$options = cf7pp_free_options();
	$mode = intval( $options['mode'] );
	$query_args = [
		'action' => 'cf7pp-ppcp-onboarding-start',
		'nonce' => wp_create_nonce( 'cf7pp-ppcp-onboarding-start' )
	];
	if ( $mode === 1 ) {
		$query_args['sandbox'] = 1;
	}
	
	return '
	<a
		id="cf7pp-ppcp-onboarding-start-btn"
		class="cf7pp-ppcp-button cf7pp-ppcp-onboarding-start"
		style="background-color: #fff; border: 1px solid #162c70; color:#162c70;"
		data-paypal-button="true"
		href="'. add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) ) .'"
		target="PPFrame"
	> <img class="cf7pp-ppcp-paypal-logo" style="max-height:25px" src="'.CF7PP_FREE_URL.'imgs/paypal-logo.png" alt="paypal-logo" /><br />Get Started</a>';
}















/**
 * Stripe Connect error notice.
 * @since 1.8
 */
add_action('admin_notices', 'cf7pp_admin_stripe_connect_error_notice');
function cf7pp_admin_stripe_connect_error_notice() {
	if (empty($_GET['cf7pp_error']) || $_GET['cf7pp_error'] != 'stripe-connect-handler') return;

	printf(
		'<div class="notice notice-error is-dismissible">
			<p>%s</p>
		</div>',
		__('An error occurred while interacting with our Stripe Connect interface.', 'contact-form-7')
	);
}

/**
 * Show admin notice for PayPal Commerce Platform.
 * @since 2.0
 */
add_action( 'admin_notices', 'cf7pp_ppcp_admin_notice' );
function cf7pp_ppcp_admin_notice() {
	$options = cf7pp_free_options();
	$env = intval( $options['mode'] ) === 2 ? 'live' : 'sandbox';
	$connected = !empty( $options['ppcp_onboarding'][$env] ) && !empty( $options['ppcp_onboarding'][$env]['seller_id'] );
	if ( $connected || !empty( $options['ppcp_notice_dismissed'] ) ||
		( isset( $_GET['page'] ) && $_GET['page'] == 'cf7pp_admin_table' && isset( $_GET['tab'] ) && $_GET['tab'] == 4 ) ) return;

	printf(
		'<div class="notice notice-error is-dismissible cf7pp-ppcp-connect-notice" data-dismiss-url="%s">
			<p>%s</p>
			%s
			<br />WPPlugin LLC is an offical PayPal Partner. Pay as you go pricing: 2%% per-transaction fee + PayPal fees.
		<br /></div>',
		add_query_arg( 'cf7pp_admin_ppcp_notice_dismiss', 1, admin_url() ),
		__( '<b>Important</b> - \'Contact Form 7 - PayPal & Stripe Add-on\' now uses the PayPal Commerce Platform.
		<u><b>PayPal Standard is now a Legacy product.</b></u> <br /><br /> <b><u>If you use PayPal, please update to PayPal Commerce Platform.</u></b>' ),
		cf7pp_paypal_commerce_onboarding_url()
	);
}

/**
 * Dismiss admin notice for PayPal Commerce Platform.
 * @since 2.0
 */
add_action( 'admin_init', 'cf7pp_free_ppcp_admin_notice_dismiss' );
function cf7pp_free_ppcp_admin_notice_dismiss() {
	if ( empty( $_GET['cf7pp_admin_ppcp_notice_dismiss'] ) ) return;

	$options = cf7pp_free_options();
	$options['ppcp_notice_dismissed'] = 1;
	cf7pp_free_options_update( $options );
	die();
}