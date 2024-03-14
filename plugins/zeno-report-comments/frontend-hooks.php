<?php
/*
 * Frontend Hooks for Zeno Report Comments.
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Run action for compatibility with v1. All code was split up in v2 with this left over.
 *
 * @since 2.0.0
 */
function zeno_report_comments_frontend_init() {

	do_action( 'zeno_report_comments_frontend_init' );

}
add_action( 'init', 'zeno_report_comments_frontend_init' );


/*
 * Alert admin via email when comment has been sent into moderation.
 *
 * @since 1.0
 *
 * @param int $comment_id
 *
 */
function zeno_report_comments_admin_notification( $comment_id ) {

	$enabled = (int) get_option( 'zrcmnt_admin_notification', 1 );

	if ( ! $enabled ) {
		return;
	}

	$comment = get_comment( $comment_id );

	$admin_email = get_option( 'admin_email' );
	$admin_email = apply_filters( 'zeno_report_comments_admin_email', $admin_email );

	/* translators: %s is the comment author */
	$subject = sprintf( esc_html__( 'A comment by %s has been flagged by users and sent back to moderation', 'zeno-report-comments' ), esc_html( $comment->comment_author ) );
	/* translators: %1$s is comment author, %2$s is comment email */
	$headers = sprintf( 'From: %1$s <%2$s>', esc_html( get_bloginfo( 'site' ) ), esc_html( get_option( 'admin_email' ) ) ) . "\r\n\r\n";
	$message = esc_html__( 'Users of your site have flagged a comment and it has been sent to moderation.', 'zeno-report-comments' ) . "\r\n";
	$message .= esc_html__( 'You are welcome to view the comment yourself at your earliest convenience.', 'zeno-report-comments' ) . "\r\n\r\n";
	$message .= esc_url_raw( add_query_arg( array(
		'action' => 'editcomment',
		'c'      => absint( $comment_id ),
		), esc_url( admin_url( 'comment.php' ) ) ) );
	$comment_text = get_comment_text( $comment_id );
	$message .= "\r\n\r\n" . esc_html__( 'Comment:', 'zeno-report-comments' ) . "\r\n" . $comment_text . "\r\n";
	$message .= "\r\n\r\n" . zeno_report_comments_get_user_info() . "\r\n";

	wp_mail( $admin_email, $subject, $message, $headers );

}
add_action( 'zeno_report_comments_mark_flagged', 'zeno_report_comments_admin_notification' );


/*
 * Alert admin via email when comment has been reported.
 *
 * @since 1.0
 *
 * @param int $comment_id
 *
 */
function zeno_report_comments_admin_notification_each( $comment_id ) {

	$enabled = (int) get_option( 'zrcmnt_admin_notification_each', 1 );

	if ( ! $enabled ) {
		return;
	}

	$comment = get_comment( $comment_id );

	$admin_email = get_option( 'admin_email' );
	$admin_email = apply_filters( 'zeno_report_comments_admin_email', $admin_email );

	/* translators: %s is the comment author */
	$subject = sprintf( esc_html__( 'A comment by %s has been flagged by a user', 'zeno-report-comments' ), esc_html( $comment->comment_author ) );
	/* translators: %1$s is comment author, %2$s is comment email */
	$headers = sprintf( 'From: %1$s <%2$s>', esc_html( get_bloginfo( 'site' ) ), esc_html( get_option( 'admin_email' ) ) ) . "\r\n\r\n";
	$message = esc_html__( 'A user of your site has flagged a comment.', 'zeno-report-comments' ) . "\r\n";
	$message .= esc_html__( 'You are welcome to view the comment yourself at your earliest convenience.', 'zeno-report-comments' ) . "\r\n\r\n";
	$message .= esc_url_raw( add_query_arg( array(
		'action' => 'editcomment',
		'c'      => absint( $comment_id ),
		), esc_url( admin_url( 'comment.php' ) ) ) );
	$comment_text = get_comment_text( $comment_id );
	$message .= "\r\n\r\n" . esc_html__( 'Comment:', 'zeno-report-comments' ) . "\r\n" . $comment_text . "\r\n";
	$message .= "\r\n\r\n" . zeno_report_comments_get_user_info() . "\r\n";

	wp_mail( $admin_email, $subject, $message, $headers );

}
add_action( 'zeno_report_comments_add_report', 'zeno_report_comments_admin_notification_each' );


/*
 * Get user info for notification emails.
 *
 * @since 2.0.3
 *
 * @return $info string
 */
function zeno_report_comments_get_user_info() {

	$info = esc_html__( 'Reporter:', 'zeno-report-comments' ) . "\r\n";
	$current_user = wp_get_current_user();

	if ( is_user_logged_in() && $current_user instanceof WP_User ) {
		$info .= sprintf( esc_html__( 'Username: %s', 'zeno-report-comments' ), esc_html( $current_user->user_login ) ) . "\r\n";
		$info .= sprintf( esc_html__( 'User email: %s', 'zeno-report-comments' ), esc_html( $current_user->user_email ) ) . "\r\n";
		$info .= sprintf( esc_html__( 'User first name: %s', 'zeno-report-comments' ), esc_html( $current_user->user_firstname ) ) . "\r\n";
		$info .= sprintf( esc_html__( 'User last name: %s', 'zeno-report-comments' ), esc_html( $current_user->user_lastname ) ) . "\r\n";
		$info .= sprintf( esc_html__( 'User display name: %s', 'zeno-report-comments' ), esc_html( $current_user->display_name ) ) . "\r\n";
		$info .= sprintf( esc_html__( 'User ID: %s', 'zeno-report-comments' ), esc_html( $current_user->ID ) ) . "\r\n";
	} else {
		$info .= esc_html__( 'User: not logged in', 'zeno-report-comments' ) . "\r\n";
	}
	$reporter_ip = zeno_report_comments_get_user_ip();
	$info .= sprintf( esc_html__( 'IP address: %s', 'zeno-report-comments' ), esc_attr( $reporter_ip ) ) . "\r\n";

	return $info;

}


function zeno_report_comments_action_enqueue_scripts() {

	$enabled = (int) get_option( 'zrcmnt_enabled' );
	if ( ! $enabled ) {
		return;
	}

	if ( zeno_report_comments_check_ip_on_blocklist() ) {
		return;
	}

	// Use home_url() if domain mapped to avoid cross-domain issues
	if ( home_url() !== site_url() ) {
		$ajaxurl = home_url( '/wp-admin/admin-ajax.php' );
	} else {
		$ajaxurl = admin_url( 'admin-ajax.php' );
	}
	$ajaxurl = apply_filters( 'zeno_report_comments_ajax_url', $ajaxurl );

	wp_enqueue_script( 'zrcmnt-ajax-request', ZENORC_URL . '/js/ajax.js', array( 'jquery' ), ZENORC_VER, true );

	$nonce = wp_create_nonce( 'zrcmnt_zeno_flag_comment_nonce' );
	$data_to_be_passed = array(
		'ajaxurl'  => $ajaxurl,
		'nonce'    => $nonce,
		'timeout'  => zeno_report_comments_get_field_name( 'timeout' ),
		'timeout2' => zeno_report_comments_get_field_name( 'timeout2' ),
	);
	wp_localize_script( 'zrcmnt-ajax-request', 'zenocommentsajax', $data_to_be_passed );

}
add_action( 'wp_enqueue_scripts', 'zeno_report_comments_action_enqueue_scripts' );



/*
 * Ajax callback to flag/report a comment.
 * AJAX action: zeno_report_comments_flag_comment
 */
function zeno_report_comments_flag_comment() {

	$enabled = (int) get_option( 'zrcmnt_enabled' );
	if ( ! $enabled ) {
		return;
	}

	if ( zeno_report_comments_check_ip_on_blocklist() ) {
		return;
	}

	$spamcheck = zeno_report_comments_check_bot_protection();
	if ( $spamcheck === 'spam' ) {
		return;
	}

	$settings = zeno_report_comments_get_settings();

	if ( ! isset( $_POST['comment_id'] ) || ! is_numeric( $_POST['comment_id'] ) || empty( $_POST['comment_id'] ) ) {
		zeno_report_comments_cond_die( $settings['invalid_values_message'] );
	}

	$comment_id = (int) $_POST['comment_id'];

	if ( zeno_report_comments_already_moderated( $comment_id ) ) {
		zeno_report_comments_cond_die( $settings['already_moderated_message'] );
	}
	if ( zeno_report_comments_already_flagged( $comment_id ) ) {
		zeno_report_comments_cond_die( $settings['already_flagged_message'] );
	}

	$nonce = '';
	if ( isset( $_POST['sc_nonce'] ) ) {
		$nonce = $_POST['sc_nonce'];
	}
	// Check for Nonce.
	if ( ! wp_verify_nonce( $nonce, 'zrcmnt_zeno_flag_comment_nonce' ) ) {
		zeno_report_comments_cond_die( $settings['invalid_nonce_message'] );
	} else {
		zeno_report_comments_mark_flagged( $comment_id );
		zeno_report_comments_cond_die( $settings['thank_you_message'] );
	}

}
add_action( 'wp_ajax_zeno_report_comments_flag_comment', 'zeno_report_comments_flag_comment' );
add_action( 'wp_ajax_nopriv_zeno_report_comments_flag_comment', 'zeno_report_comments_flag_comment' );


/*
 * Report a comment and send it to moderation if threshold is reached
 */
function zeno_report_comments_mark_flagged( $comment_id ) {

	$comment_id = (int) $comment_id;

	$settings = zeno_report_comments_get_settings();

	$data = array();
	if ( isset( $_COOKIE[ TEST_COOKIE ] ) ) {
		if ( isset( $_COOKIE[ "zfrc_flags" ] ) ) {
			$data = zeno_report_comments_unserialize_cookie( $_COOKIE[ "zfrc_flags" ] );
			if ( ! isset( $data[ "$comment_id" ] ) ) {
				$data[ "$comment_id" ] = 0;
			}
			$data[ "$comment_id" ]++;
			$cookie = zeno_report_comments_serialize_cookie( $data );
			setcookie( 'zfrc_flags', $cookie, ( time() + $settings['cookie_lifetime'] ), COOKIEPATH, COOKIE_DOMAIN );
			if ( SITECOOKIEPATH !== COOKIEPATH ) {
				setcookie( 'zfrc_flags', $cookie, ( time() + $settings['cookie_lifetime'] ), SITECOOKIEPATH, COOKIE_DOMAIN);
			}
		} else {
			if ( ! isset( $data[ "$comment_id" ] ) ) {
				$data[ "$comment_id" ] = 0;
			}
			$data[ "$comment_id" ]++;
			$cookie = zeno_report_comments_serialize_cookie( $data );
			setcookie( 'zfrc_flags', $cookie, ( time() + $settings['cookie_lifetime'] ), COOKIEPATH, COOKIE_DOMAIN );
			if ( SITECOOKIEPATH !== COOKIEPATH ) {
				setcookie( 'zfrc_flags', $cookie, ( time() + $settings['cookie_lifetime'] ), SITECOOKIEPATH, COOKIE_DOMAIN);
			}
		}
	}
	// in case we don't have cookies. fall back to transients, block based on IP, shorter timeout to keep mem usage low and don't lock out whole companies
	$transient = get_transient( md5( 'zfrc_flags' . zeno_report_comments_get_user_ip() ) );
	if ( ! $transient ) {
		set_transient( md5( 'zfrc_flags' . zeno_report_comments_get_user_ip() ), array( $comment_id => 1 ), $settings['transient_lifetime'] );
	} else {
		if ( ! isset( $transient[ "$comment_id" ] ) ) {
			$transient[ "$comment_id" ] = 0;
		}
		$transient[ "$comment_id" ]++;
		set_transient( md5( 'zfrc_flags' . zeno_report_comments_get_user_ip() ), $transient, $settings['transient_lifetime'] );
	}

	$threshold = (int) get_option( 'zrcmnt_threshold' );
	$current_reports = get_comment_meta( $comment_id, 'zrcmnt_reported', true );
	$current_reports++;
	update_comment_meta( $comment_id, 'zrcmnt_reported', $current_reports );
	do_action( 'zeno_report_comments_add_report', $comment_id );

	if ( $current_reports >= $threshold ) {
		do_action( 'zeno_report_comments_mark_flagged', $comment_id );
		wp_set_comment_status( $comment_id, 'hold' );
	}

}


/*
 * Print link to report a comment.
 * Is this even used anywhere by anyone?
 */
function zeno_report_comments_print_flagging_link( $comment_id = '', $result_id = '', $text = '' ) {

	if ( empty( $text ) ) {
		$text = esc_html__( 'Report comment', 'zeno-report-comments' );
	}
	echo zeno_report_comments_get_flagging_link( $comment_id, $result_id, $text );

}
add_action( 'comment_report_abuse_link', 'zeno_report_comments_print_flagging_link' );


/*
 * Return link to report a comment.
 */
function zeno_report_comments_get_flagging_link( $comment_id = '', $result_id = '', $text = '' ) {

	global $in_comment_loop;

	$settings = zeno_report_comments_get_settings();

	if ( empty( $comment_id ) && ! $in_comment_loop ) {
		return esc_html__( 'Wrong usage of print_flagging_link().', 'zeno-report-comments' );
	}
	if ( empty( $comment_id ) ) {
		$comment_id = get_comment_ID();
	} else {
		$comment_id = (int) $comment_id;
	}
	$comment = get_comment( $comment_id );
	if ( ! $comment ) {
		return esc_html__( 'This comment does not exist.', 'zeno-report-comments' );
	}
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		if ( $user_id === (int) $comment->user_id ) {
			return '<!-- author comment -->';
		}
	}
	if ( empty( $result_id ) ) {
		$result_id = 'zeno-comments-result-' . $comment_id;
	}
	$result_id = apply_filters( 'zeno_report_comments_result_id', $result_id );
	if ( empty( $text ) ) {
		$text = esc_html__('Report comment', 'zeno-report-comments' );
	}
	$text = apply_filters( 'zeno_report_comments_flagging_link_text', $text );

	// This comment was already moderated. Don't show the link.
	if ( zeno_report_comments_already_moderated( $comment_id ) ) {
		return $settings['already_moderated_note'];
	}

	// This user already flagged this comment. Don't show the link.
	if ( zeno_report_comments_already_flagged( $comment_id ) ) {
		return $settings['already_flagged_note'];
	}

	$bot_html = zeno_report_comments_get_bot_protection_for_flagging_link();

	return apply_filters( 'zeno_report_comments_flagging_link', '
		<span id="' . esc_attr( $result_id ) . '">
			<a class="hide-if-no-js" href="#" data-zeno-comment-id="' . esc_attr( $comment_id ) . '" rel="nofollow">' . esc_html( $text ) . '</a>
		</span>
		' ) . $bot_html;

}


/*
 * Get bot protection for the report button.
 * Added only once to the report buttons.
 * Taken from the La Sentinelle plugin:
 * https://wordpress.com/plugins/la-sentinelle-antispam
 *
 * @since 2.1.0
 *
 * @return $html string
 *
 * @uses static string $html_static
 */
function zeno_report_comments_get_bot_protection_for_flagging_link() {

	static $html_static;

	if ( is_null( $html_static ) ) {
		$field_name = zeno_report_comments_get_field_name( 'timeout' );
		$field_name2 = zeno_report_comments_get_field_name( 'timeout2' );
		$random = rand( 100, 100000 );

		$html_static = '
			<div class="zeno-report-comments-container" style="max-height:0;overflow:hidden;">
				<form>
					<input value="' . esc_attr( $random ) . '" type="text" name="' . esc_attr( $field_name ) . '" class="' . esc_attr( $field_name ) . '" placeholder="" style="transform: translateY(10000px);" />
					<input value="' . esc_attr( $random ) . '" type="text" name="' . esc_attr( $field_name2 ) . '" class="' . esc_attr( $field_name2 ) . '" placeholder="" style="transform: translateY(10000px);" />
				</form>
			</div>
			';

		return $html_static;
	} else {
		return '';
	}

}


/*
* Check timeout fields for the report button.
*
* @return string result 'spam' if it is considered spam.
*
* @since 2.1.0
*/
function zeno_report_comments_check_bot_protection() {

	$enabled = (int) get_option( 'zrcmnt_spamcheck' );
	if ( ! $enabled ) {
		return '';
	}

	$post_data = $_POST;

	if ( is_array( $post_data ) && ! empty( $post_data ) ) {
		$field_name = zeno_report_comments_get_field_name( 'timeout' );
		$field_name2 = zeno_report_comments_get_field_name( 'timeout2' );
		if ( isset($post_data["$field_name"]) && strlen($post_data["$field_name"]) > 0 && isset($post_data["$field_name2"]) && strlen($post_data["$field_name2"]) > 0 ) {
			// Input fields were filled in, so continue.
			$timeout  = (int) $post_data["$field_name"];
			$timeout2 = (int) $post_data["$field_name2"];
			if ( ( $timeout2 - $timeout ) < 2 ) {
				// Submitted less then 1 seconds after loading. Considered spam.
				return 'spam';
			}
		} else {
			// Input fields were not filled in correctly. Considered spam.
			return 'spam';
		}
	}

	return '';

}


/*
 * Callback function to automatically hook in the report link after the comment reply link if threading is enabled.
 * Hooks into reply links, works only on threaded comments and not on the max threaded comment in the thread.
 *
 * @uses comment_reply_link filter.
 *
 * @param string     $link    The HTML markup for the comment reply link.
 * @param array      $args    An array of arguments overriding the defaults.
 * @param WP_Comment $comment The object of the comment being replied.
 * @param WP_Post    $post    The WP_Post object. (not used)
 *
 * @return string    $link    The HTML markup for the comment reply link.
 */
function zeno_report_comments_add_flagging_link_to_reply_link( $comment_reply_link, $args, $comment ) {

	$enabled = (int) get_option( 'zrcmnt_enabled' );
	if ( ! $enabled ) {
		return $comment_reply_link;
	}

	if ( zeno_report_comments_check_ip_on_blocklist() ) {
		return $comment_reply_link;
	}

	$comment_id = $comment->comment_ID;
	$class = 'zeno-comments-report-link';
	if ( zeno_report_comments_already_moderated( $comment_id ) ) {
		$class .= ' zrc-already-moderated';
	}
	$pattern = '#(<a.+class=.+comment-(reply|login)-l(i|o)(.*)[^>]+>)(.+)(</a>)#msiU';
	$replacement = '$0 <span class="' . esc_attr( $class ) . '">' . zeno_report_comments_get_flagging_link( $comment_id ) . '</span>'; // $0 is the matched pattern.
	$comment_reply_link = preg_replace( $pattern, $replacement, $comment_reply_link );

	return apply_filters( 'zeno_report_comments_comment_reply_link', $comment_reply_link );

}
add_filter( 'comment_reply_link', 'zeno_report_comments_add_flagging_link_to_reply_link', 10, 3 );


/*
 * Callback function to automatically hook in the comment content if threading is disabled.
 * Hooks into comment content, but only if threading and replies are disabled.
 *
 * @uses get_comment_text filter.
 *
 * @param string     $comment_content Text of the comment.
 * @param WP_Comment $comment         The comment object.
 * @param array      $args            An array of arguments. (not used)
 *
 * @return string    $comment_content Text of the comment.
 *
 * @since 1.2.0
 */
function zeno_report_comments_add_flagging_link_to_content( $comment_content, $comment ) {

	$enabled = (int) get_option( 'zrcmnt_enabled' );
	if ( ! $enabled ) {
		return $comment_content;
	}

	if ( get_option('thread_comments') ) {
		return $comment_content; // threaded, don't add it to the content.
	}

	if ( zeno_report_comments_check_ip_on_blocklist() ) {
		return $comment_content;
	}

	if ( is_admin() ) {
		return $comment_content;
	}

	$comment_id = $comment->comment_ID;
	$class = 'zeno-comments-report-link';
	if ( zeno_report_comments_already_moderated( $comment_id ) ) {
		$class .= ' zrc-already-moderated';
	}
	$flagging_link = zeno_report_comments_get_flagging_link( $comment_id );
	if ( $flagging_link ) {
		$comment_content .= '<br /><span class="' . esc_attr( $class ) . '">' . $flagging_link . '</span>';
	}
	return $comment_content;

}
add_filter( 'comment_text', 'zeno_report_comments_add_flagging_link_to_content', 80, 2 );
