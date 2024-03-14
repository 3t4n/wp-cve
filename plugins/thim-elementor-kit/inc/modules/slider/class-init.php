<?php
namespace Thim_EL_Kit\Modules\Slider;

use Thim_EL_Kit\SingletonTrait;

class Init {

	use SingletonTrait;

	public function __construct() {
		$this->includes();
	}

	public function includes() {
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/modules/slider/class-post-type.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/modules/slider/class-taxonomy-metabox.php';
	}
}

Init::instance();
