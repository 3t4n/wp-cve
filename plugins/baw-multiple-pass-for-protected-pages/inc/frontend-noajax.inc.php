<?php
if( !defined( 'ABSPATH' ) )
	die( 'Cheatin\' uh?' );

add_action( 'the_password_form', 'bawmpp_the_password_form' );
function bawmpp_the_password_form()
{
	global $post;
	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$output = '<form method="post">
	<p>' . __("This post is password protected. To view it please enter your password below:") . '</p>
	<input type="hidden" name="post_id" value="' . $post->ID .'" />
	<input type="hidden" name="action" value="bawmmp_multiple_password_check" />
	<p><label for="' . $label . '">' . __("Password:") . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input type="submit" name="Submit" value="' . esc_attr__("Submit") . '" /></p>
	</form>
	';
	return $output;
}

add_action( 'template_redirect', 'bawmmp_multiple_password_check', 9 );
function bawmmp_multiple_password_check()
{
	global $post, $bawmpp_options;
	if( $post && isset( $_POST['post_id'], $_POST['post_password'], $_POST['action'], $_POST['Submit'] ) &&
		$post->ID===(int)$_POST['post_id'] &&
		$_POST['action']=='bawmmp_multiple_password_check' ):
		global $wp_hasher;
		if ( empty( $wp_hasher ) ):
			require_once( ABSPATH . 'wp-includes/class-phpass.php' );
			$wp_hasher = new PasswordHash( 8, true );
		endif;
		$post_ID = $bawmpp_options['clone_pass']!='on' && $post->post_parent>0 ? $post->post_parent : $post->ID;
		$a_pass = get_post_meta( $post_ID, '_morepasswords', true );
		if( $_POST['post_password']==$post->post_password ||
			( !empty( $a_pass ) && is_array( $a_pass ) && in_array( $_POST['post_password'], $a_pass ) ) ):
			setcookie( 'wp-postpass_' . COOKIEHASH, $wp_hasher->HashPassword( stripslashes( $post->post_password ) ), time() + 864000, COOKIEPATH );
			setcookie( 'bawmpp-postpass_' . COOKIEHASH, md5( COOKIEHASH . stripslashes( $_POST['post_password'] ) ), time() + 864000, COOKIEPATH );
		endif;
		wp_safe_redirect( wp_get_referer() );
		exit();
	endif;
}

add_action( 'template_redirect', 'bawmpp_template_redirect', 10 );
function bawmpp_template_redirect()
{
	global $post, $current_user, $bawmpp_options;
	if( !empty( $post ) && !empty( $post->post_password ) ):
		global $wp_hasher;
		if ( empty( $wp_hasher ) ):
			require_once( ABSPATH . 'wp-includes/class-phpass.php' );
			$wp_hasher = new PasswordHash( 8, true );
		endif;
		if( isset( $_COOKIE['bawmpp-postpass_' . COOKIEHASH] ) ):
			$a_pass = get_post_meta( $post->ID, '_morepasswords', true );
			$valid = false;
			if( !empty( $a_pass) )
				foreach( $a_pass as $p ):
					$test = md5( COOKIEHASH . stripslashes( $p ) );
					$valid = $test == $_COOKIE['bawmpp-postpass_' . COOKIEHASH];
					if( $valid )
						break;
				endforeach;
			if( !$valid )
				$valid = md5( COOKIEHASH . stripslashes( $post->post_password ) ) == $_COOKIE['bawmpp-postpass_' . COOKIEHASH];
			if( !$valid ):
				setcookie( 'wp-postpass_' . COOKIEHASH, '', time()-1, COOKIEPATH );
				setcookie( 'bawmpp-postpass_' . COOKIEHASH, '', time()-1, COOKIEPATH );
				wp_safe_redirect( $_SERVER['REQUEST_URI'] );
				die();
			endif;
		endif;
		if( post_password_required( $post ) &&
			( ( $bawmpp_options['no_admin']=='on' && current_user_can( 'administrator' ) )
				|| ( $bawmpp_options['no_author']=='on' && $current_user->ID==$post->post_author )
				|| ( $bawmpp_options['no_member']=='on' && is_user_logged_in() ) ) ):
			setcookie( 'wp-postpass_' . COOKIEHASH, $wp_hasher->HashPassword( stripslashes( $post->post_password ) ), time() + 864000, COOKIEPATH );
			wp_safe_redirect( $_SERVER['REQUEST_URI'] );
			die();
		endif;
	endif;
}