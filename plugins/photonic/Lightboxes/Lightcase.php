<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class Lightcase extends Lightbox {
	protected function __construct() {
		$this->library = 'lightcase';
		parent::__construct();
	}

	/**
	 * @param $rel_id
	 * @param Base $module
	 * @return array
	 */
	public function get_gallery_attributes($rel_id, Base $module): array {
		global $photonic_slideshow_mode;
		return [
			'class'    => $this->class,
			'rel'      => ['lightbox-photonic-' . $module->provider . '-stream-' . (empty($rel_id) ? $module->gallery_index : $rel_id)],
			'specific' => [
				'data-rel' => 'lightcase:lightbox-photonic-' . $module->provider . '-stream-' . (empty($rel_id) ? $module->gallery_index : $rel_id) . ((isset($photonic_slideshow_mode) && 'on' === $photonic_slideshow_mode) ? ':slideshow' : '')
			],
		];
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if ('google' === $module->provider) {
			if (empty($photo_data['video'])) {
				$out['data-lc-options'] = esc_attr('{"type": "image"}');
			}
			else {
				$out['data-lc-options'] = esc_attr('{"type": "video"}');
			}
		}
		elseif ('flickr' === $module->provider && !empty($photo_data['video'])) {
			$out['data-html5-href'] = $photo_data['video'];
		}
		return $out;
	}
}
