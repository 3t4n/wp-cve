<?php

//////////////////////////////////////////////////////////////
//===========================================================
// SITESEO Core
//===========================================================
// SiteSEO
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:       23rd Jan 2020
// Time:       23:00 hrs
// Site:       http://siteseo.io.com/ (SiteSEO)
// ----------------------------------------------------------
// Please Read the Terms of use at http://siteseo.io/tos
// ----------------------------------------------------------
//===========================================================
// (c)SiteSEO Team
//===========================================================
//////////////////////////////////////////////////////////////

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

// Instant Indexing
if('1' == siteseo_get_toggle_option('instant-indexing')){
	require_once dirname(__FILE__) . '/options-instant-indexing.php';
}

// Import / Export tool
add_action('init', 'siteseo_enable', 999);
function siteseo_enable(){
	if (is_admin()) {
		require_once dirname(__FILE__) . '/options-import-export.php'; //Import Export
	}
}

// Front END
if('1' == siteseo_get_toggle_option('titles')){

	function siteseo_titles_disable_archives(){
		global $wp_query;
		
		$service = siteseo_get_service('TitleOption');
		
		if('1' == $service->getArchiveAuthorDisable() && $wp_query->is_author && ! is_feed()){
			wp_safe_redirect(get_home_url(), '301');
			exit;
		}
		if('1' == $service->getArchiveDateDisable() && $wp_query->is_date && ! is_feed()){
			wp_safe_redirect(get_home_url(), '301');
			exit;
		}

		return false;
	}

	// SEO metaboxes
	add_action('after_setup_theme', 'siteseo_hide_metaboxes');
	function siteseo_hide_metaboxes(){
		global $typenow, $pagenow;

		if(!is_admin()){
			return;
		}
		
		// Post type?
		if ('post-new.php' == $pagenow || 'post.php' == $pagenow){
			function siteseo_titles_single_enable_option(){
				global $post;
				
				$siteseo_get_current_cpt = get_post_type($post);

				$options = get_option('siteseo_titles_option_name');
				if(! empty($options)){
					
					if (isset($options['titles_single_titles'][$siteseo_get_current_cpt]['enable'])) {
						return $options['titles_single_titles'][$siteseo_get_current_cpt]['enable'];
					}
				}
			}
			
			function siteseo_titles_single_enable_metabox($siteseo_get_post_types){
				global $post;
				if (1 == siteseo_titles_single_enable_option() && '' != get_post_type($post)) {
					unset($siteseo_get_post_types[get_post_type($post)]);
				}

				return $siteseo_get_post_types;
			}

			add_filter('siteseo_metaboxe_seo', 'siteseo_titles_single_enable_metabox');
			add_filter('siteseo_metaboxe_content_analysis', 'siteseo_titles_single_enable_metabox');
			add_filter('siteseo_pro_metaboxe_sdt', 'siteseo_titles_single_enable_metabox');
		}

		// Taxonomy?
		if('term.php' == $pagenow || 'edit-tags.php' == $pagenow){
			if(! empty($_GET['taxonomy'])){
				
				function siteseo_tax_single_enable_option($siteseo_get_current_tax){
					$options = get_option('siteseo_titles_option_name');
					if(! empty($options)){
						if (isset($options['titles_tax_titles'][$siteseo_get_current_tax]['enable'])) {
							return $options['titles_tax_titles'][$siteseo_get_current_tax]['enable'];
						}
					}
				}

				function siteseo_tax_single_enable_metabox($siteseo_get_taxonomies){
					$siteseo_get_current_tax = esc_attr(siteseo_opt_get('taxonomy'));
					
					if (1 == siteseo_tax_single_enable_option($siteseo_get_current_tax) && '' != $siteseo_get_current_tax) {
						unset($siteseo_get_taxonomies[$siteseo_get_current_tax]);
					}

					return $siteseo_get_taxonomies;
				}
				
				add_filter('siteseo_metaboxe_term_seo', 'siteseo_tax_single_enable_metabox');
			}
		}
	}

	// Titles and metas
	add_action('template_redirect', 'siteseo_titles_disable_archives', 0);
	add_action('wp_head', 'siteseo_load_titles_options', 0);
	function siteseo_load_titles_options(){
		if(is_admin()){
			return;
		}
		
		if ((function_exists('is_wpforo_page') && is_wpforo_page()) || (class_exists('Ecwid_Store_Page') && Ecwid_Store_Page::is_store_page())) {
			//disable on wpForo pages to avoid conflicts
			//do nothing
		} else {
			require_once dirname(__FILE__) . '/options-titles-metas.php'; //Titles & metas
		}
	}
}

if('1' == siteseo_get_toggle_option('social')){
	
	add_action('init', 'siteseo_load_oembed_options');
	function siteseo_load_oembed_options(){
		
		if(is_admin()){
			return;
		}
		
		require_once dirname(__FILE__) . '/options-oembed.php'; //Oembed
	}

	add_action('wp_head', 'siteseo_load_social_options', 0);
	function siteseo_load_social_options(){
		
		if(is_admin()){
			return;
		}
		
		//disable on wpForo, LifterLMS private area, Ecwid store pages to avoid conflicts
		if((function_exists('is_llms_private_area') && is_llms_private_area()) || (function_exists('is_wpforo_page') && is_wpforo_page()) || (class_exists('Ecwid_Store_Page') && Ecwid_Store_Page::is_store_page())) {
			//do nothing
		} else {
			require_once dirname(__FILE__) . '/options-social.php'; //Social
		}
	}
}

if ('1' == siteseo_get_toggle_option('google-analytics')){

	// User Consent JS
	function siteseo_google_analytics_cookies_js(){
		$prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script('siteseo-cookies', SITESEO_ASSETS_DIR . '/js/siteseo-cookies' . $prefix . '.js', [], SITESEO_VERSION, true);
		wp_enqueue_script('siteseo-cookies');

		wp_enqueue_script('siteseo-cookies-ajax', SITESEO_ASSETS_DIR . '/js/siteseo-cookies-ajax' . $prefix . '.js', ['jquery', 'siteseo-cookies'], SITESEO_VERSION, true);

		$days = 30;

		if(siteseo_get_service('GoogleAnalyticsOption')->getCbExpDate()){
			$days = siteseo_get_service('GoogleAnalyticsOption')->getCbExpDate();
		}
		
		$days = apply_filters('siteseo_cookies_expiration_days', $days);

		$siteseo_cookies_user_consent = [
			'siteseo_nonce' => wp_create_nonce('siteseo_cookies_user_consent_nonce'),
			'siteseo_cookies_user_consent' => admin_url('admin-ajax.php'),
			'siteseo_cookies_expiration_days' => $days,
		];
		
		wp_localize_script('siteseo-cookies-ajax', 'siteseoAjaxGAUserConsent', $siteseo_cookies_user_consent);
	}

	// Triggers WooCommerce JS
	function siteseo_google_analytics_ecommerce_js(){
		$prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script('siteseo-analytics', plugins_url('assets/js/siteseo-analytics' . $prefix . '.js', dirname(dirname(__FILE__))), [], SITESEO_VERSION, true);

		$siteseo_analytics = [
			'siteseo_nonce' => wp_create_nonce('siteseo_analytics_nonce'),
			'siteseo_analytics' => admin_url('admin-ajax.php'),
		];
		
		wp_localize_script('siteseo-analytics', 'siteseoAjaxAnalytics', $siteseo_analytics);
	}

	// Ecommerce
	function siteseo_after_update_cart(){
		check_ajax_referer('siteseo_analytics_nonce');
		
		// Extract cart
		global $woocommerce;
		
		$items_purchased = [];
		$final = [];
		
		foreach($woocommerce->cart->get_cart() as $key => $item){
			
			$product = wc_get_product($item['product_id']);
			
			// Get current product
			if($product){
				
				// Set data
				$items_purchased['id'] = esc_js($product->get_id());
				$items_purchased['name'] = esc_js($product->get_title());
				$items_purchased['quantity'] = (float) esc_js($item['quantity']);
				$items_purchased['price'] = (float) esc_js($product->get_price());

				// Extract categories
				$categories = get_the_terms($product->get_id(), 'product_cat');
				if ($categories) {
					foreach ($categories as $category) {
						$categories_out[] = $category->name;
					}
					$categories_js = esc_js(implode('/', $categories_out));
					$items_purchased['category'] = esc_js($categories_js);
				}
			}
			
			$final[] = $items_purchased;
		}

		$html = "<script>gtag('event', 'add_to_cart', {'items': " . wp_json_encode($final) . ' });</script>';

		$html = apply_filters('siteseo_gtag_ec_add_to_cart_checkout_ev', $html);

		wp_send_json_success($html);
	}
	
	add_action('wp_ajax_siteseo_after_update_cart', 'siteseo_after_update_cart');
	add_action('wp_ajax_nopriv_siteseo_after_update_cart', 'siteseo_after_update_cart');

	if('1' == siteseo_get_service('GoogleAnalyticsOption')->getDisable()){
		if(is_user_logged_in()){
			global $wp_roles;

			//Get current user role
			if(isset(wp_get_current_user()->roles[0])){
				$siteseo_user_role = wp_get_current_user()->roles[0];
				//If current user role matchs values from SiteSEO GA settings then apply
				if('' != siteseo_get_service('GoogleAnalyticsOption')->getRoles()){
					if (array_key_exists($siteseo_user_role, siteseo_get_service('GoogleAnalyticsOption')->getRoles())) {
						//do nothing
					} else {
						add_action('wp_enqueue_scripts', 'siteseo_google_analytics_cookies_js', 20, 1);
					}
				} else {
					add_action('wp_enqueue_scripts', 'siteseo_google_analytics_cookies_js', 20, 1);
				}
			} else {
				add_action('wp_enqueue_scripts', 'siteseo_google_analytics_cookies_js', 20, 1);
			}
		} else {
			add_action('wp_enqueue_scripts', 'siteseo_google_analytics_cookies_js', 20, 1);
		}
	}

	add_action('wp_head', 'siteseo_load_google_analytics_options', 0);
	function siteseo_load_google_analytics_options(){
		require_once dirname(__FILE__) . '/options-google-analytics.php'; //Google Analytics + Matomo
	}

	function siteseo_cookies_user_consent(){
		//siteseo_check_ajax_referer( 'siteseo_cookies_user_consent_nonce');
		if ('1' == siteseo_get_service('GoogleAnalyticsOption')->getHalfDisable()) {//no user consent required
			wp_send_json_success();
		} else {
			if (is_user_logged_in()) {
				global $wp_roles;

				//Get current user role
				if (isset(wp_get_current_user()->roles[0])) {
					$siteseo_user_role = wp_get_current_user()->roles[0];
					//If current user role matchs values from SiteSEO GA settings then apply
					if('' != siteseo_get_service('GoogleAnalyticsOption')->getRoles()) {
						if(array_key_exists($siteseo_user_role, siteseo_get_service('GoogleAnalyticsOption')->getRoles())){
							//do nothing
						} else {
							include_once dirname(__FILE__) . '/options-google-analytics.php'; //Google Analytics
							$data					= [];
							$data['gtag_js'] 		= siteseo_google_analytics_js(false);
							$data['matomo_js'] 		= siteseo_matomo_js(false);
							$data['clarity_js'] 	= siteseo_clarity_js(false);
							$data['body_js'] 		= siteseo_google_analytics_body_code(false);
							$data['head_js'] 		= siteseo_google_analytics_head_code(false);
							$data['footer_js'] 		= siteseo_google_analytics_footer_code(false);
							$data['custom'] 		= '';
							$data['custom'] 		= apply_filters('siteseo_custom_tracking', $data['custom']);
							wp_send_json_success($data);
						}
					} else {
						include_once dirname(__FILE__) . '/options-google-analytics.php'; //Google Analytics
						$data						= [];
						$data['gtag_js']			= siteseo_google_analytics_js(false);
						$data['matomo_js']			= siteseo_matomo_js(false);
						$data['clarity_js']			= siteseo_clarity_js(false);
						$data['body_js']			= siteseo_google_analytics_body_code(false);
						$data['head_js'] 			= siteseo_google_analytics_head_code(false);
						$data['footer_js'] 			= siteseo_google_analytics_footer_code(false);
						$data['custom']				= '';
						$data['custom']				= apply_filters('siteseo_custom_tracking', $data['custom']);
						wp_send_json_success($data);
					}
				}
			} else {
				include_once dirname(__FILE__) . '/options-google-analytics.php'; //Google Analytics
				$data						= [];
				$data['gtag_js']			= siteseo_google_analytics_js(false);
				$data['matomo_js']			= siteseo_matomo_js(false);
				$data['clarity_js']			= siteseo_clarity_js(false);
				$data['body_js']			= siteseo_google_analytics_body_code(false);
				$data['head_js']			= siteseo_google_analytics_head_code(false);
				$data['footer_js']			= siteseo_google_analytics_footer_code(false);
				$data['custom']				= '';
				$data['custom']				= apply_filters('siteseo_custom_tracking', $data['custom']);
				wp_send_json_success($data);
			}
		}
	}
	add_action('wp_ajax_siteseo_cookies_user_consent', 'siteseo_cookies_user_consent');
	add_action('wp_ajax_nopriv_siteseo_cookies_user_consent', 'siteseo_cookies_user_consent');
}

add_action('wp', 'siteseo_load_redirections_options', 0);
function siteseo_load_redirections_options(){
	if (function_exists('is_plugin_active') && is_plugin_active('thrive-visual-editor/thrive-visual-editor.php') && is_admin()) {
		return;
	}
	
	if(! is_admin()){
		require_once dirname(__FILE__) . '/options-redirections.php'; //Redirections
	}
}

if('1' == siteseo_get_toggle_option('xml-sitemap')){
	add_action('init', 'siteseo_load_sitemap', 999);
	function siteseo_load_sitemap(){
		require_once dirname(__FILE__) . '/options-sitemap.php'; //XML / HTML Sitemap
	}
}
if('1' === siteseo_get_toggle_option('advanced')){
	//Remove comment author url
	function siteseo_advanced_advanced_comments_author_url_option(){
		$options = get_option('siteseo_advanced_option_name');
		if(!empty($options)){
			if(isset($options['advanced_comments_author_url'])) {
				return $options['advanced_comments_author_url'];
			}
		}
	}
	
	if ('1' == siteseo_advanced_advanced_comments_author_url_option()) {
		add_filter('get_comment_author_url', '__return_empty_string');
	}

	//Remove website field in comments
	function siteseo_advanced_advanced_comments_website_option(){
		$options = get_option('siteseo_advanced_option_name');
		if(! empty($options)){
			if(isset($options['advanced_comments_website'])) {
				return $options['advanced_comments_website'];
			}
		}
	}
	
	if('1' == siteseo_advanced_advanced_comments_website_option()){
		function siteseo_advanced_advanced_comments_website_hook($fields){
			unset($fields['url']);

			return $fields;
		}
		add_filter('comment_form_default_fields', 'siteseo_advanced_advanced_comments_website_hook', 40);
	}

	add_action('init', 'siteseo_load_advanced_options', 0);
	function siteseo_load_advanced_options(){
		if (! is_admin()) {
			// Advanced
			require_once dirname(__FILE__) . '/options-advanced.php';
		}
	}
	
	add_action('init', 'siteseo_load_advanced_admin_options', 11);
	function siteseo_load_advanced_admin_options(){
		
		// Advanced (admin)
		require_once dirname(__FILE__) . '/options-advanced-admin.php';
		
		//Admin bar
		function siteseo_advanced_appearance_adminbar_option(){
			$options = get_option('siteseo_advanced_option_name');
			if (!empty($options)){
				if(isset($options['appearance_adminbar'])){
					return $options['appearance_adminbar'];
				}
			}
		}

		if('' != siteseo_advanced_appearance_adminbar_option()){
			add_action('admin_bar_menu', 'siteseo_advanced_appearance_adminbar_hook', 999);

			function siteseo_advanced_appearance_adminbar_hook($wp_admin_bar){
				$wp_admin_bar->remove_node('siteseo');
			}
		}
	}

	//Add nofollow noopener noreferrer to comments form link
	function siteseo_advanced_advanced_comments_form_link_option(){
		$options = get_option('siteseo_advanced_option_name');
		if(!empty($options)) {
			if (isset($options['advanced_comments_form_link'])) {
				return $options['advanced_comments_form_link'];
			}
		}
	}
	
	if('1' == siteseo_advanced_advanced_comments_form_link_option()){
		/* Custom attributes on comment link */
		add_filter('comments_popup_link_attributes', 'siteseo_comments_popup_link_attributes');
		function siteseo_comments_popup_link_attributes($attr){
			$attr = 'rel="nofollow noopener noreferrer"';
			return $attr;
		}
	}

	//primary category
	function siteseo_titles_primary_cat_hook($cats_0, $cats, $post){
		$primary_cat	= null;

		if($post){
			$_siteseo_robots_primary_cat = get_post_meta($post->ID, '_siteseo_robots_primary_cat', true);
			if (isset($_siteseo_robots_primary_cat) && '' != $_siteseo_robots_primary_cat && 'none' != $_siteseo_robots_primary_cat){
				if (null != $post->post_type && 'post' == $post->post_type) {
					$primary_cat = get_category($_siteseo_robots_primary_cat);
				}
				if (! is_wp_error($primary_cat) && null != $primary_cat) {
					return $primary_cat;
				}
			} else {
				//no primary cat
				return $cats_0;
			}
		} else {
			return $cats_0;
		}
	}
	add_filter('post_link_category', 'siteseo_titles_primary_cat_hook', 10, 3);

	function siteseo_titles_primary_wc_cat_hook($terms_0, $terms, $post){
		$primary_cat	= null;

		if($post){
			$_siteseo_robots_primary_cat = get_post_meta($post->ID, '_siteseo_robots_primary_cat', true);

			if (isset($_siteseo_robots_primary_cat) && '' != $_siteseo_robots_primary_cat && 'none' != $_siteseo_robots_primary_cat) {
				if (null != $post->post_type && 'product' == $post->post_type) {
					$primary_cat = get_term($_siteseo_robots_primary_cat, 'product_cat');
				}
				if (! is_wp_error($primary_cat) && null != $primary_cat) {
					return $primary_cat;
				}
			} else {
				//no primary cat
				return $terms_0;
			}
		} else {
			return $terms_0;
		}
	}
	add_filter('wc_product_post_type_link_product_cat', 'siteseo_titles_primary_wc_cat_hook', 10, 3);

	//No /category/ in URL
	function siteseo_advanced_advanced_category_url_option(){
		$options = get_option('siteseo_advanced_option_name');
		if(! empty($options) && isset($options['advanced_category_url'])) {
			return $options['advanced_category_url'];
		}
	}

	if('' != siteseo_advanced_advanced_category_url_option()){
		//Flush permalinks when creating/editing/deleting post categories
		add_action('created_category', 'flush_rewrite_rules');
		add_action('delete_category', 'flush_rewrite_rules');
		add_action('edited_category', 'flush_rewrite_rules');

		//@credits : WordPress VIP
		add_filter('category_rewrite_rules', 'siteseo_filter_category_rewrite_rules');
		function siteseo_filter_category_rewrite_rules($rules){
			
			if(class_exists('Sitepress')){
				global $sitepress;
				remove_filter('terms_clauses', [$sitepress, 'terms_clauses']);
				$categories = get_categories(['hide_empty' => false]);
				add_filter('terms_clauses', [$sitepress, 'terms_clauses'], 10, 4);
			}else{
				$categories = get_categories(['hide_empty' => false]);
			}
			
			if(is_array($categories) && ! empty($categories)){
				$slugs = [];

				foreach ($categories as $category) {
					if (is_object($category) && ! is_wp_error($category)) {
						if (0 == $category->category_parent) {
							$slugs[] = $category->slug;
						} else {
							$slugs[] = trim(get_category_parents($category->term_id, false, '/', true), '/');
						}
					}
				}

				if (! empty($slugs)) {
					$rules = [];

					foreach ($slugs as $slug) {
						$rules['(' . $slug . ')/feed/(feed|rdf|rss|rss2|atom)?/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
						$rules['(' . $slug . ')/(feed|rdf|rss|rss2|atom)/?$']	   = 'index.php?category_name=$matches[1]&feed=$matches[2]';
						$rules['(' . $slug . ')(/page/(\d+))?/?$']				  = 'index.php?category_name=$matches[1]&paged=$matches[3]';
					}
				}
			}
			
			$rules = apply_filters('siteseo_category_rewrite_rules', $rules);

			return $rules;
		}
		
		add_filter('term_link', 'siteseo_remove_category_base', 10, 3);
		function siteseo_remove_category_base($termlink, $term, $taxonomy){
			if ('category' == $taxonomy) {
				$category_base = get_option('category_base');

				if (class_exists('Sitepress') && defined('ICL_LANGUAGE_CODE')) {
					$category_base = apply_filters('wpml_translate_single_string', 'category', 'WordPress', 'URL category tax slug', ICL_LANGUAGE_CODE);
				}

				if ('' == $category_base) {
					$category_base = 'category';
				}

				$category_base = apply_filters('siteseo_remove_category_base', $category_base);

				if ('/' == substr($category_base, 0, 1)) {
					$category_base = substr($category_base, 1);
				}
				$category_base .= '/';

				return preg_replace('`' . preg_quote($category_base, '`') . '`u', '', $termlink, 1);
			} else {
				return $termlink;
			}
		}

		add_action('template_redirect', 'siteseo_category_redirect', 1);
		function siteseo_category_redirect(){
			if (!is_category()) {
				return;
			}
			global $wp;

			$current_url = user_trailingslashit(home_url(add_query_arg([], $wp->request)));

			$category_base = get_option('category_base');

			if (class_exists('Sitepress') && defined('ICL_LANGUAGE_CODE')) {
				$category_base = apply_filters('wpml_translate_single_string', 'category', 'WordPress', 'URL category tax slug', ICL_LANGUAGE_CODE);
			}

			$category_base = apply_filters('siteseo_remove_category_base', $category_base);

			if('' != $category_base){
				$regex = sprintf('/\/%s\//', str_replace('/', '\/', $category_base));
				if (preg_match($regex, $current_url)) {
					$new_url = str_replace('/' . $category_base, '', $current_url);
					wp_safe_redirect($new_url, 301);
					exit();
				}
			}else{
				$category_base = 'category';
				$regex		 = sprintf('/\/%s\//', str_replace('/', '\/', $category_base));
				if (preg_match($regex, $current_url)) {
					$new_url = str_replace('/' . $category_base, '', $current_url);
					wp_safe_redirect($new_url, 301);
					exit();
				}
			}
		}
	}

	//No /product-category/ in URL
	function siteseo_advanced_advanced_product_category_url_option(){
		$options = get_option('siteseo_advanced_option_name');
		if(! empty($options) && isset($options['advanced_product_cat_url'])) {
			return $options['advanced_product_cat_url'];
		}
	}

	if('' != siteseo_advanced_advanced_product_category_url_option()){
		
		//Flush permalinks when creating/editing/deleting product categories
		add_action('created_product_cat', 'flush_rewrite_rules');
		add_action('delete_product_cat', 'flush_rewrite_rules');
		add_action('edited_product_cat', 'flush_rewrite_rules');

		add_filter('product_cat_rewrite_rules', 'siteseo_filter_product_category_rewrite_rules');
		function siteseo_filter_product_category_rewrite_rules($rules){
			
			if (class_exists('Sitepress')) {
				global $sitepress;
				remove_filter('terms_clauses', [$sitepress, 'terms_clauses']);
				$categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
				add_filter('terms_clauses', [$sitepress, 'terms_clauses'], 10, 4);
			} else {
				$categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
			}
			
			if(is_array($categories) && ! empty($categories)){
				$slugs = [];

				foreach ($categories as $category) {
					if (is_object($category) && ! is_wp_error($category)) {
						if (0 == $category->parent) {
							$slugs[] = $category->slug;
						} else {
							$slugs[] = trim(get_term_parents_list($category->term_id, 'product_cat', ['separator' => '/', 'link' => false]), '/');
						}
					}
				}

				if (! empty($slugs)) {
					$rules = [];
					foreach ($slugs as $slug) {
						$rules['(' . $slug . ')(/page/(\d+))?/?$']				  = 'index.php?product_cat=$matches[1]&paged=$matches[3]';
						$rules[$slug . '/(.+?)/page/?([0-9]{1,})/?$']				= 'index.php?product_cat=$matches[1]&paged=$matches[2]';
						$rules[$slug . '/(.+?)/?$']								  = 'index.php?product_cat=$matches[1]';

						$rules[$slug . '/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?product_cat=$matches[1]&feed=$matches[2]';
						$rules[$slug . '/(.+?)/(feed|rdf|rss|rss2|atom)/?$']	  = 'index.php?product_cat=$matches[1]&feed=$matches[2]';
						$rules[$slug . '/(.+?)/embed/?$']						 = 'index.php?product_cat=$matches[1]&embed=true';
					}
				}
			}
			$rules = apply_filters('siteseo_product_category_rewrite_rules', $rules);

			return $rules;
		}

		add_filter('term_link', 'siteseo_remove_product_category_base', 10, 3);
		function siteseo_remove_product_category_base($termlink, $term, $taxonomy){
			
			if('product_cat' == $taxonomy){
				$category_base = get_option('woocommerce_permalinks');
				$category_base = $category_base['category_base'];

				if(class_exists('Sitepress') && defined('ICL_LANGUAGE_CODE')){
					$category_base = apply_filters('wpml_translate_single_string', 'product_cat', 'WordPress', 'URL product category tax slug', ICL_LANGUAGE_CODE);
				}

				if ('' == $category_base) {
					$category_base = 'product-category';
				}

				$category_base = apply_filters('siteseo_remove_product_category_base', $category_base);

				if ('/' == substr($category_base, 0, 1)) {
					$category_base = substr($category_base, 1);
				}
				$category_base .= '/';

				return preg_replace('`' . preg_quote($category_base, '`') . '`u', '', $termlink, 1);
			} else {
				return $termlink;
			}
		}

		add_action('template_redirect', 'siteseo_product_category_redirect', 1);
		function siteseo_product_category_redirect(){
			global $wp;

			$current_url = user_trailingslashit(home_url(add_query_arg([], $wp->request)));

			$category_base = get_option('woocommerce_permalinks');
			$category_base = $category_base['category_base'];

			if (class_exists('Sitepress') && defined('ICL_LANGUAGE_CODE')) {
				$category_base = apply_filters('wpml_translate_single_string', 'product_cat', 'WordPress', 'URL product category tax slug', ICL_LANGUAGE_CODE);
			}

			$category_base = apply_filters('siteseo_remove_product_category_base', $category_base);

			if ('' != $category_base) {
				if (preg_match('/\/' . $category_base . '\//', $current_url)) {
					$new_url = str_replace('/' . $category_base, '', $current_url);
					wp_safe_redirect($new_url, 301);
					exit();
				}
			} else {
				$category_base = 'product-category';

				if (preg_match('/\/' . $category_base . '\//', $current_url)) {
					$new_url = str_replace('/' . $category_base, '', $current_url);
					wp_safe_redirect($new_url, 301);
					exit();
				}
			}
		}
	}
}
