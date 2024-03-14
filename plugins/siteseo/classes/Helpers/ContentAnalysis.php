<?php

namespace SiteSEO\Helpers;

if ( ! defined('ABSPATH')) {
	exit;
}

abstract class ContentAnalysis {
	public static function getData() {
		$data = [
			'all_canonical'=> [
				'title'  => __('Canonical URL', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'schemas'=> [
				'title'  => __('Structured data types', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'old_post'=> [
				'title'  => __('Last modified date', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'words_counter'=> [
				'title'  => __('Words counter', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'keywords_density'=> [
				'title'  => __('Keywords density', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'keywords_permalink'=> [
				'title'  => __('Keywords in permalink', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'headings'=> [
				'title'  => __('Headings', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'meta_title'=> [
				'title'  => __('Meta title', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'meta_desc'=> [
				'title'  => __('Meta description', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'social'=> [
				'title'  => __('Social meta tags', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'robots'=> [
				'title'  => __('Meta robots', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'img_alt'=> [
				'title'  => __('Alternative texts of images', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'nofollow_links'=> [
				'title'  => __('NoFollow Links', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'outbound_links'=> [
				'title'  => __('Outbound Links', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'internal_links'=> [
				'title'  => __('Internal Links', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
		];

		return apply_filters('siteseo_get_content_analysis_data', $data);
	}
}
