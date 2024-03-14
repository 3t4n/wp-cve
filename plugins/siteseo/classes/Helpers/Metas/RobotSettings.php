<?php

namespace SiteSEO\Helpers\Metas;

if ( ! defined('ABSPATH')) {
	exit;
}

abstract class RobotSettings {
	protected static function getRobotPrimaryCats($id, $postType) {
		$cats = get_categories();

		if ('product' == $postType) {
			$cats = get_the_terms($id, 'product_cat');
		}

		$default = [
			'term_id' => 'none',
			'name'	=> __('None (will disable this feature)', 'siteseo'),
		];

		array_unshift($cats, $default);

		return $cats;
	}

	/**
	 * @since 5.0.0
	 *
	 * @param int|null $id
	 *
	 * @return array[]
	 *
	 *	key: string post meta
	 *	use_default: default value need to use
	 *	default: default value
	 *	label: string label
	 *	placeholder
	 */
	public static function getMetaKeys($id = null) {
		$titleOptionService = siteseo_get_service('TitleOption');

		$postType = get_post_type($id);

		$data = apply_filters('siteseo_api_meta_robot_settings', [
			[
				'key'		 => '_siteseo_robots_index',
				'type'		=> 'checkbox',
				'use_default' => $titleOptionService->getSingleCptNoIndex($id) || $titleOptionService->getTitleNoIndex() || true === post_password_required($id),
				'default'	 => 'yes',
				'label'	   => __('Do not display this page in search engine results / Sitemaps (noindex)', 'siteseo'),
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_robots_follow',
				'type'		=> 'checkbox',
				'use_default' => $titleOptionService->getSingleCptNoFollow($id) || $titleOptionService->getTitleNoFollow(),
				'default'	 => 'yes',
				'label'	   => __('Do not follow links for this page (nofollow)', 'siteseo'),
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_robots_archive',
				'type'		=> 'checkbox',
				'use_default' => $titleOptionService->getTitleNoArchive(),
				'default'	 => 'yes',
				'label'	   => __('Do not display a "Cached" link in the Google search results (noarchive)', 'siteseo'),
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_robots_snippet',
				'type'		=> 'checkbox',
				'use_default' => $titleOptionService->getTitleNoSnippet(),
				'default'	 => 'yes',
				'label'	   => __('Do not display a description in search results for this page (nosnippet)', 'siteseo'),
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_robots_imageindex',
				'type'		=> 'checkbox',
				'use_default' => $titleOptionService->getTitleNoImageIndex(),
				'default'	 => 'yes',
				'label'	   => __('Do not index images for this page (noimageindex)', 'siteseo'),
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_robots_canonical',
				'type'		=> 'input',
				'use_default' => '',
				'placeholder' => sprintf('%s %s', __('Default value: ', 'siteseo'), urldecode(get_permalink($id))),
				'default'	 => '',
				'label'	   => __('Canonical URL', 'siteseo'),
				'visible'	 => true,
			],
			[
				'key'		 => '_siteseo_robots_primary_cat',
				'type'		=> 'select',
				'use_default' => '',
				'placeholder' => '',
				'default'	 => '',
				'label'	   => __('Select a primary category', 'siteseo'),
				'description' => __('Set the category that gets used in the %category% permalink and in our breadcrumbs if you have multiple categories.', 'siteseo'),
				'options'	 => self::getRobotPrimaryCats($id, $postType),
				'visible'	 => ('post' === $postType || 'product' === $postType),
			],
		], $id);

		return $data;
	}
}
