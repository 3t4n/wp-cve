<?php
/*
Class Name: VI_WOO_COUPON_BOX_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_COUPON_BOX_Admin_Design {
	protected $settings;

	public function __construct() {

		$this->settings = new VI_WOO_COUPON_BOX_DATA();
		add_action( 'customize_register', array( $this, 'design_option_customizer' ) );
		add_action( 'wp_print_styles', array( $this, 'customize_controls_print_styles' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
		add_action( 'customize_controls_print_scripts', array( $this, 'customize_controls_print_scripts' ), 30 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ), 30 );
		add_action( 'wp_footer', array(
			$this,
			'customize_controls_print_footer_scripts'
		) );
	}

	public function customize_controls_print_styles() {
		?>
        <style id="woo-coupon-box-custom-css" type="text/css"></style>
        <style id="woo-coupon-box-custom-input-border-radius" type="text/css"></style>
		<?php

	}

	public function customize_controls_enqueue_scripts() {
		wp_enqueue_style( 'woo-coupon-box-customizer', VI_WOO_COUPON_BOX_CSS . 'customizer.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-social-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_social_icons.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-close-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_button_close_icons.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-popup-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_giftbox.css', array(), VI_WOO_COUPON_BOX_VERSION );

	}

	public function wp_enqueue_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}
		for ( $i = 1; $i < 16; $i ++ ) {
			wp_enqueue_style( 'woo-coupon-box-popup-effect-wcb-md-effect-' . $i, VI_WOO_COUPON_BOX_CSS . 'popup-effect/wcb-md-effect-' . $i . '.css', array(), VI_WOO_COUPON_BOX_VERSION );

		}
		wp_enqueue_style( 'wcb-weather-style', VI_WOO_COUPON_BOX_CSS . 'weather.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-social-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_social_icons.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-close-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_button_close_icons.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-giftbox-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_giftbox.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-basic', VI_WOO_COUPON_BOX_CSS . 'basic.css', array(), VI_WOO_COUPON_BOX_VERSION );
		// style
		$css = '';

		/*header*/
		$css .= '.wcb-coupon-box .wcb-md-content .wcb-modal-header{';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_bg_header' ) . ';';
		$css .= 'color:' . $this->settings->get_params( 'wcb_color_header' ) . ';';
		$css .= 'font-size:' . $this->settings->get_params( 'wcb_title_size' ) . 'px;';
		$css .= 'line-height:' . $this->settings->get_params( 'wcb_title_size' ) . 'px;';
		$css .= 'padding-top:' . $this->settings->get_params( 'wcb_title_space' ) . 'px;';
		$css .= 'padding-bottom:' . $this->settings->get_params( 'wcb_title_space' ) . 'px;';
		$css .= '}';

		/*body*/
		$css .= '.wcb-coupon-box .wcb-md-content .wcb-modal-body{';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_body_bg' ) . ';';
		$css .= 'color:' . $this->settings->get_params( 'wcb_body_text_color' ) . ';';
		if ( $this->settings->get_params( 'wcb_body_bg_img' ) ) {
			$css .= 'background-image:url(' . $this->settings->get_params( 'wcb_body_bg_img' ) . ');';
		}
		$css .= 'background-repeat:' . $this->settings->get_params( 'wcb_body_bg_img_repeat' ) . ';';
		$css .= 'background-size:' . $this->settings->get_params( 'wcb_body_bg_img_size' ) . ';';
		$css .= 'background-position:' . $this->settings->get_params( 'wcb_body_bg_img_position' ) . ';';
		$css .= '}';
		$css .= '.wcb-coupon-box .wcb-md-content .wcb-modal-body .wcb-coupon-message{color:' . $this->settings->get_params( 'wcb_color_message' ) . ';font-size:' . $this->settings->get_params( 'wcb_message_size' ) . 'px;text-align:' . $this->settings->get_params( 'wcb_message_align' ) . '}';

		/*text follow us*/
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-md-content .wcb-text-title', 'color', 'wcb_color_follow_us', '', '' );
		/*email input*/
		$css .= '.wcb-coupon-box .wcb-newsletter input.wcb-email{border-radius:' . $this->settings->get_params( 'wcb_email_input_border_radius' ) . 'px;}';

		$css .= '.wcb-coupon-box .wcb-modal-body .wcb-coupon-box-newsletter .wcb-newsletter-form input{margin-right:' . $this->settings->get_params( 'wcb_email_button_space' ) . 'px;}';

		/*button subscribe*/
		$css .= '.wcb-coupon-box .wcb-newsletter span.wcb-button{';
		$css .= 'color:' . $this->settings->get_params( 'wcb_button_text_color' ) . ';';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_button_bg_color' ) . ';';
		$css .= 'border-radius:' . $this->settings->get_params( 'wcb_button_border_radius' ) . 'px;';
		$css .= '}';
		/*overlay*/
		$css .= $this->generate_css( '.wcb-md-overlay', 'background', 'alpha_color_overlay', '', '' );
		/*social*/
		$css .= '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-social-icon{';
		$css .= 'font-size:' . $this->settings->get_params( 'wcb_social_icons_size' ) . 'px;';
		$css .= 'line-height:' . $this->settings->get_params( 'wcb_social_icons_size' ) . 'px;';
		$css .= '}';
		/*social-color*/
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-facebook-follow .wcb-social-icon', 'color', 'wcb_social_icons_facebook_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-twitter-follow .wcb-social-icon', 'color', 'wcb_social_icons_twitter_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-pinterest-follow .wcb-social-icon', 'color', 'wcb_social_icons_pinterest_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-instagram-follow .wcb-social-icon', 'color', 'wcb_social_icons_instagram_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-dribbble-follow .wcb-social-icon', 'color', 'wcb_social_icons_dribbble_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-tumblr-follow .wcb-social-icon', 'color', 'wcb_social_icons_tumblr_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-google-follow .wcb-social-icon', 'color', 'wcb_social_icons_google_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-vkontakte-follow .wcb-social-icon', 'color', 'wcb_social_icons_vkontakte_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-linkedin-follow .wcb-social-icon', 'color', 'wcb_social_icons_linkedin_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-youtube-follow .wcb-social-icon', 'color', 'wcb_social_icons_youtube_color', '', '' );
		wp_enqueue_style( 'woo-coupon-box-layout-1', VI_WOO_COUPON_BOX_CSS . 'layout-1.css', array(), VI_WOO_COUPON_BOX_VERSION );

		$css .= '.wcb-coupon-box .wcb-content-wrap .wcb-md-content{border-radius:' . $this->settings->get_params( 'wcb_border_radius' ) . 'px;}';
		/*button close*/
		$css                         .= '.wcb-coupon-box .wcb-content-wrap .wcb-md-close{';
		$css                         .= 'font-size:' . $this->settings->get_params( 'wcb_button_close_size' ) . 'px;';
		$css                         .= 'width:' . $this->settings->get_params( 'wcb_button_close_width' ) . 'px;';
		$css                         .= 'line-height:' . $this->settings->get_params( 'wcb_button_close_width' ) . 'px;';
		$css                         .= 'color:' . $this->settings->get_params( 'wcb_button_close_color' ) . ';';
		$css                         .= 'background:' . $this->settings->get_params( 'wcb_button_close_bg_color' ) . ';';
		$css                         .= 'border-radius:' . $this->settings->get_params( 'wcb_button_close_border_radius' ) . 'px;';
		$wcb_button_close_position_x = $this->settings->get_params( 'wcb_button_close_position_x' ) * ( - 1 );
		$wcb_button_close_position_y = $this->settings->get_params( 'wcb_button_close_position_y' ) * ( - 1 );
		$css                         .= 'right:' . $wcb_button_close_position_x . 'px;';
		$css                         .= 'top:' . $wcb_button_close_position_y . 'px;';
		$css                         .= 'overflow:unset !important';
		$css                         .= '}';

		/*popup icon*/
		$css .= '.wcb-coupon-box-small-icon{';
		$css .= 'font-size:' . $this->settings->get_params( 'wcb_popup_icon_size' ) . 'px;';
		$css .= 'line-height:' . $this->settings->get_params( 'wcb_popup_icon_size' ) . 'px;';
		$css .= 'color:' . $this->settings->get_params( 'wcb_popup_icon_color' ) . ';';
		$css .= '}';
		$css .= '.wcb-coupon-box-small-icon-wrap{';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_popup_icon_bg_color' ) . ';';
		$css .= 'border-radius:' . $this->settings->get_params( 'wcb_popup_icon_border_radius' ) . 'px;';
		$css .= '}';

		/*button no, thanks*/
		$css .= '.wcb-coupon-box .wcb-md-close-never-reminder-field .wcb-md-close-never-reminder{';
		$css .= 'color:' . $this->settings->get_params( 'wcb_no_thank_button_color' ) . ';';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_no_thank_button_bg_color' ) . ';';
		$css .= 'border-radius:' . $this->settings->get_params( 'wcb_no_thank_button_border_radius' ) . 'px;';
		$css .= '}';
		$css .= $this->settings->get_params( 'wcb_custom_css' );
		wp_add_inline_style( 'woo-coupon-box-basic', $css );
	}

	public function generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false ) {
		$return = '';
		$mod    = $this->settings->get_params( $mod_name );
		if ( ! empty( $mod ) ) {
			$return = sprintf( '%s { %s:%s; }',
				$selector,
				$style,
				$prefix . $mod . $postfix
			);
			if ( $echo ) {
				echo esc_html( $return );
			}
		}

		return $return;
	}

	public function customize_controls_print_footer_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}

		echo do_shortcode( $this->get_template( 'customize' ) );

		$hide_popup_icon = '';

		if ( ! $this->settings->get_params( 'wcb_popup_icon_enable' ) ) {
			switch ( $this->settings->get_params( 'wcb_popup_icon_position' ) ) {
				case 'top-left':
				case 'bottom-left':
					$hide_popup_icon = ' wcb-coupon-box-small-icon-hide-left';
					break;
				case 'top-right':
				case 'bottom-right':
					$hide_popup_icon = ' wcb-coupon-box-small-icon-hide-right';
					break;
			}
		}
		if ( ! $this->settings->get_params( 'wcb_popup_icon_mobile' ) ) {
			$hide_popup_icon .= ' wcb-coupon-box-small-icon-hidden-mobile';
		}
		?>
        <div class="wcb-coupon-box-small-icon-wrap wcb-coupon-box-small-icon-position-<?php echo esc_attr( $this->settings->get_params( 'wcb_popup_icon_position' ) );
		echo esc_attr( $hide_popup_icon ) ?>">
            <div class="wcb-coupon-box-small-icon-container">
                <span class="wcb-coupon-box-small-icon-close wcb_button_close_icons-cancel"> </span>
                <span class="wcb-coupon-box-small-icon <?php echo esc_attr( $this->settings->get_params( 'wcb_popup_icon' ) ) ?>"> </span>
            </div>
        </div>
		<?php
	}

	protected function get_template( $name ) {
		$title   = $this->settings->get_params( 'wcb_title' );
		$message = $this->settings->get_params( 'wcb_message' );
		$socials = $this->get_socials();

		$parten  = array(
			'/\{title\}/',
			'/\{message\}/',
			'/\{socials\}/'
		);
		$replace = array(
			esc_html( $title ),
			esc_html( $message ),
			ent2ncr( $socials )
		);

		ob_start();
		require_once VI_WOO_COUPON_BOX_TEMPLATES . $name . '.php';
		$html = ob_get_clean();
		$html = preg_replace( $parten, $replace, $html );

		return ent2ncr( $html );
	}

	protected function get_socials() {
		$link_target = $this->settings->get_params( 'wcb_social_icons_target' );

		$facebook_url  = $this->settings->get_params( 'wcb_social_icons_facebook_url' );
		$twitter_url   = $this->settings->get_params( 'wcb_social_icons_twitter_url' );
		$pinterest_url = $this->settings->get_params( 'wcb_social_icons_pinterest_url' );
		$instagram_url = $this->settings->get_params( 'wcb_social_icons_instagram_url' );
		$dribbble_url  = $this->settings->get_params( 'wcb_social_icons_dribbble_url' );
		$tumblr_url    = $this->settings->get_params( 'wcb_social_icons_tumblr_url' );
		$google_url    = $this->settings->get_params( 'wcb_social_icons_google_url' );
		$vkontakte_url = $this->settings->get_params( 'wcb_social_icons_vkontakte_url' );
		$linkedin_url  = $this->settings->get_params( 'wcb_social_icons_linkedin_url' );
		$youtube_url   = $this->settings->get_params( 'wcb_social_icons_youtube_url' );

		$facebook_select  = $this->settings->get_params( 'wcb_social_icons_facebook_select' );
		$twitter_select   = $this->settings->get_params( 'wcb_social_icons_twitter_select' );
		$pinterest_select = $this->settings->get_params( 'wcb_social_icons_pinterest_select' );
		$instagram_select = $this->settings->get_params( 'wcb_social_icons_instagram_select' );
		$dribbble_select  = $this->settings->get_params( 'wcb_social_icons_dribbble_select' );
		$tumblr_select    = $this->settings->get_params( 'wcb_social_icons_tumblr_select' );
		$google_select    = $this->settings->get_params( 'wcb_social_icons_google_select' );
		$vkontakte_select = $this->settings->get_params( 'wcb_social_icons_vkontakte_select' );
		$linkedin_select  = $this->settings->get_params( 'wcb_social_icons_linkedin_select' );
		$youtube_select   = $this->settings->get_params( 'wcb_social_icons_youtube_select' );

		$html = '<ul class="wcb-list-socials wcb-list-unstyled" id="wcb-sharing-accounts">';


		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//www.facebook.com/<?php echo esc_attr( $facebook_url ) ?>"
                                             class="wcb-social-button wcb-facebook">
            <span class="wcb-social-icon <?php echo esc_attr( $facebook_select ) ?>"> </span></a>
		<?php $facebook_html = ob_get_clean();

		$html .= '<li style="' . ( ! $facebook_url ? 'display:none' : '' ) . '" class="wcb-facebook-follow">' . $facebook_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//twitter.com/<?php echo esc_attr( $twitter_url ) ?>"
                                             class="wcb-social-button wcb-twitter">
            <span class="wcb-social-icon <?php echo esc_attr( $twitter_select ) ?>"> </span>
        </a>
		<?php
		$twitter_html = ob_get_clean();
		$html         .= '<li style="' . ( ! $twitter_url ? 'display:none' : '' ) . '" class="wcb-twitter-follow">' . $twitter_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//www.pinterest.com/<?php echo esc_attr( $pinterest_url ) ?>"
                                             class="wcb-social-button wcb-pinterest"
                                             data-pin-do="buttonFollow">
            <span class="wcb-social-icon <?php echo esc_attr( $pinterest_select ) ?>"> </span>
        </a>
		<?php
		$pinterest_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $pinterest_url ? 'display:none' : '' ) . '" class="wcb-pinterest-follow">' . $pinterest_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//www.instagram.com/<?php echo esc_attr( $instagram_url ) ?>"
                                             class="wcb-social-button wcb-instagram">
            <span class="wcb-social-icon <?php echo esc_attr( $instagram_select ) ?>"> </span>
        </a>
		<?php
		$instagram_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $instagram_url ? 'display:none' : '' ) . '" class="wcb-instagram-follow">' . $instagram_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//dribbble.com/<?php echo esc_attr( $dribbble_url ) ?>"
                                             class="wcb-social-button wcb-dribbble">
            <span class="wcb-social-icon <?php echo esc_attr( $dribbble_select ) ?>"> </span>
        </a>
		<?php
		$dribbble_html = ob_get_clean();
		$html          .= '<li style="' . ( ! $dribbble_url ? 'display:none' : '' ) . '" class="wcb-dribbble-follow">' . $dribbble_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//www.tumblr.com/follow/<?php echo esc_attr( $tumblr_url ) ?>"
                                             class="wcb-social-button wcb-tumblr">
            <span class="wcb-social-icon <?php echo esc_attr( $tumblr_select ) ?>"> </span>
        </a>
		<?php
		$tumblr_html = ob_get_clean();
		$html        .= '<li style="' . ( ! $tumblr_url ? 'display:none' : '' ) . '" class="wcb-tumblr-follow">' . $tumblr_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//plus.google.com/+<?php echo esc_attr( $google_url ) ?>"
                                             class="wcb-social-button wcb-google-plus">
            <span class="wcb-social-icon <?php echo esc_attr( $google_select ) ?>"> </span>
        </a>
		<?php
		$google_html = ob_get_clean();
		$html        .= '<li style="' . ( ! $google_url ? 'display:none' : '' ) . '" class="wcb-google-follow">' . $google_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//vk.com/<?php echo esc_attr( $vkontakte_url ) ?>"
                                             class="wcb-social-button wcb-vk">
            <span class="wcb-social-icon <?php echo esc_attr( $vkontakte_select ) ?>"> </span>
        </a>
		<?php
		$vkontakte_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $vkontakte_url ? 'display:none' : '' ) . '" class="wcb-vkontakte-follow">' . $vkontakte_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="//www.linkedin.com/in/<?php echo esc_attr( $linkedin_url ) ?>"
                                             class="wcb-social-button wcb-linkedin">
            <span class="wcb-social-icon <?php echo esc_attr( $linkedin_select ) ?>"> </span>
        </a>
		<?php
		$linkedin_html = ob_get_clean();
		$html          .= '<li style="' . ( ! $linkedin_url ? 'display:none' : '' ) . '" class="wcb-linkedin-follow">' . $linkedin_html . '</li>';

		ob_start(); ?>
        <a <?php if ( $link_target == '_blank' )
			echo esc_attr( 'target=_blank' ) ?> href="<?php echo esc_url_raw( $youtube_url ) ?>"
                                             class="wcb-social-button wcb-youtube">
            <span class="wcb-social-icon <?php echo esc_attr( $youtube_select ) ?>"> </span>
        </a>
		<?php
		$youtube_html = ob_get_clean();
		$html         .= '<li style="' . ( ! $youtube_url ? 'display:none' : '' ) . '" class="wcb-youtube-follow">' . $youtube_html . '</li>';


		$html = apply_filters( 'wcb_after_socials_html', $html );
		$html .= '</ul>';

		return $html;
	}


	public function customize_preview_init() {
		wp_enqueue_script( 'woo-coupon-box-customize-preview-js', VI_WOO_COUPON_BOX_JS . 'customize-preview.js', array(
			'jquery',
			'customize-preview',
		), VI_WOO_COUPON_BOX_VERSION, true );
		wp_localize_script( 'woo-coupon-box-customize-preview-js', 'woo_coupon_box_design_params', array(
			'wcb_recaptcha'              => $this->settings->get_params( 'wcb_recaptcha' ),
			'wcb_recaptcha_site_key'     => $this->settings->get_params( 'wcb_recaptcha_site_key' ),
			'wcb_recaptcha_version'      => $this->settings->get_params( 'wcb_recaptcha_version' ),
			'wcb_recaptcha_secret_theme' => $this->settings->get_params( 'wcb_recaptcha_secret_theme' ),
			'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
		) );
		if ( $this->settings->get_params( 'wcb_recaptcha' ) ) {

			if ( $this->settings->get_params( 'wcb_recaptcha_version' ) == 2 ) {
				?>
                <script src='https://www.google.com/recaptcha/api.js?hl=<?php echo esc_attr( get_locale() ) ?>&render=explicit' async defer></script>
				<?php
			} elseif ( $this->settings->get_params( 'wcb_recaptcha_site_key' ) ) {
				?>
                <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo esc_attr( get_locale() ) ?>&render=<?php echo esc_attr( $this->settings->get_params( 'wcb_recaptcha_site_key' ) ); ?>"></script>
				<?php
			}
		}
	}

	public function customize_controls_print_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}
		?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                wp.customize.panel('wcb_coupon_box_design', function (panel) {
                    panel.expanded.bind(function (isExpanded) {
                        var iframe = jQuery('iframe').contents().find('body');
                        if (isExpanded) {
                            iframe.find('.wcb-current-layout').addClass('wcb-md-show');
                        } else {
                            iframe.find('.wcb-current-layout').removeClass('wcb-md-show');
                        }
                    });
                });

                /*Popup icon*/
                wp.customize('woo_coupon_box_params[wcb_popup_icon]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_popup_icon label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_popup_icon .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });
                wp.customize.section('wcb_coupon_box_design_popup_icon', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        var iframe = jQuery('iframe').contents().find('body');
                        if (isExpanded) {
                            iframe.find('.wcb-current-layout').removeClass('wcb-md-show');
                        } else {
                            iframe.find('.wcb-current-layout').addClass('wcb-md-show');
                        }
                    });
                });
//                /*Button close*/
                wp.customize('woo_coupon_box_params[wcb_button_close]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_button_close label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_button_close .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_facebook_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_facebook_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_facebook_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_twitter_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_twitter_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_twitter_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_pinterest_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_pinterest_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_pinterest_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_instagram_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_instagram_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_instagram_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_dribbble_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_dribbble_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_dribbble_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_tumblr_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_tumblr_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_tumblr_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_google_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_google_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_google_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_vkontakte_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_vkontakte_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_vkontakte_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_linkedin_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_linkedin_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_linkedin_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });

                wp.customize('woo_coupon_box_params[wcb_social_icons_youtube_select]', function (value) {
                    value.bind(function (newval) {
                        jQuery('.woo_coupon_box_params-wcb_social_icons_youtube_select label').removeClass('wcb-radio-icons-active');
                        jQuery('.woo_coupon_box_params-wcb_social_icons_youtube_select .' + newval).parent().addClass('wcb-radio-icons-active');
                    });
                });
            });

        </script>
		<?php
	}

	public function design_option_customizer( $wp_customize ) {
		$wp_customize->add_panel( 'wcb_coupon_box_design', array(
			'priority'       => 200,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Coupon Box for WooCommerce', 'woo-coupon-box' ),
		) );

		$this->add_section_design_general( $wp_customize );
		$this->add_section_design_button_close( $wp_customize );
		$this->add_section_design_header( $wp_customize );
		$this->add_section_design_body( $wp_customize );
		$this->add_section_design_social( $wp_customize );
		$this->add_section_design_button_subscribe( $wp_customize );
		$this->add_section_design_popup_icon( $wp_customize );
		$this->add_section_design_custom_css( $wp_customize );
		$this->add_section_design_pro_version_features( $wp_customize );
	}


	protected function add_section_design_pro_version_features( $wp_customize ) {
		$wp_customize->add_section( 'wcb_coupon_box_design_pro_version_features', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Premium version features', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_pro_version_features]', array(
			'default'           => array(),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Lists(
				$wp_customize,
				'woo_coupon_box_params[wcb_pro_version_features]',
				array(
					'label'   => 'Premium features',
					'section' => 'wcb_coupon_box_design_pro_version_features',
					'choices' => array(
						__( 'More triggers to show coupon box', 'woo-coupon-box' ),
						__( 'More option when visitors close Coupon box', 'woo-coupon-box' ),
						__( 'More input fields including name, mobile, birthday and gender', 'woo-coupon-box' ),
						__( 'Background effect like snow, rain...', 'woo-coupon-box' ),
						__( 'More templates to choose', 'woo-coupon-box' ),
						__( 'Google font', 'woo-coupon-box' ),
						__( 'ActiveCampaign integrated', 'woo-coupon-box' ),
						__( 'Premium support', 'woo-coupon-box' ),
					)
				)
			)
		);
	}

	protected function add_section_design_general( $wp_customize ) {
		$wp_customize->add_section( 'wcb_coupon_box_design_general', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'General', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_view_mode]', array(
			'default'           => $this->settings->get_default( 'wcb_view_mode' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_view_mode]', array(
			'type'        => 'select',
			'priority'    => 10,
			'section'     => 'wcb_coupon_box_design_general',
			'label'       => __( 'Select view mode to edit', 'woo-coupon-box' ),
			'choices'     => array(
				'1' => __( 'Before subscribe', 'woo-coupon-box' ),
				'2' => __( 'After subscribe', 'woo-coupon-box' ),
			),
			'description' => __( 'This option is for you to see what it\'t like before and after visitors subscribe only', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_show_coupon]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_show_coupon' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_show_coupon]', array(
			'type'        => 'checkbox',
			'section'     => 'wcb_coupon_box_design_general',
			'label'       => __( 'Show coupon after subscribing.', 'woo-coupon-box' ),
			'description' => __( 'If you set to give coupons for subscribers, beside sending coupon code via email address, enable this to print out the coupon code inside the coupon box after subscribing.', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_general',
			'label'       => __( 'Coupon Box Rounded Corner (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );


		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_type]', array(
			'default'           => $this->settings->get_default( 'wcb_popup_type' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_popup_type]', array(
			'type'    => 'select',
			'section' => 'wcb_coupon_box_design_general', // Add a default or your own section
			'label'   => __( 'Popup Display Type', 'woo-coupon-box' ),
			'choices' => array(
				'wcb-md-effect-1'  => 'Fade in & Scale',
				'wcb-md-effect-2'  => 'Slide in (right)',
				'wcb-md-effect-3'  => 'Slide in (bottom)',
				'wcb-md-effect-4'  => 'Newspaper',
				'wcb-md-effect-5'  => 'Fall',
				'wcb-md-effect-6'  => 'Side Fall',
				'wcb-md-effect-7'  => 'Sticky Up',
				'wcb-md-effect-8'  => '3D Flip (horizontal)',
				'wcb-md-effect-9'  => '3D Flip (vertical)',
				'wcb-md-effect-10' => '3D Sign',
				'wcb-md-effect-11' => 'Super Scaled',
				'wcb-md-effect-12' => 'Just Me',
				'wcb-md-effect-13' => '3D Slit',
				'wcb-md-effect-14' => '3D Rotate Bottom',
				'wcb-md-effect-15' => '3D Rotate In Left'
			)
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[alpha_color_overlay]', array(
			'default'           => $this->settings->get_default( 'alpha_color_overlay' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Alpha_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[alpha_color_overlay]',
				array(
					'label'        => __( 'Overlay color', 'woo-coupon-box' ),
					'section'      => 'wcb_coupon_box_design_general',
					'show_opacity' => true, // Optional.
					'palette'      => array(
						'rgb(0, 0, 0)',
						'rgb(255, 255, 255)',
						'rgba(221,51,51,0.92)',
						'rgb(150, 50, 220)',
						'#dd9933',
						'#eeee22',
						'#81d742',
						'#00CC99'
					)
				)
			)
		);
		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_gdpr_checkbox]', array(
			'selector'            => '.wcb-gdpr-field',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_gdpr_checkbox]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_gdpr_checkbox' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_gdpr_checkbox]', array(
			'type'    => 'checkbox',
			'section' => 'wcb_coupon_box_design_general',
			'label'   => __( 'GDPR checkbox', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_gdpr_checkbox_checked]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_gdpr_checkbox_checked' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_gdpr_checkbox_checked]', array(
			'type'    => 'checkbox',
			'section' => 'wcb_coupon_box_design_general',
			'label'   => __( 'Set checkbox state "checked" as default', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_gdpr_message]', array(
			'default'           => $this->settings->get_default( 'wcb_gdpr_message' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_gdpr_message]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_general',
			'label'    => __( 'GDPR message', 'woo-coupon-box' )
		) );
		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_no_thank_button_enable]', array(
			'selector'            => '.wcb-md-close-never-reminder-field',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_no_thank_button_enable]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_no_thank_button_enable' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_no_thank_button_enable]', array(
			'type'    => 'checkbox',
			'section' => 'wcb_coupon_box_design_general',
			'label'   => __( 'Button \'No, thanks\'', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_no_thank_button_title]', array(
			'default'           => $this->settings->get_default( 'wcb_no_thank_button_title' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_no_thank_button_title]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_general',
			'label'    => __( 'Button title', 'woo-coupon-box' )
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_no_thank_button_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_no_thank_button_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_no_thank_button_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_general',
			'label'       => __( 'Rounded corner (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_no_thank_button_color]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_no_thank_button_color' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_no_thank_button_color]',
				array(
					'label'    => __( 'Button title color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_general',
					'settings' => 'woo_coupon_box_params[wcb_no_thank_button_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_no_thank_button_bg_color]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_no_thank_button_bg_color' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_no_thank_button_bg_color]',
				array(
					'label'    => __( 'Button background color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_general',
					'settings' => 'woo_coupon_box_params[wcb_no_thank_button_bg_color]',
				) )
		);
		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_footer_text]', array(
			'selector'            => '.wcb-footer-text',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_footer_text]', array(
			'default'           => $this->settings->get_default( 'wcb_footer_text' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_footer_text]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_general',
			'label'    => __( 'Footer Text', 'woo-coupon-box' )
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_footer_text_after_subscribe]', array(
			'default'           => $this->settings->get_default( 'wcb_footer_text_after_subscribe' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_footer_text_after_subscribe]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_general',
			'label'    => __( 'Footer Text After Subscribing', 'woo-coupon-box' )
		) );

	}

	public function add_section_design_button_close( $wp_customize ) {
		$wp_customize->add_section( 'wcb_coupon_box_design_button_close', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Button Close', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );
		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_button_close]', array(
			'selector'            => '.wcb-md-close',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close]', array(
			'default'           => $this->settings->get_default( 'wcb_button_close' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_button_close]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_button_close',
					'choices' => array(
						'wcb_button_close_icons-cancel-2'     => 'wcb_button_close_icons-cancel-2',
						'wcb_button_close_icons-cancel-music' => 'wcb_button_close_icons-cancel-music',
						'wcb_button_close_icons-close-button' => 'wcb_button_close_icons-close-button',
						'wcb_button_close_icons-cancel'       => 'wcb_button_close_icons-cancel',
						'wcb_button_close_icons-cancel-1'     => 'wcb_button_close_icons-cancel-1',
						'wcb_button_close_icons-close'        => 'wcb_button_close_icons-close',
					)
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close_color]', array(
			'default'           => $this->settings->get_default( 'wcb_button_close_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_button_close_color]',
				array(
					'label'   => __( 'Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_button_close',
				) )
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close_bg_color]', array(
			'default'           => $this->settings->get_default( 'wcb_button_close_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Alpha_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_button_close_bg_color]',
				array(
					'label'        => __( 'Background Color', 'woo-coupon-box' ),
					'section'      => 'wcb_coupon_box_design_button_close',
					'show_opacity' => true, // Optional.
					'palette'      => array(
						'rgb(0, 0, 0)',
						'rgb(255, 255, 255)',
						'rgba(221,51,51,0.92)',
						'rgb(150, 50, 220)',
						'#dd9933',
						'#eeee22',
						'#81d742',
						'#00CC99'
					)
				) )
		);


		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_button_close_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_button_close_size]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_button_close',
			'label'       => __( 'Size (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close_width]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_button_close_width' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_button_close_width]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_button_close',
			'label'       => __( 'Width (px)', 'woo-coupon-box' ),
			'description' => __( 'Helpful tip: Set width to 0 to disable button close. Visitor can still close Coupon box without subscribing by clicking around or press Esc.', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_button_close_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_button_close_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_button_close',
			'label'       => __( 'Rounded Corner (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close_position_x]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_button_close_position_x' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_button_close_position_x]', array(
			'type'    => 'number',
			'section' => 'wcb_coupon_box_design_button_close',
			'label'   => __( 'Position-x (px)', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_close_position_y]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_button_close_position_y' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_button_close_position_y]', array(
			'type'    => 'number',
			'section' => 'wcb_coupon_box_design_button_close',
			'label'   => __( 'Position-y (px)', 'woo-coupon-box' ),
		) );
	}

	public function add_section_design_header( $wp_customize ) {

		$wp_customize->add_section( 'wcb_coupon_box_design_header', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Header', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );

		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_title]', array(
			'selector'            => '.wcb-modal-header',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_title]', array(
			'default'           => $this->settings->get_default( 'wcb_title' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		if ( $this->settings->get_params( 'wcb_layout' ) == 3 ) {
			$wp_customize->add_control( 'woo_coupon_box_params[wcb_title]', array(
				'type'        => 'textarea',
				'priority'    => 10,
				'section'     => 'wcb_coupon_box_design_header',
				'label'       => __( 'Header Title', 'woo-coupon-box' ),
				'description' => esc_html__( 'Place words in <span></span> to make it display in new line', 'woo-coupon-box' ),
			) );
		} else {
			$wp_customize->add_control( 'woo_coupon_box_params[wcb_title]', array(
				'type'     => 'textarea',
				'priority' => 10,
				'section'  => 'wcb_coupon_box_design_header',
				'label'    => __( 'Header Title', 'woo-coupon-box' ),
			) );
		}
		if ( $this->settings->get_params( 'wcb_layout' ) == 3 ) {
			$wp_customize->add_setting( 'woo_coupon_box_params[wcb_title_after_subscribing]', array(
				'default'           => '',
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			) );

		} else {
			$wp_customize->add_setting( 'woo_coupon_box_params[wcb_title_after_subscribing]', array(
				'default'           => $this->settings->get_default( 'wcb_title_after_subscribing' ),
				'type'              => 'option',
				'capability'        => 'manage_options',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			) );
		}
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_title_after_subscribing]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_header',
			'label'    => __( 'Header Title After Subscribe', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_color_header]', array(
			'default'           => $this->settings->get_default( 'wcb_color_header' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_color_header]',
				array(
					'label'    => __( 'Header Color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_header',
					'settings' => 'woo_coupon_box_params[wcb_color_header]',
				) )
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_bg_header]', array(
			'default'           => $this->settings->get_default( 'wcb_bg_header' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_bg_header]',
				array(
					'label'    => __( 'Header Background Color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_header',
					'settings' => 'woo_coupon_box_params[wcb_bg_header]',
				) )
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_title_size]', array(
			'default'           => $this->settings->get_default( 'wcb_title_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_title_size]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_header',
			'label'       => __( 'Header Font Size(px)', 'woo-coupon-box' ),
			'description' => __( 'Helpful tip: Set both Header Font size and Space to 0 to hide header.', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_title_space]', array(
			'default'           => $this->settings->get_default( 'wcb_title_space' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_title_space]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_header',
			'label'       => __( 'Header Space(px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
	}

	public function add_section_design_body( $wp_customize ) {

		$wp_customize->add_section( 'wcb_coupon_box_design_body', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Body', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_body_text_color]', array(
			'default'           => $this->settings->get_default( 'wcb_body_text_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_body_text_color]',
				array(
					'label'    => __( 'Body Text Color', 'woo-coupon-box' ),
					'section'  => __( 'wcb_coupon_box_design_body', 'woo-coupon-box' ),
					'settings' => 'woo_coupon_box_params[wcb_body_text_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_body_bg]', array(
			'default'           => $this->settings->get_default( 'wcb_body_bg' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_body_bg]',
				array(
					'label'    => __( 'Body Background Color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_body',
					'settings' => 'woo_coupon_box_params[wcb_body_bg]',
				) )
		);

		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_body_bg_img]', array(
			'selector'            => array(
				'.wcb-coupon-box .wcb-content-wrap',
				'.wcb-coupon-box-2 .wcb-modal-body',
				'.wcb-coupon-box-3 .wcb-content-wrap',
				'.wcb-coupon-box-4 .wcb-content-wrap',
				'.wcb-coupon-box-5 .wcb-modal-body',
			),
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_body_bg_img]', array(
			'default'           => $this->settings->get_default( 'wcb_body_bg_img' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_body_bg_img]',
				array(
					'label'    => __( 'Body Background Image', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_body',
					'settings' => 'woo_coupon_box_params[wcb_body_bg_img]',
				) )
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_body_bg_img_repeat]', array(
			'default'           => $this->settings->get_default( 'wcb_body_bg_img_repeat' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_body_bg_img_repeat]', array(
			'type'    => 'select',
			'section' => 'wcb_coupon_box_design_body', // Add a default or your own section
			'label'   => __( 'Body Background Image Repeat', 'woo-coupon-box' ),
			'choices' => array(
				'inherit'   => __( 'inherit', 'woo-coupon-box' ),
				'no-repeat' => __( 'no-repeat', 'woo-coupon-box' ),
				'repeat'    => __( 'repeat', 'woo-coupon-box' ),
				'repeat-x'  => __( 'repeat-x', 'woo-coupon-box' ),
				'repeat-y'  => __( 'repeat-y', 'woo-coupon-box' ),
				'round'     => __( 'round', 'woo-coupon-box' ),
				'space'     => __( 'space', 'woo-coupon-box' )
			),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_body_bg_img_size]', array(
			'default'           => $this->settings->get_default( 'wcb_body_bg_img_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_body_bg_img_size]', array(
			'type'    => 'select',
			'section' => 'wcb_coupon_box_design_body', // Add a default or your own section
			'label'   => __( 'Body Background Image Size', 'woo-coupon-box' ),
			'choices' => array(
				'auto'    => __( 'Auto', 'woo-coupon-box' ),
				'inherit' => __( 'Inherit', 'woo-coupon-box' ),
				'contain' => __( 'Contain', 'woo-coupon-box' ),
				'cover'   => __( 'Cover', 'woo-coupon-box' )
			),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_body_bg_img_position]', array(
			'default'           => $this->settings->get_default( 'wcb_body_bg_img_position' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_body_bg_img_position]', array(
			'type'        => 'text',
			'section'     => 'wcb_coupon_box_design_body', // Add a default or your own section
			'label'       => __( 'Body Background Image Position', 'woo-coupon-box' ),
			'description' => __( 'Position of Background Image. Eg: 100% 100%, center...', 'woo-coupon-box' )
		) );


		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_message]', array(
			'selector'            => '.wcb-coupon-message',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_message]', array(
			'default'           => $this->settings->get_default( 'wcb_message' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_message]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_body',
			'label'    => __( 'Message', 'woo-coupon-box' )
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_message_after_subscribe]', array(
			'default'           => $this->settings->get_default( 'wcb_message_after_subscribe' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_message_after_subscribe]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_body',
			'label'    => __( 'Message After Subscribing', 'woo-coupon-box' )
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_message_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_message_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_message_size]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_body',
			'label'       => __( 'Message Size (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_message_align]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_message_align' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_message_align]', array(
			'type'    => 'select',
			'section' => 'wcb_coupon_box_design_body',
			'label'   => __( 'Message Text Align (px)', 'woo-coupon-box' ),
			'choices' => array(
				'left'    => __( 'Left', 'woo-coupon-box' ),
				'center'  => __( 'Center', 'woo-coupon-box' ),
				'right'   => __( 'Right', 'woo-coupon-box' ),
				'justify' => __( 'Justify', 'woo-coupon-box' ),
				'start'   => __( 'Start', 'woo-coupon-box' ),
				'end'     => __( 'End', 'woo-coupon-box' ),
			),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_color_message]', array(
			'default'           => $this->settings->get_default( 'wcb_color_message' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_color_message]',
				array(
					'label'    => __( 'Message Color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_body',
					'settings' => 'woo_coupon_box_params[wcb_color_message]',
				) )
		);
	}

	public function add_section_design_social( $wp_customize ) {

		$wp_customize->add_section( 'wcb_coupon_box_design_social', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Social Media', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );
		$icons = array(
			"wcb_social_icons-facebook-circular-logo",
			"wcb_social_icons-facebook-logo-1",
			"wcb_social_icons-facebook-square-social-logo",
			"wcb_social_icons-facebook-app-logo",
			"wcb_social_icons-facebook-logo",
			"wcb_social_icons-internet",
			"wcb_social_icons-twitter-logo-button",
			"wcb_social_icons-twitter-logo-silhouette",
			"wcb_social_icons-twitter",
			"wcb_social_icons-twitter-1",
			"wcb_social_icons-twitter-logo-on-black-background",
			"wcb_social_icons-twitter-sign",
			"wcb_social_icons-pinterest",
			"wcb_social_icons-pinterest-logo",
			"wcb_social_icons-pinterest-1",
			"wcb_social_icons-pinterest-2",
			"wcb_social_icons-pinterest-social-logo",
			"wcb_social_icons-pinterest-logo-1",
			"wcb_social_icons-pinterest-sign",
			"wcb_social_icons-instagram-logo",
			"wcb_social_icons-instagram-social-network-logo-of-photo-camera-1",
			"wcb_social_icons-instagram-1",
			"wcb_social_icons-social-media",
			"wcb_social_icons-instagram",
			"wcb_social_icons-instagram-social-network-logo-of-photo-camera",
			"wcb_social_icons-instagram-logo-1",
			"wcb_social_icons-instagram-2",
			"wcb_social_icons-dribbble-logo",
			"wcb_social_icons-dribble-logo-button",
			"wcb_social_icons-dribbble",
			"wcb_social_icons-dribbble-logo-1",
			"wcb_social_icons-dribbble-2",
			"wcb_social_icons-dribbble-1",
			"wcb_social_icons-tumblr-logo-1",
			"wcb_social_icons-tumblr-logo-button",
			"wcb_social_icons-tumblr",
			"wcb_social_icons-tumblr-logo-2",
			"wcb_social_icons-tumblr-logo",
			"wcb_social_icons-tumblr-1",
			"wcb_social_icons-google-plus-logo",
			"wcb_social_icons-google-plus-symbol",
			"wcb_social_icons-google-plus-social-logotype",
			"wcb_social_icons-google-plus",
			"wcb_social_icons-google-plus-social-logotype-1",
			"wcb_social_icons-google-plus-logo-on-black-background",
			"wcb_social_icons-social-google-plus-square-button",
			"wcb_social_icons-vk-social-network-logo",
			"wcb_social_icons-vk-social-logotype",
			"wcb_social_icons-vk",
			"wcb_social_icons-vk-social-logotype-1",
			"wcb_social_icons-vk-reproductor",
			"wcb_social_icons-vkontakte-logo",
			"wcb_social_icons-linkedin-logo",
			"wcb_social_icons-linkedin-button",
			"wcb_social_icons-linkedin-1",
			"wcb_social_icons-linkedin-logo-1",
			"wcb_social_icons-linkedin-sign",
			"wcb_social_icons-linkedin",
			"wcb_social_icons-youtube-logo-2",
			"wcb_social_icons-youtube-logotype-1",
			"wcb_social_icons-youtube",
			"wcb_social_icons-youtube-logotype",
			"wcb_social_icons-youtube-logo",
			"wcb_social_icons-youtube-logo-1"
		);

		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_follow_us]', array(
			'selector'            => '.wcb-sharing-container',
			'container_inclusive' => true,
			'fallback_refresh'    => false,
			'render_callback'     => false,
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_follow_us]', array(
			'default'           => $this->settings->get_default( 'wcb_follow_us' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_follow_us]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_social',
			'label'    => __( 'Text Follow Social Network', 'woo-coupon-box' )
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_color_follow_us]', array(
			'default'           => $this->settings->get_default( 'wcb_color_follow_us' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_color_follow_us]',
				array(
					'label'    => __( 'Follow Us Text Color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_social',
					'settings' => 'woo_coupon_box_params[wcb_color_follow_us]',
				) )
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_size]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Icons size (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_target]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_target' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_target]', array(
			'type'    => 'select',
			'section' => 'wcb_coupon_box_design_social',
			'label'   => __( 'When click on social icons', 'woo-coupon-box' ),
			'choices' => array(
				'_blank' => __( 'Open link in new tab', 'woo-coupon-box' ),
				'_self'  => __( 'Open link in current tab', 'woo-coupon-box' ),
			),
		) );
		$facebook = $twitter = $pinterest = $instagram = $dribbble = $tumblr = $google = $vkontakte = $linkedin = $youtube = array();
		for ( $i = 0; $i < sizeof( $icons ); $i ++ ) {
			if ( $i < 6 ) {
				$facebook[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 12 ) {
				$twitter[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 19 ) {
				$pinterest[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 27 ) {
				$instagram[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 33 ) {
				$dribbble[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 39 ) {
				$tumblr[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 46 ) {
				$google[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 52 ) {
				$vkontakte[ $icons[ $i ] ] = $icons[ $i ];
			} elseif ( $i < 58 ) {
				$linkedin[ $icons[ $i ] ] = $icons[ $i ];
			} else {
				$youtube[ $icons[ $i ] ] = $icons[ $i ];
			}
		}
		/*facebook*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_facebook_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_facebook_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_facebook_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Facebook User Name', 'woo-coupon-box' ),
			'description' => __( 'Your Facebook User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_facebook_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_facebook_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_facebook_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $facebook
				)
			)
		);


		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_facebook_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_facebook_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_facebook_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);


		/*twitter*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_twitter_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_twitter_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_twitter_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Twitter User Name', 'woo-coupon-box' ),
			'description' => __( 'Your Twitter User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_twitter_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_twitter_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_twitter_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $twitter
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_twitter_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_twitter_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_twitter_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*pinterest*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_pinterest_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_pinterest_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_pinterest_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Pinterest User Name', 'woo-coupon-box' ),
			'description' => __( 'Your Pinterest User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_pinterest_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_pinterest_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_pinterest_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $pinterest
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_pinterest_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_pinterest_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_pinterest_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*instagram*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_instagram_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_instagram_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_instagram_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Instagram User Name', 'woo-coupon-box' ),
			'description' => __( 'Your Instagram User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_instagram_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_instagram_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_instagram_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $instagram
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_instagram_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_instagram_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_instagram_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*dribbble*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_dribbble_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_dribbble_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_dribbble_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Dribbble User Name', 'woo-coupon-box' ),
			'description' => __( 'Your Dribbble User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_dribbble_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_dribbble_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_dribbble_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $dribbble
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_dribbble_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_dribbble_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_dribbble_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*tumblr*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_tumblr_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_tumblr_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_tumblr_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Tumblr User Name', 'woo-coupon-box' ),
			'description' => __( 'Your Tumblr User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_tumblr_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_tumblr_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_tumblr_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $tumblr
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_tumblr_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_tumblr_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_tumblr_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*google*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_google_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_google_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_google_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Google Plus ID', 'woo-coupon-box' ),
			'description' => __( 'Your Google Plus ID. Eg: +LarryPage', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_google_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_google_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_google_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $google
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_google_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_google_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_google_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*vkontakte*/
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_vkontakte_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_vkontakte_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_vkontakte_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'VKontakte User Name', 'woo-coupon-box' ),
			'description' => __( 'Your VKontakte User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_vkontakte_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_vkontakte_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_vkontakte_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $vkontakte
				)
			)
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_vkontakte_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_vkontakte_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_vkontakte_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*linkedin*/

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_linkedin_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_linkedin_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_linkedin_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $linkedin
				)
			)
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_linkedin_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_linkedin_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_linkedin_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Linkedin User Name', 'woo-coupon-box' ),
			'description' => __( 'Your Linkedin User Name. Eg: villatheme', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_linkedin_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_linkedin_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_linkedin_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);

		/*youtube*/

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_youtube_select]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_youtube_select' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_youtube_select]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_social',
					'choices' => $youtube
				)
			)
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_youtube_url]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_social_icons_youtube_url' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_social_icons_youtube_url]', array(
			'type'        => 'url',
			'section'     => 'wcb_coupon_box_design_social',
			'label'       => __( 'Youtube URL', 'woo-coupon-box' ),
			'description' => __( 'Your Youtube full url. Eg: https://www.youtube.com/channel/UCbCfnjbtBZIQfzLvXgNpbKw', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_social_icons_youtube_color]', array(
			'default'           => $this->settings->get_default( 'wcb_social_icons_youtube_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_social_icons_youtube_color]',
				array(
					'label'   => __( 'Icon Color', 'woo-coupon-box' ),
					'section' => 'wcb_coupon_box_design_social',
				) )
		);


	}

	public function add_section_design_button_subscribe( $wp_customize ) {
		$wp_customize->add_section( 'wcb_coupon_box_design_button_subscribe', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Button Subscribe & Email field', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_email_input_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_email_input_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_email_input_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_button_subscribe',
			'label'       => __( 'Emai Input Rounded Corner (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_email_button_space]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_email_button_space' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_email_button_space]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_button_subscribe',
			'label'       => __( 'Space between email field & button SUBSCRIBE', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );

		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_button_text]', array(
			'selector'            => '.wcb-newsletter-form',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_text]', array(
			'default'           => $this->settings->get_default( 'wcb_button_text' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_button_text]', array(
			'type'     => 'text',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_button_subscribe',
			'label'    => __( 'Button Title', 'woo-coupon-box' )
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_text_color]', array(
			'default'           => $this->settings->get_default( 'wcb_button_text_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_button_text_color]',
				array(
					'label'    => __( 'Button Text Color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_button_subscribe',
					'settings' => 'woo_coupon_box_params[wcb_button_text_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_bg_color]', array(
			'default'           => $this->settings->get_default( 'wcb_button_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_button_bg_color]',
				array(
					'label'    => __( 'Button Background Color', 'woo-coupon-box' ),
					'section'  => 'wcb_coupon_box_design_button_subscribe',
					'settings' => 'woo_coupon_box_params[wcb_button_bg_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_button_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_button_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_button_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_button_subscribe',
			'label'       => __( 'Button "SUBSCRIBE" Rounded Corner (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );

	}

	protected function add_section_design_popup_icon( $wp_customize ) {
		$wp_customize->add_section( 'wcb_coupon_box_design_popup_icon', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Popup Icons', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );
		$wp_customize->selective_refresh->add_partial( 'woo_coupon_box_params[wcb_popup_icon_enable]', array(
			'selector'            => '.wcb-coupon-box-small-icon-wrap',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon_enable]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_show_coupon' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_popup_icon_enable]', array(
			'type'        => 'checkbox',
			'section'     => 'wcb_coupon_box_design_popup_icon',
			'label'       => __( 'Coupon box icon', 'woo-coupon-box' ),
			'description' => __( 'A small icon to call coupon again after a visitor closes coupon box without subscribing', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon_mobile]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_popup_icon_mobile' ),
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_popup_icon_mobile]', array(
			'type'        => 'checkbox',
			'section'     => 'wcb_coupon_box_design_popup_icon',
			'label'       => __( 'Coupon box icon on mobile', 'woo-coupon-box' ),
			'description' => __( 'Uncheck this to hide the coupon box icon on mobile', 'woo-coupon-box' ),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon_position]', array(
			'default'           => $this->settings->get_default( 'wcb_popup_icon_position' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_popup_icon_position]', array(
			'type'    => 'select',
			'section' => 'wcb_coupon_box_design_popup_icon', // Add a default or your own section
			'label'   => __( 'Icon position', 'woo-coupon-box' ),
			'choices' => array(
				'top-left'     => __( 'Top Left', 'woo-coupon-box' ),
				'top-right'    => __( 'Top Right', 'woo-coupon-box' ),
				'bottom-left'  => __( 'Bottom Left', 'woo-coupon-box' ),
				'bottom-right' => __( 'Bottom Right', 'woo-coupon-box' ),

			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_popup_icon_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_popup_icon_size]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_popup_icon',
			'label'       => __( 'Icons size (px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wcb_popup_icon_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_popup_icon_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wcb_coupon_box_design_popup_icon',
			'label'       => __( 'Icon wrap rounded corner(px)', 'woo-coupon-box' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon_color]', array(
			'default'           => $this->settings->get_default( 'wcb_popup_icon_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Alpha_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_popup_icon_color]',
				array(
					'label'        => __( 'Icon color', 'woo-coupon-box' ),
					'section'      => 'wcb_coupon_box_design_popup_icon',
					'show_opacity' => true, // Optional.
					'palette'      => array(
						'rgb(0, 0, 0)',
						'rgb(255, 255, 255)',
						'rgba(221,51,51,0.92)',
						'rgb(150, 50, 220)',
						'#dd9933',
						'#eeee22',
						'#81d742',
						'#00CC99'
					)
				)
			)
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon_bg_color]', array(
			'default'           => $this->settings->get_default( 'wcb_popup_icon_bg_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Alpha_Color_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_popup_icon_bg_color]',
				array(
					'label'        => __( 'Icon background color', 'woo-coupon-box' ),
					'section'      => 'wcb_coupon_box_design_popup_icon',
					'show_opacity' => true, // Optional.
					'palette'      => array(
						'rgb(0, 0, 0)',
						'rgb(255, 255, 255)',
						'rgba(221,51,51,0.92)',
						'rgb(150, 50, 220)',
						'#dd9933',
						'#eeee22',
						'#81d742',
						'#00CC99'
					)
				)
			)
		);
		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_popup_icon]', array(
			'default'           => $this->settings->get_default( 'wcb_popup_icon' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WOO_COUPON_BOX_Radio_Icons_Control(
				$wp_customize,
				'woo_coupon_box_params[wcb_popup_icon]',
				array(
					'label'   => 'Icons',
					'section' => 'wcb_coupon_box_design_popup_icon',
					'choices' => array(
						"wcb_giftbox-aniversary-giftbox"                    => "wcb_giftbox-aniversary-giftbox",
						"wcb_giftbox-big-giftbox-and-gift-with-heart"       => "wcb_giftbox-big-giftbox-and-gift-with-heart",
						"wcb_giftbox-big-giftbox-with-bun"                  => "wcb_giftbox-big-giftbox-with-bun",
						"wcb_giftbox-big-giftbox-with-lateral-lace"         => "wcb_giftbox-big-giftbox-with-lateral-lace",
						"wcb_giftbox-big-giftbox-with-ribbon"               => "wcb_giftbox-big-giftbox-with-ribbon",
						"wcb_giftbox-cylindrical-giftbox-with-ribbon"       => "wcb_giftbox-cylindrical-giftbox-with-ribbon",
						"wcb_giftbox-gifbox-with-lace"                      => "wcb_giftbox-gifbox-with-lace",
						"wcb_giftbox-gifbox-with-ribbon-in-the-middle"      => "wcb_giftbox-gifbox-with-ribbon-in-the-middle",
						"wcb_giftbox-gifbox-with-ribbon-on-top"             => "wcb_giftbox-gifbox-with-ribbon-on-top",
						"wcb_giftbox-gifbox-wrapped-with-ribbon"            => "wcb_giftbox-gifbox-wrapped-with-ribbon",
						"wcb_giftbox-gift-with-bow"                         => "wcb_giftbox-gift-with-bow",
						"wcb_giftbox-gift-with-ribbon"                      => "wcb_giftbox-gift-with-ribbon",
						"wcb_giftbox-giftbox-side"                          => "wcb_giftbox-giftbox-side",
						"wcb_giftbox-giftbox-with-a-big-ribbon-on-cover"    => "wcb_giftbox-giftbox-with-a-big-ribbon-on-cover",
						"wcb_giftbox-giftbox-with-a-heart"                  => "wcb_giftbox-giftbox-with-a-heart",
						"wcb_giftbox-giftbox-with-a-heart-on-side"          => "wcb_giftbox-giftbox-with-a-heart-on-side",
						"wcb_giftbox-giftbox-with-big-lace"                 => "wcb_giftbox-giftbox-with-big-lace",
						"wcb_giftbox-giftbox-with-big-lace-1"               => "wcb_giftbox-giftbox-with-big-lace-1",
						"wcb_giftbox-giftbox-with-big-ribbon"               => "wcb_giftbox-giftbox-with-big-ribbon",
						"wcb_giftbox-giftbox-with-big-ribbon-1"             => "wcb_giftbox-giftbox-with-big-ribbon-1",
						"wcb_giftbox-giftbox-with-big-ribbon-2"             => "wcb_giftbox-giftbox-with-big-ribbon-2",
						"wcb_giftbox-giftbox-with-big-ribbon-3"             => "wcb_giftbox-giftbox-with-big-ribbon-3",
						"wcb_giftbox-giftbox-with-bun"                      => "wcb_giftbox-giftbox-with-bun",
						"wcb_giftbox-giftbox-with-flower"                   => "wcb_giftbox-giftbox-with-flower",
						"wcb_giftbox-giftbox-with-hearts"                   => "wcb_giftbox-giftbox-with-hearts",
						"wcb_giftbox-giftbox-with-lace-on-a-side"           => "wcb_giftbox-giftbox-with-lace-on-a-side",
						"wcb_giftbox-giftbox-with-long-ribbon"              => "wcb_giftbox-giftbox-with-long-ribbon",
						"wcb_giftbox-giftbox-with-ribbon"                   => "wcb_giftbox-giftbox-with-ribbon",
						"wcb_giftbox-giftbox-with-ribbon-on-one-side"       => "wcb_giftbox-giftbox-with-ribbon-on-one-side",
						"wcb_giftbox-giftbox-with-ribbon-on-top"            => "wcb_giftbox-giftbox-with-ribbon-on-top",
						"wcb_giftbox-giftbox-with-ribbon-on-top-1"          => "wcb_giftbox-giftbox-with-ribbon-on-top-1",
						"wcb_giftbox-giftbox-wrapped"                       => "wcb_giftbox-giftbox-wrapped",
						"wcb_giftbox-heart-shape-giftbox-with-lace"         => "wcb_giftbox-heart-shape-giftbox-with-lace",
						"wcb_giftbox-heart-shape-giftbox-with-ribbon"       => "wcb_giftbox-heart-shape-giftbox-with-ribbon",
						"wcb_giftbox-heart-shapped-gifbox-with-ribbon"      => "wcb_giftbox-heart-shapped-gifbox-with-ribbon",
						"wcb_giftbox-open-box-with-two-hearts"              => "wcb_giftbox-open-box-with-two-hearts",
						"wcb_giftbox-open-gitfbox-with-two-hearts"          => "wcb_giftbox-open-gitfbox-with-two-hearts",
						"wcb_giftbox-polka-dots-giftbox-with-lace"          => "wcb_giftbox-polka-dots-giftbox-with-lace",
						"wcb_giftbox-rectangular-giftbox-with-flower"       => "wcb_giftbox-rectangular-giftbox-with-flower",
						"wcb_giftbox-round-gift-box-with-lace"              => "wcb_giftbox-round-gift-box-with-lace",
						"wcb_giftbox-round-giftbox-with-flower"             => "wcb_giftbox-round-giftbox-with-flower",
						"wcb_giftbox-square-gifbox-wrapped"                 => "wcb_giftbox-square-gifbox-wrapped",
						"wcb_giftbox-square-gifsoft-with-bun"               => "wcb_giftbox-square-gifsoft-with-bun",
						"wcb_giftbox-square-giftbox-with-big-lace"          => "wcb_giftbox-square-giftbox-with-big-lace",
						"wcb_giftbox-square-giftbox-with-big-ribbon"        => "wcb_giftbox-square-giftbox-with-big-ribbon",
						"wcb_giftbox-three-giftboxes-with-ribbon-and-heart" => "wcb_giftbox-three-giftboxes-with-ribbon-and-heart",
						"wcb_giftbox-two-gifboxes-tied-together"            => "wcb_giftbox-two-gifboxes-tied-together",
						"wcb_giftbox-two-gifboxes-wrapped"                  => "wcb_giftbox-two-gifboxes-wrapped",
						"wcb_giftbox-two-giftboxes"                         => "wcb_giftbox-two-giftboxes",
						"wcb_giftbox-valentines-giftbox"                    => "wcb_giftbox-valentines-giftbox"
					),
				)
			)
		);

	}

	protected function add_section_design_custom_css( $wp_customize ) {

		$wp_customize->add_section( 'wcb_coupon_box_design_custom_css', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => __( 'Custom CSS', 'woo-coupon-box' ),
			'panel'          => 'wcb_coupon_box_design',
		) );

		$wp_customize->add_setting( 'woo_coupon_box_params[wcb_custom_css]', array(
			'default'           => $this->settings->get_default( 'wcb_custom_css' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_coupon_box_params[wcb_custom_css]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wcb_coupon_box_design_custom_css',
			'label'    => __( 'Custom CSS', 'woo-coupon-box' )
		) );
	}

}
