<?php 

namespace Element_Ready\Base;

class BaseController
{
	public $plugin_path;

	public $plugin_url;

	public $plugin;

	public function __construct() {
		$this->plugin_path = ELEMENT_READY_DIR_PATH;
		$this->plugin_url  = ELEMENT_READY_DIR_URL;
		$this->plugin      = ELEMENT_READY_BASE;
	}
}