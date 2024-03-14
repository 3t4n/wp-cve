<?php
namespace logged_in_as;
/**
 * Inject some css to decorate the target menu item
 */


add_action( 'wp_enqueue_scripts', '\logged_in_as\liam_register_style', 99 );
add_action( 'plugins_loaded', '\logged_in_as\liam_maybe_print_css' );


// Prevent direct file access
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Taken from simple-custom-css plugin. Thank you :) jms
 *
 * Enqueue link to add CSS through PHP.
 *
 * This is a typical WP Enqueue statement, except that the URL of the stylesheet is simply a query var.
 * This query var is passed to the URL, and when it is detected by liam_maybe_print_css(),
 * it writes its PHP/CSS to the browser.
 *
 * @since  1.0.0
 *
 * @action wp_enqueue_scripts, 99
 */
function liam_register_style() {
	
	$url = home_url();

	if ( is_ssl() ) {
		$url = home_url( '/', 'https' );
	}

	wp_register_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters
		'logged-in-as_style',
		add_query_arg(
			array(
				'logged-in-as' => 1,
			),
			$url ),
		array(),
		mt_rand() 		// apply a random number for version to force the browser to always load, cache busting
	);

	wp_enqueue_style( 'logged-in-as_style' );
}


/**
 * If the current GET is for us, echo the css.
 *
 * @since  1.0.0
 *
 * @action plugins_loaded
 */
function liam_maybe_print_css() {

	// Only print CSS if this is a stylesheet request.
	if ( ! isset( $_GET['logged-in-as'] ) || intval( $_GET['logged-in-as'] ) !== 1 ) {  // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

//	ob_start(); //removed  -- jerry
	header( 'Content-type: text/css' );

	liam_echo_css();

	die();
}


/**
 * Echo the CSS.
 *
 * @since 1.0.0
 */
function liam_echo_css() {

	$user_id = get_current_user_id();

	// no logged in user, bail
	if ( $user_id == 0 )
		return false;
		
	$avatar_size = (int)get_option( Logged_in_As_Options::$avatar_size_option );

	// avatar size <10, bail
	if ( $avatar_size < 10 )
		return false;
	
	// a very useful function as it turns out.. only way I found to see if there is a user-avatar defined
	$args = get_avatar_data( $user_id, [ 'size' => $avatar_size ] );
	
	// no avatar, bail
	if ( !$args[ 'found_avatar' ] )
		return;
	
	$image_url = $args[ 'url' ];
	if ( $image_url ) {
	
		$css = '
		/* Add an image for the current user to the target element. (- menu item) */
		.' . LIA_ICON_CSS_CLASS . ' > a::before { 
		display: inline-block; 
		content: "";												/* blank content ... */
		height: ' . $avatar_size . 'px;			/* ... with a fixed size ... */
		width: ' . $avatar_size .'px;
		border-radius: 50%;									/* ... and rounded courners */
		vertical-align: middle !important;	/* The target text appears vertically centred in relation to the image */
		margin-right: 5px;									/* A wee space between the image and the text */
		background-image: url( ' . $image_url . ' );
		background-size: ' . $avatar_size . 'px;	/* to scale the avatar */
		background-repeat: no-repeat;
		}';
		
		$css .= '
		/* By adding an image to the target in ::before adjust the vertical align of any ::after element so that the target, ::before and ::after stays aligned  */
		/* Introduced because of the menu-with-children "arrow" */
		.' . LIA_ICON_CSS_CLASS . ' > a::after { 
		vertical-align: middle !important;
		position: static !important;
		}';
		

		echo $css;
	}
}

