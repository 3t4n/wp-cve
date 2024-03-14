<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Components\Photo;
use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class Lightgallery extends Lightbox {
	protected function __construct() {
		$this->library = 'lightgallery';
		parent::__construct();
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if (!empty($photo_data['download'])) {
			$out['data-download-url'] = $photo_data['download'];
		}

		if (!empty($photo_data['video'])) {
			$out['data-video'] = esc_attr('{"source": [{"src": "' . $photo_data['video'] . '", "type": "video/mp4"}], "attributes": {"preload": false, "playsinline": true, "controls": true}}');
		}

		$out['data-sub-html'] = $photo_data['title'];

		return $out;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_grid_link(Photo $photo, array $short_code, Base $module): string {
		if (!empty($photo->video)) {
			return '';
		}
		return parent::get_grid_link($photo, $short_code, $module);
	}
}
