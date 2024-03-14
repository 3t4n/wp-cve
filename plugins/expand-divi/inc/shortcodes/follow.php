<?php
// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * social follow shortcode
 *
 */
function expand_divi_social_follow_shortcode() {
	$options = get_option( 'expand_divi' );

	$option_fields = ['facebook_follow_url', 'facebook_follow_text', 'twitter_follow_url', 'twitter_follow_text', 'youtube_follow_url', 'youtube_follow_text', 'email_follow_url', 'email_follow_text', 'linkedin_follow_url', 'linkedin_follow_text', 'instagram_follow_url', 'instagram_follow_text', 'whatsapp_follow_url', 'whatsapp_follow_text', 'rss_follow_url', 'rss_follow_text', 'soundcloud_follow_url', 'soundcloud_follow_text'];

	foreach ( $option_fields as $option_field ) {
		isset( $options[$option_field] ) ? $$option_field = $options[$option_field] : $$option_field = '';
	}

	$html = '<div class="expand_divi_follow_icons"><ul>';
		if ( ( $facebook_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_facebook_follow_icon"><a href="';
			$html .= $facebook_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $facebook_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $twitter_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_twitter_follow_icon"><a href="';
			$html .= $twitter_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $twitter_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $youtube_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_youtube_follow_icon"><a href="';
			$html .= $youtube_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $twitter_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $email_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_email_follow_icon"><a href="';
			$html .= $email_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $email_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $linkedin_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_linkedin_follow_icon"><a href="';
			$html .= $linkedin_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $linkedin_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $instagram_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_instagram_follow_icon"><a href="';
			$html .= $instagram_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $instagram_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $whatsapp_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_whatsapp_follow_icon"><a href="';
			$html .= $whatsapp_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $whatsapp_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $rss_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_rss_follow_icon"><a href="';
			$html .= $rss_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $rss_follow_text;
			$html .= '</a></li>';
		}
		if ( ( $soundcloud_follow_url !== '' ) ) {
			$html .= '<li class="expand_divi_soundcloud_follow_icon"><a href="';
			$html .= $soundcloud_follow_url;
			$html .= '" target="_blank" rel="external">';
			$html .= $soundcloud_follow_text;
			$html .= '</a></li>';
		}
	$html .= '</ul></div>';

	return $html;
}

add_shortcode( 'ed_follow_icons', 'expand_divi_social_follow_shortcode');