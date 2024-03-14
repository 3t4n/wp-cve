<?php
/**
 * @package Frontend
 * @sub-package Webmaster Tools
 */

/**
 * Get the webmaster setting for header and format output
 * @return [string] [webmaster header information]
 */
function catchwebtools_webmaster_header_display(){
	$webmaster_settings	=	catchwebtools_get_options( 'catchwebtools_webmaster' );

	$output		=	'';
	if( isset( $webmaster_settings['status'] ) && $webmaster_settings['status'] ){

		if( isset( $webmaster_settings['header'] ) && '' != $webmaster_settings['header'] )
			$output .= $webmaster_settings['header'] . PHP_EOL;

		if( isset( $webmaster_settings['google-site-verification'] ) && '' != $webmaster_settings['google-site-verification'] )
			$output .= '<meta name="google-site-verification" content="'. esc_attr( $webmaster_settings['google-site-verification'] ) .'" />' . PHP_EOL;

		if( isset( $webmaster_settings['msvalidate.01'] ) && '' != $webmaster_settings['msvalidate.01'] )
			$output .= '<meta name="msvalidate.01" content="'. esc_attr( $webmaster_settings['msvalidate.01'] ) .'" />' . PHP_EOL;

		if( isset( $webmaster_settings['alexaVerifyID'] ) && '' != $webmaster_settings['alexaVerifyID'] )
			$output .= '<meta name="alexaVerifyID" content="'. esc_attr( $webmaster_settings['alexaVerifyID'] ) .'" />' . PHP_EOL;

		if( isset( $webmaster_settings['p:domain_verify'] ) && '' != $webmaster_settings['p:domain_verify'] )
			$output .= '<meta name="p:domain_verify" content="'. esc_attr( $webmaster_settings['p:domain_verify'] ) .'" />' . PHP_EOL;

		if( isset( $webmaster_settings['yandexverify'] ) && '' != $webmaster_settings['yandexverify'] )
			$output .= '<meta name="yandex-verification" content="'. esc_attr( $webmaster_settings['yandexverify'] ) .'" />' . PHP_EOL;
	}
	return $output;
}

/**
 * Get the webmaster setting for footer and format output
 * @return [string] [webmaster footer information]
 */
function catchwebtools_webmaster_footer_display(){
	$webmaster_settings	= catchwebtools_get_options( 'catchwebtools_webmaster' );
	$output		=	'';
	if( isset( $webmaster_settings['status'] ) && $webmaster_settings['status'] ){
		if( isset( $webmaster_settings['footer'] ) && $webmaster_settings['footer'] )
			$output .= $webmaster_settings['footer'] . PHP_EOL ;
	}
	return $output;
}


/**
 * Filter the feed URI if the user has input a custom feed URI.
 *
 * Applied in the `get_feed_link()` WordPress function.
 *
 * @since 1.4
 *
 * @uses catchwebtools_get_option() Get theme setting value.
 *
 * @param string $output From the get_feed_link() WordPress function.
 * @param string $feed   Optional. Defaults to default feed. Feed type (rss2, rss, sdf, atom).
 *
 * @return string Amended feed URL.
 */
function catchwebtools_feed_links_filter( $output, $feed ) {
	$webmaster_settings	=	catchwebtools_get_options( 'catchwebtools_webmaster' );

	if( isset( $webmaster_settings['status'] ) && $webmaster_settings['status'] ) {
		$feed_uri = ( isset( $webmaster_settings['feed_uri'] ) && '' != $webmaster_settings['feed_uri'] ) ? $webmaster_settings['feed_uri'] : false ;
		$comments_feed_uri = ( isset( $webmaster_settings['comments_feed_uri'] ) && '' != $webmaster_settings['comments_feed_uri'] ) ? $webmaster_settings['comments_feed_uri'] : false;

		if ( $feed_uri && ! mb_strpos( $output, 'comments' ) && in_array( $feed, array( '', 'rss2', 'rss', 'rdf', 'atom' ) ) ) {
			$output = esc_url( $feed_uri );
		}

		if ( $comments_feed_uri && mb_strpos( $output, 'comments' ) ) {
			$output = esc_url( $comments_feed_uri );
		}
	}
	return $output;

}
add_filter( 'feed_link', 'catchwebtools_feed_links_filter', 10, 2 );

/**
 * Redirect the browser to the custom feed URI.
 *
 * Exits PHP after redirect.
 *
 * @since 1.3.0
 *
 * @uses catchwebtools_get_option() Get theme setting value.
 *
 * @return null Return early on failure. Exits on success.
 */
function catchwebtools_feed_redirect() {
	$webmaster_settings	=	catchwebtools_get_options( 'catchwebtools_webmaster' );
	if( isset( $webmaster_settings['status'] ) && $webmaster_settings['status'] ) {
		if ( ! is_feed() || ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/feed(blitz|burner|validator)/i', $_SERVER['HTTP_USER_AGENT'] ) ) )
			return;

		//* Don't redirect if viewing archive, search, or post comments feed
		if ( is_archive() || is_search() || is_singular() )
			return;

		$feed_uri 			= ( isset( $webmaster_settings['feed_uri'] ) && '' != $webmaster_settings['feed_uri'] ) ? $webmaster_settings['feed_uri'] : false ;

		$comments_feed_uri 	= ( isset( $webmaster_settings['comments_feed_uri'] ) && '' != $webmaster_settings['comments_feed_uri'] ) ? $webmaster_settings['comments_feed_uri'] : false;


		if ( $feed_uri && ! is_comment_feed() ) {
			wp_redirect( $feed_uri, 302 );
			exit;
		}

		if ( $comments_feed_uri && is_comment_feed() ) {
			wp_redirect( $comments_feed_uri, 302 );
			exit;
		}
	}

}
add_action( 'template_redirect', 'catchwebtools_feed_redirect' );