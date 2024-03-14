<?php

namespace ZPOS\Admin;

use ZPOS\Admin\Setting\Page;
use ZPOS\Admin\Tabs\Addons as AddonsTab;
use ZPOS\Plugin;

class Addons extends Page
{
	public $capability = 'access_woocommerce_pos_addons';
	public $title = 'Addons';

	public function __construct($slug, $parent)
	{
		parent::__construct($slug, $parent);

		add_action('admin_enqueue_scripts', [$this, 'scripts']);
	}

	public function getTabs()
	{
		return [new AddonsTab()];
	}

	public function scripts()
	{
		if ($this->isRequested()) {
			wp_enqueue_script('pos_admin', Plugin::getAssetUrl('admin.js'), ['pos_commons', 'wp-i18n']);

			if (Plugin::getManifest('admin.css')) {
				wp_enqueue_style('pos_admin', Plugin::getAssetUrl('admin.css'));
			}
		}
	}
}
