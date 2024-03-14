<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://pixeldima.com
 * @since      1.0.0
 *
 * @package    Dima_Take_Action
 * @subpackage Dima_Take_Action/public/partials
 */
?>

<?php
global $dima_ta_demo;
if ( sizeof( $dima_ta_demo ) == 0 ) {
	return;
}
#button
$btn_height       = $dima_ta_demo['dima-ta-banner-btn-height'];
$banner_font_size = $dima_ta_demo['dima-ta-banner-font-size'];
if ( isset( $dima_ta_demo['dima-ta-btn-bg-color']['rgba'] ) ) {
	$btn_bg_color = $dima_ta_demo['dima-ta-btn-bg-color']['rgba'];
} else {
	$btn_bg_color = $dima_ta_demo['dima-ta-btn-bg-color']['color'];
}
if ( isset( $dima_ta_demo['dima-ta-btn-text-color']['rgba'] ) ) {
	$btn_txt_color = $dima_ta_demo['dima-ta-btn-text-color']['rgba'];
} else {
	$btn_txt_color = $dima_ta_demo['dima-ta-btn-text-color']['color'];
}
#banner
$banner_enabled     = $dima_ta_demo['dima-ta-banner-enabled'];
$float_btn_enabled  = $dima_ta_demo['dima-ta-float-button-enabled'];
$banner_height      = $dima_ta_demo['dima-ta-banner-height'];
$banner_width       = $dima_ta_demo['dima-ta-banner-width'];
$banner_width_unite = $dima_ta_demo['dima-ta-banner-width-unite'];
if ( $banner_width_unite != 'px' ) {
	$banner_width_unite = '%';
	$banner_width       = $dima_ta_demo['dima-ta-banner-width-per'];
}
#color
$color_type = $dima_ta_demo['dima-ta-bg-type'];
$color_from = $dima_ta_demo['dima-ta-banner-gradient-color']['from'];
$color_to   = $dima_ta_demo['dima-ta-banner-gradient-color']['to'];
if ( isset( $dima_ta_demo['dima-ta-bg-color']['rgba'] ) ) {
	$color_solid = $dima_ta_demo['dima-ta-bg-color']['rgba'];
} else {
	$color_solid = $dima_ta_demo['dima-ta-bg-color']['color'];
}
if ( isset( $dima_ta_demo['dima-ta-banner-img']['url'] ) ) {
	$banner_img = $dima_ta_demo['dima-ta-banner-img']['url'];
} else {
	$banner_img = '';
}

if ( isset( $dima_ta_demo['dima-ta-text-color']['rgba'] ) ) {
	$color_text = $dima_ta_demo['dima-ta-text-color']['rgba'];
} else {
	$color_text = $dima_ta_demo['dima-ta-text-color']['color'];
}
?>

<?php if ( $banner_enabled ) { ?>
    <style media="screen">

        .framed .dima-framed-line.line-top {
            position: absolute;
        }

        .navbar_is_dark .dima-navbar-wrap.desk-nav .dima-navbar-transparent.fix_nav {
            border-top-width: 1px;
        }

        #take-action-banner {
            display: none;
        }

        .headerstrip {
            height: <?php echo  esc_attr($banner_height) ?>px;
            position: relative;
            z-index: 9999;
        }

        <?php  if($color_type=='gradient'){ ?>
        .headerstrip-content-background {
            opacity: 1;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: <?php echo $color_from; ?>; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(left, <?php echo esc_attr($color_from); ?>, <?php echo esc_attr($color_to); ?>); /* For Safari 5.1 to 6.0 */
            background: linear-gradient(to right, <?php echo esc_attr($color_from); ?>, <?php echo esc_attr($color_to); ?>); /* Standard syntax */
            background-repeat: repeat-x;
        }

        <?php  } else if( $banner_img != '' ){ ?>
        .headerstrip-content-background {
            opacity: 1;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("<?php echo $banner_img; ?>");
            background-repeat: repeat-x;
        }

        <?php } else { ?>
        .headerstrip-content-background {
            opacity: 1;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: <?php echo $color_solid; ?>; /* For browsers that do not support gradients */
            background-repeat: repeat-x;
        }

        <?php } ?>

        .take-action-on-buttom .headerstrip {
            position: fixed;
            bottom: 0;
            width: 100%;
            left: 0;
            right: 0;
        }

        .headerstrip .headerstrip-canvas {
            height: <?php echo esc_attr( $banner_height ) ?>px;
            margin: auto auto;
        }

        .headerstrip .headerstrip-content {
            position: relative;
            background-size: contain;
            background-repeat: no-repeat;
            background-size: 1000px <?php echo esc_attr( $banner_height ) ?>px;
            width: 100%;
            height: <?php echo esc_attr( $banner_height ) ?>px;
            max-width: <?php echo esc_attr( $banner_width).esc_attr( $banner_width_unite) ?>;
            margin: 0 auto;
        }

        .headerstrip .headerstrip-text {
            position: relative;
            color: <?php echo esc_attr($color_text) ?>;
            text-decoration: none;
            line-height: <?php echo esc_attr( $banner_height ) ?>px;
            letter-spacing: 0.8px;
            padding-left: 10px;
            font-size: <?php echo esc_attr( $banner_font_size ) ?>px;
        }

        .headerstrip .headerstrip-text a {
            color: <?php echo esc_attr($color_text) ?>;
            border-bottom: 1px dotted rgba(255, 255, 255, 0.2);
        }

        .headerstrip .headerstrip-text a:hover {
            color: <?php echo esc_attr($color_text) ?>;
            border-bottom: 1px dotted rgba(255, 255, 255, 0.5);
        }

        .headerstrip .headerstrip-text strong {
            font-weight: 600;
        }

        .headerstrip .headerstrip-cta {
            position: absolute;
            top: <?php echo esc_attr( ($banner_height - $btn_height ) / 2 ) ?>px;
            right: 45px;
            background-color: <?php echo esc_attr($btn_bg_color); ?>;
            padding: 0 5px;
            color: <?php echo esc_attr($btn_txt_color); ?>;
            font-size: 14px;
            line-height: <?php echo esc_attr($btn_height); ?>px;
            letter-spacing: 0.8px;
            border-radius: 3px;
            box-shadow: 0px 5px 15px 0px rgba(0, 0, 0, 0.4);
            text-decoration: none;
            display: block;
            text-align: center;
            margin: 0 auto;
            min-width: 160px;
        }

        .take-action-no-close .headerstrip .headerstrip-cta {
            right: 0;
        }

        .headerstrip-cta-mobile {
            color: #fff;
            text-decoration: underline;
            padding-left: 5px;
        }

        .is-hidden-desktop .headerstrip-content {
            text-align: center;
        }

        .is-hidden-desktop .headerstrip-text {
            position: relative;
            font-size: 14px;
        }

        .headerstrip-cta-mobile:hover {
            color: #fff;
        }

        .headerstrip .banner__dismiss {
            position: absolute;
            opacity: 0.4;
            color: white;
            top: <?php echo esc_attr( ( $banner_height - 14 ) / 2 ) ?>px;
            right: 10px;
            text-decoration: none;
        }

        .dismiss_icon {
            width: 20px;
            height: 20px;
            fill: white;
            display: inline-block;
        }

        .headerstrip .banner__dismiss {
            color: #fff;
        }

        @media (max-width: 1024px) {
            .is-hidden-tablet-and-below {
                display: none !important;
            }
        }

        @media (min-width: 1025px) {
            .is-hidden-desktop {
                display: none !important
            }
        }
    </style>
<?php } ?>

<?php
if ( $float_btn_enabled ) {

	if ( isset( $dima_ta_demo['dima-ta-float-button-logo-bg-color']['rgba'] ) ) {
		$logo_bg_color = $dima_ta_demo['dima-ta-float-button-logo-bg-color']['rgba'];
	} else {
		$logo_bg_color = $dima_ta_demo['dima-ta-float-button-logo-bg-color']['color'];
	}
	if ( isset( $dima_ta_demo['dima-ta-float-button-color']['rgba'] ) ) {
		$color_bg = $dima_ta_demo['dima-ta-float-button-color']['rgba'];
	} else {
		$color_bg = $dima_ta_demo['dima-ta-float-button-color']['color'];
	}
	if ( isset( $dima_ta_demo['dima-ta-float-button-txt-color']['rgba'] ) ) {
		$float_btn_color_txt = $dima_ta_demo['dima-ta-float-button-txt-color']['rgba'];
	} else {
		$float_btn_color_txt = $dima_ta_demo['dima-ta-float-button-txt-color']['color'];
	}

	$float_btn_h = '50';

	?>
    <style>

        body #dima-btn-fixed-button {
            right: 35px;
            bottom: 55px;
            height: <?php echo $float_btn_h; ?>px;
            display: table;
            vertical-align: middle;
        }

        .dima-float-button-envato {
            background: <?php echo $logo_bg_color; ?>;
            padding: 0 15px;
            display: table-cell;
            line-height: <?php echo $float_btn_h; ?>px;
            border-bottom-left-radius: 5px;
            border-top-left-radius: 5px;
        }

        .dima-float-button-envato img {
            width: <?php echo $float_btn_h - 10; ?>px;
        }

        .dima-float-buy-text {
            display: table-cell;
            padding: 0 15px;
            line-height: <?php echo $float_btn_h; ?>px;
        }

        #dima-btn-fixed-button .dima-buy-button-top {
            font-family: "Poppins", Helvetica, Georgia, Times, serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 15px;
            overflow: hidden;
            color: <?php echo $float_btn_color_txt; ?>;
        }

        #dima-btn-fixed-button {
            position: fixed;
            z-index: 9998;
            -moz-box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            -webkit-box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            -moz-transform: translateY(0);
            -ms-transform: translateY(0);
            -webkit-transform: translateY(0);
            transform: translateY(0);
            -moz-transition: background .3s ease, bottom 1.3s ease, -moz-transform .3s ease, box-shadow .3s ease;
            -o-transition: background .3s ease, bottom 1.3s ease, -o-transform .3s ease, box-shadow .3s ease;
            -webkit-transition: background .3s ease, bottom 1.3s ease, -webkit-transform .3s ease, box-shadow .3s ease;
            transition: background .3s ease, bottom 1.3s ease, transform .3s ease, box-shadow .3s ease;
            border-radius: 5px;
        }

        #dima-btn-fixed-button:hover {
            -moz-box-shadow: 0 3px 10px 0 rgba(102, 102, 255, 0.3);
            -webkit-box-shadow: 0 3px 10px 0 rgba(102, 102, 255, 0.3);
            box-shadow: 0 3px 10px 0 rgba(102, 102, 255, 0.3);
            -moz-transform: translateY(2px);
            -ms-transform: translateY(2px);
            -webkit-transform: translateY(2px);
            transform: translateY(2px);
        }

        #dima-btn-fixed-button {
            background: <?php echo $color_bg; ?>;
        }

        @media only screen and (max-width: 840px) {
            body #dima-btn-fixed-button {
                display: none;
            }
        }

        <?php if(is_rtl()){ ?>
        .dima-float-button-envato {
            float: left;
        }

        .dima-float-buy-text {
            float: right;
        }

        #dima-btn-fixed-button .dima-buy-button-top {
            font-family: "Droid Arabic Kufi", "Helvetica Neue", Helvetica, sans-serif;
        }

        <?php } ?>
    </style>
<?php }