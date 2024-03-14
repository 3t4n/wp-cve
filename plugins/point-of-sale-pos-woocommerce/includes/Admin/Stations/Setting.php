<?php

namespace ZPOS\Admin\Stations;

use ZPOS\Admin\Setting\Post;
use ZPOS\Admin\Stations\Post as PostType;
use ZPOS\Admin\Stations\Tabs\Cart;
use ZPOS\Admin\Stations\Tabs\General;
use ZPOS\Admin\Stations\Tabs\Products;
use ZPOS\Admin\Stations\Tabs\Tax;
use ZPOS\Admin\Stations\Tabs\Users;
use ZPOS\Plugin;
use ZPOS\Station;

class Setting extends Post
{
	public function __construct()
	{
		parent::__construct(PostType::TYPE);
	}

	public function getTabs()
	{
		return [new General(), new Products(), new Cart(), new Tax(), new Users()];
	}

	public function enqueueScripts()
	{
		parent::enqueueScripts();

		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');

		wp_enqueue_script('pos_admin', Plugin::getAssetUrl('admin.js'), ['pos_commons', 'wp-i18n']);

		if (Plugin::getManifest('admin.css')) {
			wp_enqueue_style('pos_admin', Plugin::getAssetUrl('admin.css'));
		}
	}

	public static function isWCStationEdit()
	{
		if (!is_admin() || !isset($_GET['post'])) {
			return false;
		}
		return Station::isWCStation($_GET['post']);
	}
}
