<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Components\Photo;
use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class Thickbox extends Lightbox {
	protected function __construct() {
		$this->library = 'thickbox';
		parent::__construct();
	}

	public function get_lightbox_title(Photo $photo, Base $module, $title, $alt_title, $target) {
		return ($title ?: $alt_title);
	}
}
