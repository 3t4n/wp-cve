<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Titles & metas
// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

include_once ABSPATH . 'wp-admin/includes/plugin.php';

//Single CPT Titles
function siteseo_titles_single_titles_option() {
	
	$get_current_cpt = get_post_type();

	$options = siteseo_get_service('TitleOption')->searchOptionByKey('titles_single_titles');
	if( ! empty($options) && isset($options[$get_current_cpt]['title'])) {
		return $options[$get_current_cpt]['title'];
	}
}

//Tax archives Titles
function siteseo_titles_tax_titles_option() {
	$queried_object = get_queried_object();
	$siteseo_get_current_tax = null !== $queried_object ? $queried_object->taxonomy : '';

	$options = siteseo_get_service('TitleOption')->searchOptionByKey('titles_tax_titles');
	if( ! empty($options) && isset($options[$siteseo_get_current_tax]['title'])){
		return $options[$siteseo_get_current_tax]['title'];
	}
}

//Single CPT Description
function siteseo_titles_single_desc_option(){
	
	$siteseo_get_current_cpt = get_post_type();

	$options = siteseo_get_service('TitleOption')->searchOptionByKey('titles_single_titles');
	if( ! empty($options) && isset($options[$siteseo_get_current_cpt]['description'])){
		return $options[$siteseo_get_current_cpt]['description'];
	}
}

//Archive CPT Description
function siteseo_titles_archive_desc_option() {
	$siteseo_get_current_cpt = get_post_type();

	$options = siteseo_get_service('TitleOption')->searchOptionByKey('titles_archive_titles');
	if ( ! empty($options) && isset($options[$siteseo_get_current_cpt]['description'])) {
		return $options[$siteseo_get_current_cpt]['description'];
	}
}

//Tax archives Desc
function siteseo_titles_tax_desc_option() {
	$queried_object = get_queried_object();
	$siteseo_get_current_tax = $queried_object->taxonomy;

	$options = siteseo_get_service('TitleOption')->searchOptionByKey('titles_archive_titles');
	if(!empty($options) && isset($options[$siteseo_get_current_tax]['description'])) {
		return $options[$siteseo_get_current_tax]['description'];
	}
}

//THE Title Tag
function siteseo_titles_the_title() {
	$variables = null;
	$variables = apply_filters('siteseo_dyn_variables_fn', $variables);

	$post									= $variables['post'];
	$term									= $variables['term'];
	$siteseo_titles_title_template		  	= $variables['siteseo_titles_title_template'];
	$siteseo_titles_description_template	= $variables['siteseo_titles_description_template'];
	$siteseo_paged							= $variables['siteseo_paged'];
	$the_author_meta						= $variables['the_author_meta'];
	$sep									= $variables['sep'];
	$siteseo_excerpt						= $variables['siteseo_excerpt'];
	$post_category							= $variables['post_category'];
	$post_tag								= $variables['post_tag'];
	$get_search_query						= $variables['get_search_query'];
	$woo_single_cat_html					= $variables['woo_single_cat_html'];
	$woo_single_tag_html					= $variables['woo_single_tag_html'];
	$woo_single_price						= $variables['woo_single_price'];
	$woo_single_price_exc_tax				= $variables['woo_single_price_exc_tax'];
	$woo_single_sku							= $variables['woo_single_sku'];
	$author_bio								= $variables['author_bio'];
	$siteseo_get_the_excerpt				= $variables['siteseo_get_the_excerpt'];
	$siteseo_titles_template_variables_array = $variables['siteseo_titles_template_variables_array'];
	$siteseo_titles_template_replace_array	= $variables['siteseo_titles_template_replace_array'];
	$siteseo_excerpt_length					= $variables['siteseo_excerpt_length'];
	$page_id								= get_option('page_for_posts');

	$getHomeSiteTitle = siteseo_get_service('TitleOption')->getHomeSiteTitle();
	$getArchivesAuthorTitle = siteseo_get_service('TitleOption')->getArchivesAuthorTitle();
	
	if (is_front_page() && is_home() && isset($post) && '' == get_post_meta($post->ID, '_siteseo_titles_title', true)) { //HOMEPAGE
		if ('' != $getHomeSiteTitle) {
			$siteseo_titles_the_title = esc_attr($getHomeSiteTitle);

			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_title);
		}
	} elseif (is_front_page() && isset($post) && '' == get_post_meta($post->ID, '_siteseo_titles_title', true)) { //STATIC HOMEPAGE
		if ('' != $getHomeSiteTitle) {
			$siteseo_titles_the_title = esc_attr($getHomeSiteTitle);

			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_title);
		}
	} elseif (is_home() && '' != get_post_meta($page_id, '_siteseo_titles_title', true)) { //BLOG PAGE
		if (get_post_meta($page_id, '_siteseo_titles_title', true)) { //IS METABOXE
			$siteseo_titles_the_title = esc_attr(get_post_meta($page_id, '_siteseo_titles_title', true));

			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_title);
		}
	} elseif (is_home() && ('posts' == get_option('show_on_front'))) { //YOUR LATEST POSTS
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if (is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php')) {
		}
		if ('' != $getHomeSiteTitle) {
			$siteseo_titles_the_title = esc_attr($getHomeSiteTitle);

			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_title);
		}
	} elseif (function_exists('bp_is_group') && bp_is_group()) {
		if ('' != siteseo_get_service('TitleOption')->getTitleBpGroups()) {
			$siteseo_titles_the_title = esc_attr(siteseo_get_service('TitleOption')->getTitleBpGroups());

			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_title);
		}
	} elseif (is_singular()) { //IS SINGULAR
		//IS BUDDYPRESS ACTIVITY PAGE
		if (function_exists('bp_is_current_component') && true == bp_is_current_component('activity')) {
			$post->ID = buddypress()->pages->activity->id;
		}
		//IS BUDDYPRESS MEMBERS PAGE
		if (function_exists('bp_is_current_component') && true == bp_is_current_component('members')) {
			$post->ID = buddypress()->pages->members->id;
		}

		//IS BUDDYPRESS GROUPS PAGE
		if (function_exists('bp_is_current_component') && true == bp_is_current_component('groups')) {
			$post->ID = buddypress()->pages->groups->id;
		}

		if (get_post_meta($post->ID, '_siteseo_titles_title', true)) { //IS METABOXE
			$siteseo_titles_the_title = esc_attr(get_post_meta($post->ID, '_siteseo_titles_title', true));

			preg_match_all('/%%_cf_(.*?)%%/', $siteseo_titles_the_title, $matches); //custom fields

			if ( ! empty($matches)) {
				$siteseo_titles_cf_template_variables_array = [];
				$siteseo_titles_cf_template_replace_array   = [];

				foreach ($matches['0'] as $key => $value) {
					$siteseo_titles_cf_template_variables_array[] = $value;
				}

				foreach ($matches['1'] as $key => $value) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				}
			}

			preg_match_all('/%%_ct_(.*?)%%/', $siteseo_titles_the_title, $matches2); //custom terms taxonomy

			if ( ! empty($matches2)) {
				$siteseo_titles_ct_template_variables_array = [];
				$siteseo_titles_ct_template_replace_array   = [];

				foreach ($matches2['0'] as $key => $value) {
					$siteseo_titles_ct_template_variables_array[] = $value;
				}

				foreach ($matches2['1'] as $key => $value) {
					$term = wp_get_post_terms($post->ID, $value);
					if ( ! is_wp_error($term)) {
						$terms									   = esc_attr($term[0]->name);
						$siteseo_titles_ct_template_replace_array[] = apply_filters('siteseo_titles_custom_tax', $terms, $value);
					}
				}
			}

			preg_match_all('/%%_ucf_(.*?)%%/', $siteseo_titles_the_title, $matches3); //user meta

			if ( ! empty($matches3)) {
				$siteseo_titles_ucf_template_variables_array = [];
				$siteseo_titles_ucf_template_replace_array   = [];

				foreach ($matches3['0'] as $key => $value) {
					$siteseo_titles_ucf_template_variables_array[] = $value;
				}

				foreach ($matches3['1'] as $key => $value) {
					$siteseo_titles_ucf_template_replace_array[] = esc_attr(get_user_meta(get_current_user_id(), $value, true));
				}
			}

			//Default
			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_title);

			//Custom fields
			if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
				$siteseo_titles_title_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_title_template);
			}

			//Custom terms taxonomy
			if ( ! empty($matches2) && ! empty($siteseo_titles_ct_template_variables_array) && ! empty($siteseo_titles_ct_template_replace_array)) {
				$siteseo_titles_title_template = str_replace($siteseo_titles_ct_template_variables_array, $siteseo_titles_ct_template_replace_array, $siteseo_titles_title_template);
			}

			//User meta
			if ( ! empty($matches3) && ! empty($siteseo_titles_ucf_template_variables_array) && ! empty($siteseo_titles_ucf_template_replace_array)) {
				$siteseo_titles_title_template = str_replace($siteseo_titles_ucf_template_variables_array, $siteseo_titles_ucf_template_replace_array, $siteseo_titles_title_template);
			}
		} else { //DEFAULT GLOBAL
			$siteseo_titles_single_titles_option = esc_attr(siteseo_titles_single_titles_option());

			preg_match_all('/%%_cf_(.*?)%%/', $siteseo_titles_single_titles_option, $matches); //custom fields

			if ( ! empty($matches)) {
				$siteseo_titles_cf_template_variables_array = [];
				$siteseo_titles_cf_template_replace_array   = [];

				foreach ($matches['0'] as $key => $value) {
					$siteseo_titles_cf_template_variables_array[] = $value;
				}

				foreach ($matches['1'] as $key => $value) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				}
			}

			preg_match_all('/%%_ct_(.*?)%%/', $siteseo_titles_single_titles_option, $matches2); //custom terms taxonomy

			if ( ! empty($matches2)) {
				$siteseo_titles_ct_template_variables_array = [];
				$siteseo_titles_ct_template_replace_array   = [];

				foreach ($matches2['0'] as $key => $value) {
					$siteseo_titles_ct_template_variables_array[] = $value;
				}

				foreach ($matches2['1'] as $key => $value) {
					$term = wp_get_post_terms($post->ID, $value);
					if ( ! is_wp_error($term) && isset($term[0])) {
						$terms									   = esc_attr($term[0]->name);
						$siteseo_titles_ct_template_replace_array[] = apply_filters('siteseo_titles_custom_tax', $terms, $value);
					}
				}
			}

			preg_match_all('/%%_ucf_(.*?)%%/', $siteseo_titles_single_titles_option, $matches3); //user meta

			if ( ! empty($matches3)) {
				$siteseo_titles_ucf_template_variables_array = [];
				$siteseo_titles_ucf_template_replace_array   = [];

				foreach ($matches3['0'] as $key => $value) {
					$siteseo_titles_ucf_template_variables_array[] = $value;
				}

				foreach ($matches3['1'] as $key => $value) {
					$siteseo_titles_ucf_template_replace_array[] = esc_attr(get_user_meta(get_current_user_id(), $value, true));
				}
			}

			//Default
			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_single_titles_option);

			// Custom fields
			if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
				$siteseo_titles_title_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_title_template);
			}

			// Custom terms taxonomy
			if ( ! empty($matches2) && ! empty($siteseo_titles_ct_template_variables_array) && ! empty($siteseo_titles_ct_template_replace_array)) {
				$siteseo_titles_title_template = str_replace($siteseo_titles_ct_template_variables_array, $siteseo_titles_ct_template_replace_array, $siteseo_titles_title_template);
			}

			// User meta
			if ( ! empty($matches3) && ! empty($siteseo_titles_ucf_template_variables_array) && ! empty($siteseo_titles_ucf_template_replace_array)) {
				$siteseo_titles_title_template = str_replace($siteseo_titles_ucf_template_variables_array, $siteseo_titles_ucf_template_replace_array, $siteseo_titles_title_template);
			}
		}
	} elseif (is_post_type_archive() && !is_tax() && siteseo_titles_archive_titles_option()) { //IS POST TYPE ARCHIVE (!is_tax required for TEC)
		$siteseo_titles_archive_titles_option = esc_attr(siteseo_titles_archive_titles_option());

		$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_archive_titles_option);
	} elseif ((is_tax() || is_category() || is_tag()) && siteseo_titles_tax_titles_option()) { //IS TAX
		$siteseo_titles_tax_titles_option = esc_attr(siteseo_titles_tax_titles_option());

		if (get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_titles_title', true)) {
			$siteseo_titles_title_template = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_titles_title', true));
			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_title_template);
		} else {
			$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_tax_titles_option);
		}

		preg_match_all('/%%_cf_(.*?)%%/', $siteseo_titles_title_template, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				$siteseo_titles_cf_template_replace_array[] = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, $value, true));
			}
		}

		// Custom fields
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_titles_title_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_title_template);
		}
	} elseif (is_author() && $getArchivesAuthorTitle) { //IS AUTHOR
		$siteseo_titles_archives_author_title_option = esc_attr($getArchivesAuthorTitle);

		preg_match_all('/%%_ucf_(.*?)%%/', $siteseo_titles_archives_author_title_option, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				$siteseo_titles_cf_template_replace_array[] = esc_attr(get_user_meta(get_current_user_id(), $value, true));
			}
		}

		// Default
		$siteseo_titles_title_template = esc_attr($getArchivesAuthorTitle);

		// User meta
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_titles_title_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_title_template);
		}

		$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_title_template);
	} elseif (is_date() && siteseo_get_service('TitleOption')->getTitleArchivesDate()) { //IS DATE
		$siteseo_titles_archives_date_title_option = esc_attr(siteseo_get_service('TitleOption')->getTitleArchivesDate());

		$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_archives_date_title_option);
	} elseif (is_search() && siteseo_get_service('TitleOption')->getTitleArchivesSearch()) { //IS SEARCH
		$siteseo_titles_archives_search_title_option = esc_attr(siteseo_get_service('TitleOption')->getTitleArchivesSearch());

		$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_archives_search_title_option);
	} elseif (is_404() && siteseo_get_service('TitleOption')->getTitleArchives404()) { //IS 404
		$siteseo_titles_archives_404_title_option = esc_attr(siteseo_get_service('TitleOption')->getTitleArchives404());

		$siteseo_titles_title_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_archives_404_title_option);
	}

	// Hook on Title tag - 'siteseo_titles_title'
	if (has_filter('siteseo_titles_title')) {
		$siteseo_titles_title_template = apply_filters('siteseo_titles_title', $siteseo_titles_title_template);
	}

	// Return Title tag
	return $siteseo_titles_title_template;
}

if (apply_filters('siteseo_old_pre_get_document_title', true)) {
	$priority = apply_filters( 'siteseo_titles_the_title_priority', 10 );
	add_filter('pre_get_document_title', 'siteseo_titles_the_title', $priority);

	// Avoid TEC rewriting our title tag on Venue and Organizer pages
	if (is_plugin_active('the-events-calendar/the-events-calendar.php')) {
		if (
			function_exists('tribe_is_event') && tribe_is_event() ||
			function_exists('tribe_is_venue') && tribe_is_venue() ||
			function_exists('tribe_is_organizer') && tribe_is_organizer()
			// function_exists('tribe_is_month') && tribe_is_month() && is_tax() ||
			// function_exists('tribe_is_upcoming') && tribe_is_upcoming() && is_tax() ||
			// function_exists('tribe_is_past') && tribe_is_past() && is_tax() ||
			// function_exists('tribe_is_week') && tribe_is_week() && is_tax() ||
			// function_exists('tribe_is_day') && tribe_is_day() && is_tax() ||
			// function_exists('tribe_is_map') && tribe_is_map() && is_tax() ||
			// function_exists('tribe_is_photo') && tribe_is_photo() && is_tax()
		) {
			add_filter('pre_get_document_title', 'siteseo_titles_the_title', 20);
		}
	}
}

// THE Meta Description
function siteseo_titles_the_description_content() {
	$variables = null;
	$variables = apply_filters('siteseo_dyn_variables_fn', $variables);

	$post											= $variables['post'];
	$term											= $variables['term'];
	$siteseo_titles_title_template					= $variables['siteseo_titles_title_template'];
	$siteseo_titles_description_template			= $variables['siteseo_titles_description_template'];
	$siteseo_paged 									= $variables['siteseo_paged'];
	$the_author_meta 								= $variables['the_author_meta'];
	$sep 											= $variables['sep'];
	$siteseo_excerpt 								= $variables['siteseo_excerpt'];
	$post_category 									= $variables['post_category'];
	$post_tag 										= $variables['post_tag'];
	$post_thumbnail_url 							= $variables['post_thumbnail_url'];
	$get_search_query 								= $variables['get_search_query'];
	$woo_single_cat_html 							= $variables['woo_single_cat_html'];
	$woo_single_tag_html 							= $variables['woo_single_tag_html'];
	$woo_single_price 								= $variables['woo_single_price'];
	$woo_single_price_exc_tax						= $variables['woo_single_price_exc_tax'];
	$woo_single_sku 								= $variables['woo_single_sku'];
	$author_bio 									= $variables['author_bio'];
	$siteseo_get_the_excerpt 						= $variables['siteseo_get_the_excerpt'];
	$siteseo_titles_template_variables_array		= $variables['siteseo_titles_template_variables_array'];
	$siteseo_titles_template_replace_array			= $variables['siteseo_titles_template_replace_array'];
	$siteseo_excerpt_length 						= $variables['siteseo_excerpt_length'];
	$page_id 										= get_option('page_for_posts');
	
	$descriptionOption = siteseo_get_service('TitleOption')->getHomeDescriptionTitle();
	
	if (is_front_page() && is_home() && isset($post) && '' == get_post_meta($post->ID, '_siteseo_titles_desc', true)) { // HOMEPAGE
		if ('' != $descriptionOption) { //IS GLOBAL
			$siteseo_titles_the_description = esc_attr($descriptionOption);

			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
		}
	} elseif (is_front_page() && isset($post) && '' == get_post_meta($post->ID, '_siteseo_titles_desc', true)) { // STATIC HOMEPAGE
		if ('' != $descriptionOption && '' == get_post_meta($post->ID, '_siteseo_titles_desc', true)) { // IS GLOBAL
			$siteseo_titles_the_description = esc_attr($descriptionOption);

			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
		}
	} elseif (is_home() && '' != get_post_meta($page_id, '_siteseo_titles_desc', true)) { //BLOG PAGE
		if (get_post_meta($page_id, '_siteseo_titles_desc', true)) {
			$siteseo_titles_the_description_meta = esc_html(get_post_meta($page_id, '_siteseo_titles_desc', true));
			$siteseo_titles_the_description	  = $siteseo_titles_the_description_meta;

			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
		}
	} elseif (is_home() && ('posts' == get_option('show_on_front'))) { //YOUR LATEST POSTS
		if ('' != $descriptionOption) { //IS GLOBAL
			$siteseo_titles_the_description = esc_attr($descriptionOption);

			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
		}
	} elseif (function_exists('bp_is_group') && bp_is_group()) {
		if ('' != siteseo_get_service('TitleOption')->getBpGroupsDesc()) {
			$siteseo_titles_the_description = esc_attr(siteseo_get_service('TitleOption')->getBpGroupsDesc());

			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
		}
	} elseif (is_singular()) { //IS SINGLE
		if (get_post_meta($post->ID, '_siteseo_titles_desc', true)) { //IS METABOXE
			$siteseo_titles_the_description = esc_attr(get_post_meta($post->ID, '_siteseo_titles_desc', true));

			preg_match_all('/%%_cf_(.*?)%%/', $siteseo_titles_the_description, $matches); //custom fields

			if ( ! empty($matches)) {
				$siteseo_titles_cf_template_variables_array = [];
				$siteseo_titles_cf_template_replace_array   = [];

				foreach ($matches['0'] as $key => $value) {
					$siteseo_titles_cf_template_variables_array[] = $value;
				}

				foreach ($matches['1'] as $key => $value) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				}
			}

			preg_match_all('/%%_ct_(.*?)%%/', $siteseo_titles_the_description, $matches2); //custom terms taxonomy

			if ( ! empty($matches2)) {
				$siteseo_titles_ct_template_variables_array = [];
				$siteseo_titles_ct_template_replace_array   = [];

				foreach ($matches2['0'] as $key => $value) {
					$siteseo_titles_ct_template_variables_array[] = $value;
				}

				foreach ($matches2['1'] as $key => $value) {
					$term = wp_get_post_terms($post->ID, $value);
					if ( ! is_wp_error($term)) {
						$terms									   = esc_attr($term[0]->name);
						$siteseo_titles_ct_template_replace_array[] = apply_filters('siteseo_titles_custom_tax', $terms, $value);
					}
				}
			}

			preg_match_all('/%%_ucf_(.*?)%%/', $siteseo_titles_the_description, $matches3); //user meta

			if ( ! empty($matches3)) {
				$siteseo_titles_ucf_template_variables_array = [];
				$siteseo_titles_ucf_template_replace_array   = [];

				foreach ($matches3['0'] as $key => $value) {
					$siteseo_titles_ucf_template_variables_array[] = $value;
				}

				foreach ($matches3['1'] as $key => $value) {
					$siteseo_titles_ucf_template_replace_array[] = esc_attr(get_user_meta(get_current_user_id(), $value, true));
				}
			}

			// Default
			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);

			// Custom fields
			if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
				$siteseo_titles_description_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_description_template);
			}

			// Custom terms taxonomy
			if ( ! empty($matches2) && ! empty($siteseo_titles_ct_template_variables_array) && ! empty($siteseo_titles_ct_template_replace_array)) {
				$siteseo_titles_description_template = str_replace($siteseo_titles_ct_template_variables_array, $siteseo_titles_ct_template_replace_array, $siteseo_titles_description_template);
			}

			// User meta
			if ( ! empty($matches3) && ! empty($siteseo_titles_ucf_template_variables_array) && ! empty($siteseo_titles_ucf_template_replace_array)) {
				$siteseo_titles_description_template = str_replace($siteseo_titles_ucf_template_variables_array, $siteseo_titles_ucf_template_replace_array, $siteseo_titles_description_template);
			}
		} elseif ('' != siteseo_titles_single_desc_option()) { //IS GLOBAL
			$siteseo_titles_the_description = esc_attr(siteseo_titles_single_desc_option());

			preg_match_all('/%%_cf_(.*?)%%/', $siteseo_titles_the_description, $matches); //custom fields

			if ( ! empty($matches)) {
				$siteseo_titles_cf_template_variables_array = [];
				$siteseo_titles_cf_template_replace_array   = [];

				foreach ($matches['0'] as $key => $value) {
					$siteseo_titles_cf_template_variables_array[] = $value;
				}

				foreach ($matches['1'] as $key => $value) {
					$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
				}
			}

			preg_match_all('/%%_ct_(.*?)%%/', $siteseo_titles_the_description, $matches2); //custom terms taxonomy

			if ( ! empty($matches2)) {
				$siteseo_titles_ct_template_variables_array = [];
				$siteseo_titles_ct_template_replace_array   = [];

				foreach ($matches2['0'] as $key => $value) {
					$siteseo_titles_ct_template_variables_array[] = $value;
				}

				foreach ($matches2['1'] as $key => $value) {
					$term = wp_get_post_terms($post->ID, $value);
					if ( ! is_wp_error($term)) {
						$terms									   = esc_attr($term[0]->name);
						$siteseo_titles_ct_template_replace_array[] = apply_filters('siteseo_titles_custom_tax', $terms, $value);
					}
				}
			}

			preg_match_all('/%%_ucf_(.*?)%%/', $siteseo_titles_the_description, $matches3); //user meta

			if ( ! empty($matches3)) {
				$siteseo_titles_ucf_template_variables_array = [];
				$siteseo_titles_ucf_template_replace_array   = [];

				foreach ($matches3['0'] as $key => $value) {
					$siteseo_titles_ucf_template_variables_array[] = $value;
				}

				foreach ($matches3['1'] as $key => $value) {
					$siteseo_titles_ucf_template_replace_array[] = esc_attr(get_user_meta(get_current_user_id(), $value, true));
				}
			}

			// Default
			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);

			// Custom fields
			if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
				$siteseo_titles_description_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_description_template);
			}

			// Custom terms taxonomy
			if ( ! empty($matches2) && ! empty($siteseo_titles_ct_template_variables_array) && ! empty($siteseo_titles_ct_template_replace_array)) {
				$siteseo_titles_description_template = str_replace($siteseo_titles_ct_template_variables_array, $siteseo_titles_ct_template_replace_array, $siteseo_titles_description_template);
			}

			// User meta
			if ( ! empty($matches3) && ! empty($siteseo_titles_ucf_template_variables_array) && ! empty($siteseo_titles_ucf_template_replace_array)) {
				$siteseo_titles_description_template = str_replace($siteseo_titles_ucf_template_variables_array, $siteseo_titles_ucf_template_replace_array, $siteseo_titles_description_template);
			}
		} else {
			setup_postdata($post);
			if ('' != $siteseo_get_the_excerpt || '' != get_the_content()) { //DEFAULT EXCERPT OR THE CONTENT
				$siteseo_titles_the_description = wp_trim_words(stripslashes_deep(wp_filter_nohtml_kses($siteseo_get_the_excerpt)), $siteseo_excerpt_length);

				$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
			}
		}
	} elseif (is_post_type_archive() && !is_tax() && siteseo_titles_archive_desc_option()) { //IS POST TYPE ARCHIVE (!is_tax() required for TEC)
		$siteseo_titles_the_description = esc_attr(siteseo_titles_archive_desc_option());

		$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
	} elseif ((is_tax() || is_category() || is_tag()) && siteseo_titles_tax_desc_option()) { //IS TAX
		$siteseo_titles_the_description = esc_attr(siteseo_titles_tax_desc_option());

		if (get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_titles_desc', true)) {
			$siteseo_titles_description_template = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_titles_desc', true));
			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_description_template);
		} else {
			$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
		}

		preg_match_all('/%%_cf_(.*?)%%/', $siteseo_titles_the_description, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				$siteseo_titles_cf_template_replace_array[] = esc_attr(get_term_meta(get_queried_object()->{'term_id'}, $value, true));
			}
		}

		// Custom fields
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_titles_description_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_description_template);
		}
	} elseif (is_author() && siteseo_get_service('TitleOption')->getArchivesAuthorDescription()) { //IS AUTHOR
		$siteseo_titles_archives_author_desc_option = esc_attr(siteseo_get_service('TitleOption')->getArchivesAuthorDescription());

		preg_match_all('/%%_ucf_(.*?)%%/', $siteseo_titles_archives_author_desc_option, $matches); //custom fields

		if ( ! empty($matches)) {
			$siteseo_titles_cf_template_variables_array = [];
			$siteseo_titles_cf_template_replace_array   = [];

			foreach ($matches['0'] as $key => $value) {
				$siteseo_titles_cf_template_variables_array[] = $value;
			}

			foreach ($matches['1'] as $key => $value) {
				$siteseo_titles_cf_template_replace_array[] = esc_attr(get_user_meta(get_current_user_id(), $value, true));
			}
		}

		// Default
		$siteseo_titles_description_template = esc_attr(siteseo_get_service('TitleOption')->getArchivesAuthorDescription());

		// User meta
		if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
			$siteseo_titles_description_template = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_titles_description_template);
		}

		$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_description_template);
	} elseif (is_date() && siteseo_get_service('TitleOption')->getArchivesDateDesc()) { //IS DATE
		$siteseo_titles_the_description = esc_attr(siteseo_get_service('TitleOption')->getArchivesDateDesc());

		$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
	} elseif (is_search() && siteseo_get_service('TitleOption')->getArchivesSearchDesc()) { //IS SEARCH
		$siteseo_titles_the_description = esc_attr(siteseo_get_service('TitleOption')->getArchivesSearchDesc());

		$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
	} elseif (is_404() && siteseo_get_service('TitleOption')->getArchives404Desc()) { //IS 404
		$siteseo_titles_the_description = esc_attr(siteseo_get_service('TitleOption')->getArchives404Desc());

		$siteseo_titles_description_template = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_titles_the_description);
	}
	
	// Hook on meta description - 'siteseo_titles_desc'
	if (has_filter('siteseo_titles_desc')) {
		$siteseo_titles_description_template = apply_filters('siteseo_titles_desc', $siteseo_titles_description_template);
	}
	
	// Return meta desc tag
	return $siteseo_titles_description_template;
}

function siteseo_titles_the_description() {
	if ('' != siteseo_titles_the_description_content()) {
		$html = '<meta name="description" content="' . esc_attr(siteseo_titles_the_description_content()) . '" />';
		$html .= "\n";
		echo wp_kses_post($html);
	}
}

if(apply_filters('siteseo_old_wp_head_description', true)) {
	add_action('wp_head', 'siteseo_titles_the_description', 1);
}

// Advanced
// noindex
// Single CPT noindex
function siteseo_titles_single_cpt_noindex_option() {
	$siteseo_get_current_cpt = get_post_type();

	$options = get_option('siteseo_titles_option_name');
	
	if ( ! empty($options) && isset($options['titles_single_titles'][$siteseo_get_current_cpt]['noindex'])) {
		return $options['titles_single_titles'][$siteseo_get_current_cpt]['noindex'];
	}
}

// Archive CPT noindex
function siteseo_titles_archive_cpt_noindex_option() {
	$siteseo_get_current_cpt = get_post_type();

	$options = get_option('siteseo_titles_option_name');
	if ( ! empty($options) && isset($options['titles_archive_titles'][$siteseo_get_current_cpt]['noindex'])) {
		return $options['titles_archive_titles'][$siteseo_get_current_cpt]['noindex'];
	}
}

// Tax archive noindex
function siteseo_titles_tax_noindex_option() {
	$queried_object = get_queried_object();
	$siteseo_get_current_tax = null !== $queried_object ? $queried_object->taxonomy : '';

	if (null !== $queried_object && 'yes' == get_term_meta($queried_object->term_id, '_siteseo_robots_index', true)) {
		return get_term_meta($queried_object->term_id, '_siteseo_robots_index', true);
	} else {
		$options = get_option('siteseo_titles_option_name');
		if ( ! empty($options) && isset($options['titles_tax_titles'][$siteseo_get_current_tax]['noindex'])) {
			return $options['titles_tax_titles'][$siteseo_get_current_tax]['noindex'];
		}
	}
}

// noindex single CPT
function siteseo_titles_noindex_post_option() {
	$_siteseo_robots_index = get_post_meta(get_the_ID(), '_siteseo_robots_index', true);
	if ('yes' == $_siteseo_robots_index) {
		return $_siteseo_robots_index;
	}
}

function siteseo_titles_noindex_bypass() {
	
	//init
	$siteseo_titles_noindex ='';
	$page_id = get_option('page_for_posts');
	if (is_singular() && true === post_password_required()) { //if password required, set noindex
		$siteseo_titles_noindex = 'noindex';
	} else {
		if (siteseo_get_service('TitleOption')->getTitleNoIndex()) { //Single CPT Global Advanced tab
			$siteseo_titles_noindex = siteseo_get_service('TitleOption')->getTitleNoIndex();
		} elseif (is_singular() && siteseo_titles_single_cpt_noindex_option()) { //Single CPT Global
			$siteseo_titles_noindex = siteseo_titles_single_cpt_noindex_option();
		} elseif (is_singular() && siteseo_titles_noindex_post_option()) { //Single CPT Metaboxe
			$siteseo_titles_noindex = siteseo_titles_noindex_post_option();
		} elseif (is_home() && '' != get_post_meta($page_id, '_siteseo_robots_index', true)) { //BLOG PAGE
			$siteseo_titles_noindex = get_post_meta($page_id, '_siteseo_robots_index', true);
		} elseif (is_post_type_archive() && siteseo_titles_archive_cpt_noindex_option()) { //Is POST TYPE ARCHIVE
			$siteseo_titles_noindex = siteseo_titles_archive_cpt_noindex_option();
		} elseif ((is_tax() || is_category() || is_tag()) && siteseo_titles_tax_noindex_option()) { //Is TAX
			$siteseo_titles_noindex = siteseo_titles_tax_noindex_option();
		} elseif (is_author() && siteseo_get_service('TitleOption')->getArchiveAuthorNoindex()) { //Is Author archive
			$siteseo_titles_noindex = siteseo_get_service('TitleOption')->getArchiveAuthorNoindex();
		} elseif (function_exists('bp_is_group') && bp_is_group() && siteseo_get_service('TitleOption')->getTitleBpGroupsNoindex()) { //Is BuddyPress group single
			$siteseo_titles_noindex = siteseo_get_service('TitleOption')->getTitleBpGroupsNoindex();
		} elseif (is_date() && siteseo_get_service('TitleOption')->searchOptionByKey('titles_archives_date_noindex')) { //Is Date archive
			$siteseo_titles_noindex = siteseo_get_service('TitleOption')->searchOptionByKey('titles_archives_date_noindex');
		} elseif (is_search() && siteseo_get_service('TitleOption')->searchOptionByKey('titles_archives_search_title_noindex')()) {//Is Search
			$siteseo_titles_noindex = siteseo_get_service('TitleOption')->searchOptionByKey('titles_archives_search_title_noindex')();
		} elseif (is_paged() && siteseo_get_service('TitleOption')->searchOptionByKey('titles_paged_noindex')){//Is paged archive
			$siteseo_titles_noindex = siteseo_get_service('TitleOption')->searchOptionByKey('titles_paged_noindex');
		} elseif (is_404()) { //Is 404 page
			$siteseo_titles_noindex = 'noindex';
		} elseif (is_attachment() && siteseo_get_service('TitleOption')->searchOptionByKey('titles_attachments_noindex')) {
			$siteseo_titles_noindex = 'noindex';
		}
	}

	$siteseo_titles_noindex = apply_filters('siteseo_titles_noindex_bypass', $siteseo_titles_noindex);

	//remove hreflang if noindex
	if ('1' == $siteseo_titles_noindex || true == $siteseo_titles_noindex) {
		//WPML
		add_filter('wpml_hreflangs', '__return_false');

		//MultilingualPress v2
		add_filter('multilingualpress.render_hreflang', '__return_false');

		//TranslatePress
		add_filter('trp-exclude-hreflang', '__return_true');
	}
	//Return noindex tag
	return $siteseo_titles_noindex;
}

//nofollow
//Single CPT nofollow
function siteseo_titles_single_cpt_nofollow_option() {
	$siteseo_get_current_cpt = get_post_type();

	$options = get_option('siteseo_titles_option_name');
	if( ! empty($options) && isset($options['titles_single_titles'][$siteseo_get_current_cpt]['nofollow'])) {
		return $options['titles_single_titles'][$siteseo_get_current_cpt]['nofollow'];
	}
}

//Archive CPT nofollow
function siteseo_titles_archive_cpt_nofollow_option() {
	
	$siteseo_get_current_cpt = get_post_type();

	$options = get_option('siteseo_titles_option_name');
	if ( ! empty($options) && isset($options['titles_archive_titles'][$siteseo_get_current_cpt]['nofollow'])) {
		return $options['titles_archive_titles'][$siteseo_get_current_cpt]['nofollow'];
	}
}

//Tax archive nofollow
function siteseo_titles_tax_nofollow_option() {
	$queried_object = get_queried_object();
	$siteseo_get_current_tax = $queried_object->taxonomy;

	if ('yes' == get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_robots_follow', true)) {
		return get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_robots_follow', true);
	} else {
		$options = get_option('siteseo_titles_option_name');
		if ( ! empty($options) && isset($options['titles_tax_titles'][$siteseo_get_current_tax]['nofollow'])) {
			return $options['titles_tax_titles'][$siteseo_get_current_tax]['nofollow'];
		}
	}
}

function siteseo_titles_nofollow_post_option() {
	$_siteseo_robots_follow = get_post_meta(get_the_ID(), '_siteseo_robots_follow', true);
	if ('yes' == $_siteseo_robots_follow) {
		return $_siteseo_robots_follow;
	}
}

function siteseo_titles_nofollow_bypass() {
	
	//init
	$siteseo_titles_nofollow ='';
	$page_id = get_option('page_for_posts');
	
	if (siteseo_get_service('TitleOption')->getTitleNoFollow()) { //Single CPT Global Advanced tab
		$siteseo_titles_nofollow = siteseo_get_service('TitleOption')->getTitleNoFollow();
	} elseif (is_singular() && siteseo_titles_single_cpt_nofollow_option()) { //Single CPT Global
		$siteseo_titles_nofollow = siteseo_titles_single_cpt_nofollow_option();
	} elseif (is_singular() && siteseo_titles_nofollow_post_option()) { //Single CPT Metaboxe
		$siteseo_titles_nofollow = siteseo_titles_nofollow_post_option();
	} elseif (is_home() && '' != get_post_meta($page_id, '_siteseo_robots_follow', true)) { //BLOG PAGE
		$siteseo_titles_nofollow = get_post_meta($page_id, '_siteseo_robots_follow', true);
	} elseif (is_post_type_archive() && siteseo_titles_archive_cpt_nofollow_option()) { //IS POST TYPE ARCHIVE
		$siteseo_titles_nofollow = siteseo_titles_archive_cpt_nofollow_option();
	} elseif ((is_tax() || is_category() || is_tag()) && siteseo_titles_tax_nofollow_option()) { //IS TAX
		$siteseo_titles_nofollow = siteseo_titles_tax_nofollow_option();
	}

	return $siteseo_titles_nofollow;
}

// Date in SERPs
function siteseo_titles_single_cpt_date_option() {
	$siteseo_get_current_cpt = get_post_type();

	$options = get_option('siteseo_titles_option_name');
	if ( ! empty($options) && isset($options['titles_single_titles'][$siteseo_get_current_cpt]['date'])) {
		return $options['titles_single_titles'][$siteseo_get_current_cpt]['date'];
	}
}

function siteseo_titles_single_cpt_date_hook() {
	if ( ! is_front_page() && ! is_home()) {
		if (is_singular() && '1' == siteseo_titles_single_cpt_date_option()) {
			$siteseo_get_current_pub_post_date = get_the_date('c');
			$siteseo_get_current_up_post_date  = get_the_modified_date('c');
			$html = '<meta property="article:published_time" content="' . esc_attr($siteseo_get_current_pub_post_date) . '" />';
			$html .= "\n";

			$html = apply_filters('siteseo_titles_article_published_time', $html);

			echo wp_kses_post($html);

			$html = '<meta property="article:modified_time" content="' . esc_attr($siteseo_get_current_up_post_date) . '" />';
			$html .= "\n";

			$html = apply_filters('siteseo_titles_article_modified_time', $html);

			echo wp_kses_post($html);

			$html = '<meta property="og:updated_time" content="' . esc_attr($siteseo_get_current_up_post_date) . '" />';
			$html .= "\n";

			$html = apply_filters('siteseo_titles_og_updated_time', $html);

			echo wp_kses_post($html);
		}
	}
}
add_action('wp_head', 'siteseo_titles_single_cpt_date_hook', 1);

// thumbnail in Google Custom Search
function siteseo_titles_single_cpt_thumb_gcs_option() {
	$siteseo_get_current_cpt = get_post_type();

	$options = get_option('siteseo_titles_option_name');
	if ( ! empty($options) && isset($options['titles_single_titles'][$siteseo_get_current_cpt]['thumb_gcs'])) {
		return $options['titles_single_titles'][$siteseo_get_current_cpt]['thumb_gcs'];
	}
}

function siteseo_titles_single_cpt_thumb_gcs() {
	if ( ! is_front_page() && ! is_home()) {
		if (is_singular() && '1' == siteseo_titles_single_cpt_thumb_gcs_option()) {
			if (get_the_post_thumbnail_url(get_the_ID())) {
				$html = '<meta name="thumbnail" content="' . get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') . '" />';
				$html .= "\n";

				$html = apply_filters('siteseo_titles_gcs_thumbnail', $html);

				echo wp_kses_post($html);
			}
		}
	}
}
add_action('wp_head', 'siteseo_titles_single_cpt_thumb_gcs', 1);

function siteseo_titles_noarchive_post_option() {
	$_siteseo_robots_archive = get_post_meta(get_the_ID(), '_siteseo_robots_archive', true);
	if ('yes' == $_siteseo_robots_archive) {
		return $_siteseo_robots_archive;
	}
}

function siteseo_titles_noarchive_bypass() {
	$page_id = get_option('page_for_posts');
	if (siteseo_get_service('TitleOption')->getTitleNoArchive()) {
		return siteseo_get_service('TitleOption')->getTitleNoArchive();
	} elseif (is_singular() && siteseo_titles_noarchive_post_option()) {
		return siteseo_titles_noarchive_post_option();
	} elseif (is_home() && '' != get_post_meta($page_id, '_siteseo_robots_archive', true)) { //BLOG PAGE
		return get_post_meta($page_id, '_siteseo_robots_archive', true);
	} elseif (is_tax() || is_category() || is_tag()) {
		if ('yes' == get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_robots_archive', true)) {
			return get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_robots_archive', true);
		}
	}
}

function siteseo_titles_nosnippet_post_option() {
	$_siteseo_robots_snippet = get_post_meta(get_the_ID(), '_siteseo_robots_snippet', true);
	if ('yes' == $_siteseo_robots_snippet) {
		return $_siteseo_robots_snippet;
	}
}

function siteseo_titles_nosnippet_bypass() {
	$page_id = get_option('page_for_posts');
	if (siteseo_get_service('TitleOption')->getTitleNoSnippet()) {
		return siteseo_get_service('TitleOption')->getTitleNoSnippet();
	} elseif (is_singular() && siteseo_titles_nosnippet_post_option()) {
		return siteseo_titles_nosnippet_post_option();
	} elseif (is_home() && '' != get_post_meta($page_id, '_siteseo_robots_snippet', true)) { //BLOG PAGE
		return get_post_meta($page_id, '_siteseo_robots_snippet', true);
	} elseif (is_tax() || is_category() || is_tag()) {
		if ('yes' == get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_robots_snippet', true)) {
			return get_term_meta(get_queried_object()->{'term_id'}, '_siteseo_robots_snippet', true);
		}
	}
}

function siteseo_titles_noimageindex_post_option() {
	$_siteseo_robots_imageindex = get_post_meta(get_the_ID(), '_siteseo_robots_imageindex', true);
	if ('yes' == $_siteseo_robots_imageindex) {
		return $_siteseo_robots_imageindex;
	}
}

function siteseo_titles_noimageindex_bypass() {
	if (siteseo_get_service('TitleOption')->getTitleNoImageIndex()) {
		return siteseo_get_service('TitleOption')->getTitleNoImageIndex();
	} elseif (is_singular() && siteseo_titles_noimageindex_post_option()) {
		return siteseo_titles_noimageindex_post_option();
	} elseif (is_tax() || is_category() || is_tag()) {
		$queried_object = get_queried_object();
		if (null != $queried_object) {
			if ('yes' == get_term_meta($queried_object->term_id, '_siteseo_robots_imageindex', true)) {
				return get_term_meta($queried_object->term_id, '_siteseo_robots_imageindex', true);
			}
		}
	}
}

// Polylang
function siteseo_remove_hreflang_polylang($hreflangs) {
	$hreflangs = [];

	return $hreflangs;
}

if ('0' != get_option('blog_public')) {// Discourage search engines from indexing this site is OFF
	function siteseo_titles_advanced_robots_hook() {
		$siteseo_comma_array = [];

		if ('' != siteseo_titles_noindex_bypass()) {
			$siteseo_titles_noindex = 'noindex';
			// Hook on meta robots noindex - 'siteseo_titles_noindex'
			if (has_filter('siteseo_titles_noindex')) {
				$siteseo_titles_noindex = apply_filters('siteseo_titles_noindex', $siteseo_titles_noindex);
			}
			array_push($siteseo_comma_array, $siteseo_titles_noindex);
		}
		if ('' != siteseo_titles_nofollow_bypass()) {
			$siteseo_titles_nofollow = 'nofollow';
			// Hook on meta robots nofollow - 'siteseo_titles_nofollow'
			if (has_filter('siteseo_titles_nofollow')) {
				$siteseo_titles_nofollow = apply_filters('siteseo_titles_nofollow', $siteseo_titles_nofollow);
			}
			array_push($siteseo_comma_array, $siteseo_titles_nofollow);
		}
		if ('' != siteseo_titles_noarchive_bypass()) {
			$siteseo_titles_noarchive = 'noarchive';
			// Hook on meta robots noarchive - 'siteseo_titles_noarchive'
			if (has_filter('siteseo_titles_noarchive')) {
				$siteseo_titles_noarchive = apply_filters('siteseo_titles_noarchive', $siteseo_titles_noarchive);
			}
			array_push($siteseo_comma_array, $siteseo_titles_noarchive);
		}
		if ('' != siteseo_titles_noimageindex_bypass()) {
			$siteseo_titles_noimageindex = 'noimageindex';
			// Hook on meta robots noimageindex - 'siteseo_titles_noimageindex'
			if (has_filter('siteseo_titles_noimageindex')) {
				$siteseo_titles_noimageindex = apply_filters('siteseo_titles_noimageindex', $siteseo_titles_noimageindex);
			}
			array_push($siteseo_comma_array, $siteseo_titles_noimageindex);
		}
		if ('' != siteseo_titles_nosnippet_bypass()) {
			$siteseo_titles_nosnippet = 'nosnippet';
			// Hook on meta robots nosnippet - 'siteseo_titles_nosnippet'
			if (has_filter('siteseo_titles_nosnippet')) {
				$siteseo_titles_nosnippet = apply_filters('siteseo_titles_nosnippet', $siteseo_titles_nosnippet);
			}
			array_push($siteseo_comma_array, $siteseo_titles_nosnippet);
		}

		// remove hreflang tag from Polylang if noindex
		if (in_array('noindex', $siteseo_comma_array)) {
			add_filter('pll_rel_hreflang_attributes', 'siteseo_remove_hreflang_polylang');
		}

		if ( ! in_array('noindex', $siteseo_comma_array) && ! in_array('nofollow', $siteseo_comma_array)) {
			$siteseo_titles_max_snippet = 'index, follow';
			array_unshift($siteseo_comma_array, $siteseo_titles_max_snippet);
		}

		if (in_array('nofollow', $siteseo_comma_array) && ! in_array('noindex', $siteseo_comma_array)) {
			$siteseo_titles_max_snippet = 'index';
			array_unshift($siteseo_comma_array, $siteseo_titles_max_snippet);
		}

		if (in_array('noindex', $siteseo_comma_array) && ! in_array('nofollow', $siteseo_comma_array)) {
			$siteseo_titles_max_snippet = 'follow';
			array_unshift($siteseo_comma_array, $siteseo_titles_max_snippet);
		}

		// Default meta robots
		$siteseo_titles_robots = '<meta name="robots" content="';

		$siteseo_comma_array = apply_filters('siteseo_titles_robots_attrs', $siteseo_comma_array);

		$siteseo_comma_count = count($siteseo_comma_array);
		for ($i = 0; $i < $siteseo_comma_count; ++$i) {
			$siteseo_titles_robots .= $siteseo_comma_array[$i];
			if ($i < ($siteseo_comma_count - 1)) {
				$siteseo_titles_robots .= ', ';
			}
		}

		$siteseo_titles_robots .= '" />';
		$siteseo_titles_robots .= "\n";

		// new meta robots
		if ( ! in_array('noindex', $siteseo_comma_array)) {
			$siteseo_titles_max_snippet = 'max-snippet:-1, max-image-preview:large, max-video-preview:-1';
			array_push($siteseo_comma_array, $siteseo_titles_max_snippet);

			// Googlebot
			$siteseo_titles_robots .= '<meta name="googlebot" content="';

			$siteseo_comma_array = apply_filters('siteseo_titles_robots_attrs', $siteseo_comma_array);

			$siteseo_comma_count = count($siteseo_comma_array);
			for ($i = 0; $i < $siteseo_comma_count; ++$i) {
				$siteseo_titles_robots .= $siteseo_comma_array[$i];
				if ($i < ($siteseo_comma_count - 1)) {
					$siteseo_titles_robots .= ', ';
				}
			}

			$siteseo_titles_robots .= '" />';
			$siteseo_titles_robots .= "\n";

			// Bingbot
			$siteseo_titles_robots .= '<meta name="bingbot" content="';

			$siteseo_comma_array = apply_filters('siteseo_titles_robots_attrs', $siteseo_comma_array);

			$siteseo_comma_count = count($siteseo_comma_array);
			for ($i = 0; $i < $siteseo_comma_count; ++$i) {
				$siteseo_titles_robots .= $siteseo_comma_array[$i];
				if ($i < ($siteseo_comma_count - 1)) {
					$siteseo_titles_robots .= ', ';
				}
			}

			$siteseo_titles_robots .= '" />';
			$siteseo_titles_robots .= "\n";
		}
		// Hook on meta robots all - 'siteseo_titles_robots'
		if (has_filter('siteseo_titles_robots')) {
			$siteseo_titles_robots = apply_filters('siteseo_titles_robots', $siteseo_titles_robots);
		}
		echo wp_kses($siteseo_titles_robots, ['meta' => ['name' => true, 'content' => true]]);
	}
	add_action('wp_head', 'siteseo_titles_advanced_robots_hook', 1);
}

if(siteseo_get_service('TitleOption')->geNoSiteLinksSearchBox()) {
	function siteseo_titles_nositelinkssearchbox_hook() {
		echo '<meta name="google" content="nositelinkssearchbox" />';
		echo "\n";
	}
	add_action('wp_head', 'siteseo_titles_nositelinkssearchbox_hook', 2);
}

// link rel prev/next
if(siteseo_get_service('TitleOption')->getPagedRel()) {
	function siteseo_titles_paged_rel_hook() {
		global $paged;
		if (get_previous_posts_link()) { ?>
			<link rel="prev" href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>" />
		<?php }
		if (get_next_posts_link()) { ?>
			<link rel="next" href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>" />
		<?php }
	}
	add_action('wp_head', 'siteseo_titles_paged_rel_hook', 9);
}

//canonical
function siteseo_titles_canonical_post_option() {
	$_siteseo_robots_canonical = get_post_meta(get_the_ID(), '_siteseo_robots_canonical', true);
	if ('' != $_siteseo_robots_canonical) {
		return $_siteseo_robots_canonical;
	}
}

function siteseo_titles_canonical_term_option() {
	$queried_object = get_queried_object();
	$termId =  null !== $queried_object ? $queried_object->term_id : '';
	if ( ! empty($termId)) {
		$_siteseo_robots_canonical = get_term_meta($termId, '_siteseo_robots_canonical', true);
		if ('' != $_siteseo_robots_canonical) {
			return $_siteseo_robots_canonical;
		}
	}
}

if (function_exists('siteseo_titles_noindex_bypass') && '1' != siteseo_titles_noindex_bypass() && 'yes' != siteseo_titles_noindex_bypass()) {//Remove Canonical if noindex
	$page_id = get_option('page_for_posts');
	if (is_singular() && siteseo_titles_canonical_post_option()) { //CUSTOM SINGLE CANONICAL
		function siteseo_titles_canonical_post_hook() {
			$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode(siteseo_titles_canonical_post_option())) . '" />';
			//Hook on post canonical URL - 'siteseo_titles_canonical'
			if (has_filter('siteseo_titles_canonical')) {
				$siteseo_titles_canonical = apply_filters('siteseo_titles_canonical', $siteseo_titles_canonical);
			}
			echo wp_kses($siteseo_titles_canonical, ['link' => ['rel' => true, 'href' => true]]) . "\n";
		}
		add_action('wp_head', 'siteseo_titles_canonical_post_hook', 1);
	} elseif (is_home() && '' != get_post_meta($page_id, '_siteseo_robots_canonical', true)) { //BLOG PAGE
		function siteseo_titles_canonical_post_hook() {
			$page_id				   = get_option('page_for_posts');
			$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode(get_post_meta($page_id, '_siteseo_robots_canonical', true))) . '" />';
			// Hook on post canonical URL - 'siteseo_titles_canonical'
			if (has_filter('siteseo_titles_canonical')) {
				$siteseo_titles_canonical = apply_filters('siteseo_titles_canonical', $siteseo_titles_canonical);
			}
			echo wp_kses($siteseo_titles_canonical, ['link' => ['rel' => true, 'href' => true]]) . "\n";
		}
		add_action('wp_head', 'siteseo_titles_canonical_post_hook', 1, 1);
	} elseif ((is_tax() || is_category() || is_tag()) && siteseo_titles_canonical_term_option()) { // CUSTOM TERM CANONICAL
		function siteseo_titles_canonical_term_hook() {
			$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode(siteseo_titles_canonical_term_option())) . '" />';
			// Hook on post canonical URL - 'siteseo_titles_canonical'
			if (has_filter('siteseo_titles_canonical')) {
				$siteseo_titles_canonical = apply_filters('siteseo_titles_canonical', $siteseo_titles_canonical);
			}
			echo wp_kses($siteseo_titles_canonical, ['link' => ['rel' => true, 'href' => true]]) . "\n";
		}
		add_action('wp_head', 'siteseo_titles_canonical_term_hook', 1);
	} elseif ( ! is_404()) { // DEFAULT CANONICAL
		function siteseo_titles_canonical_hook() {
			global $wp;

			$current_url = user_trailingslashit(home_url(add_query_arg([], $wp->request)));

			if (is_search()) {
				$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode(get_home_url() . '/search/' . get_search_query())) . '" />';
			} elseif (is_paged() && is_singular()) {//Paginated pages
				$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode(get_permalink())) . '" />';
			} elseif (is_paged()) {
				$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode($current_url)) . '" />';
			} elseif (is_singular()) {
				$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode(get_permalink())) . '" />';
			} else {
				$siteseo_titles_canonical = '<link rel="canonical" href="' . htmlspecialchars(urldecode($current_url)) . '" />';
			}
			
			// Hook on post canonical URL - 'siteseo_titles_canonical'
			if (has_filter('siteseo_titles_canonical')) {
				$siteseo_titles_canonical = apply_filters('siteseo_titles_canonical', $siteseo_titles_canonical);
			}
			echo wp_kses($siteseo_titles_canonical, ['link' => ['rel' => true, 'href' => true]]) . "\n";
		}
		add_action('wp_head', 'siteseo_titles_canonical_hook', 1);
	}
}
