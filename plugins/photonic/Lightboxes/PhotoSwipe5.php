<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Lightboxes\Features\Show_Videos_Inline;
use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';
require_once 'Features/Show_Videos_Inline.php';

class PhotoSwipe5 extends Lightbox {
	use Show_Videos_Inline;

	protected function __construct() {
		$this->library = 'photoswipe5';
		parent::__construct();
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if (!empty($photo_data['height']) && !empty($photo_data['width'])) {
			$out['data-pswp-height'] = $photo_data['height'];
			$out['data-pswp-width'] = $photo_data['width'];
		}

		if (!empty($photo_data['video'])) {
			$out['data-html5-href'] = $photo_data['video'];
		}
		return $out;
	}
}
