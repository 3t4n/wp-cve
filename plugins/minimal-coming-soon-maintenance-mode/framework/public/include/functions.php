<?php

/**
 * Required functions for the plugin.
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
	die;
}

function csmm_render_template( $options ) {

    if (ob_get_length() > 0 ) {
      ob_end_clean();
    }

	/**
	 * Using the nocache_headers() to ensure that different nocache headers are sent to different browsers.
	 * We don't want any browser to cache the maintainance page.
	 * Also, output buffering is turned on.
	 */
	nocache_headers();
	ob_start();


	// Checking for options required for the plugin
	if ( empty( $options['title'] ) ) 				:	$options['title'] 				= __( 'Maintainance Mode', 'minimal-coming-soon-maintenance-mode' );					endif;
	if ( empty( $options['input_text'] ) )			:	$options['input_text'] 			= __( 'Enter your email address..', 'minimal-coming-soon-maintenance-mode' );	 	endif;
	if ( empty( $options['button_text'] ) )			:	$options['button_text'] 		= __( 'Subscribe', 'minimal-coming-soon-maintenance-mode' ); 						endif;

	// Response message
	if ( empty( $options['message_noemail'] ) )		:	$options['message_noemail'] 	=__( 'Oops! Something went wrong.', 'minimal-coming-soon-maintenance-mode' ); 		endif;
	if ( empty( $options['message_subscribed'] ) )	:	$options['message_subscribed'] 	=__( 'You are already subscribed!', 'minimal-coming-soon-maintenance-mode' ); 		endif;
	if ( empty( $options['message_wrong'] ) )		:	$options['message_wrong'] 		=__( 'Oops! Something went wrong.', 'minimal-coming-soon-maintenance-mode' ); 		endif;
	if ( empty( $options['message_done'] ) )		:	$options['message_done'] 		=__( 'Thank you! We\'ll be in touch!', 'minimal-coming-soon-maintenance-mode' ); 	endif;


	// Template file
	if ( '1' == $options['disable_settings'] ) {
		require_once CSMM_PATH . 'framework/public/views/blank.php';
	} else {
		require_once CSMM_PATH . 'framework/public/views/html.php';
	}

	ob_flush();
	exit();
}


function csmm_linkback() {
  $options = csmm_get_options();
  $out = '';

  if (empty($options['love'])) {
    return $out;
  }

  $out .= '<div id="linkback"><p>';
  $tmp = md5(get_site_url());
  if ($tmp[0] < '4') {
    $out .= 'Create stunning <a href="' . csmm_generate_web_link('show-love-create-stunning') . '" target="_blank" rel="nofollow">coming soon pages for WordPress</a>. Completely free.';
  } elseif ($tmp[0] < '8') {
    $out .= 'Create <a href="' . csmm_generate_web_link('show-love-create-free') . '" target="_blank" rel="nofollow">free maintenance mode pages for WordPress</a> like this one in under a minute.';
  } elseif ($tmp[0] < 'c') {
    $out .= 'Join more than 80,000 happy people using the <a href="' . csmm_generate_web_link('show-love-join-80k') . '" target="_blank" rel="nofollow">free Coming Soon &amp; Maintenance Mode plugin for WordPress</a>.';
  } else {
    $out .= 'Create free <a href="' . csmm_generate_web_link('show-love-create-free') . '" target="_blank" rel="nofollow">maintenance mode pages for WordPress</a>.';
  }
  $out .='</p></div>';

  return $out;
} // csmm_linkback


// To check the referrer
function csmm_check_referrer() {

	// List of crawlers to check for
	$crawlers = array(
		'Abacho'          	=> 	'AbachoBOT',
		'Accoona'         	=> 	'Acoon',
		'AcoiRobot'       	=> 	'AcoiRobot',
		'Adidxbot'        	=> 	'adidxbot',
		'AltaVista robot' 	=> 	'Altavista',
		'Altavista robot' 	=> 	'Scooter',
		'ASPSeek'         	=> 	'ASPSeek',
		'Atomz'           	=> 	'Atomz',
		'Bing'            	=> 	'bingbot',
		'BingPreview'     	=> 	'BingPreview',
		'CrocCrawler'     	=> 	'CrocCrawler',
		'Dumbot' 			    => 	'Dumbot',
		'eStyle Bot'     	=> 	'eStyle',
		'FAST-WebCrawler'	=> 	'FAST-WebCrawler',
		'GeonaBot'       	=> 	'GeonaBot',
		'Gigabot'        	=> 	'Gigabot',
		'Google'         	=> 	'Googlebot',
		'ID-Search Bot'  	=> 	'IDBot',
		'Lycos spider'   	=> 	'Lycos',
		'MSN'            	=> 	'msnbot',
		'MSRBOT'         	=> 	'MSRBOT',
		'Rambler'        	=> 	'Rambler',
		'Scrubby robot'  	=> 	'Scrubby',
		'Yahoo'          	=> 	'Yahoo'
	);


	// Checking for the crawler over here
	if ( csmm_string_to_array( $_SERVER['HTTP_USER_AGENT'], $crawlers ) ) {
		return true;
	}


	return false;

}

function csmm_render_template_x(){
    $signals_csmm_options = csmm_get_options();
    csmm_render_template( $signals_csmm_options );
}

// Function to match the user agent with the crawlers array
function csmm_string_to_array( $str, $array ) {

	$regexp = '~(' . implode( '|', array_values( $array ) ) . ')~i';
	return ( bool ) preg_match( $regexp, $str );

}
