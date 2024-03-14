<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Restrict SEO metaboxes to user roles
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_advanced_security_metaboxe_role_hook_option() {
	$options = get_option('siteseo_advanced_option_name');
	if( ! empty($options) && isset($options['security_metaboxe_role'])) {
		return $options['security_metaboxe_role'];
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Check global settings
///////////////////////////////////////////////////////////////////////////////////////////////////
if ( ! function_exists('siteseo_titles_single_term_noindex_option')) {
	function siteseo_titles_single_term_noindex_option() {
		global $tax;
		$siteseo_get_current_tax = $tax->name;

		$options = get_option('siteseo_titles_option_name');
		if ( ! empty($options) && isset($options['titles_tax_titles'][$siteseo_get_current_tax]['noindex'])) {
			return $options['titles_tax_titles'][$siteseo_get_current_tax]['noindex'];
		}
	}
}

if ( ! function_exists('siteseo_titles_single_term_nofollow_option')) {
	function siteseo_titles_single_term_nofollow_option() {
		global $tax;
		$siteseo_get_current_tax = $tax->name;

		$options = get_option('siteseo_titles_option_name');
		if ( ! empty($options) && isset($options['titles_tax_titles'][$siteseo_get_current_tax]['nofollow'])) {
			return $options['titles_tax_titles'][$siteseo_get_current_tax]['nofollow'];
		}
	}
}

//Metaboxe position
if ( ! function_exists('siteseo_advanced_appearance_term_metaboxe_position_option')) {
	function siteseo_advanced_appearance_term_metaboxe_position_option() {
		$options = get_option('siteseo_advanced_option_name');
		if( ! empty($options) && isset($options['appearance_metaboxe_position'])) {
			return $options['appearance_metaboxe_position'];
		}
	}
}

//////////////////////////////////////////////
//Display metabox in Custom Taxonomy
//////////////////////////////////////////////
function siteseo_display_seo_term_metaboxe() {
	add_action('init', 'siteseo_init_term_metabox', 11);

	function siteseo_init_term_metabox() {
		$siteseo_get_taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();
		$siteseo_get_taxonomies = apply_filters('siteseo_metaboxe_term_seo', $siteseo_get_taxonomies);

		if ( ! empty($siteseo_get_taxonomies)) {
			if (function_exists('siteseo_advanced_appearance_term_metaboxe_position_option')) {
				switch (siteseo_advanced_appearance_term_metaboxe_position_option()) {
					case 'high':
						$priority = 1;
						break;
					case 'default':
						$priority = 10;
						break;
					case 'low':
						$priority = 100;
						break;
					default:
						$priority = 10;
						break;
				}
			} else {
				$priority = 10;
			}
			$priority = apply_filters('siteseo_metaboxe_term_seo_priority', $priority);
			foreach ($siteseo_get_taxonomies as $key => $value) {
				add_action($key . '_edit_form', 'siteseo_tax', $priority, 2); //Edit term page
				add_action('edit_' . $key,   'siteseo_tax_save_term', $priority, 2); //Edit save term
			}
		}
	}

	function siteseo_tax($term) {
		wp_nonce_field(plugin_basename(__FILE__), 'siteseo_cpt_nonce');

		global $typenow;

		//init
		$disabled = [];

		wp_enqueue_script('siteseo-cpt-tabs-js', SITESEO_ASSETS_DIR . '/js/siteseo-metabox.js', ['jquery-ui-tabs'], SITESEO_VERSION);

		if ('siteseo_404' != $typenow) {
			//Tagify
			wp_enqueue_script('siteseo-tagify-js', SITESEO_ASSETS_DIR . '/js/tagify.min.js', ['jquery'], SITESEO_VERSION, true);
			wp_register_style('siteseo-tagify', SITESEO_ASSETS_DIR . '/css/tagify.min.css', [], SITESEO_VERSION);
			wp_enqueue_style('siteseo-tagify');

			//Register Google Snippet Preview / Content Analysis JS
			wp_enqueue_script('siteseo-cpt-counters-js', SITESEO_ASSETS_DIR . '/js/siteseo-counters.min.js', ['jquery', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-autocomplete'], SITESEO_VERSION);

			$siteseo_real_preview = [
				'siteseo_nonce'		 => wp_create_nonce('siteseo_real_preview_nonce'),
				'siteseo_real_preview'  => admin_url('admin-ajax.php'),
				'i18n'				   => ['progress' => __('Analysis in progress...', 'siteseo')],
				'ajax_url'			   => admin_url('admin-ajax.php'),
				'get_preview_meta_title' => wp_create_nonce('get_preview_meta_title'),
			];
			wp_localize_script('siteseo-cpt-counters-js', 'siteseoAjaxRealPreview', $siteseo_real_preview);

			wp_enqueue_script('siteseo-media-uploader-js', SITESEO_ASSETS_DIR . '/js/siteseo-media-uploader.js', ['jquery'], SITESEO_VERSION, false);
			wp_enqueue_media();
		}

		$siteseo_titles_title = get_term_meta($term->term_id, '_siteseo_titles_title', true);
		$siteseo_titles_desc = get_term_meta($term->term_id, '_siteseo_titles_desc', true);

		$disabled['robots_index'] = '';
		if (siteseo_titles_single_term_noindex_option() || siteseo_get_service('TitleOption')->getTitleNoIndex()) {
			$siteseo_robots_index = 'yes';
			$disabled['robots_index'] = 'disabled';
		} else {
			$siteseo_robots_index = get_term_meta($term->term_id, '_siteseo_robots_index', true);
		}

		$disabled['robots_follow'] = '';
		if (siteseo_titles_single_term_nofollow_option() || siteseo_get_service('TitleOption')->getTitleNoFollow()) {
			$siteseo_robots_follow = 'yes';
			$disabled['robots_follow'] = 'disabled';
		} else {
			$siteseo_robots_follow = get_term_meta($term->term_id, '_siteseo_robots_follow', true);
		}

		$disabled['archive'] = '';
		if (siteseo_get_service('TitleOption')->getTitleNoArchive()) {
			$siteseo_robots_archive = 'yes';
			$disabled['archive'] = 'disabled';
		} else {
			$siteseo_robots_archive = get_term_meta($term->term_id, '_siteseo_robots_archive', true);
		}

		$disabled['snippet'] ='';
		if (siteseo_get_service('TitleOption')->getTitleNoSnippet()) {
			$siteseo_robots_snippet = 'yes';
			$disabled['snippet'] = 'disabled';
		} else {
			$siteseo_robots_snippet = get_term_meta($term->term_id, '_siteseo_robots_snippet', true);
		}

		$disabled['imageindex'] ='';
		if (siteseo_get_service('TitleOption')->getTitleNoImageIndex()) {
			$siteseo_robots_imageindex = 'yes';
			$disabled['imageindex'] = 'disabled';
		} else {
			$siteseo_robots_imageindex = get_term_meta($term->term_id, '_siteseo_robots_imageindex', true);
		}

		$siteseo_robots_canonical = get_term_meta($term->term_id, '_siteseo_robots_canonical', true);
		$siteseo_social_fb_title = get_term_meta($term->term_id, '_siteseo_social_fb_title', true);
		$siteseo_social_fb_desc = get_term_meta($term->term_id, '_siteseo_social_fb_desc', true);
		$siteseo_social_fb_img = get_term_meta($term->term_id, '_siteseo_social_fb_img', true);
		$siteseo_social_fb_img_attachment_id = get_term_meta($term->term_id, '_siteseo_social_fb_img_attachment_id', true);
		$siteseo_social_fb_img_width = get_term_meta($term->term_id, '_siteseo_social_fb_img_width', true);
		$siteseo_social_fb_img_height = get_term_meta($term->term_id, '_siteseo_social_fb_img_height', true);
		$siteseo_social_twitter_title = get_term_meta($term->term_id, '_siteseo_social_twitter_title', true);
		$siteseo_social_twitter_desc = get_term_meta($term->term_id, '_siteseo_social_twitter_desc', true);
		$siteseo_social_twitter_img = get_term_meta($term->term_id, '_siteseo_social_twitter_img', true);
		$siteseo_social_twitter_img_attachment_id = get_term_meta($term->term_id, '_siteseo_social_twitter_img_attachment_id', true);
		$siteseo_social_twitter_img_width = get_term_meta($term->term_id, '_siteseo_social_twitter_img_width', true);
		$siteseo_social_twitter_img_height = get_term_meta($term->term_id, '_siteseo_social_twitter_img_height', true);
		$siteseo_redirections_enabled = get_term_meta($term->term_id, '_siteseo_redirections_enabled', true);
		$siteseo_redirections_logged_status	= get_term_meta($term->term_id, '_siteseo_redirections_logged_status', true);
		$siteseo_redirections_type = get_term_meta($term->term_id, '_siteseo_redirections_type', true);
		$siteseo_redirections_value = get_term_meta($term->term_id, '_siteseo_redirections_value', true);

		require_once dirname(dirname(__FILE__)) . '/admin-dyn-variables-helper.php'; //Dynamic variables
		require_once dirname(__FILE__) . '/admin-metaboxes-form.php'; //Metaboxe HTML
	}

	function siteseo_tax_save_term($term_id) {
		//Nonce
		if ( ! isset($_POST['siteseo_cpt_nonce']) || ! wp_verify_nonce(siteseo_opt_post('siteseo_cpt_nonce'), plugin_basename(__FILE__))) {
			return $term_id;
		}

		//Taxonomy object
		$taxonomy = get_taxonomy(get_current_screen()->taxonomy);

		//Check permission
		if ( ! current_user_can($taxonomy->cap->edit_terms, $term_id)) {
			return $term_id;
		}

		$seo_tabs = [];
		$seo_tabs = json_decode(stripslashes(htmlspecialchars_decode(siteseo_opt_post('seo_tabs'))));

		if (in_array('title-tab', $seo_tabs)) {
			if (!empty($_POST['siteseo_titles_title'])) {
				update_term_meta($term_id, '_siteseo_titles_title', siteseo_opt_post('siteseo_titles_title'));
			} else {
				delete_term_meta($term_id, '_siteseo_titles_title');
			}
			if (!empty($_POST['siteseo_titles_desc'])) {
				update_term_meta($term_id, '_siteseo_titles_desc', siteseo_opt_post('siteseo_titles_desc'));
			} else {
				delete_term_meta($term_id, '_siteseo_titles_desc');
			}
		}
		if (in_array('advanced-tab', $seo_tabs)) {
			if (isset($_POST['siteseo_robots_index'])) {
				update_term_meta($term_id, '_siteseo_robots_index', 'yes');
			} else {
				delete_term_meta($term_id, '_siteseo_robots_index', '');
			}
			if (isset($_POST['siteseo_robots_follow'])) {
				update_term_meta($term_id, '_siteseo_robots_follow', 'yes');
			} else {
				delete_term_meta($term_id, '_siteseo_robots_follow', '');
			}
			if (isset($_POST['siteseo_robots_imageindex'])) {
				update_term_meta($term_id, '_siteseo_robots_imageindex', 'yes');
			} else {
				delete_term_meta($term_id, '_siteseo_robots_imageindex', '');
			}
			if (isset($_POST['siteseo_robots_archive'])) {
				update_term_meta($term_id, '_siteseo_robots_archive', 'yes');
			} else {
				delete_term_meta($term_id, '_siteseo_robots_archive', '');
			}
			if (isset($_POST['siteseo_robots_snippet'])) {
				update_term_meta($term_id, '_siteseo_robots_snippet', 'yes');
			} else {
				delete_term_meta($term_id, '_siteseo_robots_snippet', '');
			}
			if (!empty($_POST['siteseo_robots_canonical'])) {
				update_term_meta($term_id, '_siteseo_robots_canonical', siteseo_opt_post('siteseo_robots_canonical'));
			} else {
				delete_term_meta($term_id, '_siteseo_robots_canonical');
			}
		}

		if (in_array('social-tab', $seo_tabs)) {
			//Facebook
			if (!empty($_POST['siteseo_social_fb_title'])) {
				update_term_meta($term_id, '_siteseo_social_fb_title', siteseo_opt_post('siteseo_social_fb_title'));
			} else {
				delete_term_meta($term_id, '_siteseo_social_fb_title');
			}
			if (!empty($_POST['siteseo_social_fb_desc'])) {
				update_term_meta($term_id, '_siteseo_social_fb_desc', siteseo_opt_post('siteseo_social_fb_desc'));
			} else {
				delete_term_meta($term_id, '_siteseo_social_fb_desc');
			}
			if (!empty($_POST['siteseo_social_fb_img'])) {
				update_term_meta($term_id, '_siteseo_social_fb_img', siteseo_opt_post('siteseo_social_fb_img'));
			}
			if (!empty($_POST['siteseo_social_fb_img_attachment_id']) && !empty($_POST['siteseo_social_fb_img'])) {
				update_term_meta($term_id, '_siteseo_social_fb_img_attachment_id', siteseo_opt_post('siteseo_social_fb_img_attachment_id'));
			} else {
				delete_term_meta($term_id, '_siteseo_social_fb_img_attachment_id');
			}
			if (!empty($_POST['siteseo_social_fb_img_width']) && !empty($_POST['siteseo_social_fb_img'])) {
				update_term_meta($term_id, '_siteseo_social_fb_img_width', siteseo_opt_post('siteseo_social_fb_img_width'));
			} else {
				delete_term_meta($term_id, '_siteseo_social_fb_img_width');
			}
			if (!empty($_POST['siteseo_social_fb_img_height']) && !empty($_POST['siteseo_social_fb_img'])) {
				update_term_meta($term_id, '_siteseo_social_fb_img_height', siteseo_opt_post('siteseo_social_fb_img_height'));
			} else {
				delete_term_meta($term_id, '_siteseo_social_fb_img_height');
			}

			//Twitter
			if (!empty($_POST['siteseo_social_twitter_title'])) {
				update_term_meta($term_id, '_siteseo_social_twitter_title', siteseo_opt_post('siteseo_social_twitter_title'));
			} else {
				delete_term_meta($term_id, '_siteseo_social_twitter_title');
			}
			if (!empty($_POST['siteseo_social_twitter_desc'])) {
				update_term_meta($term_id, '_siteseo_social_twitter_desc', siteseo_opt_post('siteseo_social_twitter_desc'));
			} else {
				delete_term_meta($term_id, '_siteseo_social_twitter_desc');
			}
			if (!empty($_POST['siteseo_social_twitter_img'])) {
				update_term_meta($term_id, '_siteseo_social_twitter_img', siteseo_opt_post('siteseo_social_twitter_img'));
			}
		}
		if (in_array('redirect-tab', $seo_tabs)) {
			if (isset($_POST['siteseo_redirections_type'])) {
				update_term_meta($term_id, '_siteseo_redirections_type', siteseo_opt_post('siteseo_redirections_type'));
			}
			if (isset($_POST['siteseo_redirections_logged_status'])) {
				update_term_meta($term_id, '_siteseo_redirections_logged_status', siteseo_opt_post('siteseo_redirections_logged_status'));
			}
			if (!empty($_POST['siteseo_redirections_value'])) {
				update_term_meta($term_id, '_siteseo_redirections_value', siteseo_opt_post('siteseo_redirections_value'));
			} else {
				delete_term_meta($term_id, '_siteseo_redirections_value');
			}
			if (isset($_POST['siteseo_redirections_enabled'])) {
				update_term_meta($term_id, '_siteseo_redirections_enabled', 'yes');
			} else {
				delete_term_meta($term_id, '_siteseo_redirections_enabled', '');
			}
		}
		
		// In place of whole $_POST we are just sending the min required fields,
		// We are forced to do it this way to get the WordPress review done.
		$term['tag_ID'] = siteseo_opt_post('tag_ID');
		$term['taxonomy'] = siteseo_opt_post('taxonomy');
		$term['slug'] = siteseo_opt_post('slug');
		$term['name'] = siteseo_opt_post('name');
		$term['description'] = siteseo_opt_post('description');
		$term['siteseo_robots_breadcrumbs'] = siteseo_opt_post('siteseo_robots_breadcrumbs');
		
		do_action('siteseo_seo_metabox_term_save', $term_id, $term);
	}
}

if (is_user_logged_in()) {
	if (is_super_admin()) {
		siteseo_display_seo_term_metaboxe();
	} else {
		global $wp_roles;

		//Get current user role
		if (isset(wp_get_current_user()->roles[0])) {
			$siteseo_user_role = wp_get_current_user()->roles[0];

			//If current user role matchs values from Security settings then apply
			if (function_exists('siteseo_advanced_security_metaboxe_role_hook_option') && '' != siteseo_advanced_security_metaboxe_role_hook_option()) {
				if (array_key_exists($siteseo_user_role, siteseo_advanced_security_metaboxe_role_hook_option())) {
					//do nothing
				} else {
					siteseo_display_seo_term_metaboxe();
				}
			} else {
				siteseo_display_seo_term_metaboxe();
			}
		}
	}
}
