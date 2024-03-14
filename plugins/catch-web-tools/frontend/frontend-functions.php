<?php
/**
 * @package Frontend
 */


/**
 * Enqueue genericons
 */
function catchwebtools_enqueue_genericons() {
	$social_settings = catchwebtools_get_options( 'catchwebtools_social' );

	if( $social_settings['status'] ) {
		wp_enqueue_style( 'cwt-genericons', CATCHWEBTOOLS_URL . 'css/genericons.css', false, '3.4.1' );

		$social_brand_color = $social_settings['social_icon_brand_color'];

		if( 'hover' == $social_brand_color || 'hover-static' == $social_brand_color ) {
			wp_enqueue_style( 'catch-web-tools-social-icons', CATCHWEBTOOLS_URL . 'css/social-icons.css', false, '24022016' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'catchwebtools_enqueue_genericons' );

require_once CATCHWEBTOOLS_PATH . 'frontend/inc/webmasters-tools.php';

require_once CATCHWEBTOOLS_PATH . 'frontend/inc/opengraph-tools.php';

require_once CATCHWEBTOOLS_PATH . 'frontend/inc/seo.php';

require_once CATCHWEBTOOLS_PATH . 'frontend/inc/custom-css.php';

/**
 * Function to get header information to output in wp_head
 * @uses catchwebtools_webmaster_header_display, catchwebtools_opengraph_display, catchwebtools_seo_display, catchwebtools_custom_css_display
 */
function catchwebtools_get_header_information(){
	$webmaster	= catchwebtools_webmaster_header_display();
	$opengraph	= catchwebtools_opengraph_display();
	$seo		= catchwebtools_seo_display();
	$custom_css	= catchwebtools_custom_css_display();

	if ( '' == $webmaster && '' == $opengraph && '' == $seo && '' == $custom_css ) {
		//Bail early if all modles are empty
		return;
	}

	echo '<!-- This site is optimized with the Catch Web Tools v'. CATCHWEBTOOLS_VERSION .' - https://catchplugins.com/plugins/catch-web-tools/ -->'. PHP_EOL ;
	echo '<!-- CWT Webmaster Tools -->'. PHP_EOL . $webmaster . PHP_EOL ;
	echo '<!-- CWT Opengraph Tools -->'. PHP_EOL . $opengraph. PHP_EOL ;
	echo '<!-- CWT SEO -->'. PHP_EOL . $seo. PHP_EOL ;
	echo '<!-- CWT Custom CSS -->'. PHP_EOL . $custom_css. PHP_EOL ;
	echo '<!-- / Catch Web Tools plugin. -->'. PHP_EOL ;
}
add_action( 'wp_head', 'catchwebtools_get_header_information', 99 );


/**
 * Function to get footer information to output in wp_footer
 * @uses catchwebtools_webmaster_footer_display
 */
function catchwebtools_get_footer_information(){
	$webmaster	=	catchwebtools_webmaster_footer_display();

	echo '<!-- This site is optimized with the Catch Web Tools v'. CATCHWEBTOOLS_VERSION .' - https://catchplugins.com/plugins/catch-web-tools/ -->'. PHP_EOL ;
	echo $webmaster. PHP_EOL ;
	echo '<!-- / Catch Web Tools plugin. -->'. PHP_EOL ;
}
add_action( 'wp_footer', 'catchwebtools_get_footer_information', 99 );
