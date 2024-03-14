<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Lightboxes\Features\Show_Videos_Inline;
use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';
require_once 'Features/Show_Videos_Inline.php';

class Fancybox extends Lightbox {
	use Show_Videos_Inline;

	protected function __construct() {
		$this->library = 'fancybox';
		parent::__construct();
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if ('google' === $module->provider) {
			if (empty($photo_data['video'])) {
				$out['data-fancybox'] = '{type: \"image\"}';
			}
		}

		if (!empty($photo_data['video'])) {
			$out['data-html5-href'] = $photo_data['video'];
		}
		return $out;
	}
}
