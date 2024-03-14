<?php
namespace SiteSeoElementorAddon\Admin;

class Siteseo_Meta_Helper {

	public static function get_meta_fields() {
		return [
			'_siteseo_titles_title',
			'_siteseo_titles_desc',
			'_siteseo_robots_index',
			'_siteseo_robots_follow',
			'_siteseo_robots_imageindex',
			'_siteseo_robots_archive',
			'_siteseo_robots_snippet',
			'_siteseo_robots_canonical',
			'_siteseo_robots_primary_cat',
			'_siteseo_robots_breadcrumbs',
			'_siteseo_social_fb_title',
			'_siteseo_social_fb_desc',
			'_siteseo_social_fb_img',
			'_siteseo_social_twitter_title',
			'_siteseo_social_twitter_desc',
			'_siteseo_social_twitter_img',
			'_siteseo_redirections_enabled',
			'_siteseo_redirections_type',
			'_siteseo_redirections_value',
			'_siteseo_analysis_target_kw',
			'_siteseo_analysis_data',
		];
	}
}

function siteseo_get_meta_helper() {
	return new Siteseo_Meta_Helper();
}
