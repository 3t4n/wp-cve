<?php

namespace CatFolder_Document_Gallery;

use CatFolder_Document_Gallery\Utils\SingletonTrait;

class Initialize {

	use SingletonTrait;

	protected function __construct() {
		\CatFolder_Document_Gallery\Engine\Blocks\Blocks::get_instance();
		\CatFolder_Document_Gallery\Engine\RestAPI::get_instance();
		\CatFolder_Document_Gallery\Engine\PostType::get_instance();
		\CatFolder_Document_Gallery\Engine\Shortcode::get_instance();
		\CatFolder_Document_Gallery\Engine\Thumbnail\Thumbnail::get_instance();
	}
}
