<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# cau hinh gmail smtp
if(isset($foxtool_options['mail-gsmtp1'])){
function foxtool_mail_smtp($phpmailer) {
	global $foxtool_options;
	$smtp_name = !empty($foxtool_options['mail-gsmtp11']) ? $foxtool_options['mail-gsmtp11'] : null;
	$smtp_e = !empty($foxtool_options['mail-gsmtp12']) ? $foxtool_options['mail-gsmtp12'] : null;
	$smtp_tk = !empty($foxtool_options['mail-gsmtp13']) ? $foxtool_options['mail-gsmtp13'] : null;
	$smtp_mk = !empty($foxtool_options['mail-gsmtp14']) ? $foxtool_options['mail-gsmtp14'] : null;
	$smtp_sv = !empty($foxtool_options['mail-gsmtp15']) ? $foxtool_options['mail-gsmtp15'] : null;
	$smtp_hot = !empty($foxtool_options['mail-gsmtp16']) ? $foxtool_options['mail-gsmtp16'] : null;
	$smtp_kn = !empty($foxtool_options['mail-gsmtp17']) ? $foxtool_options['mail-gsmtp17'] : null;
	if (!is_object( $phpmailer ))
	$phpmailer = (object) $phpmailer;
	$phpmailer->Mailer = 'smtp';
	if(isset($foxtool_options['mail-gsmtp18'])){
		$phpmailer->SMTPAuth = true;
	} else {
		$phpmailer->SMTPAuth = false;	
	}
	$phpmailer->FromName = $smtp_name;
	$phpmailer->From = $smtp_e;
	$phpmailer->Username = $smtp_tk;
	$phpmailer->Password = $smtp_mk;
	$phpmailer->Host = $smtp_sv;
	$phpmailer->Port = $smtp_hot;
	$phpmailer->SMTPSecure = $smtp_kn;
}
add_action( 'phpmailer_init', 'foxtool_mail_smtp');
# test thu email
function foxtool_send_email() {
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'ft-send-email-nonce')) {
        $admin_email = get_option('admin_email');
        $to = $admin_email;
        $subject = __('Test SMTP email sending from your website', 'foxtool');
        $message = __('If you receive this email, then the SMTP email sending function is working well', 'foxtool');
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $sent = wp_mail($to, $subject, $message, $headers);
        if ($sent) {
            echo __('Email sent successfully', 'foxtool');
        } else {
            echo __('Unable to send email!', 'foxtool');
        }
    }
    wp_die();
}
add_action('wp_ajax_ft_send_email', 'foxtool_send_email'); 
add_action('wp_ajax_nopriv_ft_send_email', 'foxtool_send_email'); 
}
# thong bao email khi co ai tra loi nhan xet
if(isset($foxtool_options['mail-com1'])){
function foxtool_send_email_on_reply( $comment_id, $comment_object ) {
  $parent_comment = get_comment( $comment_object->comment_parent );
  $to = $parent_comment->comment_author_email;
  $subject = __('Your comment has just been replied to', 'foxtool');
  $message = __('Your comment has just been replied to in the post', 'foxtool') .' '. get_the_title( $comment_object->comment_post_ID ) . ': ' . get_comment_link( $comment_id );
  wp_mail( $to, $subject, $message );
}
add_action( 'wp_insert_comment', 'foxtool_send_email_on_reply', 10, 2 );
}
# thong bao email cho tac gia khi co binh luan moi
if(isset($foxtool_options['mail-com2'])){
function foxtool_notify_post_author_on_comment( $comment_id ) {
    $comment = get_comment( $comment_id );
    $post = get_post( $comment->comment_post_ID );
    $author = get_userdata( $post->post_author );
    if ( $author->user_email !== $comment->comment_author_email ) {
        $message = sprintf(
            __( 'Hello %s. A new comment has been posted on your article "%s". You can view the comment here: %s', 'foxtool' ),
            $author->display_name,
            $post->post_title,
            get_comment_link( $comment_id )
        );
        $subject = sprintf( __( '[%s] New comment on the article "%s"', 'foxtool' ), get_bloginfo( 'name' ), $post->post_title );
        wp_mail( $author->user_email, $subject, $message );
    }
}
add_action( 'comment_post', 'foxtool_notify_post_author_on_comment' );
}
# gui email xac minh tài khoan khi dang ky
if (isset($foxtool_options['mail-new1'])){
function foxtool_email_verification( $user_id ){
	global $foxtool_options;
    $email = get_userdata( $user_id )->user_email;
    $subject = !empty($foxtool_options['mail-new11']) ? $foxtool_options['mail-new11'] : __('Welcome to the website', 'foxtool');
    $message = !empty($foxtool_options['mail-new12']) ? $foxtool_options['mail-new12'] : __('This is an email notification of your successful registration', 'foxtool');
    $headers = 'Content-Type: text/html; charset=UTF-8';
    wp_mail( $email, $subject, $message, $headers );
}
add_action( 'user_register', 'foxtool_email_verification', 10, 1 );
}








