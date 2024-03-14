<?php

namespace Photonic_Plugin\Core;

class Utilities {
	public static function get_formatted_post_type_array() {
		global $photonic_post_type_array;
		$ret = [];

		$post_types = get_post_types(['show_ui' => 1], 'objects');
		if (!empty($post_types)) {
			foreach ($post_types as $name => $post_type) {
				$ret[$name] = ["title" => $post_type->label . " (Post type: $name)", "depth" => 0];
			}
		}

		$photonic_post_type_array = $ret;
		return $ret;
	}

	public static function get_pages() {
		$pages = get_pages();
		$output = [
			'' => '',
		];
		foreach ($pages as $page) {
			$output[$page->ID] = $page->post_title;
		}
		return $output;
	}

	public static function title_caption_options($blank = false, $selection = false) {
		$ret = [
			''           => esc_html__('Default from settings', 'photonic'),
			'none'       => esc_html__('No title / caption / description', 'photonic'),
			'title'      => esc_html__('Always use the photo title, even if blank', 'photonic'),
			'desc'       => esc_html__('Always use the photo description / caption, even if blank', 'photonic'),
			'desc-title' => esc_html__('Use the photo description / caption. If blank use the title', 'photonic'),
			'title-desc' => esc_html__('Use the photo title. If blank use the description / caption', 'photonic'),
		];

		if (!$blank) {
			unset($ret['']);
		}
		elseif (!empty($ret[$selection])) {
			$ret[''] .= ' - ' . $ret[$selection];
		}

		return $ret;
	}

	public static function layout_options($show_blank = false, $blank_text = '') {
		$ret = [];
		if ($show_blank) {
			$ret[''] = $blank_text;
		}
		return array_merge(
			$ret,
			[
				'strip-below' => esc_html__('Thumbnail strip below slideshow', 'photonic'),
				'strip-above' => esc_html__('Thumbnail strip above slideshow', 'photonic'),
				'strip-right' => esc_html__('OBSOLETE - Thumbnail strip to the right of the slideshow', 'photonic'),
				'no-strip'    => esc_html__('Slideshow without thumbnails', 'photonic'),
				'square'      => esc_html__('Square thumbnail grid, lightbox', 'photonic'),
				'circle'      => esc_html__('Circular thumbnail grid, lightbox', 'photonic'),
				'random'      => esc_html__('Random justified gallery, lightbox', 'photonic'),
				'masonry'     => esc_html__('Masonry layout, lightbox', 'photonic'),
				'mosaic'      => esc_html__('Mosaic layout, lightbox', 'photonic'),
			]
		);
	}

	public static function media_options($blank = false, $selection = false) {
		$options = [
			''       => esc_html__('Default from settings', 'photonic'),
			'photos' => esc_html__('Photos only', 'photonic'),
			'videos' => esc_html__('Videos only', 'photonic'),
			'all'    => esc_html__('Both photos and videos', 'photonic'),
		];

		if (!$blank) {
			unset($options['']);
		}
		elseif (!empty($options[$selection])) {
			$options[''] .= ' - ' . $options[$selection];
		}

		return $options;
	}

	/**
	 * @param $show_full
	 * @param bool $return_formatted
	 * @return array
	 */
	public static function get_wp_image_sizes($show_full, $return_formatted = false) {
		global $_wp_additional_image_sizes;
		$image_sizes = [];
		$standard_sizes = ['thumbnail', 'medium', 'large'];
		if ($show_full) {
			$standard_sizes[] = 'full';
		}
		foreach ($standard_sizes as $standard_size) {
			if ('full' !== $standard_size) {
				$image_sizes[$standard_size] = ['width' => get_option($standard_size . '_size_w'), 'height' => get_option($standard_size . '_size_h')];
			}
			else {
				$image_sizes[$standard_size] = ['width' => esc_html__('Original width', 'photonic'), 'height' => esc_html__('Original height', 'photonic')];
			}
		}
		if (is_array($_wp_additional_image_sizes)) {
			$image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
		}

		if ($return_formatted) {
			$formatted = [];
			foreach ($image_sizes as $size_name => $size_attrs) {
				$formatted[$size_name] = "$size_name ({$size_attrs['width']} &times; {$size_attrs['height']})";
			}
			return $formatted;
		}
		return $image_sizes;
	}
}
