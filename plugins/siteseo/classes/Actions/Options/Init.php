<?php

namespace SiteSEO\Actions\Options;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Core\Hooks\ActivationHook;
use SiteSEO\Helpers\TagCompose;
use SiteSEO\Tags\PostTitle;
use SiteSEO\Tags\SiteTagline;
use SiteSEO\Tags\SiteTitle;

class Init implements ActivationHook
{
	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	public function activate() {
		//Enable features==========================================================================
		$this->setToggleOptions();

		//Titles & metas===========================================================================
		$this->setTitleOptions();

		//XML Sitemap==============================================================================
		$this->setSitemapOptions();

		//Social===================================================================================
		$this->setSocialOptions();

		//Advanced=================================================================================
		$this->setAdvancedOptions();
	}

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	protected function setAdvancedOptions() {
		$advancedOptions = get_option('siteseo_advanced_option_name');

		//Init if option doesn't exist
		if (false === $advancedOptions) {
			$advancedOptions = [];
		}

		$advancedOptions = [
			'advanced_attachments' => '1',
			'advanced_tax_desc_editor' => '1',
			'appearance_title_col' => '1',
			'appearance_meta_desc_col' => '1',
			'appearance_score_col' => '1',
			'appearance_noindex_col' => '1',
			'appearance_nofollow_col' => '1',
			'appearance_universal_metabox_disable'	=> '1',
		];

		//Check if the value is an array (important!)
		if (is_array($advancedOptions)) {
			add_option('siteseo_advanced_option_name', $advancedOptions);
		}
	}

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	protected function setSocialOptions() {
		$socialOptions = get_option('siteseo_social_option_name');

		//Init if option doesn't exist
		if (false === $socialOptions) {
			$socialOptions = [];
		}

		$socialOptions = [
			'social_facebook_og'  => '1',
			'social_twitter_card' => '1',
		];

		//Check if the value is an array (important!)
		if (is_array($socialOptions)) {
			add_option('siteseo_social_option_name', $socialOptions);
		}
	}

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	protected function setSitemapOptions() {
		$sitemapOptions = get_option('siteseo_xml_sitemap_option_name');

		//Init if option doesn't exist
		if (false === $sitemapOptions) {
			$sitemapOptions = [];
		}

		$sitemapOptions = [
			'xml_sitemap_general_enable' => '1',
			'xml_sitemap_img_enable' => '1',
		];

		global $wp_post_types;

		$args = [
			'show_ui' => true,
		];

		$post_types = get_post_types($args, 'objects', 'and');

		foreach ($post_types as $siteseo_cpt_key => $siteseo_cpt_value) {
			if ('post' == $siteseo_cpt_key || 'page' == $siteseo_cpt_key || 'product' == $siteseo_cpt_key) {
				$sitemapOptions['xml_sitemap_post_types_list'][$siteseo_cpt_key]['include'] = '1';
			}
		}

		$args = [
			'show_ui' => true,
			'public'  => true,
		];

		$taxonomies = get_taxonomies($args, 'objects', 'and');

		foreach ($taxonomies as $siteseo_tax_key => $siteseo_tax_value) {
			if ('category' == $siteseo_tax_key || 'post_tag' == $siteseo_tax_key) {
				$sitemapOptions['xml_sitemap_taxonomies_list'][$siteseo_tax_key]['include'] = '1';
			}
		}

		//Check if the value is an array (important!)
		if (is_array($sitemapOptions)) {
			add_option('siteseo_xml_sitemap_option_name', $sitemapOptions);
		}
	}

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	protected function setToggleOptions() {
		$toggleOptions = get_option('siteseo_toggle');

		//Init if option doesn't exist
		if (false === $toggleOptions) {
			$toggleOptions = [];
		}

		$toggleOptions = [
			'toggle-titles' => '1',
			'toggle-xml-sitemap' => '1',
			'toggle-social' => '1',
			'toggle-google-analytics' => '1',
			'toggle-instant-indexing' => '1',
			'toggle-advanced' => '1',
			'toggle-dublin-core' => '1',
			'toggle-local-business' => '1',
			'toggle-rich-snippets' => '1',
			'toggle-breadcrumbs' => '1',
			'toggle-robots' => '1',
			'toggle-404' => '1',
			'toggle-bot' => '1',
			'toggle-inspect-url' => '1',
			'toggle-ai' => '1',
		];

		if (is_plugin_active('woocommerce/woocommerce.php')) {
			$toggleOptions['toggle-woocommerce'] = '1';
		}

		// Check if the value is an array (important!)
		if (is_array($toggleOptions)) {
			update_option('siteseo_toggle', $toggleOptions);
		}
	}

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	protected function setTitleOptions() {
		$titleOptions = get_option('siteseo_titles_option_name');

		//Init if option doesn't exist
		if (false === $titleOptions) {
			$titleOptions = [];
		}

		//Site Title
		$titleOptions = [
			'titles_home_site_title' => TagCompose::getValueWithTag(SiteTitle::NAME),
			'titles_home_site_desc'  => TagCompose::getValueWithTag(SiteTagline::NAME),
			'titles_sep'			 => '-',
		];

		//Post Types
		$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
		if ( ! empty($postTypes)) {
			foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
				$titleOptions['titles_single_titles'][$siteseo_cpt_key] = [
					'title' => sprintf(
						'%s %s %s',
						TagCompose::getValueWithTag(PostTitle::NAME),
						'%%sep%%',
						TagCompose::getValueWithTag(SiteTitle::NAME)
					),
					'description' => TagCompose::getValueWithTag('post_excerpt'),
				];
			}
		}

		//Taxonomies
		$taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();
		if (empty($taxonomies)) {
			foreach ($taxonomies as $siteseo_tax_key => $siteseo_tax_value) {
				//Title
				if ('category' == $siteseo_tax_key) {
					$titleOptions['titles_tax_titles'][$siteseo_tax_key]['title'] = '%%_category_title%% %%current_pagination%% %%sep%% %%sitetitle%%';
				} elseif ('post_tag' == $siteseo_tax_key) {
					$titleOptions['titles_tax_titles'][$siteseo_tax_key]['title'] = '%%tag_title%% %%current_pagination%% %%sep%% %%sitetitle%%';
				} else {
					$titleOptions['titles_tax_titles'][$siteseo_tax_key]['title'] = '%%term_title%% %%current_pagination%% %%sep%% %%sitetitle%%';
				}

				//Desc
				if ('category' == $siteseo_tax_key) {
					$titleOptions['titles_tax_titles'][$siteseo_tax_key]['description'] = '%%_category_description%%';
				} elseif ('post_tag' == $siteseo_tax_key) {
					$titleOptions['titles_tax_titles'][$siteseo_tax_key]['description'] = '%%tag_description%%';
				} else {
					$titleOptions['titles_tax_titles'][$siteseo_tax_key]['description'] = '%%term_description%%';
				}
			}
		}

		//Archives
		$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
		if (! empty($postTypes)) {
			foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
				$titleOptions['titles_archive_titles'][$siteseo_cpt_key]['title'] = '%%cpt_plural%% %%current_pagination%% %%sep%% %%sitetitle%%';
			}
		}

		//Author
		$titleOptions['titles_archives_author_title']   = '%%post_author%% %%sep%% %%sitetitle%%';
		$titleOptions['titles_archives_author_noindex'] = '1';

		//Date
		$titleOptions['titles_archives_date_title']   = '%%archive_date%% %%sep%% %%sitetitle%%';
		$titleOptions['titles_archives_date_noindex'] = '1';

		//BuddyPress Groups
		if (is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php')) {
			$titleOptions['titles_bp_groups_title'] = '%%post_title%% %%sep%% %%sitetitle%%';
		}

		//Search
		$titleOptions['titles_archives_search_title'] = '%%search_keywords%% %%sep%% %%sitetitle%%';

		//404
		$titleOptions['titles_archives_404_title'] = __('404 - Page not found', 'siteseo') . ' %%sep%% %%sitetitle%%';

		//Link rel prev/next
		$titleOptions['titles_paged_rel'] = '1';

		//Check if the value is an array (important!)
		if (is_array($titleOptions)) {
			add_option('siteseo_titles_option_name', $titleOptions);
		}
	}
}
