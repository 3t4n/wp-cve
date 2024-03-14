<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class GLightbox extends Lightbox {
	protected function __construct() {
		$this->library = 'glightbox';
		parent::__construct();
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if (empty($photo_data['video'])) {
			$out['data-type'] = 'image';
		}
		elseif (in_array($module->provider, ['google', 'flickr'], true)) {
			$out['data-type'] = 'video';
			$out['data-format'] = 'mp4';
		}
		else {
			$out['data-type'] = 'video';
		}
		return $out;
	}
}
