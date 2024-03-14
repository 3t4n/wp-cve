<?php
/**
 * Plugin Name: HT Mega - Absolute Addons for WPBakery Page Builder (formerly Visual Composer)
 * Description: A huge collection of addons for WPBakery Page Builder
 * Plugin URI: 	http://demo.wphash.com/htmegavc/
 * Author: 		HT Plugins
 * Author URI: 	https://hasthemes.com/plugins
 * Version: 	1.0.8
 * License:     GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: htmegavc
*/

// define path
define( 'HTMEGAVC_URI', plugins_url('', __FILE__) );
define( 'HTMEGAVC_ASSETS_URI', plugins_url('assets', __FILE__) );
define( 'HTMEGAVC_LIBS_URI', plugins_url('libs', __FILE__) );
define( 'HTMEGAVC_DIR', dirname( __FILE__ ) );

// notice
include_once( HTMEGAVC_DIR. '/inc/activation-notice.php');

// exit if vc is not active
if ( ! in_array('js_composer/js_composer.php', get_option('active_plugins') ) ) return;

// include admin files
include_once( HTMEGAVC_DIR. '/admin/admin-init.php');

// Register frontend assets
function htmegavc_frontend_assets() {
	// Styles
	wp_register_style( 'htmegavc-global-style', HTMEGAVC_ASSETS_URI . '/css/global.css');
	wp_enqueue_style( 'htmegavc-global-style' );

	wp_register_style( 'htbbootstrap', HTMEGAVC_LIBS_URI . '/bootstrap/htbbootstrap.css');
	wp_enqueue_style( 'htbbootstrap' );

	wp_register_script( 'popper', HTMEGAVC_LIBS_URI . '/bootstrap/popper.min.js', array('jquery'), '', '');
	wp_enqueue_script( 'popper' );

	wp_register_script( 'htbbootstrap', HTMEGAVC_LIBS_URI . '/bootstrap/htbbootstrap.js', array('jquery'), '', '');
	wp_enqueue_script( 'htbbootstrap' );
}
add_action('wp_enqueue_scripts','htmegavc_frontend_assets');

// include addon files
add_action('init', 'htmegavc_load_addons');
function htmegavc_load_addons(){
	include_once( HTMEGAVC_DIR. '/inc/helper-functions.php');

	// On off check
	$accordion = htmegavc_get_option( 'accordion', 'htmegavc_element_tabs', 'on' );
	$animatesectiontitle = htmegavc_get_option( 'animatesectiontitle', 'htmegavc_element_tabs', 'on' );
	$button = htmegavc_get_option( 'button', 'htmegavc_element_tabs', 'on' );
	$blockquote = htmegavc_get_option( 'blockquote', 'htmegavc_element_tabs', 'on' );
	$brandlogo = htmegavc_get_option( 'brandlogo', 'htmegavc_element_tabs', 'on' );
	$businesshours = htmegavc_get_option( 'businesshours', 'htmegavc_element_tabs', 'on' );
	$calltoaction = htmegavc_get_option( 'calltoaction', 'htmegavc_element_tabs', 'on' );
	$countdown = htmegavc_get_option( 'countdown', 'htmegavc_element_tabs', 'on' );
	$counter = htmegavc_get_option( 'counter', 'htmegavc_element_tabs', 'on' );


	$dropcaps = htmegavc_get_option( 'dropcaps', 'htmegavc_element_tabs', 'on' );
	$thumbgallery = htmegavc_get_option( 'thumbgallery', 'htmegavc_element_tabs', 'on' );
	$googlemap = htmegavc_get_option( 'googlemap', 'htmegavc_element_tabs', 'on' );
	$galleryjustify = htmegavc_get_option( 'galleryjustify', 'htmegavc_element_tabs', 'on' );
	$imagecomparison = htmegavc_get_option( 'imagecomparison', 'htmegavc_element_tabs', 'on' );
	$imagemagnifier = htmegavc_get_option( 'imagemagnifier', 'htmegavc_element_tabs', 'on' );
	$imagegrid = htmegavc_get_option( 'imagegrid', 'htmegavc_element_tabs', 'on' );
	$imagemasonry = htmegavc_get_option( 'imagemasonry', 'htmegavc_element_tabs', 'on' );
	$lightbox = htmegavc_get_option( 'lightbox', 'htmegavc_element_tabs', 'on' );
	
	$popover = htmegavc_get_option( 'popover', 'htmegavc_element_tabs', 'on' );
	$pricingtable = htmegavc_get_option( 'pricingtable', 'htmegavc_element_tabs', 'on' );
	$progressbar = htmegavc_get_option( 'progressbar', 'htmegavc_element_tabs', 'on' );
	$sectiontitle = htmegavc_get_option( 'sectiontitle', 'htmegavc_element_tabs', 'on' );
	$testimonial = htmegavc_get_option( 'testimonial', 'htmegavc_element_tabs', 'on' );
	$teammember = htmegavc_get_option( 'teammember', 'htmegavc_element_tabs', 'on' );
	$tooltip = htmegavc_get_option( 'tooltip', 'htmegavc_element_tabs', 'on' );
	$verticletimeline = htmegavc_get_option( 'verticletimeline', 'htmegavc_element_tabs', 'on' );
	$videoplayer = htmegavc_get_option( 'videoplayer', 'htmegavc_element_tabs', 'on' );

	// 3rd party
	$mailchimpwp = htmegavc_get_option( 'mailchimpwp', 'htmegavc_thirdparty_element_tabs', 'on' );
	$contactform = htmegavc_get_option( 'contactform', 'htmegavc_thirdparty_element_tabs', 'on' );

	if(file_exists( HTMEGAVC_DIR. '/addons/countdown/htmegavc-countdown.php' ) && $accordion === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/accordion/htmegavc-accordion.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/animated-heading/htmegavc-animated-heading.php' ) && $animatesectiontitle === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/animated-heading/htmegavc-animated-heading.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/button/htmegavc-button.php' ) && $button === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/button/htmegavc-button.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/blockquote/htmegavc-blockquote.php' ) && $blockquote === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/blockquote/htmegavc-blockquote.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/brands/htmegavc-brands.php' ) && $brandlogo === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/brands/htmegavc-brands.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/business-hours/htmegavc-business-hours.php' ) && $businesshours === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/business-hours/htmegavc-business-hours.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/call-to-action/htmegavc-call-to-action.php' ) && $calltoaction === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/call-to-action/htmegavc-call-to-action.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/countdown/htmegavc-countdown.php' ) && $countdown === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/countdown/htmegavc-countdown.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/counter/htmegavc-counter.php' ) && $counter === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/counter/htmegavc-counter.php');
	}



	if(file_exists(HTMEGAVC_DIR. '/addons/dropcaps/htmegavc-dropcaps.php' ) && $dropcaps === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/dropcaps/htmegavc-dropcaps.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/slider-thumb-gallery/htmegavc-slider-thumb-gallery.php' ) && $thumbgallery === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/slider-thumb-gallery/htmegavc-slider-thumb-gallery.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/google-map/htmegavc-google-map.php' ) && $googlemap === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/google-map/htmegavc-google-map.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/image-justify-gallery/htmegavc-image-justify-gallery.php' ) && $galleryjustify === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/image-justify-gallery/htmegavc-image-justify-gallery.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/image-comparison/htmegavc-image-comparison.php' ) && $imagecomparison === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/image-comparison/htmegavc-image-comparison.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/image-grid/htmegavc-image-grid.php' ) && $imagegrid === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/image-grid/htmegavc-image-grid.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/image-magnifier/htmegavc-image-magnifier.php' ) && $imagemagnifier === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/image-magnifier/htmegavc-image-magnifier.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/image-masonry/htmegavc-image-masonry.php' ) && $imagemasonry === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/image-masonry/htmegavc-image-masonry.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/lightbox/htmegavc-lightbox.php' ) && $lightbox === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/lightbox/htmegavc-lightbox.php');
	}



	if(file_exists(HTMEGAVC_DIR. '/addons/popover/htmegavc-popover.php' ) && $popover === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/popover/htmegavc-popover.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/pricing-table/htmegavc-pricing-table.php' ) && $pricingtable === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/pricing-table/htmegavc-pricing-table.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/progress-bar/hemegavc-progress-bar.php' ) && $progressbar === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/progress-bar/hemegavc-progress-bar.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/section-title/htmegavc-section-title.php' ) && $sectiontitle === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/section-title/htmegavc-section-title.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/testimonial/htmegavc-testimonial.php' ) && $testimonial === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/testimonial/htmegavc-testimonial.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/team/hemegavc-team.php' ) && $teammember === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/team/hemegavc-team.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/tooltip/htmegavc-tooltip.php' ) && $tooltip === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/tooltip/htmegavc-tooltip.php');
	}

	if(file_exists(HTMEGAVC_DIR. '/addons/vertical-timeline/htmegavc-vertical-timeline.php' ) && $verticletimeline === 'on' ){
		include_once( HTMEGAVC_DIR. '/addons/vertical-timeline/htmegavc-vertical-timeline.php');
	}

	if(file_exists(HTMEGAVC_DIR.  '/addons/video-player/htmegavc-video-player.php' ) && $videoplayer === 'on' ){
		include_once( HTMEGAVC_DIR.  '/addons/video-player/htmegavc-video-player.php');
	}


	if(file_exists(HTMEGAVC_DIR.  '/addons/mailchimp-for-wp/htmegavc-mailchimp-for-wp.php' ) && class_exists('MC4WP_MailChimp') && $mailchimpwp === 'on'){
		include_once( HTMEGAVC_DIR. '/addons/mailchimp-for-wp/htmegavc-mailchimp-for-wp.php');
	}
	if(file_exists(HTMEGAVC_DIR.  '/addons/contact-form-seven/htmegavc-contact-form-seven.php' ) &&  class_exists('WPCF7') && $contactform === 'on'){
		include_once( HTMEGAVC_DIR. '/addons/contact-form-seven/htmegavc-contact-form-seven.php');
	}
}


/*
Return: google fonts data array
*/
function htmegavc_build_google_font_data( $google_fonts_data ) {
    $google_fonts_obj = new Vc_Google_Fonts();
    $google_fonts_data = strlen( $google_fonts_data ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( '', $google_fonts_data ) : '';

    return $google_fonts_data;
}

function htmegavc_build_google_font_style( $google_fonts_data ) {
    if (!is_array($google_fonts_data)){
        return;
    }

    $styles = array();

    $google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
    $styles[] = 'font-family:' . $google_fonts_family[0];
    $google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );


    if(count($google_fonts_styles) > 1){
    	$styles[] = 'font-weight:' . $google_fonts_styles[1];
    	$styles[] = 'font-style:' . $google_fonts_styles[2];
    }

    if ( ! empty( $styles ) ) {
        $style = esc_attr( implode( ';', $styles ) );
    } else {
        $style = '';
    }

    return $style;
}

function htmegavc_combine_font_container($arr){
    $htmegavc_member_name_style = '';
    if($arr){
      foreach(explode('|', $arr) as $item){
        if($item == 'font_family:Use%20From%20Theme'){
          continue;
        }
        $htmegavc_member_name_style .= $item . ';';
      }
      $htmegavc_member_name_style = preg_replace(array('/_/', '/%23/', '/%20/'), array('-', '#', ' '), $htmegavc_member_name_style);
    }

    return $htmegavc_member_name_style;
}
