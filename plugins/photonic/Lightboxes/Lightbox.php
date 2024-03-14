<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Components\Photo;
use Photonic_Plugin\Platforms\Base;

abstract class Lightbox {
	/** @var array */
	public $class;

	public $supports_video;

	public $library;
	public $default_lightbox_text;

	/**
	 * Lightbox constructor.
	 */
	protected function __construct() {
		require_once PHOTONIC_PATH . '/Platforms/Base.php';
		$this->class                 = ['photonic-lb', sanitize_html_class('photonic-' . $this->library), sanitize_html_class($this->library)];
		$this->default_lightbox_text = apply_filters('photonic_default_lightbox_text', esc_attr__('View', 'photonic'));
	}

	final public static function get_instance() {
		static $instances = array();
		$called_class = get_called_class();

		if (!isset($instances[$called_class])) {
			$instances[$called_class] = new $called_class();
		}
		return $instances[$called_class];
	}

	/**
	 * @param $rel_id
	 * @param Base $module
	 * @return array
	 */
	public function get_gallery_attributes($rel_id, Base $module): array {
		return [
			'class'    => $this->class,
			'rel'      => ['lightbox-photonic-' . $module->provider . '-stream-' . (empty($rel_id) ? $module->gallery_index : esc_attr($rel_id))],
			'specific' => [],
		];
	}

	public function get_container_classes(): string {
		return '';
	}

	/**
	 * Some lightboxes require some additional attributes for individual photos. E.g. LightGallery requires something to show the title etc.
	 * This method returns such additional information. Not to be confused with <code>get_lightbox_attributes</code>, which
	 * returns information for the gallery as a whole.
	 *
	 * @param array $photo_data
	 * @param Base $module
	 * @return array
	 */
	public function get_photo_attributes(array $photo_data, Base $module): array { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$out = [];
		if (!empty($photo_data['video'])) {
			$out['data-photonic-media-type'] = 'video';
		}
		else {
			$out['data-photonic-media-type'] = 'image';
		}
		return $out;
	}

	/**
	 * Used to generate markup for video elements in a grid so that they may be processed in a lightbox. If a lightbox handles videos
	 * without any special handling, the default method suffices. But in some cases the lightbox may need to display video as an inline
	 * element, in which case the respective lightbox file overrides this (typically via the Show_Videos_Inline trait).
	 *
	 * @param Photo $photo
	 * @param Base $module
	 * @param string $indent
	 * @return string
	 */
	public function get_video_markup(Photo $photo, Base $module, string $indent): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		return '';
	}

	public function get_lightbox_title(Photo $photo, Base $module, $title, $alt_title, $target) {
		$url = $this->get_title_link($photo);
		if (empty($title)) {
			$shown_title = $module->link_lightbox_title ? $this->default_lightbox_text : $alt_title;
		}
		else {
			$shown_title = $title;
		}

		if (!empty($shown_title)) {
			// In the following esc_attr() is correct, because this information is used within attributes.
			if ($module->link_lightbox_title && !empty($url)) {
				$title_markup = esc_attr("<a href='$url' $target>") . esc_attr(stripslashes(wp_filter_nohtml_kses($shown_title))) . esc_attr("</a>");

				if ($module->show_buy_link && !empty($photo->buy_link)) {
					$title_markup .= esc_attr('<a class="photonic-buy-link" href="' . esc_url_raw($photo->buy_link) . '" target="_blank" title="' . __('Buy', 'photonic') . '"><div class="icon-buy"></div></a>');
				}
			}
			else {
				$title_markup = esc_attr(stripslashes(wp_filter_nohtml_kses($shown_title)));
			}
		}
		else {
			$title_markup = '';
		}
		return apply_filters('photonic_lightbox_title_markup', $title_markup);
	}

	/**
	 * @param Photo $photo
	 * @param array $short_code
	 * @param Base $module
	 * @return mixed
	 */
	public function get_grid_link(Photo $photo, array $short_code, Base $module): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		if ('none' === $this->library && !empty($photo->main_page) && !empty($short_code['link']) && 'page' === $short_code['link']) {
			return esc_url($photo->main_page);
		}
		return esc_url($photo->video ?: $photo->main_image);
	}

	/**
	 * @param $photo
	 * @return string
	 */
	public function get_title_link($photo): string {
		return esc_url($photo->main_page ?: '');
	}
}
