<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Please do not load this file directly.' );
}

/*-------------------------------------------------------------------------------*/
/*  Enqueue when option on each post/page is ON ( Yes )
/*-------------------------------------------------------------------------------*/
function glg_post_page_hook( $content )
{

    global $post;

    if ( trim( get_option( 'glg_gallery_active' ) ) != 'off' ) {

        if ( trim( get_post_meta( $post->ID, 'glg_meta_options', true ) ) != 'no' ) {

            add_shortcode( 'gallery', 'glg_make_sure_link_to_media_file' );

            add_action( 'print_footer_scripts', 'glg_frontend_wp_footer' );

            wp_enqueue_script( 'glg-photobox' );

            wp_enqueue_style( 'glg-photobox-style' );

            if ( has_filter( 'glg_frontend_enqueue_filter' ) ) {

                apply_filters( 'glg_frontend_enqueue_filter', '' );

            }

        }

    }

    return $content;

}

/*-------------------------------------------------------------------------------*/
/*  Make sure to set the "Link to" to Media File
/*-------------------------------------------------------------------------------*/
function glg_make_sure_link_to_media_file( $atts )
{

    if ( isset( $atts ) ) {
        $atts['link'] = 'file';
    }

    return gallery_shortcode( $atts );

}

/*-------------------------------------------------------------------------------*/
/*  Enqueue Admin Script
/*-------------------------------------------------------------------------------*/
function glg_admin_enqueue_scripts()
{

    $is_rtl = ( is_rtl() ? '-rtl' : '' );

    wp_register_style( 'glg-settings', plugins_url( 'css/glg-settings'.$is_rtl.'.css', dirname( __FILE__ ) ), false, GLG_VERSION );
    wp_register_script( 'glg-settings-tab', plugins_url( 'js/settings/option-tab.js', dirname( __FILE__ ) ), false, GLG_VERSION );

    wp_register_style( 'glg-checkbox-style', plugins_url( 'css/iosCheckbox.css', dirname( __FILE__ ) ), false, GLG_VERSION );
    wp_register_script( 'glg-checkbox', plugins_url( 'js/settings/iosCheckbox.js', dirname( __FILE__ ) ), false, GLG_VERSION );

}

/*-------------------------------------------------------------------------------*/
/*  Enqueue Frontend Script
/*-------------------------------------------------------------------------------*/
function glg_frontend_enqueue_scripts()
{

    wp_register_script( 'glg-photobox', GLG_URL.'/js/jquery/photobox/jquery.photobox.min.js', array(), GLG_VERSION, false );
    wp_register_style( 'glg-photobox-style', GLG_URL.'/css/photobox/photobox.min.css', array(), GLG_VERSION, false );

}

// Add PB Script in Footer
function glg_frontend_wp_footer()
{

    $isauto  = ( get_option( 'glg_gallery_autoplay' ) ? get_option( 'glg_gallery_autoplay' ) : 'true' );
    $time    = ( get_option( 'ecf_slide_every' ) ? get_option( 'ecf_slide_every' ) : '3' );
    $isthumb = ( get_option( 'glg_gallery_thumbnails' ) ? get_option( 'glg_gallery_thumbnails' ) : 'true' );

    ob_start();?>

<!--[if lt IE 9]><link rel="stylesheet" href="<?php echo GLG_URL; ?> '/css/photobox/photobox.ie.css'.'"><![endif]-->

<style type="text/css">
#pbOverlay { background:<?php
if ( get_option( 'glg_gallery_overlay_color' ) ) {echo 'rgba('.glg_hex2rgb( get_option( 'glg_gallery_overlay_color' ) ).',.90)';} else {echo 'rgba(0,0,0,.90)';}
    ?>  none repeat scroll 0% 0% !important; }
	<?php
if ( get_option( 'glg_gallery_show_captions' ) == 'true' ) {echo '.gallery-caption, .blocks-gallery-item figcaption {}';} else {echo '.gallery-caption, .blocks-gallery-item figcaption { display: none !important; }';}
    ?>
	.pbWrapper > img{display: inline;}
	#pbThumbsToggler {display: none !important;}
</style>

<script type="text/javascript">// <![CDATA[
jQuery(document).ready(function($) {
	/* START --- <?php echo GLG_ITEM_NAME; ?> --- */
	<?php
if ( get_option( 'glg_gallery_fancy_caption' ) == 'true' ) {?>

	/* Replace default title to more fancy :) */
	$('.gallery img').each(function(i) {

		$alt = $(this).attr('alt');

		$(this).attr('alt', $alt.replace(/-|_/g, ' '));

		$altnew = $(this).attr('alt').replace(/\b[a-z]/g, function(letter) {

			    return letter.toUpperCase();

			});

		$(this).attr('alt', $altnew );

	});

		<?php }

    if ( trim( get_option( 'glg_gallery_active' ) ) != 'off' ) {
        global $post;

        if ( trim( get_post_meta( $post->ID, 'glg_meta_options', true ) ) != 'no' ) {
            ?>
	/* Gutenberg Adaptive */
	$('.blocks-gallery-item, .wp-block-image').each(function(i) {

		var $blck = $(this).find('img'),
		$isSrc = $blck.attr('src');

		if (! $blck.closest('a').length) {
			$blck.wrap('<a class="glg-a-custom-wrap" href="'+$isSrc+'"></a>');
		}
		else {
			$blck.closest('a').addClass('glg-a-custom-wrap');
		}

	});

	<?php
}

    }

    ?>

	/* Initialize!
	.glg-a-custom-wrap (Block Gallery)
	.carousel-item:not(".bx-clone") > a:not(".icp_custom_link") (Image Carousel)
	.gallery-item > dt > a (Native Gallery) */
	$('.gallery, .ghozylab-gallery, .wp-block-gallery')
		.photobox('.carousel-item > a:not(".icp_custom_link"),a.glg-a-custom-wrap, .gallery-item > dt > a, .gallery-item > div > a',{
			autoplay: <?php echo $isauto; ?>,
			time: <?php echo $time.'000'; ?>,
			thumbs: <?php echo $isthumb; ?>,
			counter: ''
		}, callback);
		function callback(){
		};

});

/* END --- <?php echo GLG_ITEM_NAME; ?> --- */

// ]]></script>


    <?php

    if ( has_filter( 'glg_footer_frontend_filter' ) ) {

        apply_filters( 'glg_footer_frontend_filter', '' );

    }

    $glg_pb = ob_get_clean();
    echo $glg_pb;

}

/*-------------------------------------------------------------------------------*/
/*  HEX to RGB
/*-------------------------------------------------------------------------------*/
function glg_hex2rgb( $hex )
{

    $hex = str_replace( '#', '', $hex );

    if ( strlen( $hex ) == 3 ) {

        $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );

    } else {

        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );

    }

    $rgb = array( $r, $g, $b );

    return implode( ',', $rgb ); // returns an array with the rgb values

}

function glg_trial_notify()
{

    if ( get_option( 'glg_trial_notify' ) == '' ) {

        $current_user = wp_get_current_user();
        $cnt          = '<span class="cd_pp_img"><a class="glg_go" href="https://trial.ghozylab.com/" target="_blank"></a><img src="'.plugins_url( 'images/banners/trial_banner.png', dirname( __FILE__ ) ).'"></span>';

        wp_enqueue_style( 'glg-popup-css', plugins_url( 'css/popup.css', dirname( __FILE__ ) ) );
        wp_enqueue_script( 'glg-popup', plugins_url( 'js/settings/popup.js', dirname( __FILE__ ) ) );
        wp_localize_script( 'glg-popup', 'glg_popup', array( 'content' => $cnt ) );

    }

}

function glg_hide_notify()
{

    update_option( 'glg_trial_notify', 'done' );
    wp_die();

}

add_action( 'wp_ajax_glg_hide_notify', 'glg_hide_notify' );