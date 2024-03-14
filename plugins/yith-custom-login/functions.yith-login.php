<?php
/**
 * Functions
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Custom Login
 * @version 1.0.2
 */

if ( !defined( 'YITH_LOGIN' ) ) { exit; } // Exit if accessed directly

if( !function_exists( 'yith_login_is_enabled' ) ) {
    /**
     * Return if the plugin is enabled
     *
     * @return bool
     * @since 0.9.0
     */
    function yith_login_is_enabled() {
        return get_option('yith_login_enable') == '1';
    }
}

if( ! function_exists( 'yith_show_gfont_gdpr_disclaimer' ) ){
	/**
	 * Get the Google Fonts GDPR disclaimer  box in plugin panel options
	 *
	 * @since 1.6.0
	 * @author YITH <plugins@yithemes.com>
	 * @return void
	 */
	function yith_show_gfont_gdpr_disclaimer( $show = true ){
		$gfont_disclaimer_title = esc_html__( 'Google Fonts and GDPR', 'yith-maintenance-mode' );
		$gfont_faq_url         = 'https://developers.google.com/fonts/faq#what_does_using_the_google_fonts_api_mean_for_the_privacy_of_my_users';
		$gfont_disclaimer_text  = wp_kses_post( __( 'As you can see in <a target="_blank" href="' . $gfont_faq_url . '" rel="noopener">Google FAQ</a>: <blockquote class="yith-gfont-quote-disclamer">The Google Fonts API is designed to limit the collection, storage, and use of end-user data to what is needed to serve fonts efficiently. […] Google Fonts logs records of the CSS and the font file requests, and access to this data is kept secure. […] We use data from Google’s web crawler to detect which websites use Google fonts.</blockquote>In other words, when someone visits your website, Google will be able to access the IP address they used to access it. As a result of using Google Fonts, you implicitly accept their terms and conditions, and you must inform people visiting your site of this in accordance with the current GDPR law in Europe.', 'yith-maintenance-mode' ) );
		printf( '<div id="yith-gfont-disclamer" class="notice update-nag"><h4>%s</h4>%s</div>', $gfont_disclaimer_title, $gfont_disclaimer_text );
	}

	add_action( 'yith_panel_before_panel', 'yith_show_gfont_gdpr_disclaimer' );
}
