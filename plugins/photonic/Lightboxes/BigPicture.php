<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class BigPicture extends Lightbox {
	protected function __construct() {
		$this->library = 'bigpicture';
		parent::__construct();
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if (!empty($photo_data['video'])) {
			$out['data-bp'] = $photo_data['video'];
			$out['data-bp-type'] = 'video';
		}
		else {
			$out['data-bp'] = $photo_data['image'];
		}
		return $out;
	}
}
