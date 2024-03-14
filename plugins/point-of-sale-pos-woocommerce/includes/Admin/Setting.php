<?php

namespace ZPOS\Admin;

use ZPOS\Admin\Setting\Page;
use ZPOS\Admin\Tabs\General;
use ZPOS\Admin\Tabs\Gateway;
use ZPOS\Admin\Tabs\Users;
use ZPOS\Admin\Tabs\Connection;
use ZPOS\Admin\Tabs\Debug;
use ZPOS\Plugin;

class Setting extends Page
{
	public $capability = 'manage_woocommerce_pos';
	public $title;

	public function __construct($slug, $parent)
	{
		parent::__construct($slug, $parent);

		$this->title = __('Settings', 'zpos-wp-api');

		add_action('admin_enqueue_scripts', [$this, 'scripts']);

		add_action('save_post_product', [$this, 'update_product_variations']);
	}

	public function getTabs()
	{
		return [new General(), new Gateway(), new Users(), new Connection(), new Debug()];
	}

	public function scripts()
	{
		if ($this->isRequested()) {
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');

			wp_enqueue_script('pos_admin', Plugin::getAssetUrl('admin.js'), ['pos_commons', 'wp-i18n']);

			if (Plugin::getManifest('admin.css')) {
				wp_enqueue_style('pos_admin', Plugin::getAssetUrl('admin.css'));
			}
		}
	}

	public function update_product_variations($post_id)
	{
		$product = wc_get_product($post_id);
		if ($product->is_type('variable')) {
			foreach ($product->get_children() as $child_id) {
				wp_update_post([
					'ID' => $child_id,
					'post_modified_gmt' => get_gmt_from_date(current_time('mysql')),
				]);
			}
		}
	}
}
