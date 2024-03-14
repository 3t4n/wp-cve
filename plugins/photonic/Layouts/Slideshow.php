<?php

namespace Photonic_Plugin\Layouts;

use Photonic_Plugin\Components\Photo_List;
use Photonic_Plugin\Platforms\Base;

require_once 'Level_One_Gallery.php';

/**
 * Generates the slideshow layout for level 1 objects. Level 2 cannot be displayed as slideshows.
 */
class Slideshow extends Core_Layout implements Level_One_Gallery {
	protected function __construct() {
		global $photonic_slideshow_interval;

		$this->common_parameters = [
			'fx'             => 'slide',    // Splide effects: fade and slide
			'timeout'        => !empty($photonic_slideshow_interval) && is_numeric($photonic_slideshow_interval) ? $photonic_slideshow_interval : 5000,    // Time between slides in ms
			'speed'          => 1000,    // Time for each transition
			'pause'          => true,    // Pause on hover
			'strip-style'    => 'thumbs',
			'controls'       => 'show',
		];
		parent::__construct();
	}

	public function generate_level_1_gallery(Photo_List $photo_list, array $short_code, Base $module): string {
		$photos = $photo_list->photos;
		if (!is_array($photos) || empty($photos)) {
			return '';
		}

		$short_code = array_merge($this->common_parameters, $short_code);

		global $photonic_wp_slide_adjustment, $photonic_slideshow_interval;
		$data_attr = '';
		foreach ($short_code as $key => $value) {
			if (in_array($key, ['speed', 'timeout', 'fx', 'pause', 'layout', 'strip-style', 'controls', 'columns'], true)) {
				$data_attr .= 'data-photonic-' . $key . '="' . esc_attr($value) . '" ';
			}
		}

		$style = esc_attr(empty($short_code['style']) ? (empty($short_code['layout']) ? '' : $short_code['layout']) : $short_code['style']);
		$title_position = esc_attr(empty($short_code['title_position']) ? $photo_list->title_position : $short_code['title_position']);

		global $photonic_slideshow_prevent_autostart;
		if ('wp' === $module->provider) {
			$pager_position = !empty($short_code['style']) ? $short_code['style'] : $short_code['layout'];
		}
		else {
			$pager_position = $short_code['layout'];
		}
		$pager_position = esc_attr($pager_position);

		$perPage = (empty($short_code['columns']) || 'auto' === $short_code['columns'] || !is_numeric($short_code['columns'])) ? 1 : $short_code['columns'];
		$splide_options = [
			'type'         => (!empty($short_code['fx']) && 'fade' === $short_code['fx']) ? 'fade' : 'loop',
			'perPage'      => esc_attr($perPage),
			'autoplay'     => !(isset($photonic_slideshow_prevent_autostart) && 'on' === $photonic_slideshow_prevent_autostart),
			'interval'     => esc_attr((empty($short_code['timeout']) || !is_numeric($short_code['timeout'])) ? (empty($photonic_slideshow_interval) || !is_numeric($photonic_slideshow_interval) ? 5000 : $photonic_slideshow_interval) : $short_code['timeout']),
			'speed'        => esc_attr((empty($short_code['speed']) || !is_numeric($short_code['speed'])) ? 1000 : $short_code['speed']),
			'drag'         => true,
			'pauseOnHover' => !(0 === $short_code['pause'] || '0' === $short_code['pause']),
			'pagination'   => ('strip-below' === $pager_position || 'strip-right' === $pager_position) && 'button' === $short_code['strip-style'],
			'slideFocus'   => false,
			// 'updateOnMove' => true,
			'arrows'       => empty($short_code['controls']) || 'hide' !== $short_code['controls'],
			// 'cover' => $photonic_wp_slide_adjustment !== 'side-white' && $photonic_wp_slide_adjustment !== 'adapt-height',
			// 'heightRatio' => ($photonic_wp_slide_adjustment !== 'side-white' && $photonic_wp_slide_adjustment !== 'adapt-height') ? 0.5 : 0,
			'direction'    => 'ltr',
			'breakpoints'  => [
				480 => [
					'perPage' => 1,
				]
			],
		];

		$splide_options = esc_attr(wp_json_encode($splide_options));
		$ret = '';
		if ('strip-above' === $pager_position && 'thumbs' === $short_code['strip-style']) {
			// $ret .= $this->get_thumbnails($photos, $module);
			$ret .= $this->get_secondary_slider($photos, $module);
		}

		$ret .= "<div id='photonic-slideshow-{$module->provider}-{$module->gallery_index}' class='photonic-slideshow splide {$style} title-display-{$title_position} photonic-slideshow-" . esc_attr($photonic_wp_slide_adjustment) . "' data-splide='$splide_options'>\n";

		$ret .= "\t<div class='splide__track'>\n";
		$ret .= "\t\t<ul class='photonic-slideshow-content splide__list' $data_attr>\n";

		foreach ($photos as $photo) {
			// $ret .= "\t\t\t<li class='photonic-slideshow-img splide__slide' data-thumb='{$photo->thumbnail}'>\n";
			$ret .= "\t\t\t<li class='photonic-slideshow-img splide__slide'>\n";
			$title = esc_attr(wp_kses_post($photo->title));
			$description = esc_attr(wp_kses_post($photo->description));
			if ('desc' === $short_code['caption'] || ('title-desc' === $short_code['caption'] && empty($title)) || ('desc-title' === $short_code['caption'] && !empty($description))) {
				$title = $description;
			}
			elseif (('desc-title' === $short_code['caption'] && empty($title)) || 'none' === $short_code['caption']) {
				$title = '';
			}

			$ret .= "\t\t\t\t<div class='splide__slide__container'>\n";
			if (!isset($photo->video)) {
				if ('tooltip' === $title_position) {
					$tooltip = 'data-photonic-tooltip="' . $title . '" ';
				}
				else {
					$tooltip = '';
				}
				$ret .= "\t\t\t\t<img src='" . esc_url($photo->main_image) . "' alt='{$title}' title='" . (('regular' === $title_position || 'tooltip' === $title_position) ? $title : '') . "' $tooltip id='photonic-slideshow-{$module->provider}-{$module->gallery_index}-{$photo->id}' />\n";
			}
			else {
				$ret .= "\t\t\t\t<video controls loop><source src='" . esc_url($photo->video) . "' type='video/mp4'><img src='" . esc_url($photo->main_image) . "' alt=''></video>";
			}

			$shown_title = '';
			if (in_array($title_position, ['below', 'hover-slideup-show', 'hover-slidedown-show', 'slideup-stick'], true) && !empty($title)) {
				$shown_title = "\t\t\t\t" . '<div class="photonic-title-info">' . "\n\t\t\t\t" . '<div class="photonic-photo-title photonic-title">' . wp_specialchars_decode($title, ENT_QUOTES) . '</div>' . "\n\t\t\t" . '</div>' . "\n";
			}

			if (!empty($title)) {
				$ret .= $shown_title;
			}

			$ret .= "\t\t\t\t</div>\n"; // .splide__slide__container
			$ret .= "\t\t\t</li>\n"; // .splide__slide
		}
		$ret .= "\t\t</ul>\n"; // .splide__list
		$ret .= "\t</div><!-- splide__track -->\n";

		$ret .= "</div><!-- .photonic-slideshow-->\n";

		if (('strip-below' === $pager_position || 'strip-right' === $pager_position) && 'thumbs' === $short_code['strip-style']) {
			$ret .= $this->get_secondary_slider($photos, $module);
		}
		return $ret;
	}

	/**
	 * @param array $photos
	 * @param Base $module
	 * @return string
	 */
	private function get_secondary_slider(array $photos, Base $module): string {
		$thumb_options = [
			'fixedWidth'   => 100,
			'height'       => 60,
			'gap'          => 10,
			'cover'        => true,
			'isNavigation' => true,
			'pagination'   => false,
			'arrows'       => false,
			// 'focus' => 'center',
			'breakpoints'  => [
				'600' => [
					'fixedWidth' => 66,
					'height'     => 40,
				],
			],
		];
		$thumb_options = esc_attr(wp_json_encode($thumb_options));

		$ret = "<div id='photonic-slideshow-{$module->provider}-{$module->gallery_index}-thumbs' class='photonic-slideshow-thumbs splide thumbnails js-thumbnails' data-splide='$thumb_options'>\n";
		$ret .= "\t<div class='splide__track'>\n";
		$ret .= "\t<ul class='splide__list'>\n";
		foreach ($photos as $photo) {
			$ret .= "\t\t<li class='splide__slide' tabindex='0'>\n";
			$ret .= "\t\t\t<img src='" . esc_url($photo->thumbnail) . "' alt=''>\n";
			$ret .= "\t\t</li>\n";
		}
		$ret .= "\t</ul>\n";
		$ret .= "\t</div><!-- photonic-slideshow-thumbs -->\n";
		$ret .= "</div>\n";
		return $ret;
	}
}
