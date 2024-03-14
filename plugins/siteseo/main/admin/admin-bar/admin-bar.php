<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

//Noindex alert?
function siteseo_advanced_appearance_adminbar_noindex_option(){
	
	$options = get_option('siteseo_advanced_option_name');
	
	if(empty($options) || !isset($options['appearance_adminbar_noindex'])) {
		return;
	}

	return $options['appearance_adminbar_noindex'];
}

// Admin bar customization.
add_action('admin_bar_menu', 'siteseo_admin_bar_links', 99);
function siteseo_admin_bar_links() {
	
	if(!current_user_can(siteseo_capability('manage_options', 'admin_bar'))
		|| !(function_exists('siteseo_advanced_appearance_adminbar_option') && '1' != siteseo_advanced_appearance_adminbar_option())
	){
		return;
	}
	
	global $wp_admin_bar;

	$title = '<div id="siteseo-ab-icon" class="ab-item svg siteseo-logo" style="background-image: url('.SITESEO_ASSETS_DIR.'/img/logo-24.svg) !important"></div> ' . __('SiteSEO', 'siteseo');
	$title = apply_filters('siteseo_adminbar_icon', $title);

	$noindex = '';
	if('1' != siteseo_advanced_appearance_adminbar_noindex_option()){
		if ('1' == siteseo_get_service('TitleOption')->getTitleNoIndex() || '1' != get_option('blog_public')) {
			$noindex .= '<a class="wrap-siteseo-noindex" href="' . admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_advanced') . '">';
			$noindex .= '<span class="ab-icon dashicons dashicons-hidden"></span>';
			$noindex .= __('noindex is on!', 'siteseo');
			$noindex .= '</a>';
		}
		$noindex = apply_filters('siteseo_adminbar_noindex', $noindex);
	}
	
	// Adds a new top level admin bar link and a submenu to it
	$wp_admin_bar->add_menu([
		'parent'	=> false,
		'id'		   => 'siteseo',
		'title'		=> $title . $noindex,
		'href'		 => admin_url('admin.php?page=siteseo'),
	]);

	// noindex/nofollow per CPT
	if(function_exists('get_current_screen') && null != get_current_screen()
		&& (get_current_screen()->post_type || get_current_screen()->taxonomy)
	){
		$robots = '';

		$options = get_option('siteseo_titles_option_name');

		if (get_current_screen()->taxonomy) {
			$noindex  = isset($options['titles_single_titles'][get_current_screen()->taxonomy]['noindex']);
			$nofollow = isset($options['titles_single_titles'][get_current_screen()->taxonomy]['nofollow']);
		} else {
			$noindex  = isset($options['titles_single_titles'][get_current_screen()->post_type]['noindex']);
			$nofollow = isset($options['titles_single_titles'][get_current_screen()->post_type]['nofollow']);
		}

		if (get_current_screen()->taxonomy) {
			/* translators: %s taxonomy name */
			$robots .= '<span class="wrap-siteseo-cpt-seo">' . sprintf(__('SEO for "%s"', 'siteseo'), get_current_screen()->taxonomy) . '</span>';
		} else {
			/* translators: %s custom post type name */
			$robots .= '<span class="wrap-siteseo-cpt-seo">' . sprintf(__('SEO for "%s"', 'siteseo'), get_current_screen()->post_type) . '</span>';
		}
		$robots .= '<span class="wrap-siteseo-cpt-noindex">';

		if (true === $noindex) {
			$robots .= '<span class="ab-icon dashicons dashicons-marker on"></span>';
			$robots .= __('noindex is on!', 'siteseo');
		} else {
			$robots .= '<span class="ab-icon dashicons dashicons-marker off"></span>';
			$robots .= __('noindex is off.', 'siteseo');
		}

		$robots .= '</span>';

		$robots .= '<span class="wrap-siteseo-cpt-nofollow">';

		if (true === $nofollow) {
			$robots .= '<span class="ab-icon dashicons dashicons-marker on"></span>';
			$robots .= __('nofollow is on!', 'siteseo');
		} else {
			$robots .= '<span class="ab-icon dashicons dashicons-marker off"></span>';
			$robots .= __('nofollow is off.', 'siteseo');
		}

		$robots .= '</span>';

		$wp_admin_bar->add_menu([
			'parent'	=> 'siteseo',
			'id'		   => 'siteseo_custom_sub_menu_meta_robots',
			'title'		=> $robots,
			'href'		 => admin_url('admin.php?page=siteseo-titles'),
		]);
	}

	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_titles',
		'title'		=> __('Titles & Metas', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-titles'),
	]);
	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_xml_sitemap',
		'title'		=> __('Sitemaps', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-xml-sitemap'),
	]);
	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_social',
		'title'		=> __('Social Networks', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-social'),
	]);
	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_google_analytics',
		'title'		=> __('Analytics', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-google-analytics'),
	]);
	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_instant_indexing',
		'title'		=> __('Instant Indexing', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-instant-indexing'),
	]);
	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_advanced',
		'title'		=> __('Advanced', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-advanced'),
	]);
	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_import_export',
		'title'		=> __('Tools', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-import-export'),
	]);

	do_action('siteseo_admin_bar_items');

	$wp_admin_bar->add_menu([
		'parent'	=> 'siteseo',
		'id'		   => 'siteseo_custom_sub_menu_wizard',
		'title'		=> __('Configuration wizard', 'siteseo'),
		'href'		 => admin_url('admin.php?page=siteseo-setup'),
	]);
	
}
