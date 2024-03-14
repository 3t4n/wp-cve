<?php

namespace SiteSEO\Services\Options;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Constants\Options;

class Sitemap {
	const NAME_SERVICE = 'SitemapOption';

	/**
	 * @since 4.3.0
	 *
	 * @return array
	 */
	public function getOption() {
		return get_option(Options::KEY_OPTION_SITEMAP);
	}

	/**
	 * @since 4.3.0
	 *
	 * @return string|nul
	 *
	 * @param string $key
	 */
	protected function searchOptionByKey($key) {
		$data = $this->getOption();

		if (empty($data)) {
			return null;
		}

		if ( ! isset($data[$key])) {
			return null;
		}

		return $data[$key];
	}

	/**
	 * @since 4.3.0
	 *
	 * @return string|null
	 */
	public function isEnabled() {
		return $this->searchOptionByKey('xml_sitemap_general_enable');
	}

	/**
	 * @since 5.7.0
	 *
	 * @return string|null
	 */
	public function videoIsEnabled() {
		return $this->searchOptionByKey('xml_sitemap_video_enable');
	}

	/**
	 * @since 4.3.0
	 *
	 * @return string|null
	 */
	public function getPostTypesList() {
		return $this->searchOptionByKey('xml_sitemap_post_types_list');
	}

	/**
	 * @since 4.3.0
	 *
	 * @return string|null
	 */
	public function getTaxonomiesList() {
		return $this->searchOptionByKey('xml_sitemap_taxonomies_list');
	}

	/**
	 * @since 4.3.0
	 *
	 * @return string|null
	 */
	public function authorIsEnable() {
		return $this->searchOptionByKey('xml_sitemap_author_enable');
	}

	/**
	 * @since 4.3.0
	 *
	 * @return string|null
	 */
	public function imageIsEnable() {
		return $this->searchOptionByKey('xml_sitemap_img_enable');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string|null
	 */
	public function getHtmlEnable() {
		return $this->searchOptionByKey('xml_sitemap_html_enable');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string|null
	 */
	public function getHtmlMapping() {
		return $this->searchOptionByKey('xml_sitemap_html_mapping');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string|null
	 */
	public function getHtmlExclude() {
		return $this->searchOptionByKey('xml_sitemap_html_exclude');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string|null
	 */
	public function getHtmlOrder() {
		return $this->searchOptionByKey('xml_sitemap_html_order');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string|null
	 */
	public function getHtmlOrderBy() {
		return $this->searchOptionByKey('xml_sitemap_html_orderby');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string|null
	 */
	public function getHtmlDate() {
		return $this->searchOptionByKey('xml_sitemap_html_date');
	}

	/**
	 * @since 5.9.0
	 *
	 * @return string|null
	 */
	public function getHtmlArchiveLinks() {
		return $this->searchOptionByKey('xml_sitemap_html_archive_links');
	}
}
