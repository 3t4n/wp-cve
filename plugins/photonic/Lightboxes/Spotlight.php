<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class Spotlight extends Lightbox {
	protected function __construct() {
		$this->library = 'spotlight';
		parent::__construct();
		$this->class = ['photonic-lb', 'photonic-spotlight'];
	}

	public function get_container_classes(): string {
		return "spotlight-group";
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if (!empty($photo_data['video'])) {
			$out['data-src-mp4'] = $photo_data['video'];
			$out['data-media'] = 'video';
			$out['data-poster'] = $photo_data['poster'];
		}
		return $out;
	}
}
