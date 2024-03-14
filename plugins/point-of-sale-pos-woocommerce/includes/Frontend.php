<?php

namespace ZPOS;

use ZPOS\Admin\Stations\Post;
use ZPOS\Admin\Tabs\Users\UserSettings;
use ZPOS_UI\License as UILicense;

class Frontend
{
	const SLUG = PLUGIN_NAME;

	public function __construct()
	{
		if (Plugin::isActive('pos-ui') && UILicense::isActive()) {
			add_action('init', [$this, 'init']);
			add_action('zpos_frontend_pre_template_redirect', [self::class, 'checkUserAssign']);
		}
	}

	public function init()
	{
		add_action('template_redirect', [$this, 'template_redirect'], 1);
		add_action('zpos_enqueue_scripts', [$this, 'enqueue_app_scripts']);
	}

	public function is_pos()
	{
		return is_single() && get_post_type() === Post::TYPE;
	}

	public function template_redirect()
	{
		if (!$this->is_pos() || Login::maybe_redirect_to_login()) {
			return;
		}

		if (!self::checkUserAssign()) {
			self::user_assign_message_error();
		}

		if (!$this->current_user_can_access()) {
			wp_die(
				sprintf(
					'<p>%1$s</p><p style="text-align: center"><a class="button" href="%2$s">%3$s</a></p>',
					__('Oops. You do not have permissions to access this station', 'zpos-wp-api'),
					add_query_arg('post_type', Post::TYPE, admin_url('edit.php')),
					__('Go to POS Stations Dashboard', 'zpos-wp-api')
				)
			);
		}

		$this->disable_cache();
		$this->render();

		exit();
	}

	public static function checkUserAssign()
	{
		$userAssign = in_array(
			wp_get_current_user()->user_login,
			array_values(UserSettings::getUsers())
		);

		return apply_filters(__METHOD__, $userAssign);
	}

	protected function current_user_can_access()
	{
		return current_user_can('access_woocommerce_pos', get_the_ID());
	}

	public static function get_user_mode()
	{
		return wp_get_current_user()->has_cap('pay_for_order', -1);
	}

	private function disable_cache()
	{
		// disable W3 Total Cache minify
		if (!defined('DONOTMINIFY')) {
			define('DONOTMINIFY', 'true');
		}

		// disable WP Super Cache
		if (!defined('DONOTCACHEPAGE')) {
			define('DONOTCACHEPAGE', 'true');
		}
	}

	public function render()
	{
		$station = new Station(get_the_ID());

		if (isset($_GET['debug']) && $_GET['debug'] === '1') {
			update_option('pos_debug_mode', true);
			wp_redirect($station->getBaseURL());
			exit();
		}

		if (isset($_GET['force']) && $_GET['force'] === 'logout') {
			wp_logout();
			wp_redirect($station->getBaseURL());
			exit();
		}

		$this->html($station, [$this, 'head'], [$this, 'body']);
	}

	public function enqueue_app_scripts()
	{
		wp_register_script('pos-common', Plugin::getAssetUrl('commons.js'), [], false, true);
		wp_enqueue_script(
			'pos-app',
			\ZPOS_UI\Plugin::getAssetUrl('app.js'),
			['pos-common'],
			false,
			true
		);
		wp_set_script_translations('pos-app', 'zpos-wp-api');
	}

	public function user_assign_message_error()
	{
		$message = [
			__('Change your User Assignments to access the POS system.', 'zpos-wp-api'),
			sprintf(
				'<a style="margin: 20px auto 0 auto; display: block; width: 250px; text-align: center;" class="button" href="%s">%s</a>',
				Admin::getPageURL('settings') . '#users',
				__('Go to User Assignment Settings', 'zpos-wp-api')
			),
		];
		wp_die(implode('<br>', $message));
	}

	public function html($station, ...$args)
	{
		echo '<!DOCTYPE html>';
		echo "\n";
		echo '<html>';
		echo "\n";
		foreach ($args as $arg) {
			if (is_callable($arg)) {
				call_user_func($arg, $station);
			} else {
				echo $arg;
			}
		}
		echo '</html>';
	}

	public function head($station)
	{
		$POS_SETTINGS = self::getPOSSettings($station);

		$POS_SETTINGS['WEBPACK_PUBLIC_PATH'] = \ZPOS_UI\Plugin::getAssetUrl('/', true, true);

		$hide_errors =
			!(defined('WP_DEBUG') && defined('WP_DEBUG_DISPLAY') && WP_DEBUG && WP_DEBUG_DISPLAY) ||
			0 === intval(ini_get('display_errors'));

		echo '<head>';
		echo "\n";
		echo '<title>POS</title>';
		echo "\n";
		echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">';
		echo "\n";
		echo '<script>';
		echo "\n";

		foreach ($POS_SETTINGS as $key => $value) {
			echo "\twindow." . $key . ' = ' . json_encode($value) . ';';
			echo "\n";
		}

		if (!$hide_errors) {
			echo 'alert(\'Debug mode enabled.\nDebug mode may cause runtime error and performance issues. Do not use debug mode in production.\');';
			echo "\n";
		}
		echo '</script>';
		if (class_exists('\WC_Gateway_Stripe')) {
			echo '<script id="stripe-js" src="https://js.stripe.com/v3/" async></script>';
			echo "\n";
		}
		do_action(__METHOD__, $station);
		echo "\n";
		echo '</head>';
		echo "\n";
	}

	public function body($station)
	{
		echo '<body>';
		echo "\n";
		echo '<div id="root"></div>';
		echo "\n";

		do_action('zpos_enqueue_scripts');
		do_action('wp_print_footer_scripts');
		if (\ZPOS_UI\Plugin::getManifest('app.css')) {
			echo '<link rel="stylesheet" href="' . \ZPOS_UI\Plugin::getAssetUrl('app.css') . '">';
			echo "\n";
		}
		do_action(__METHOD__, $station);
		echo '</body>';
		echo "\n";
	}

	public static function getPOSSettings($station)
	{
		$plugin_data = get_plugin_data(PLUGIN_ROOT_FILE);

		return [
			'WC_NONCE' => wp_create_nonce('wp_rest'),
			'WC_REST' => esc_url_raw(rest_url('/' . REST_NAMESPACE . '/')),
			'WC_CUSTOMERS_URL' => current_user_can('edit_user')
				? admin_url('users.php?role=customer')
				: null,
			'WC_ORDERS_URL' => current_user_can('edit_shop_orders')
				? admin_url('edit.php?post_type=shop_order')
				: null,
			'POS_VERSION' => $plugin_data['Version'],
			'LOGOUT' => str_replace('&amp;', '&', wp_logout_url($station->getBaseURL())),
			'FORCE_LOGOUT' => add_query_arg('force', 'logout', $station->getBaseURL()),
			'USERNAME' => self::getUserName(),
			'FULL_NAME' => self::getFullName(),
			'SITE_NAME' => esc_js(get_bloginfo('name')),
			'STATION_NAME' => esc_js($station->post->post_title),
			'MENU' => self::getMenu(),
			'TABS' => self::normalize_tabs_slug($station->getData('pos_tabs')),
			'posID' => $station->getID(),
			'USER_MODE' => self::get_user_mode(),
			'BASE_URL' => $station->getBaseURL(),
			'DEBUG_URL' => $station->getDebugURL(),
			'DEBUG' => (bool) get_option('pos_debug_mode'),
			'LOGO' => self::getLogo(),
			'IS_MOBILE_APP' => Plugin::isMobileApp(),
			'HOME_URL' => home_url(),
			'ADMIN_EMAIL_DOMAIN' => explode('@', get_option('admin_email'))[1],
			'LOCALE' => explode('_', get_user_locale())[0],
			'LOCALE_PATH' => Plugin::isActive('pos-ui')
				? \ZPOS_UI\Plugin::getUrl('lang/pos/json/', true)
				: '',
		];
	}

	private static function normalize_tabs_slug($tabs)
	{
		$result = [];

		foreach ($tabs as $key => $value) {
			$key = str_replace(' ', '', $key);
			$key_parts = explode(':', $key);
			$with_prefix = 2 === count($key_parts);
			$slugs = $with_prefix ? explode(',', $key_parts[1]) : [$key];
			$normalized_slugs = array_map(function ($slug) {
				return Admin\Woocommerce\Categories::normalize_slug(strtolower(urlencode($slug)));
			}, $slugs);
			$normalized_key = $with_prefix ? $key_parts[0] . ':' : '';
			$normalized_key .= implode(',', $normalized_slugs);
			$result[$normalized_key] = $value;
		}

		return $result;
	}

	public static function getMenu()
	{
		$menu = [
			current_user_can('edit_products')
				? [
					'title' => __('Products', 'zpos-wp-api'),
					'url' => admin_url('edit.php?post_type=product'),
					'icon' => 'th-large',
				]
				: null,
			[
				'title' => __('Orders', 'zpos-wp-api'),
				'icon' => 'list-alt',
				'name' => 'orders',
			],
			current_user_can('manage_options') && defined('\Zhours\ACTIVE') && \Zhours\ACTIVE
				? [
					'title' => _x('Hours', 'Order hours plugin', 'zpos-wp-api'),
					'url' => \Zhours\Aspect\Page::get('order hours')->getUrl(),
					'icon' => 'clock',
				]
				: null,
			current_user_can('view_woocommerce_reports')
				? [
					'title' => __('Reports', 'zpos-wp-api'),
					'url' => admin_url(
						'admin.php?page=wc-admin&path=%2Fanalytics%2Forders&filter=pos-stations'
					),
					'icon' => 'chart-area',
				]
				: null,
			[
				'title' => __('Customers', 'zpos-wp-api'),
				'inner_link' => true,
				'url' => '/modal/customers/view',
				'icon' => 'user',
			],
			current_user_can('edit_coupon')
				? [
					'title' => __('Coupons', 'zpos-wp-api'),
					'url' => admin_url('edit.php?post_type=shop_coupon'),
					'icon' => 'tag',
				]
				: null,
			current_user_can('edit_shop_orders')
				? [
					'title' => __('Dashboard', 'zpos-wp-api'),
					'url' => admin_url(),
					'icon' => 'tachometer',
				]
				: null,
			current_user_can('manage_woocommerce_pos')
				? [
					'title' => __('Settings', 'zpos-wp-api'),
					'url' => Admin::getPageURL('settings'),
					'icon' => 'cog',
				]
				: null,
			[
				'title' => __('Stations', 'zpos-wp-api'),
				'url' => current_user_can('manage_woocommerce_pos')
					? Admin::getPageURL('pos-stations')
					: get_permalink(get_option('woocommerce_myaccount_page_id')),
				'icon' => 'cash-register',
			],
		];

		return array_values(array_filter($menu));
	}

	public static function getLogo()
	{
		$image_id = get_option('pos_logo');
		$image = wp_get_attachment_image_src($image_id);
		if ($image) {
			$src = $image[0];
		} else {
			$src = null;
		}
		return $src;
	}

	public static function getFullName()
	{
		$user = wp_get_current_user();
		return !empty($user->first_name) || !empty($user->last_name)
			? $user->first_name . ' ' . $user->last_name
			: null;
	}

	public static function getUserName()
	{
		$user = wp_get_current_user();
		return !empty($user->user_nicename) ? $user->user_nicename : $user->user_login;
	}
}
