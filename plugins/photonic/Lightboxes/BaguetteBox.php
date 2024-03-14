<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class BaguetteBox extends Lightbox {
	protected function __construct() {
		$this->library = 'baguettebox';
		parent::__construct();
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if (!empty($photo_data['video'])) {
			$out['data-html5-href'] = $photo_data['video'];
			$out['data-content-type'] = 'video';
		}
		else {
			$out['data-content-type'] = 'image';
		}
		return $out;
	}
}
