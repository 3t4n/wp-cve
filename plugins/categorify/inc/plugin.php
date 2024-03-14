<?php

class Categorify_Plugin{

	public function __construct()
	{	
		$this->init_files();
	}

	private function init_files()
	{
		include_once ( CATEGORIFY_PATH . 'inc/category.php');
		include_once ( CATEGORIFY_PATH . 'inc/helper.php');
		include_once ( CATEGORIFY_PATH . 'inc/sidebar.php');
		include_once ( CATEGORIFY_PATH . 'inc/settings/settings.php');
	}

}

new Categorify_Plugin();