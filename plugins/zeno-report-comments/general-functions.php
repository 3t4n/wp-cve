<?php
/*
 * General functions for Zeno Report Comments.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
* Load Language files for frontend and backend.
*/
function zeno_report_comments_load_language() {
	load_plugin_textdomain( 'zeno-report-comments', false, ZENORC_FOLDER . '/lang' );
}
add_action( 'plugins_loaded', 'zeno_report_comments_load_language' );


/*
 * Validate user IP, include known proxy headers if needed
 */
function zeno_report_comments_get_user_ip() {

	$include_proxy = apply_filters( 'zeno_report_comments_include_proxy_ips', false );
	if ( true === $include_proxy ) {
		$proxy_headers = array(
			'HTTP_VIA',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED',
			'HTTP_CLIENT_IP',
			'HTTP_FORWARDED_FOR_IP',
			'VIA',
			'X_FORWARDED_FOR',
			'FORWARDED_FOR',
			'X_FORWARDED',
			'FORWARDED',
			'CLIENT_IP',
			'FORWARDED_FOR_IP',
			'HTTP_PROXY_CONNECTION',
			'REMOTE_ADDR',
		);
		$remote_ip = false;
		foreach ( $proxy_headers as $header ) {
			if ( isset( $_SERVER["$header"] ) ) {
				$remote_ip = sanitize_text_field( $_SERVER["$header"] );
				break;
			}
		}
		return $remote_ip;
	}

	$remote_ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
	return $remote_ip;

}


/*
 * Die() with or without screen based on JS availability
 */
function zeno_report_comments_cond_die( $message ) {

	if ( isset( $_POST['no_js'] ) && true === (bool) $_POST['no_js'] ) {
		wp_die( esc_html( $message ), esc_html__( 'Zeno Report Comments Notice', 'zeno-report-comments' ), array( 'response' => 200 ) );
	} else {
		die( esc_html( $message ) );
	}

}


/*
 * Check if this comment was moderated by a moderator.
 *
 * @param $comment_id int ID of the comment.
 * @return bool true if it was already moderated, false if not.
 *
 * @since 1.3.6
 */
function zeno_report_comments_already_moderated( $comment_id ) {

	$comment_id = (int) $comment_id;

	// we will not send a comment into moderation twice. the moderator is the boss here.
	$already_moderated = (bool) get_comment_meta( $comment_id, 'zrcmnt_moderated', true );
	if ( true === $already_moderated ) {
		// Have this filter return true if the boss wants to allow comments to be reflagged.
		if ( ! apply_filters( 'zeno_report_comments_allow_moderated_to_be_reflagged', false ) ) {
			return true;
		}
	}

	return false;

}


/*
 * Check if this comment was flagged by this user before.
 */
function zeno_report_comments_already_flagged( $comment_id ) {

	$comment_id = (int) $comment_id;

	// check if cookies are enabled and use cookie store
	if ( isset( $_COOKIE[ TEST_COOKIE ] ) ) {
		if ( isset( $_COOKIE[ "zfrc_flags" ] ) ) {
			$data = zeno_report_comments_unserialize_cookie( $_COOKIE[ "zfrc_flags" ] );
			if ( is_array( $data ) && isset( $data[ "$comment_id" ] ) ) {
				return true;
			}
		}
	}

	$settings = zeno_report_comments_get_settings();

	// in case we don't have cookies. fall back to transients, block based on IP/User Agent
	$transient = get_transient( md5( 'zfrc_flags' . zeno_report_comments_get_user_ip() ) );
	if ( $transient ) {
		if (
			// check if no cookie and transient is set
			( ! isset( $_COOKIE[ TEST_COOKIE ] ) && isset( $transient[ "$comment_id" ] ) ) ||
			// or check if cookies are enabled and comment is not flagged but transients show a relatively high number and assume fraud
			( isset( $_COOKIE[ TEST_COOKIE ] ) && isset( $transient[ "$comment_id" ] ) && $transient[ "$comment_id" ] >= $settings['no_cookie_grace'] )
			) {

			return true;
		}
	}

	return false;

}


/*
 * Several settings to be used in this plugin.
 *
 * @uses static $settings with static data.
 * @return array with settings.
 * @since 2.0.0
 *
 */
function zeno_report_comments_get_settings() {

	static $settings;

	if ( is_array( $settings ) && ! empty( $settings ) ) {
		return $settings;
	}

	$settings = array();

	// amount of possible attempts transient hits per comment before a COOKIE enabled negative check is considered invalid.
	// transient hits will be counted up per ip any time a user flags a comment.
	// this number should be always lower than your threshold to avoid manipulation.
	$settings['no_cookie_grace']    = 3;
	$settings['cookie_lifetime']    = 604800; // lifetime of the cookie ( 1 week ). After this duration a user can report a comment again.
	$settings['transient_lifetime'] = 86400; // lifetime of fallback transients. lower to keep things usable.

	// error
	$invalid_nonce_message = esc_html__( 'The Nonce was invalid. Please refresh and try again.', 'zeno-report-comments' );
	$settings['invalid_nonce_message'] = apply_filters( 'zeno_report_comments_invalid_nonce_message', $invalid_nonce_message );

	// error
	$invalid_values_message = esc_html__( 'Cheating huh?', 'zeno-report-comments' );
	$settings['invalid_values_message'] = apply_filters( 'zeno_report_comments_invalid_values_message', $invalid_values_message );


	// note displayed in the frontend when the user flagged this comment.
	$thank_you_message = esc_html__( 'Thank you for your feedback. We will look into it.', 'zeno-report-comments' );
	$settings['thank_you_message'] = apply_filters( 'zeno_report_comments_thank_you_message', $thank_you_message );


	// messaged in the ajax frontend when the user had already flagged this comment.
	$already_flagged_message = esc_html__( 'It seems you already reported this comment.', 'zeno-report-comments' );
	$settings['already_flagged_message'] = apply_filters( 'zeno_report_comments_already_flagged_message', $already_flagged_message );


	// note displayed in the frontend instead of the report link when a comment was flagged.
	$already_flagged_note = ' ' . esc_html__( 'flagged', 'zeno-report-comments' );
	$settings['already_flagged_note'] = apply_filters( 'zeno_report_comments_already_flagged_note', $already_flagged_note );


	// messaged in the ajax frontend when the comment was already moderated by a moderator.
	$already_moderated_message = ' ' . esc_html__( 'This comment is already moderated', 'zeno-report-comments' );
	$settings['already_moderated_message'] = apply_filters( 'zeno_report_comments_already_moderated_message', $already_moderated_message );


	// note displayed in the frontend instead of the report link when a comment was moderated by a moderator.
	$already_moderated_note = ' ' . esc_html__( 'moderated', 'zeno-report-comments' );
	$settings['already_moderated_note'] = apply_filters( 'zeno_report_comments_already_moderated_note', $already_moderated_note );


	// messaged in the ajax admin column after moderation.
	$moderated_message = '0 ' . esc_html__( 'moderated', 'zeno-report-comments' );
	$settings['moderated_message'] = apply_filters( 'zeno_report_comments_moderated_message', $moderated_message );


	// note displayed in the admin column after moderation.
	$moderated_note = ' ' . esc_html__( 'moderated', 'zeno-report-comments' );
	$settings['moderated_note'] = apply_filters( 'zeno_report_comments_moderated_note', $moderated_note );


	return $settings;

}



// need to do this at template_redirect because is_feed isn't available yet.
function zeno_report_comments_add_test_cookie() {

	//Set a cookie now to see if they are supported by the browser.
	// Don't add cookie if it's already set; and don't do it for feeds
	if ( ! is_feed() && ! isset( $_COOKIE[ TEST_COOKIE ] ) ) {
		setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
		if ( SITECOOKIEPATH !== COOKIEPATH ) {
			setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);
		}
	}

}
add_action( 'template_redirect', 'zeno_report_comments_add_test_cookie' );


/*
 * Helper functions to (un)/serialize cookie values
 */
function zeno_report_comments_serialize_cookie( $value ) {
	$value = zeno_report_comments_clean_cookie_data( $value );
	return base64_encode( json_encode( $value ) );
}
function zeno_report_comments_unserialize_cookie( $value ) {
	$data = json_decode( base64_decode( $value ) );
	return zeno_report_comments_clean_cookie_data( $data );
}


function zeno_report_comments_clean_cookie_data( $data ) {

	$clean_data = array();

	if ( is_object( $data ) ) {
		// json_decode decided to make an object. Turn it into an array.
		$data = get_object_vars( $data );
	}

	if ( ! is_array( $data ) ) {
		$data = array();
	}

	foreach ( $data as $comment_id => $count ) {
		if ( is_numeric( $comment_id ) && is_numeric( $count ) ) {
			$clean_data[ "$comment_id" ] = $count;
		}
	}

	return $clean_data;

}


/*
 * Check on frontend for blocklisted IP address.
 * Borrowed from wp-includes/comment.php check_comment().
 * Uses blocklisted IP address from WordPress Core Comments.
 *
 * @since 1.4.0
 *
 * @return bool true when on blocklist, false when not.
 */
function zeno_report_comments_check_ip_on_blocklist() {

	$mod_keys = trim( get_option( 'moderation_keys' ) );

	// If moderation 'keys' (keywords) are set, process them.
	$words = array();
	if ( ! empty( $mod_keys ) ) {
		$words = explode( "\n", $mod_keys );
	}

	if ( ! empty( $words ) ) {
		foreach ( (array) $words as $word ) {
			$word = trim( $word );

			// Skip empty lines.
			if ( empty( $word ) ) {
				continue;
			}

			/*
			 * Do some escaping magic so that '#' (number of) characters in the spam
			 * words don't break things:
			 */
			$word = preg_quote( $word, '#' );

			/*
			 * Check the comment fields for moderation keywords. If any are found,
			 * fail the check for the given field by returning false.
			 */
			$pattern = "#$word#i";

			$user_ip = zeno_report_comments_get_user_ip();
			if ( preg_match( $pattern, $user_ip ) ) {
				return true;
			}
		}
	}

	return false;

}


/*
 * Use a custom field name for the bot protection fields that are different for each website.
 *
 * @param string field name of the requested field.
 * @return string hashed fieldname or fieldname, prepended with zeno_report_comments.
 *
 * @since 2.1.0
 */
function zeno_report_comments_get_field_name( $field ) {

	$blog_url = get_option( 'siteurl' );
	// $blog_url = get_bloginfo('wpurl'); // Will be different depending on scheme (http/https).

	$key = 'zeno_report_comments_' . $field . '_field_name_' . $blog_url;
	$field_name = wp_hash( $key, 'auth' );
	$field_name = 'zeno_report_comments_' . $field_name;

	return $field_name;

}
