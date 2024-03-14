<?php

namespace Photonic_Plugin\Lightboxes;

require_once 'Lightbox.php';

class None extends Lightbox {
	protected function __construct() {
		$this->library = 'none';
		parent::__construct();
	}
}
