<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Redirections
// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

// Enabled
function siteseo_redirections_enabled(){
	if (is_home() && get_option( 'page_for_posts' ) !='' && get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_enabled',true)) {
		$siteseo_redirections_enabled = get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_enabled',true);
		return $siteseo_redirections_enabled;
	} else {
		global $post;
		if ($post) {
			if (get_post_meta($post->ID,'_siteseo_redirections_enabled',true)) {
				$siteseo_redirections_enabled = get_post_meta($post->ID,'_siteseo_redirections_enabled',true);
				return $siteseo_redirections_enabled;
			}
		}
	}
}

function siteseo_redirections_term_enabled(){
	
	if(!get_queried_object_id()){
		return;
	}

	$value = get_term_meta(get_queried_object_id(),'_siteseo_redirections_enabled', true);
	
	if(empty($value)){
		return;
	}

	return $value;
}

// Login status
function siteseo_redirections_logged_status(){
	if (is_home() && get_option( 'page_for_posts' ) != ''
	&& get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_logged_status',true)){
		return get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_logged_status', true);
	} else {
		global $post;
		if($post) {
			if (get_post_meta($post->ID,'_siteseo_redirections_logged_status',true)) {
				return get_post_meta($post->ID, '_siteseo_redirections_logged_status', true);
			}
		}
	}
}

function siteseo_redirections_term_logged_status(){
	
	if (!get_queried_object_id()) {
		return;
	}

	$value = get_term_meta(get_queried_object_id(),'_siteseo_redirections_logged_status',true);
	if (empty($value)) {
		return;
	}

	return $value;
}

// Type
function siteseo_redirections_type(){
	if (is_home() && get_option( 'page_for_posts' ) !=''
	&& get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_type',true)) {
		return get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_type',true);
	} else {
		global $post;
		if (get_post_meta($post->ID,'_siteseo_redirections_type',true)) {
			return get_post_meta($post->ID,'_siteseo_redirections_type',true);
		}
	}
}

function siteseo_redirections_term_type() {
	if (!get_queried_object_id()) {
		return;
	}
	$value = get_term_meta(get_queried_object_id(),'_siteseo_redirections_type',true);
	if (empty($value)) {
		return;
	}

	return $value;
}

// URL to redirect
function siteseo_redirections_value() {
	global $post;
	
	if (is_singular() && get_post_meta($post->ID,'_siteseo_redirections_value',true)){
		$redirections_value = html_entity_decode(esc_url(get_post_meta($post->ID,'_siteseo_redirections_value',true)));
		return $redirections_value;
	} elseif (is_home() && get_option( 'page_for_posts' ) !='' && get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_value',true)) {
		$redirections_value = html_entity_decode(esc_url(get_post_meta(get_option( 'page_for_posts' ),'_siteseo_redirections_value',true)));
		return $redirections_value;
 	} elseif ((is_tax() || is_category() || is_tag()) && get_term_meta(get_queried_object_id(),'_siteseo_redirections_value',true) !='') {
		$redirections_value = html_entity_decode(esc_url(get_term_meta(get_queried_object_id(),'_siteseo_redirections_value',true)));
		return $redirections_value;
	} else {
		$redirections_value = basename(parse_url(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), PHP_URL_PATH));
		$siteseo_redirections_query = new WP_Query( array(
				'post_type' => 'siteseo_404',
				'posts_per_page' => '-1',
				'update_post_term_cache' => false, // don't retrieve post terms
				'update_post_meta_cache' => false, // don't retrieve post meta
			)
		);
		$all_titles = array();
		if ( $siteseo_redirections_query->have_posts() ) {
			while ( $siteseo_redirections_query->have_posts() ) {
				$siteseo_redirections_query->the_post();
				array_push($all_titles, get_the_title());
			}
			if (in_array($redirections_value, $all_titles)) {
				//do_redirect
				return $redirections_value;
			}
			wp_reset_postdata();
		}
	}
}

function siteseo_redirections_hook() {
	
	//If the current screen is: Elementor editor
	if ( class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		return;
	}

	//If the current screen is: Elementor preview mode
	if ( class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		return;
	}

	$metaValueByLoggedIn = \is_user_logged_in() ? 'only_logged_in' : 'only_not_logged_in';

	// Term
	if ((is_tax() || is_category() || is_tag()) && siteseo_redirections_term_enabled() =='yes') {
		if (siteseo_redirections_term_logged_status() === $metaValueByLoggedIn || siteseo_redirections_term_logged_status() === 'both' || empty(siteseo_redirections_term_logged_status())) {
			if (siteseo_redirections_term_type() && siteseo_redirections_value() !='') {
				wp_safe_redirect( siteseo_redirections_value(), siteseo_redirections_term_type() );
				exit();
			}
		}
	}
	
	// Post
	elseif (siteseo_redirections_enabled() =='yes') {
		if (siteseo_redirections_logged_status() === $metaValueByLoggedIn || siteseo_redirections_logged_status() === 'both' || empty(siteseo_redirections_logged_status())) {
			if (siteseo_redirections_type() && siteseo_redirections_value() !='') {
				wp_safe_redirect( siteseo_redirections_value(), siteseo_redirections_type() );
				exit();
			}
		}
	}
}
add_action('template_redirect', 'siteseo_redirections_hook', 1);
