<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

add_action( 'template_redirect', 'siteseo_redirections_attachments', 2 );
function siteseo_redirections_attachments(){
	
	if(siteseo_get_service('AdvancedOption')->getAdvancedAttachments() != '1'){
		return;
	}
	
	global $post;
	
	if( is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent != 0) ){
		wp_safe_redirect( get_permalink( $post->post_parent ), 301 );
		exit();
	}elseif(is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent == 0)){
		wp_safe_redirect(get_home_url(), 302);
		exit();
	}
}

add_action( 'template_redirect', 'siteseo_redirections_attachments_file', 1 );
function siteseo_redirections_attachments_file(){
	if(siteseo_get_service('AdvancedOption')->getAdvancedAttachmentsFile() =='1'){
		if ( is_attachment() ) {
			wp_safe_redirect( wp_get_attachment_url(), 301 );
			exit();
		}
	}
}

// Remove reply to com link
if ('1' == siteseo_get_service('AdvancedOption')->getAdvancedReplytocom()) {
	add_filter('comment_reply_link', 'siteseo_remove_reply_to_com');
}

function siteseo_remove_reply_to_com($link) {
	return preg_replace('/href=\'(.*(\?|&)replytocom=(\d+)#respond)/', 'href=\'#comment-$3', $link);
}

//Remove noreferrer on links
if ('1' == siteseo_get_service('AdvancedOption')->getAdvancedNoReferrer()) {
	add_filter('the_content', 'siteseo_remove_noreferrer', 999);
}

function siteseo_remove_noreferrer($content) {
	
	if(empty($content)){
		return $content;
	}

	$attrs = [
		"noreferrer " => "",
		" noreferrer" => ""
	];

	$attrs = apply_filters( 'siteseo_link_attrs', $attrs );

	return strtr($content, $attrs);
}

//Remove WP meta generator
if ('1' == siteseo_get_service('AdvancedOption')->getAdvancedWPGenerator()) {
	remove_action('wp_head', 'wp_generator');
}

//Remove hentry post class
if ('1' == siteseo_get_service('AdvancedOption')->getAdvancedHentry()) {
	
	add_filter('post_class', 'siteseo_advanced_advanced_hentry_hook');
	function siteseo_advanced_advanced_hentry_hook($classes) {

		$classes = array_diff($classes, ['hentry']);

		return $classes;
	}
}

//WordPress
if ('1' == siteseo_get_service('AdvancedOption')->getAdvancedWPShortlink()) {
	remove_action('wp_head', 'wp_shortlink_wp_head');
}

//WordPress WLWManifest
if ('1' == siteseo_get_service('AdvancedOption')->getAdvancedWPManifest()) {
	remove_action('wp_head', 'wlwmanifest_link');
}

//WordPress RSD
if ('1' == siteseo_get_service('AdvancedOption')->getAdvancedWPRSD()) {
	remove_action('wp_head', 'rsd_link');
}

//Google site verification
add_action('wp_head', 'siteseo_advanced_advanced_google_hook', 2);
function siteseo_advanced_advanced_google_hook() {
	if(is_home() || is_front_page()){
		$optionGoogle = siteseo_get_service('AdvancedOption')->getAdvancedGoogleVerification();
		if (!empty($optionGoogle)) {
			$siteseo_advanced_advanced_google = '<meta name="google-site-verification" content="' . esc_attr($optionGoogle) . '" />';
			$siteseo_advanced_advanced_google .= "\n";
			echo wp_kses($siteseo_advanced_advanced_google, ['meta' => ['name' => true,'content' => true]]);
		}
	}
}

//Bing site verification
add_action('wp_head', 'siteseo_advanced_advanced_bing_hook', 2);
function siteseo_advanced_advanced_bing_hook() {
	if (is_home() || is_front_page()) {
		$optionBing = siteseo_get_service('AdvancedOption')->getAdvancedBingVerification();
		if (!empty($optionBing)) {
			$siteseo_advanced_advanced_bing = '<meta name="msvalidate.01" content="' . esc_attr($optionBing) . '" />';
			$siteseo_advanced_advanced_bing .= "\n";
			echo wp_kses($siteseo_advanced_advanced_bing, array('meta' => array('name' => true,'content' => true)));
		}
	}
}

//Pinterest site verification
add_action('wp_head', 'siteseo_advanced_advanced_pinterest_hook', 2);
function siteseo_advanced_advanced_pinterest_hook() {
	if (is_home() || is_front_page()) {
		$optionPinterest =siteseo_get_service('AdvancedOption')->getAdvancedPinterestVerification();
		if (!empty($optionPinterest)) {
			$siteseo_advanced_advanced_pinterest = '<meta name="p:domain_verify" content="' . esc_attr($optionPinterest) . '" />';
			$siteseo_advanced_advanced_pinterest .= "\n";
			echo wp_kses($siteseo_advanced_advanced_pinterest, array('meta' => array('name' => true,'content' => true)));
		}
	}
}

//Yandex site verification
add_action('wp_head', 'siteseo_advanced_advanced_yandex_hook', 2);
function siteseo_advanced_advanced_yandex_hook() {
	if (is_home() || is_front_page()) {
		$contentYandex = siteseo_get_service('AdvancedOption')->getAdvancedYandexVerification();

		if(empty($contentYandex)){
			return;
		}

		$meta = '<meta name="yandex-verification" content="' . esc_attr($contentYandex) . '" />';
		$meta .= "\n";
		echo wp_kses($meta, array('meta' => array('name' => true, 'content' => true)));
	}
}

//Automatic alt text based on target kw
if(!empty(siteseo_get_service('AdvancedOption')->getAdvancedImageAutoAltTargetKw())){
	
	add_filter('wp_get_attachment_image_attributes', 'siteseo_auto_img_alt_thumb_target_kw', 10, 2);
	function siteseo_auto_img_alt_thumb_target_kw($atts, $attachment) {
		if ( ! is_admin()) {
			if (empty($atts['alt'])) {
				if ('' != get_post_meta(get_the_ID(), '_siteseo_analysis_target_kw', true)) {
					$atts['alt'] = esc_html(get_post_meta(get_the_ID(), '_siteseo_analysis_target_kw', true));

					$atts['alt'] = apply_filters('siteseo_auto_image_alt_target_kw', $atts['alt']);
				}
			}
		}

		return $atts;
	}

	// Replace alt for content no use gutenberg.
	add_filter('the_content', 'siteseo_auto_img_alt_target_kw', 20);
	function siteseo_auto_img_alt_target_kw($content) {
		if (empty($content)) {
			return $content;
		}

		$target_keyword = get_post_meta(get_the_ID(), '_siteseo_analysis_target_kw', true);

		$target_keyword = apply_filters('siteseo_auto_image_alt_target_kw', $target_keyword);

		if (empty($target_keyword)) {
			return $content;
		}

		$regex = '#<img[^>]* alt=(?:\"|\')(?<alt>([^"]*))(?:\"|\')[^>]*>#mU';

		preg_match_all($regex, $content, $matches);

		$matchesTag = $matches[0];
		$matchesAlt = $matches['alt'];

		if (empty($matchesAlt)) {
			return $content;
		}

		$regexSrc = '#<img[^>]* src=(?:\"|\')(?<src>([^"]*))(?:\"|\')[^>]*>#mU';

		foreach ($matchesAlt as $key => $alt) {
			if ( ! empty($alt)) {
				continue;
			}
			$contentMatch = $matchesTag[$key];
			preg_match($regexSrc, $contentMatch, $matchSrc);

			$contentToReplace  = str_replace('alt=""', 'alt="' . htmlspecialchars(esc_html($target_keyword)) . '"', $contentMatch);

			if ($contentMatch !== $contentToReplace) {
				$content = str_replace($contentMatch, $contentToReplace, $content);
			}
		}

		return $content;
	}
}
