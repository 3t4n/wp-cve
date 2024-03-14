<?php

namespace WilokeEmailCreator\Templates\Controllers;
class TemplateRegistry
{
	public function __construct()
	{
		add_action('init', [$this, 'registerPostType'], 1);
	}

	public function registerPostType()
	{
		$aConfig = include plugin_dir_path(__FILE__) . "../Configs/PostType.php";
		foreach ($aConfig as $key => $aItem) {
			register_post_type(
				$aItem['postType'],
				$aItem
			);
		}
	}
}
