<?php

namespace ZPOS;

use ZPOS\Admin\Stations\Post as StationPost;

class Admin
{
	const SLUG = PLUGIN_NAME;
	public $menu;

	public function __construct()
	{
		if (Plugin::isMobileApp()) {
			define('IFRAME_REQUEST', true);
		}

		add_action('admin_enqueue_scripts', [$this, 'scripts'], 1);

		$this->initMenu();
		$this->noticeSSL();

		new Admin\QuickStart();
		new Admin\Analytics();
		new Admin\Reports();
		new Admin\Orders();
		new Admin\User();
		new Admin\Woocommerce();
		new Admin\Layout();
		new Admin\Stations\Setup();
	}

	public static function getPageURL($name)
	{
		switch ($name) {
			case 'pos-stations':
			case 'stations':
				return add_query_arg('post_type', StationPost::TYPE, admin_url('edit.php'));
			case 'addons':
				return add_query_arg('page', self::SLUG . '_addons', admin_url(StationPost::parentLink()));
			case 'settings':
				return add_query_arg('page', self::SLUG, admin_url(StationPost::parentLink()));
		}
	}

	private function initMenu()
	{
		$this->menu = new Admin\Menu();
		$setting = new Admin\Setting(self::SLUG, StationPost::parentLink());
		// todo: ADDONS, hidden for the v1 launch
		// $addons = new Addons(self::SLUG . '_addons', Post::parentLink());
		// $this->menu->links[] = $addons;
		$this->menu->links[] = $setting;
	}

	public function scripts()
	{
		wp_register_script('pos_commons', Plugin::getAssetUrl('commons.js'), false, false, true);
		wp_localize_script('pos_commons', 'POS_COMMONS', [
			'PUBLIC_PATH' => Plugin::getUrl('assets/core/', true),
		]);
		Model\VatControl::enqueue_admin_assets();
	}

	public function noticeSSL()
	{
		if (is_ssl()) {
			return;
		}

		add_action('admin_notices', function () {
			?>
			<div class="notice notice-warning">
				<p>
					<b><?php _e('Point of Sale POS WooCommerce', 'zpos-wp-api'); ?></b>
					<br>
					<?php _e(
     	'A SSL certificate is not detected. The checkout may not be secure. Please ensure your server has a valid SSL Certificate.',
     	'zpos-wp-api'
     ); ?>
				</p>
			</div>
			<?php
		});
	}
}
