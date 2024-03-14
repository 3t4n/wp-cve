<?php

namespace ZPOS\Admin;

use ZPOS\Admin;
use ZPOS\Admin\Stations\Post;
use ZPOS\Plugin;
use Zprint\POS;

class Menu
{
	protected $parent;
	public $links;

	public function __construct()
	{
		$this->parent = Post::parentLink();

		add_action('admin_menu', [$this, 'init']);
		add_action('admin_enqueue_scripts', [$this, 'scripts']);
	}

	public function init()
	{
		global $submenu;

		// remove add new link
		unset($submenu[$this->parent][10]);

		for ($l = 0; $l < count($this->links); $l++) {
			/** @var Admin\Setting\Page $link */
			$link = $this->links[$l];

			add_submenu_page($this->parent, $link->title, $link->title, $link->capability, $link->slug, [
				$link,
				'render',
			]);
		}

		if (isset($submenu[$this->parent])) {
			$pos_submenu = &$submenu[$this->parent];

			if (is_array($pos_submenu)) {
				foreach ($pos_submenu as $key => $menu) {
					if ($menu[2] === 'edit.php?post_type=' . Post::TYPE) {
						$pos_submenu[$key][0] = __('Stations', 'zpos-wp-api');
					}
				}
			}
		}
	}

	public function scripts()
	{
		wp_enqueue_script('pos_menu', Plugin::getAssetUrl('menu.js'), ['pos_commons']);
		if (Plugin::getManifest('menu.css')) {
			wp_enqueue_style('pos_menu', Plugin::getAssetUrl('menu.css'));
		}
	}
}
