<?php
/* @wordpress-plugin
 * Plugin Name:       Spoki - Chat Buttons and WooCommerce Notifications
 * Description:       Integrate WhatsApp on your Business! Use Spoki to recover abandoned carts, add chat buttons and send order status notifications via WhatsApp.
 * Version:           2.15.11
 * Author:            NextAI Srl
 * Author URI:        https://spoki.it
 * Text Domain:       spoki
 * Domain Path:       /languages
 * License:           GPLv2
 */

define('SPOKI_PLUGIN_FILE', __FILE__);
define('SPOKI_BASE', plugin_basename(SPOKI_PLUGIN_FILE));
define('SPOKI_DIR', plugin_dir_path(SPOKI_PLUGIN_FILE));
define('SPOKI_URL', plugins_url('/', SPOKI_PLUGIN_FILE));
define('SPOKI_SETTING_TABLE', 'spoki_setting');
define('SPOKI_ABANDONMENT_TABLE', 'spoki_abandonment');

include_once SPOKI_DIR . 'includes/constants.php';
include_once SPOKI_DIR . 'includes/spoki-functions.php';
include_once SPOKI_DIR . 'modules/abandoned-carts/spoki-abandoned-carts.php';

add_filter('cron_schedules', 'add_cron_interval');
add_filter('http_request_timeout', 'extend_http_request_timeout');

function add_cron_interval($schedules)
{
	$schedules['every_day'] = array(
		'interval' => 86400,
		'display' => esc_html__('Every day'),);
	return $schedules;
}


function extend_http_request_timeout($timeout)
{
	return 30; // seconds
}

add_action('plugins_loaded', function () {
	/** Init Spoki */
	Spoki();
}, 9);


function Spoki()
{
	return WpSpoki::instance();
}

final class WpSpoki
{
	protected static $_instance = null;
	public $version = '';
	private $langs = '';
	public $shop = [];

	public $api_plan = SPOKI_BASE_API . "plan/";
	public $api_status = SPOKI_BASE_API . "status/";
	public $api_enable_flex = SPOKI_BASE_API . "enable/";
	public $api_account = SPOKI_BASE_API . "account/";

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		$data = get_file_data(__FILE__, array('ver' => 'Version', 'langs' => 'Domain Path'));
		$this->version = $data['ver'];
		$this->langs = $data['langs'];
		$this->options = get_option(SPOKI_OPTIONS);

		$billing_data = isset($this->options['billing_data']) ? $this->options['billing_data'] : [];
		$has_fixed_support_button = isset($this->options['buttons']['fixed_support_button_check']) && $this->options['buttons']['fixed_support_button_check'] == 1;
		$has_product_item_listing_button = isset($this->options['woocommerce']['product_item_listing_button_check']) && $this->options['woocommerce']['product_item_listing_button_check'] == 1;
		$has_cart_button = isset($this->options['woocommerce']['cart_button_check']) && $this->options['woocommerce']['cart_button_check'] == 1;
		$has_single_product_button = isset($this->options['woocommerce']['single_product_button_check']) && $this->options['woocommerce']['single_product_button_check'] == 1;
		$has_abandoned_carts = isset($this->options['abandoned_carts']['enable_tracking']) && $this->options['abandoned_carts']['enable_tracking'] == 1;
		$has_order_updated_notification = (isset($this->options['woocommerce']['order_updated']) && $this->options['woocommerce']['order_updated'] == 1);
		$has_order_created_notification = (isset($this->options['woocommerce']['order_created']) && $this->options['woocommerce']['order_created'] == 1);
		$has_order_deleted_notification = (isset($this->options['woocommerce']['order_deleted']) && $this->options['woocommerce']['order_deleted'] == 1);
		$has_order_note_added_notification = (isset($this->options['woocommerce']['order_note_added']) && $this->options['woocommerce']['order_note_added'] == 1);
		$has_leave_review_notification = (isset($this->options['woocommerce']['leave_review']) && $this->options['woocommerce']['leave_review'] == 1);
		$has_notifications = (
			$has_order_updated_notification ||
			$has_order_created_notification ||
			$has_order_deleted_notification ||
			$has_order_note_added_notification ||
			$has_leave_review_notification
		);
		$has_order_created_to_seller_notification = (isset($this->options['woocommerce']['order_created_to_seller']) && $this->options['woocommerce']['order_created_to_seller'] == 1);
		$has_cart_recovered_to_seller_notification = (isset($this->options['abandoned_carts']['notify_to_admin']) && $this->options['abandoned_carts']['notify_to_admin'] == 1);
		$is_auto_update_disabled = (isset($this->options['disable_auto_update']) && $this->options['disable_auto_update'] == 1);
		$abandoned_carts_waiting_minutes = (isset($this->options['abandoned_carts']['waiting_minutes'])) ? intval($this->options['abandoned_carts']['waiting_minutes']) : 15;

		$this->shop = [
			"plugin_version" => $this->version,
			"name" => $this->options['shop_name'] ?? get_bloginfo('name'),
			"url" => get_bloginfo('url'),
			"email" => $this->options['email'] ?? get_bloginfo('admin_email'),
			"language" => $this->options['language'] ?? get_bloginfo('language'),
			"telephone" => ($this->options['prefix'] ?? '') . ($this->options['telephone'] ?? ''),
			"contact_link" => isset($this->options['contact_link']) ? $this->options['contact_link'] : null,
			"default_prefix" => isset($this->options['default_prefix']) ? $this->options['default_prefix'] : null,
			"billing_data" => $billing_data,
			"has_fixed_support_button" => $has_fixed_support_button,
			"has_product_item_listing_button" => $has_product_item_listing_button,
			"has_cart_button" => $has_cart_button,
			"has_single_product_button" => $has_single_product_button,
			"has_abandoned_carts" => $has_abandoned_carts,
			"has_order_updated_notification" => $has_order_updated_notification,
			"has_order_created_notification" => $has_order_created_notification,
			"has_order_deleted_notification" => $has_order_deleted_notification,
			"has_order_note_added_notification" => $has_order_note_added_notification,
			"has_leave_review_notification" => $has_leave_review_notification,
			"has_notifications" => $has_notifications,
			"has_order_created_to_seller_notification" => $has_order_created_to_seller_notification,
			"has_cart_recovered_to_seller_notification" => $has_cart_recovered_to_seller_notification,
			"abandoned_carts_waiting_minutes" => $abandoned_carts_waiting_minutes,
			"is_auto_update_disabled" => $is_auto_update_disabled,
		];

		register_activation_hook(SPOKI_PLUGIN_FILE, array($this, 'activation_reset'));
		register_deactivation_hook(SPOKI_PLUGIN_FILE, array($this, 'deactivation_reset'));

		add_action('init', 'spoki_update_po_file');
		add_action('admin_menu', array($this, 'add_option_menu'));
		add_action('admin_menu', array($this, 'add_submenu'), 99);
		add_filter("plugin_action_links_" . plugin_basename(__FILE__), array($this, 'plugin_settings_link'));
		add_action('wp_enqueue_scripts', array($this, 'add_styles'));
		add_action('admin_enqueue_scripts', array($this, 'add_admin_styles'));
		add_action('admin_enqueue_scripts', array($this, 'add_admin_scripts'));
		add_action('updated_option', array($this, 'on_option_added'), 10, 3);
		add_filter('auto_update_plugin', array($this, 'auto_update_plugin'), 10, 2);
		add_action('spoki_cron_hook', array($this, 'check_secret_status'));
		if (!wp_next_scheduled('spoki_cron_hook')) {
			$this->check_secret_status();
			wp_schedule_event(time(), 'every_day', 'spoki_cron_hook');
		}

		$this->includes();
		$this->render_buttons();
		$this->handle_woocommerce();
		$this->handle_abandoned_carts();

		add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widgets'));

	}

	/**
	 * Include all Spoki required files
	 */
	private function includes()
	{
		require_once SPOKI_DIR . 'includes/spoki-functions.php';
	}

	/**
	 * Get the setting link of the plugin
	 *
	 * @param $links
	 * @return mixed
	 */
	public function plugin_settings_link($links)
	{
		$settings_link = '<a href="admin.php?page=' . SPOKI_PLUGIN_NAME . '">' . __('Settings', SPOKI_PLUGIN_NAME) . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Add the Spoki option to the menu
	 */
	public function add_option_menu()
	{
		add_menu_page(
			'Spoki Options',
			"Spoki",
			'manage_options',
			'spoki',
			array($this, 'render_setup_page'),
			plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/logo.svg',
			98
		);
		add_action('admin_init', array($this, 'register_option_var'));
	}

	/**
	 * Add the Spoki option submenu
	 */
	function add_submenu()
	{
		$has_woocommerce = ('woocommerce/woocommerce.php');
		$is_pro = isset($this->options['account_info']['plan']['is_pro']) && $this->options['account_info']['plan']['is_pro'] == true;

		add_submenu_page('spoki', __('Statistics', "spoki"), __('Statistics', "spoki"), 'manage_options', "admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=welcome");
		add_submenu_page('spoki', __('Buttons', "spoki"), __('Buttons', "spoki"), 'manage_options', "admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=buttons");
		add_submenu_page('spoki', __('Customer Notifications', "spoki"), __('Customer Notifications', "spoki"), 'manage_options', "admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=customer-notifications");
		add_submenu_page('spoki', __('Seller Notifications', "spoki"), __('Seller Notifications', "spoki"), 'manage_options', "admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=seller-notifications");
		add_submenu_page('spoki', __('Abandoned Carts', "spoki"), __('Abandoned Carts', "spoki"), 'manage_options', "admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=abandoned-carts");
		add_submenu_page('spoki', __('Settings', "spoki"), __('Settings', "spoki"), 'manage_options', "admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=settings");
		add_submenu_page('spoki', __('Invite a friend', "spoki"), __('Invite a friend', "spoki"), 'manage_options', "admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=invite-a-friend");
		if (!$is_pro) {
			add_submenu_page('spoki', __('Upgrade', "spoki"), "<span class='color-spoki text-bold'>⇡ " . __('Upgrade', "spoki") . "</span>", 'manage_options', $this->get_pro_plan_link());
		}

		if ($has_woocommerce) {
			add_submenu_page(
				'woocommerce',
				__('Spoki', "spoki"),
				__('Spoki', "spoki"),
				'manage_options',
				"admin.php?page=" . SPOKI_PLUGIN_NAME . "&tab=welcome",
				null,
				8
			);
		}

	}

	/**
	 * Register the Spoki option
	 */
	public function register_option_var()
	{
		register_setting('wp-spoki-option', SPOKI_OPTIONS);
	}

	/**
	 * Add website spoki styles
	 */
	public function add_styles()
	{
		$styles = ['buttons'];
		foreach ($styles as $style) {
			echo "<style id='spoki-style-$style'>";
			include SPOKI_DIR . "assets/css/$style.css";
			echo "</style>";
		}
	}

	/**
	 * Add admin spoki styles
	 */
	public function add_admin_styles()
	{
		$styles = ['admin.css', 'onboarding.css', 'account-overview.css', 'spoki-overview.css'];

		foreach ($styles as $style) {
			echo "<style>";
			include_once SPOKI_DIR . 'assets/css/' . $style;
			echo "</style>";
		}

		$this->add_styles();
	}

	/**
	 * Add admin spoki scripts
	 */
	public function add_admin_scripts()
	{
		$scripts = ['spoki-admin.js', 'spoki-shadowed-buttons.js'];

		foreach ($scripts as $script) {
			echo "<script>";
			include_once SPOKI_DIR . 'assets/js/' . $script;
			echo "</script>";
		}
	}

	/**
	 * Enable plugin autoupdate
	 */
	function auto_update_plugin($update, $item)
	{
		$plugins = array('spoki');
		$is_auto_update_disabled = (isset($this->options['disable_auto_update']) && $this->options['disable_auto_update'] == 1);
		if (in_array($item->slug, $plugins) && !$is_auto_update_disabled)
			return true;
		else return $update;
	}

	/**
	 * Register widgets for Elementor
	 */
	public function register_elementor_widgets()
	{
		if (spoki_has_elementor()) {
			include_once SPOKI_DIR . 'widgets/class-elementor-spoki-button.php';
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_Spoki_Button_Widget());
		}
	}

	/**
	 * Handle option submit
	 *
	 * @param $option
	 * @param $old_value
	 * @param $value
	 */
	public function on_option_added($option, $old_value, $value)
	{
		if ($option == SPOKI_OPTIONS) {

			/** Onboarding Without WooCommerce */
			if (isset($value['onboarding']['without_wc'])) {
				$this->update_options($value, [
					"telephone" => $value['onboarding']['telephone'],
					"prefix" => $value['onboarding']['prefix'],
					"onboarding" => null,
				]);
				$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=buttons');
				header("Location: {$url}");
				exit;
			}

			/** Onboarding With WooCommerce */

			if (isset($value['onboarding']['with_wc'])) {
				$response = array(
					"secret" => $value['onboarding']['spoki_onboarding_secret'],
					"delivery_url" => $value['onboarding']['spoki_onboarding_delivery_url'],
				);
				if (isset($response['secret']) && isset($response['delivery_url'])) {
					$this->update_options($value, [
						"telephone" => $value['onboarding']['telephone'],
						"prefix" => $value['onboarding']['prefix'],
						"email" => $value['onboarding']['email'],
						"shop_name" => $value['onboarding']['shop_name'],
						"secret" => $response['secret'],
						"delivery_url" => $response['delivery_url'],
						"onboarding" => null,
						"woocommerce" => [
							"order_created" => 0,
							"order_updated" => 0,
							"order_deleted" => 0,
							"order_note_added" => 0,
							"leave_review" => 0,
							"leave_review_days" => 5,
							"order_created_to_seller" => 0,
						],
						"abandoned_carts" => [
							"enable_tracking" => 0,
							"notify_to_admin" => 0,
						],
						"secret_status" => [
							'secret' => $response['secret'],
							'delivery_url' => $response['delivery_url'],
							'code' => 200,
							'message' => ''
						]
					]);
					$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=customer-notifications');
					header("Location: {$url}");
					exit;
				}
			}

			if (isset($value['account_info']['response_json'])) {
				$account_info = json_decode($value['account_info']['response_json'] ?? '{}', true);
				if ($account_info) {
					$this->update_options(get_option(SPOKI_OPTIONS), ["account_info" => $account_info]);
				}
			}

			/** Settings updated for registered account */
			if (isset($value['is_settings'])) {
				$keys_changed = ($value['secret'] != $old_value['secret']) || ($value['delivery_url'] != $old_value['delivery_url']);
				if ($keys_changed) {
					$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=welcome');
					header("Location: {$url}");
					exit;
				}
			}

			/** Enable all notifications from dashboard */
			if (isset($value['enable_notifications']) && $value['enable_notifications'] == 1) {
				$this->update_options(get_option(SPOKI_OPTIONS), [
					"enable_notifications" => 0,
					"woocommerce" => [
						"order_created" => 1,
						"order_updated" => 1,
						"order_deleted" => 1,
						"order_note_added" => 1,
						"leave_review" => 1,
						"leave_review_days" => 5,
					]
				]);
				$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=customer-notifications');
				header("Location: {$url}");
				exit;
			}

			/** Enable order status notifications from dashboard */
			if (isset($value['enable_order_status_notifications']) && $value['enable_order_status_notifications'] == 1) {
				$this->update_options(get_option(SPOKI_OPTIONS), [
					"enable_order_status_notifications" => 0,
					"woocommerce" => [
						"order_created" => 1,
						"order_updated" => 1,
						"order_deleted" => 1,
					]
				]);
				$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=customer-notifications');
				header("Location: {$url}");
				exit;
			}

			/** Enable seller notifications from dashboard */
			if (isset($value['enable_seller_notifications']) && $value['enable_seller_notifications'] == 1) {
				$this->update_options(get_option(SPOKI_OPTIONS), [
					"enable_seller_notifications" => 0,
					"woocommerce" => [
						"order_created_to_seller" => 1,
					]
				]);
				$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=seller-notifications');
				header("Location: {$url}");
				exit;
			}

			/** Enable leave review notification from dashboard */
			if (isset($value['enable_leave_review_notification']) && $value['enable_leave_review_notification'] == 1) {
				$this->update_options(get_option(SPOKI_OPTIONS), [
					"enable_leave_review_notification" => 0,
					"woocommerce" => [
						"leave_review" => 1,
						"leave_review_days" => 5,
					]
				]);
				$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=customer-notifications');
				header("Location: {$url}");
				exit;
			}

			/** Enable enable order note added notification from dashboard */
			if (isset($value['enable_order_note_added_notification']) && $value['enable_order_note_added_notification'] == 1) {
				$this->update_options(get_option(SPOKI_OPTIONS), [
					"enable_order_note_added_notification" => 0,
					"woocommerce" => [
						"order_note_added" => 1,
					]
				]);
				$url = admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME . '&tab=customer-notifications');
				header("Location: {$url}");
				exit;
			}
		}
	}

	/**
	 * Handle WooCommerce features
	 */
	public function handle_woocommerce()
	{
		if (isset($this->options['secret'])) {
			include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			if (spoki_has_woocommerce()) {
				add_action('woocommerce_checkout_update_order_meta', array($this, 'send_woocommerce_order_created_alert'), 1, 3);
				add_action('woocommerce_order_status_changed', array($this, 'send_woocommerce_order_created_or_updated_alert'), 1, 3);
				add_action('woocommerce_cancelled_order', array($this, 'send_woocommerce_order_deleted_alert'), 10, 3);
				add_action('woocommerce_trash_order', array($this, 'send_woocommerce_order_deleted_alert'), 10, 3);
				add_action('woocommerce_order_note_added', array($this, 'send_woocommerce_note_alert'), 10, 2);
				add_action('woocommerce_order_status_completed', array($this, 'send_woocommerce_review_alert'), 10, 2);

				if (isset($this->options['woocommerce']['cart_button_hide_checkout_button_check']) && $this->options['woocommerce']['cart_button_hide_checkout_button_check'] == 1) {
					add_action('woocommerce_proceed_to_checkout', array($this, 'disable_checkout_button'), 1);
				}
			}
		}
	}

	/**
	 * Handle WooCommerce abandoned carts feature
	 */
	public function handle_abandoned_carts()
	{
		$Abandonment = Spoki_Abandoned_Carts::instance();
		if (isset($this->options['abandoned_carts']['enable_tracking']) && $this->options['abandoned_carts']['enable_tracking'] == 1) {
			$this->initialize_cart_abandonment_tables();
			$Abandonment->set_spoki_setting_by_meta("plugin_activated", "true");
			do_action('spoki_cartflow_ca_init');
		} elseif (wp_next_scheduled('spoki_abandoned_carts_cron_hook')) {
			# Unschedule hook for versions <= v2.8.1
			$Abandonment->unschedule_hook();
		}
	}

	/**
	 * Create new database tables for plugin updates.
	 *
	 * @return void
	 */
	public function initialize_cart_abandonment_tables()
	{
		include_once SPOKI_DIR . 'modules/abandoned-carts/spoki-abandoned-carts-db.php';
		$db = Spoki_Abandoned_Carts_Db::instance();
		$db->create_tables();
		$db->init_tables();
	}

	/**
	 * Send the Spoki notification for order status created or updated
	 *
	 * @param $order_get_id
	 * @param $from
	 * @param $to
	 */
	public function send_woocommerce_order_created_alert($order_get_id)
	{
		$this->send_woocommerce_order_alert($order_get_id, 'order.updated');
	}

	/**
	 * Send the Spoki notification for order status created or updated
	 *
	 * @param $order_get_id
	 * @param $from
	 * @param $to
	 */
	public function send_woocommerce_order_created_or_updated_alert($order_get_id, $from, $to)
	{
		$this->send_woocommerce_order_alert($order_get_id, 'order.updated', ["from_status" => $from, "to_status" => $to]);
	}

	/**
	 * Send the Spoki notification for review
	 *
	 * @param $order_get_id
	 */
	public function send_woocommerce_review_alert($order_get_id)
	{
		$review_link = (isset($this->options['woocommerce']['leave_review_link']) && $this->options['woocommerce']['leave_review_link'] != "") ? $this->options['woocommerce']['leave_review_link'] : "";
		$review_link_days = (isset($this->options['woocommerce']['leave_review_days']) && $this->options['woocommerce']['leave_review_days'] != "") ? $this->options['woocommerce']['leave_review_days'] : 5;
		$this->send_woocommerce_order_alert($order_get_id, 'order.review', ["review_link" => $review_link, "delay" => intval($review_link_days) * 24 * 60 * 60]);
	}

	/**
	 * Notify the Abandoned Cart Info Spoki notification
	 *
	 * @param $session_id
	 * @param $checkoutDetails
	 * @param $topic
	 * @return bool
	 */
	public function notify_abandoned_cart_info($session_id, $checkoutDetails, $topic): bool
	{
		$other_fields = unserialize($checkoutDetails->other_fields);
		$parts = explode(',', $other_fields['spoki_location']);
		$country = $parts[0];

		$phone = trim(sanitize_text_field($other_fields['spoki_phone_number']));
		if ($phone != '' && $phone[0] != '+') {
			$default_prefix = isset($this->options['default_prefix']) ? $this->options['default_prefix'] : '';
			$phone = $default_prefix . $phone;
		}

		$checkout_base_url = (isset($this->options['custom_checkout_url']) && $this->options['custom_checkout_url'] != "") ? $this->options['custom_checkout_url'] : wc_get_page_permalink('checkout');
		$custom_checkout_session_id_param = (isset($this->options['custom_checkout_session_id_param']) && $this->options['custom_checkout_session_id_param'] != "") ? $this->options['custom_checkout_session_id_param'] : "session_id";

		$data = [
			"customer" => [
				'email' => $checkoutDetails->email,
				'phone' => $phone,
				'country' => sanitize_text_field($country),
				'first_name' => sanitize_text_field($other_fields['spoki_first_name']),
				'last_name' => sanitize_text_field($other_fields['spoki_last_name']),
				'name' => sanitize_text_field($other_fields['spoki_first_name']) . ' ' . sanitize_text_field($other_fields['spoki_last_name']),
			],
			"checkout" => [
				"checkout_url" => $checkout_base_url . '?' . $custom_checkout_session_id_param . '=' . $checkoutDetails->session_id,
				"checkout_info" => $other_fields,
				"cart_contacted_url" => get_bloginfo('url') . '/wp-json/api/v1/setCartAsContacted',
				"total" => $checkoutDetails->cart_total,
				"currency" => get_woocommerce_currency(),
			],
			"session_id" => $session_id,
		];
		return $this->spoki_send($data, $topic);
	}

	/**
	 * Send the Spoki notification for order deleted
	 *
	 * @param $order_get_id
	 */
	public function send_woocommerce_order_deleted_alert($order_get_id)
	{
		$this->send_woocommerce_order_alert($order_get_id, 'order.deleted');
	}

	/**
	 * Send the Spoki notification for order status
	 *
	 * @param $order_get_id
	 * @param $topic
	 * @param $additional_data
	 */
	public function send_woocommerce_order_alert($order_get_id, $topic, $additional_data = [])
	{
		$order = wc_get_order($order_get_id);

		// Session Handling
		$session = [];
		try {
			$session_id = WC()->session ? WC()->session->get('spoki_session_id') : null;
			if (!$session_id && $this->shop['has_abandoned_carts']) {
				$spoki_abandoned_carts = Spoki_Abandoned_Carts::instance();
				$checkout = $spoki_abandoned_carts->get_order_checkout_details($order_get_id);
				$other_fields = unserialize($checkout->other_fields);
				$session_id = $other_fields['spoki_session_id'];
			}
			if ($session_id) {
				$session = ['session_id' => $session_id];
			}
		} catch (Exception $e) {
			// Can't set session_id
		}

		$order_data = array_merge(((isset($order) && false != $order ? $order->get_data() : ["id" => $order_get_id])), $additional_data);
		if (!isset($order_data["to_status"])) {
			$order_data["from_status"] = $order_data["status"];
			$order_data["to_status"] = $order_data["status"];
		}

		$parts = explode(',', isset($order_data['billing']['country']) ? $order_data['billing']['country'] : '');
		$country = $parts[0];
		$phone = trim(isset($order_data['billing']['phone']) ? $order_data['billing']['phone'] : '');
		if ($phone != '' && $phone[0] != '+') {
			$default_prefix = isset($this->options['default_prefix']) ? $this->options['default_prefix'] : '';
			$phone = $default_prefix . $phone;
		}
		$customer = [
			'email' => isset($order_data['billing']['email']) ? $order_data['billing']['email'] : '',
			'phone' => $phone,
			'country' => $country ?? '',
			'first_name' => (isset($order_data['billing']['first_name']) ? $order_data['billing']['first_name'] : ''),
			'last_name' => (isset($order_data['billing']['last_name']) ? $order_data['billing']['last_name'] : ''),
			'name' => (isset($order_data['billing']['first_name']) ? $order_data['billing']['first_name'] : '') . ' ' . (isset($order_data['billing']['last_name']) ? $order_data['billing']['last_name'] : ''),
		];
		$this->spoki_send(array_merge([
			"order" => $order_data,
			"customer" => $customer,
		], $session), $topic);
	}

	/**
	 * Send the Spoki notification for tracking number
	 *
	 * @param $id
	 * @param $order
	 */
	public function send_woocommerce_note_alert($id, $order)
	{
		$comment = get_comment($id);
		if (isset($comment->comment_content)) {
			$is_gls_tracking = substr(strtolower($comment->comment_content), 0, 4) == '[gls';
			$is_user_tracking = substr(strtolower($comment->comment_content), 0, 10) == '[tracking]';

			/** Send the message only if it is a tracking order note */
			if ($comment->comment_type == 'order_note' && ($is_gls_tracking || $is_user_tracking)) {
				$order_data = $order->get_data();
				$this->send_woocommerce_order_alert($order_data['id'], 'order.tracking', ["note" => $comment]);
			}
		}
	}

	/**
	 * Send the Spoki notification
	 *
	 * @param $data
	 * @return bool
	 */
	public function spoki_send($data, $topic): bool
	{
		$shop = ["shop" => $this->shop];

		$request_params = array(
			"headers" => array(
				"Authorization" => $this->options['secret'],
				"language" => $this->shop['language'],
				"X-Wc-Webhook-Topic" => $topic,
			),
			"body" => wp_json_encode(array_merge($data, $shop)),
			"sslverify" => false,
			"timeout" => 60,
		);

		$url = $this->options['delivery_url'];
		$response = wp_remote_post($url, $request_params);
		$code = wp_remote_retrieve_response_code($response);
		return $code == 200;
	}

	/**
	 * Fetch and update the account_info
	 */
	public function fetch_account_info()
	{
		$request_params = [
			"headers" => [
				"Authorization" => $this->options['secret'],
				"language" => $this->shop['language']
			],
			"sslverify" => false,
			"timeout" => 60,
		];
		$response = wp_remote_get($this->api_plan, $request_params);
		$account_info = json_decode(wp_remote_retrieve_body($response), true);
		$this->update_options(get_option(SPOKI_OPTIONS), ["account_info" => $account_info]);
		return $account_info;
	}

	/**
	 * Check the status of the spoki keys
	 *
	 * @return array|WP_Error
	 */
	public function check_spoki_status()
	{
		$secret = $this->options['secret'] ?? null;
		$has_woocommerce = spoki_has_woocommerce();

		$body = [
			"is_plugin_active" => true,
			"plugin_version" => $this->version,
			"url" => $this->shop['url'],
			"name" => $this->shop['name'],
			"language" => $this->shop['language'],
			"email" => $this->shop['email'],
			"telephone" => $this->shop['telephone'],
			"has_woocommerce" => $has_woocommerce,
			"is_widget_fixed_enabled" => $this->shop['has_fixed_support_button'],
			"is_widget_woo_shop_enabled" => $this->shop['has_product_item_listing_button'],
			"is_widget_woo_item_enabled" => $this->shop['has_single_product_button'],
			"is_widget_woo_cart_enabled" => $this->shop['has_cart_button'],
			"is_widget_woo_checkout_enabled" => $this->shop['has_cart_button'],
			"is_abandoned_carts_enabled" => $this->shop['has_abandoned_carts'],
			"is_woo_send_enabled" => $has_woocommerce && $this->shop['has_notifications'],
			"is_order_updated_notification_enabled" => $this->shop['has_order_updated_notification'],
			"is_order_created_notification_enabled" => $this->shop['has_order_created_notification'],
			"is_order_deleted_notification_enabled" => $this->shop['has_order_deleted_notification'],
			"is_order_note_added_notification_enabled" => $this->shop['has_order_note_added_notification'],
			"is_leave_review_notification_enabled" => $this->shop['has_leave_review_notification'],
			"has_cart_recovered_to_seller_notification" => $this->shop['has_cart_recovered_to_seller_notification'],
			"contact_link" => $this->shop['contact_link'] ?: '',
			"default_prefix" => $this->shop['default_prefix'] ?: '',
			"zip_code" => $this->shop['billing_data']['zip_code'] ?: '',
			"province" => $this->shop['billing_data']['province'] ?: '',
			"country" => $this->shop['billing_data']['country'] ?: '',
			"route" => $this->shop['billing_data']['route'] ?: '',
			"city" => $this->shop['billing_data']['city'] ?: '',
			"vat_number" => $this->shop['billing_data']['vat_number'] ?: '',
			"vat_name" => $this->shop['billing_data']['vat_name'] ?: '',
			"c_f" => $this->shop['billing_data']['c_f'] ?: '',
			"pec" => $this->shop['billing_data']['pec'] ?: '',
			"sid" => $this->shop['billing_data']['sid'] ?: '',
		];

		if ($this->shop['has_abandoned_carts']) {
			$spoki_abandoned_carts = Spoki_Abandoned_Carts::instance();
			$abandoned_report = $spoki_abandoned_carts->get_report_by_type(SPOKI_CART_ABANDONED_ORDER);
			$recovered_report = $spoki_abandoned_carts->get_report_by_type(SPOKI_CART_COMPLETED_ORDER);
			$body["abandoned_carts_reports"] = [
				"abandoned" => $abandoned_report,
				"recovered" => $recovered_report,
			];
		}

		$request_params = [
			"headers" => [
				"Authorization" => $secret,
				"language" => $this->shop['language'],
			],
			"body" => $body,
			"sslverify" => false,
			"timeout" => 60,
		];
		return wp_remote_post($this->api_status, $request_params);
	}

	/**
	 * Render the admin setup page
	 */
	public function render_setup_page()
	{
		require_once SPOKI_DIR . 'includes/page-setup.php';
		$this->render_components();
	}


	/**
	 * Render the Spoki Components
	 */
	public function render_components()
	{
		$components = ['abandoned-carts-info-dialog', 'customer-notifications-info-dialog', 'buttons-info-dialog'];
		foreach ($components as $component) {
			include_once SPOKI_DIR . "components/$component.php";
		}
	}

	/**
	 * Render the WhatsApp buttons in the website
	 */
	public function render_buttons()
	{
		$has_fixed_support_button = isset($this->options['buttons']['fixed_support_button_check']) && $this->options['buttons']['fixed_support_button_check'] == 1;
		$has_product_item_listing_button = isset($this->options['woocommerce']['product_item_listing_button_check']) && $this->options['woocommerce']['product_item_listing_button_check'] == 1;
		$has_cart_button = isset($this->options['woocommerce']['cart_button_check']) && $this->options['woocommerce']['cart_button_check'] == 1;
		$has_single_product_button = isset($this->options['woocommerce']['single_product_button_check']) && $this->options['woocommerce']['single_product_button_check'] == 1;

		// Floating Button
		if ($has_fixed_support_button) {
			add_action('wp_footer', array($this, 'render_floating_button'), 1);
		}

		// Product Item Listing Button
		if ($has_product_item_listing_button) {
			add_action('woocommerce_after_shop_loop_item', array($this, 'render_product_item_listing_button'), 99);
		}

		// Cart Page Button
		if ($has_cart_button) {
			add_action('woocommerce_after_cart_totals', array($this, 'render_cart_button'), 99);
		}

		// Single Product Page Button
		if ($has_single_product_button) {
			$position = isset($this->options['woocommerce']['single_product_button_position']) ? $this->options['woocommerce']['single_product_button_position'] : 'after_atc';
			switch ($position) {
				case 'under_atc':
					add_action('woocommerce_after_add_to_cart_form', array($this, 'render_single_product_button'), 99);
					break;
				case 'after_shortdesc':
					add_action('woocommerce_before_add_to_cart_form', array($this, 'render_single_product_button'), 99);
					break;
				case 'after_atc':
				default:
					add_action('woocommerce_after_add_to_cart_button', array($this, 'render_single_product_button'), 99);
					break;
			}
		}

		/**
		 * Render the spoki button wherever you want using the shortcode
		 * @param $attrs
		 * @param null $content
		 */
		function spoki_button_shortcode($attrs, $content = null)
		{
			$hide_non_working = $attrs['hide_non_working'] ?? '';
			$working_days_times_options = Spoki()->options['working_days_times'];
			$is_working_days_times_enabled = isset($working_days_times_options['enabled']) && $working_days_times_options['enabled'] == 1;
			$is_non_working_day_time = spoki_is_non_working_day_time($working_days_times_options);
			if ($hide_non_working && $is_working_days_times_enabled && $is_non_working_day_time) {
				return;
			}

			$phone = $attrs['phone'] ?? Spoki()->shop['telephone'];
			$cta = $attrs['cta'] ?? '';
			$title = $attrs['title'] ?? '';

			$final_message = urlencode($attrs['message'] ?? '');
			$is_non_working_days_times_text_enabled = $attrs['enable_non_working_message'] ?? '';
			if ($is_non_working_day_time && $is_non_working_days_times_text_enabled) {
				$final_message = urlencode($attrs['non_working_message'] ?? '');
			}

			$additional_class = $attrs['additional_class'] ?? '';
			$type = 5;
			$color = $attrs['color'] ?? null;
			$border_type = $attrs['border_type'] ?? null;
			$margin = [
				"top" => $attrs['margin_top'] ?? null,
				"bottom" => $attrs['margin_bottom'] ?? null,
				"left" => $attrs['margin_left'] ?? null,
				"right" => $attrs['margin_right'] ?? null,
			];
			$padding = [
				"top" => $attrs['padding_top'] ?? null,
				"bottom" => $attrs['padding_bottom'] ?? null,
				"left" => $attrs['padding_left'] ?? null,
				"right" => $attrs['padding_right'] ?? null,
			];
			$font_size = $attrs['font_size'] ?? null;
			$id = $attrs['id'] ?? null;
			$class_names = $attrs['class_names'] ?? null;
			$custom_css = $attrs['custom_css'] ?? null;

			Spoki()->render_relative_button($phone, $cta, $title, $final_message, $additional_class, $type, $margin, $padding, $color, $border_type, $font_size, '', $id, $class_names, $custom_css);
		}

		add_shortcode('spoki_button', 'spoki_button_shortcode');

		add_action('wp_footer', array($this, 'render_shadowed_buttons'));
	}

	public function disable_checkout_button()
	{
		remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
	}

	/**
	 *
	 */
	public function render_shadowed_buttons()
	{
		echo "<script>";
		include_once SPOKI_DIR . 'assets/js/spoki-shadowed-buttons.js';
		echo "</script>";
	}

	/**
	 * Render the WhatsApp FAB on the website in every page
	 */
	public function render_floating_button()
	{
		if (isset($this->options['telephone']) && $this->options['telephone'] != '') {

			/** Check visibility conditions */
			$hide_on_posts = isset($this->options['buttons']['fixed_support_button_hide_post_page']) && $this->options['buttons']['fixed_support_button_hide_post_page'] == 1;
			if ($hide_on_posts && is_single() && 'post' == get_post_type()) {
				return;
			}
			$hide_on_pages = isset($this->options['buttons']['fixed_support_button_hide_single_page']) && $this->options['buttons']['fixed_support_button_hide_single_page'] == 1;
			if ($hide_on_pages && is_page() && 'page' == get_post_type()) {
				return;
			}
			$hide_on_shop = isset($this->options['buttons']['fixed_support_button_hide_shop_page']) && $this->options['buttons']['fixed_support_button_hide_shop_page'] == 1;
			if ($hide_on_shop && is_shop()) {
				return;
			}
			$hide_on_product = isset($this->options['buttons']['fixed_support_button_hide_product_page']) && $this->options['buttons']['fixed_support_button_hide_product_page'] == 1;
			if ($hide_on_product && is_product()) {
				return;
			}
			$hide_on_cart = isset($this->options['buttons']['fixed_support_button_hide_cart_page']) && $this->options['buttons']['fixed_support_button_hide_cart_page'] == 1;
			if ($hide_on_cart && is_cart()) {
				return;
			}
			$hide_on_checkout = isset($this->options['buttons']['fixed_support_button_hide_checkout_page']) && $this->options['buttons']['fixed_support_button_hide_checkout_page'] == 1;
			if ($hide_on_checkout && (is_checkout() || is_checkout_pay_page())) {
				return;
			}
			$hide_non_working = isset($this->options['buttons']['fixed_support_button_hide_non_working']) && $this->options['buttons']['fixed_support_button_hide_non_working'] == 1;
			$is_working_days_times_enabled = isset($this->options['working_days_times']['enabled']) && $this->options['working_days_times']['enabled'] == 1;
			$is_non_working_day_time = isset($this->options['working_days_times']) && spoki_is_non_working_day_time($this->options['working_days_times']);;
			if ($hide_non_working && $is_working_days_times_enabled && $is_non_working_day_time) {
				return;
			}

			/** Can render, go on */
			$prefix = isset($this->options['prefix']) ? $this->options['prefix'] : '';
			$phone = $prefix . $this->options['telephone'];
			$text = $this->options['buttons']['fixed_support_button_text'];
			$is_non_working_days_times_text_enabled = isset($this->options['buttons']['fixed_support_button_non_working_text_enabled']) && $this->options['buttons']['fixed_support_button_non_working_text_enabled'] == 1;
			if ($is_non_working_day_time && $is_non_working_days_times_text_enabled) {
				$text = isset($this->options['buttons']['fixed_support_button_non_working_text']) ? $this->options['buttons']['fixed_support_button_non_working_text'] : "";
			}
			$position = $this->options['buttons']['fixed_support_button_position'] == 'Left' ? 'left' : 'right';
			$border_type = isset($this->options['buttons']['fixed_support_button_border']) ? $this->options['buttons']['fixed_support_button_border'] : 'circle';
			$size = isset($this->options['buttons']['fixed_support_button_size']) ? $this->options['buttons']['fixed_support_button_size'] : '50';
			$icon_size = '65%';
			$border_radius = '50%';
			if ($border_type == 'squared') {
				$border_radius = '0';
			} elseif ($border_type == 'rounded') {
				$border_radius = '8px';
			}
			$color = isset($this->options['buttons']['fixed_support_button_color']) ? $this->options['buttons']['fixed_support_button_color'] : '#23D366';
			$bottom_space = isset($this->options['buttons']['fixed_support_button_bottom_space']) ? $this->options['buttons']['fixed_support_button_bottom_space'] : '12';
			$side_space = isset($this->options['buttons']['fixed_support_button_side_space']) ? $this->options['buttons']['fixed_support_button_side_space'] : '12';
			$furl = get_bloginfo('url');
			$wa_link = SPOKI_BASE_API_BUTTONS . "?type=1&phone=$phone&text=" . urlencode($text) . "&furl=$furl";

			$spoki_fixed_btn_label = "";
			$has_label = isset($this->options['buttons']['fixed_support_button_show_label']) && $this->options['buttons']['fixed_support_button_show_label'] == 1;
			if ($has_label) {
				$show_on_hover = (isset($this->options['buttons']['fixed_support_button_show_label_on_hover']) && $this->options['buttons']['fixed_support_button_show_label_on_hover'] == 1) ? 'hide-not-hover' : '';
				$label_space = intval($size) + 12;
				$label_content = (isset($this->options['buttons']['fixed_support_button_label_content']) && $this->options['buttons']['fixed_support_button_label_content'] != "") ? $this->options['buttons']['fixed_support_button_label_content'] : __('Chat with us 👋', "spoki");
				$label_font_size = (isset($this->options['buttons']['fixed_support_button_label_font_size']) && $this->options['buttons']['fixed_support_button_label_font_size'] != "") ? $this->options['buttons']['fixed_support_button_label_font_size'] : '16';
				$label_text_color = isset($this->options['buttons']['fixed_support_button_label_text_color']) ? $this->options['buttons']['fixed_support_button_label_text_color'] : '#333333';
				$label_background_color = isset($this->options['buttons']['fixed_support_button_label_background_color']) ? $this->options['buttons']['fixed_support_button_label_background_color'] : '#FFFFFF';
				$label_border_radius = $border_type == 'squared' ? '0' : '10';
				$label_delay = (intval((isset($this->options['buttons']['fixed_support_button_label_delay']) && $this->options['buttons']['fixed_support_button_label_delay'] != "") ? $this->options['buttons']['fixed_support_button_label_delay'] : '0') * 1000) + 1000;
				$spoki_fixed_btn_label = "<div class='spoki-fixed-btn-label $show_on_hover' style='display:none;$position:{$label_space}px;font-size:{$label_font_size}px;color:$label_text_color;background-color:$label_background_color;border-radius:{$label_border_radius}px;'>$label_content</div><script>(function (d) {setTimeout(function (){try {const shadowedButton = d.querySelector('#spoki-shadowed-fixed-button'); if (shadowedButton) {shadowedButton.shadowRoot.querySelector('.spoki-fixed-btn-label').style.display ='';}} catch (e) {console.log(e)}}, $label_delay)})(document)</script>";
			}

			$spoki_fixed_btn_popup = "";
			$has_popup = isset($this->options['buttons']['fixed_support_button_show_popup']) && $this->options['buttons']['fixed_support_button_show_popup'] == 1;
			if ($has_popup) {
				$spoki_fixed_btn_popup_delay = (intval((isset($this->options['buttons']['fixed_support_button_popup_delay']) && $this->options['buttons']['fixed_support_button_popup_delay'] != "") ? $this->options['buttons']['fixed_support_button_popup_delay'] : '0') * 1000) + 1000;
				$spoki_fixed_btn_popup = "<span class='spoki-fixed-btn-popup' style='display: none;'>1</span><script>(function (d) {setTimeout(function (){try {const shadowedButton = d.querySelector('#spoki-shadowed-fixed-button'); if (shadowedButton) {shadowedButton.shadowRoot.querySelector('.spoki-fixed-btn-popup').style.display ='';}} catch (e) {console.log(e)}}, $spoki_fixed_btn_popup_delay)})(document)</script>";
			}

			$hidden_mobile = isset($this->options['buttons']['fixed_support_button_hide_mobile']) && $this->options['buttons']['fixed_support_button_hide_mobile'] == 1 ? "hidden-mobile" : "";
			$hidden_tablet = isset($this->options['buttons']['fixed_support_button_hide_tablet']) && $this->options['buttons']['fixed_support_button_hide_tablet'] == 1 ? "hidden-tablet" : "";
			$hidden_desktop = isset($this->options['buttons']['fixed_support_button_hide_desktop']) && $this->options['buttons']['fixed_support_button_hide_desktop'] == 1 ? "hidden-desktop" : "";
			$class = "$hidden_mobile $hidden_tablet $hidden_desktop";

			$chat_widget = "";
			$has_chat_widget = isset($this->options['buttons']['fixed_support_button_show_chat_widget']) && $this->options['buttons']['fixed_support_button_show_chat_widget'] == 1;
			if ($has_chat_widget) {
				$background_image_url = plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/wa-background.jpeg';
				$from_message = $this->options['buttons']['fixed_support_button_chat_widget_message'];
				$send_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M1.101 21.757L23.8 12.028 1.101 2.3l.011 7.912 13.623 1.816-13.623 1.817-.011 7.912z"></path></svg>';
				$chat_widget_delay = intval((isset($this->options['buttons']['fixed_support_button_chat_widget_delay']) && $this->options['buttons']['fixed_support_button_chat_widget_delay'] != "") ? $this->options['buttons']['fixed_support_button_chat_widget_delay'] : '0') * 1000;
				$chat_widget_delay_script = "";
				if ($chat_widget_delay != 0) {
					$chat_widget_delay = $chat_widget_delay + 1000;
					$chat_widget_delay_script = "<script>(function (d) {setTimeout(function (){try {const shadowedButton = d.querySelector('#spoki-shadowed-fixed-button'); if (shadowedButton) {shadowedButton.shadowRoot.querySelector('#spoki-chat-preview').classList.remove('hidden')}} catch (e) {console.log(e)}}, $chat_widget_delay)})(document)</script>";
				}
				$chat_widget_script = "<script>(function (d) {setTimeout(function(){const shadowedButton = d.querySelector('#spoki-shadowed-fixed-button'); if (shadowedButton) {shadowedButton.shadowRoot.querySelector('#spoki-chat-link').addEventListener('click', function(e){e.preventDefault();document.querySelector('#spoki-shadowed-fixed-button').shadowRoot.querySelector('#spoki-chat-preview').classList.toggle('hidden')})}}, 1000)})(document)</script>$chat_widget_delay_script";
				$link = SPOKI_BASE_API_BUTTONS;
				$furl = get_bloginfo('url');
				$chat_widget = "<div id='spoki-chat-preview' class='hidden' style='bottom:{$size}px;background-image: url(\"{$background_image_url}\")'><div class='spoki-chat-preview-chat-message'>{$from_message}</div><div id='spoki-chat-preview-footer'><form method='get' target='_blank' action='$link'><input type='hidden' name='phone' value='{$phone}'/><input type='hidden' name='furl' value='{$furl}'/><input type='hidden' name='type' value='1'/><input id='spoki-chat-preview-message' type='text' name='text' value='$text' /><button id='spoki-chat-preview-send' type='submit'>{$send_icon}</button></form></div></div>$chat_widget_script";
			}

			echo "<div id='spoki-shadowed-fixed-button' class='spoki-shadowed-button'><div id='spoki-fixed-btn' class='$class' style='$position:{$side_space}px;bottom:{$bottom_space}px;'>{$chat_widget}{$spoki_fixed_btn_label}{$spoki_fixed_btn_popup}<a id='spoki-chat-link' style='background-color:{$color};border-radius:$border_radius;height:{$size}px;width:{$size}px;' href='$wa_link' target='_blank' rel='nofollow'>" . spoki_get_wa_logo($size) . "</a></div></div>";
		}
	}

	/**
	 * Render the product button for every product in shop page
	 */
	public function render_product_item_listing_button()
	{
		global $product;
		$hide_non_working = isset($this->options['woocommerce']['product_item_listing_button_hide_non_working']) && $this->options['woocommerce']['product_item_listing_button_hide_non_working'] == 1;
		$is_working_days_times_enabled = isset($this->options['working_days_times']['enabled']) && $this->options['working_days_times']['enabled'] == 1;
		$is_non_working_day_time = isset($this->options['working_days_times']) && spoki_is_non_working_day_time($this->options['working_days_times']);;
		if ($hide_non_working && $is_working_days_times_enabled && $is_non_working_day_time) {
			return;
		}

		$prefix = isset($this->options['prefix']) ? $this->options['prefix'] : '';
		$phone = $prefix . $this->options['telephone'];
		$cta = __("Request support on WhatsApp", "spoki");
		if (isset($this->options['woocommerce']['product_item_listing_button_cta']) && !empty($this->options['woocommerce']['product_item_listing_button_cta'])) {
			$cta = $this->options['woocommerce']['product_item_listing_button_cta'];
		}
		$message = __("Hi, I want to buy:", "spoki");
		if (isset($this->options['woocommerce']['product_item_listing_button_text']) && !empty($this->options['woocommerce']['product_item_listing_button_text'])) {
			$message = $this->options['woocommerce']['product_item_listing_button_text'];
		}
		$is_non_working_days_times_text_enabled = isset($this->options['woocommerce']['product_item_listing_button_non_working_text_enabled']) && $this->options['woocommerce']['product_item_listing_button_non_working_text_enabled'] == 1;
		if ($is_non_working_day_time && $is_non_working_days_times_text_enabled) {
			$message = isset($this->options['woocommerce']['product_item_listing_button_non_working_text']) ? $this->options['woocommerce']['product_item_listing_button_non_working_text'] : "";
		}
		$color = isset($this->options['woocommerce']['product_item_listing_button_color']) ? $this->options['woocommerce']['product_item_listing_button_color'] : '#23D366';
		$font_size = isset($this->options['woocommerce']['product_item_listing_button_font_size']) ? $this->options['woocommerce']['product_item_listing_button_font_size'] : '12';
		$border_type = isset($this->options['woocommerce']['product_item_listing_button_border']) ? $this->options['woocommerce']['product_item_listing_button_border'] : 'rounded';

		$margin = [
			"top" => isset($this->options['woocommerce']['product_item_listing_button_margin_top']) ? $this->options['woocommerce']['product_item_listing_button_margin_top'] : '4',
			"bottom" => isset($this->options['woocommerce']['product_item_listing_button_margin_bottom']) ? $this->options['woocommerce']['product_item_listing_button_margin_bottom'] : '4',
			"left" => isset($this->options['woocommerce']['product_item_listing_button_margin_left']) ? $this->options['woocommerce']['product_item_listing_button_margin_left'] : '0',
			"right" => isset($this->options['woocommerce']['product_item_listing_button_margin_right']) ? $this->options['woocommerce']['product_item_listing_button_margin_right'] : '0',
		];

		$padding = [
			"top" => isset($this->options['woocommerce']['product_item_listing_button_padding_top']) ? $this->options['woocommerce']['product_item_listing_button_padding_top'] : '8',
			"bottom" => isset($this->options['woocommerce']['product_item_listing_button_padding_bottom']) ? $this->options['woocommerce']['product_item_listing_button_padding_bottom'] : '8',
			"left" => isset($this->options['woocommerce']['product_item_listing_button_padding_left']) ? $this->options['woocommerce']['product_item_listing_button_padding_left'] : '14',
			"right" => isset($this->options['woocommerce']['product_item_listing_button_padding_right']) ? $this->options['woocommerce']['product_item_listing_button_padding_right'] : '14',
		];

		$this->render_product_button($product, $phone, $cta, $message, $margin, $padding, $color, $border_type, $font_size, true);
	}

	/**
	 * Render the button in the cart page
	 */
	public function render_cart_button()
	{
		global $product;
		$hide_non_working = isset($this->options['woocommerce']['cart_button_hide_non_working']) && $this->options['woocommerce']['cart_button_hide_non_working'] == 1;
		$is_working_days_times_enabled = isset($this->options['working_days_times']['enabled']) && $this->options['working_days_times']['enabled'] == 1;
		$is_non_working_day_time = isset($this->options['working_days_times']) && spoki_is_non_working_day_time($this->options['working_days_times']);;
		if ($hide_non_working && $is_working_days_times_enabled && $is_non_working_day_time) {
			return;
		}
		$prefix = isset($this->options['prefix']) ? $this->options['prefix'] : '';
		$phone = $prefix . $this->options['telephone'];
		$cta = __("Order via WhatsApp", "spoki");
		if (isset($this->options['woocommerce']['cart_button_cta']) && !empty($this->options['woocommerce']['cart_button_cta'])) {
			$cta = $this->options['woocommerce']['cart_button_cta'];
		}
		$message = __("Hi, I want to buy:", "spoki");
		if (isset($this->options['woocommerce']['cart_button_text']) && !empty($this->options['woocommerce']['cart_button_text'])) {
			$message = $this->options['woocommerce']['cart_button_text'];
		}
		$is_non_working_days_times_text_enabled = isset($this->options['woocommerce']['cart_button_non_working_text_enabled']) && $this->options['woocommerce']['cart_button_non_working_text_enabled'] == 1;
		if ($is_non_working_day_time && $is_non_working_days_times_text_enabled) {
			$message = isset($this->options['woocommerce']['cart_button_non_working_text']) ? $this->options['woocommerce']['cart_button_non_working_text'] : "";
		}
		$color = isset($this->options['woocommerce']['cart_button_color']) ? $this->options['woocommerce']['cart_button_color'] : '#23D366';
		$font_size = isset($this->options['woocommerce']['cart_button_font_size']) ? $this->options['woocommerce']['cart_button_font_size'] : '12';
		$icon_size = intval($font_size) < 16 ? 16 : $font_size;
		$final_message = urlencode($message);
		$products = WC()->cart->get_cart();

		foreach ($products as $item) {
			$product_id = $item['product_id'];
			$qty = $item['quantity'];
			$product = wc_get_product($product_id);
			$product_url = $product->get_permalink();
			$product_title = $product->get_name();
			$price = wp_strip_all_tags(wc_price(wc_get_price_including_tax($product)));
			$encoded_title = urlencode($product_title);
			$encoded_product_url = urlencode($product_url);
			$final_message .= "%0D%0A%0D%0A_(ID:%20$product_id)_%20*$encoded_title*%20$price%20x$qty%0D%0A$encoded_product_url";
		}
		$total = wp_strip_all_tags(wc_price(WC()->cart->get_subtotal()));
		$final_message .= "%0D%0A%0D%0A*" . __("Total", "spoki") . "*: %20$total%20";

		$furl = get_bloginfo('url');
		$href = SPOKI_BASE_API_BUTTONS . "?type=4&phone=$phone&text=$final_message&furl=$furl";
		$title = "$cta";
		$class = 'button spoki-button size-4';
		$border_type = isset($this->options['woocommerce']['cart_button_border']) ? $this->options['woocommerce']['cart_button_border'] : 'rounded';

		$border_radius = '16px';
		if ($border_type == 'squared') {
			$border_radius = '0';
		}

		$margin = [
			"top" => isset($this->options['woocommerce']['cart_button_margin_top']) ? $this->options['woocommerce']['cart_button_margin_top'] : '4',
			"bottom" => isset($this->options['woocommerce']['cart_button_margin_bottom']) ? $this->options['woocommerce']['cart_button_margin_bottom'] : '4',
			"left" => isset($this->options['woocommerce']['cart_button_margin_left']) ? $this->options['woocommerce']['cart_button_margin_left'] : '0',
			"right" => isset($this->options['woocommerce']['cart_button_margin_right']) ? $this->options['woocommerce']['cart_button_margin_right'] : '0',
		];
		$padding = [
			"top" => isset($this->options['woocommerce']['cart_button_padding_top']) ? $this->options['woocommerce']['cart_button_padding_top'] : '8',
			"bottom" => isset($this->options['woocommerce']['cart_button_padding_bottom']) ? $this->options['woocommerce']['cart_button_padding_bottom'] : '8',
			"left" => isset($this->options['woocommerce']['cart_button_padding_left']) ? $this->options['woocommerce']['cart_button_padding_left'] : '14',
			"right" => isset($this->options['woocommerce']['cart_button_padding_right']) ? $this->options['woocommerce']['cart_button_padding_right'] : '14',
		];

		$margin_style = "margin-top:{$margin['top']}px;margin-bottom:{$margin['bottom']}px;margin-left:{$margin['left']}px;margin-right:{$margin['right']}px;";
		$padding_style = "padding-top:{$padding['top']}px;padding-bottom:{$padding['bottom']}px;padding-left:{$padding['left']}px;padding-right:{$padding['right']}px;";

		echo "<div class='spoki-shadowed-button'><div class='spoki-button-relative' style='{$margin_style}'><a href='$href' target='_blank' rel='nofollow' title='$title' class='$class' style='background-color:$color;border-radius:$border_radius;font-size:{$font_size}px;{$padding_style}'>" . spoki_get_wa_logo($icon_size) . "<span>$cta</span></a></div></div>";
	}

	/**
	 * Render the button in the product page
	 */
	public function render_single_product_button()
	{
		global $product;
		$hide_non_working = isset($this->options['woocommerce']['single_product_button_hide_non_working']) && $this->options['woocommerce']['single_product_button_hide_non_working'] == 1;
		$is_working_days_times_enabled = isset($this->options['working_days_times']['enabled']) && $this->options['working_days_times']['enabled'] == 1;
		$is_non_working_day_time = isset($this->options['working_days_times']) && spoki_is_non_working_day_time($this->options['working_days_times']);
		if ($hide_non_working && $is_working_days_times_enabled && $is_non_working_day_time) {
			return;
		}

		$prefix = isset($this->options['prefix']) ? $this->options['prefix'] : '';
		$phone = $prefix . $this->options['telephone'];
		$cta = __("Request support on WhatsApp", "spoki");
		if (isset($this->options['woocommerce']['single_product_button_cta']) && !empty($this->options['woocommerce']['single_product_button_cta'])) {
			$cta = $this->options['woocommerce']['single_product_button_cta'];
		}
		$message = __("Hi, I want to buy:", "spoki");
		if (isset($this->options['woocommerce']['single_product_button_text']) && !empty($this->options['woocommerce']['single_product_button_text'])) {
			$message = $this->options['woocommerce']['single_product_button_text'];
		}
		$is_non_working_days_times_text_enabled = isset($this->options['woocommerce']['single_product_button_non_working_text_enabled']) && $this->options['woocommerce']['single_product_button_non_working_text_enabled'] == 1;
		if ($is_non_working_day_time && $is_non_working_days_times_text_enabled) {
			$message = isset($this->options['woocommerce']['single_product_button_non_working_text']) ? $this->options['woocommerce']['single_product_button_non_working_text'] : "";
		}
		$position = isset($this->options['woocommerce']['single_product_button_position']) ? $this->options['woocommerce']['single_product_button_position'] : 'after_atc';
		$color = isset($this->options['woocommerce']['single_product_button_color']) ? $this->options['woocommerce']['single_product_button_color'] : '#23D366';
		$font_size = isset($this->options['woocommerce']['single_product_button_font_size']) ? $this->options['woocommerce']['single_product_button_font_size'] : '12';
		$border_type = isset($this->options['woocommerce']['single_product_button_border']) ? $this->options['woocommerce']['single_product_button_border'] : 'rounded';

		$margin = [
			"top" => isset($this->options['woocommerce']['single_product_button_margin_top']) ? $this->options['woocommerce']['single_product_button_margin_top'] : '4',
			"bottom" => isset($this->options['woocommerce']['single_product_button_margin_bottom']) ? $this->options['woocommerce']['single_product_button_margin_bottom'] : '4',
			"left" => isset($this->options['woocommerce']['single_product_button_margin_left']) ? $this->options['woocommerce']['single_product_button_margin_left'] : '0',
			"right" => isset($this->options['woocommerce']['single_product_button_margin_right']) ? $this->options['woocommerce']['single_product_button_margin_right'] : '0',
		];

		$padding = [
			"top" => isset($this->options['woocommerce']['single_product_button_padding_top']) ? $this->options['woocommerce']['single_product_button_padding_top'] : '8',
			"bottom" => isset($this->options['woocommerce']['single_product_button_padding_bottom']) ? $this->options['woocommerce']['single_product_button_padding_bottom'] : '8',
			"left" => isset($this->options['woocommerce']['single_product_button_padding_left']) ? $this->options['woocommerce']['single_product_button_padding_left'] : '14',
			"right" => isset($this->options['woocommerce']['single_product_button_padding_right']) ? $this->options['woocommerce']['single_product_button_padding_right'] : '14',
		];

		$this->render_product_button($product, $phone, $cta, $message, $margin, $padding, $color, $border_type, $font_size, false, $position);
	}

	/**
	 * Render the button of a product
	 *
	 * @param $product
	 * @param $phone
	 * @param $cta
	 * @param $message
	 * @param null $position
	 */
	public function render_product_button($product, $phone, $cta, $message, $margin, $padding, $color = '#23D366', $border_type = 'rounded', $font_size = '12', $is_listing = false, $position = null)
	{
		$product_url = $product->get_permalink();
		$product_title = $product->get_name();
		$product_id = $product->get_id();
		$price = wp_strip_all_tags(wc_price(wc_get_price_including_tax($product)));
		$encoded_message = urlencode($message);
		$encoded_title = urlencode($product_title);
		$encoded_product_url = urlencode($product_url);
		$final_message = "$encoded_message%0D%0A%0D%0A(ID:%20$product_id)%20*$encoded_title*%20$price%0D%0A$encoded_product_url";
		$type = $is_listing ? '2' : '3';
		$title = "$cta $product_title";
		$additional_class = sprintf('product_type_%s', $product->get_type());

		$this->render_relative_button($phone, $cta, $title, $final_message, $additional_class, $type, $margin, $padding, $color, $border_type, $font_size, $position);
	}

	/**
	 * Render a relative button
	 */
	public function render_relative_button($phone, $cta, $title, $final_message, $additional_class, $type, $margin, $padding, $color = '#23D366', $border_type = 'rounded', $font_size = '12', $position = null, $id = '', $class_names = '', $custom_css = '')
	{
		$class = sprintf('button spoki-button %s', $additional_class);
		if (isset($position)) {
			$class .= " size-2 $position";
		} else {
			$class .= " size-4";
		}

		$border_radius = '16px';
		if ($border_type == 'squared') {
			$border_radius = '0';
		}

		$furl = get_bloginfo('url');
		$href = SPOKI_BASE_API_BUTTONS . "?type={$type}&phone=$phone&text=$final_message&furl=$furl";
		$icon_size = intval($font_size) < 16 ? 16 : $font_size;

		$margin_style = "margin-top:{$margin['top']}px;margin-bottom:{$margin['bottom']}px;margin-left:{$margin['left']}px;margin-right:{$margin['right']}px;";
		$padding_style = "padding-top:{$padding['top']}px;padding-bottom:{$padding['bottom']}px;padding-left:{$padding['left']}px;padding-right:{$padding['right']}px;";

		echo "<div class='spoki-shadowed-button'><div class='spoki-button-relative $class_names' id='$id' style='$margin_style'><a href='$href' target='_blank' rel='nofollow' title='$title' class='$class' style='background-color:$color;border-radius:$border_radius;font-size:{$font_size}px;$padding_style'>" . spoki_get_wa_logo($icon_size) . "<span>$cta</span></a><style>$custom_css</style></div></div>";
	}

	/**
	 * Get the link to change plan to pro
	 *
	 * @return string
	 */
	public function get_pro_plan_link(): string
	{
		return 'https://spoki.it/upgrade-spoki-plugin/';
	}

	/**
	 * Update Spoki options
	 *
	 * @param $current_options
	 * @param $new_options
	 */
	private function update_options($current_options, $new_options)
	{
		$c_options = is_array($current_options) ? $current_options : [];
		if (isset($new_options["woocommerce"])) {
			$woocommerce = [];
			if (isset($c_options["abandoned_carts"])) {
				$woocommerce = array_merge($c_options["woocommerce"], $new_options["woocommerce"]);
			} else {
				$woocommerce = $new_options["woocommerce"];
			}
			$new_options["woocommerce"] = $woocommerce;
		}
		if (isset($new_options["abandoned_carts"])) {
			$abandoned_carts = [];
			if (isset($c_options["abandoned_carts"])) {
				$abandoned_carts = array_merge($c_options["abandoned_carts"], $new_options["abandoned_carts"]);
			} else {
				$abandoned_carts = $new_options["abandoned_carts"];
			}
			$new_options["abandoned_carts"] = $abandoned_carts;
		}
		if (isset($new_options["secret_status"])) {
			$secret_status = [];
			if (isset($c_options["secret_status"])) {
				$secret_status = array_merge($c_options["secret_status"], $new_options["secret_status"]);
			} else {
				$secret_status = $new_options["secret_status"];
			}
			$new_options["secret_status"] = $secret_status;
		}
		if (isset($new_options["account_info"])) {
			$account_info = [];
			if (isset($c_options["account_info"])) {
				$account_info = array_merge($c_options["account_info"], $new_options["account_info"]);
			} else {
				$account_info = $new_options["account_info"];
			}
			$new_options["account_info"] = $account_info;
		}
		update_option(SPOKI_OPTIONS, array_merge($c_options, $new_options));
		$this->options = get_option(SPOKI_OPTIONS);
	}

	/**
	 * Check the secret status periodically
	 */
	public function check_secret_status()
	{
		$this->fetch_secret_status(true);
	}

	/**
	 * Fetch the status of the Spoki keys
	 */
	public function fetch_secret_status($force_checking = false)
	{
		$response = null;
		if ($force_checking) {
			$response = $this->check_spoki_status();
		}

		if (!isset($this->options['secret']) || $this->options['secret'] == '' || !isset($this->options['delivery_url']) || $this->options['delivery_url'] == '') {
			$this->update_options(get_option(SPOKI_OPTIONS), ["secret_status" => [
				'secret' => isset($this->options['secret']) ? $this->options['secret'] : '',
				'delivery_url' => isset($this->options['delivery_url']) ? $this->options['delivery_url'] : '',
				'code' => 0,
				'message' => ''
			]]);
		} else if (!isset($this->options['secret_status']) || $this->options['secret_status']['code'] != 200 || $this->options['secret'] != $this->options['secret_status']['secret'] || $this->options['delivery_url'] != $this->options['secret_status']['delivery_url']) {
			if (!$force_checking) {
				$response = $this->check_spoki_status();
			}
			$this->update_options(get_option(SPOKI_OPTIONS), ["secret_status" => [
				'secret' => isset($this->options['secret']) ? $this->options['secret'] : '',
				'delivery_url' => isset($this->options['delivery_url']) ? $this->options['delivery_url'] : '',
				'code' => wp_remote_retrieve_response_code($response),
				'message' => wp_remote_retrieve_response_message($response)
			]]);
		}
		return $response;
	}

	/**
	 * Activation Reset
	 */
	public function activation_reset()
	{
		register_uninstall_hook(SPOKI_PLUGIN_FILE, array($this, 'uninstall_plugin'));
		if (!class_exists('WooCommerce')) {
			return;
		}
		$this->initialize_cart_abandonment_tables();
		$Abandonment = Spoki_Abandoned_Carts::instance();
		$spokiDomain = $Abandonment->get_spoki_setting_by_meta("spoki_domain");
		$Abandonment->set_spoki_setting_by_meta("plugin_activated", "true");
		if ($spokiDomain != null || $spokiDomain != "")
			$Abandonment->save_webhook_url($spokiDomain);
	}

	/**
	 * Deactivation Reset
	 */
	public function deactivation_reset()
	{
		$Abandonment = Spoki_Abandoned_Carts::instance();
		# Unschedule hook for versions <= v2.8.1
		$Abandonment->unschedule_hook();
		if (!class_exists('WooCommerce')) {
			return;
		}
		$spokiDomain = $Abandonment->get_spoki_setting_by_meta("spoki_domain");
		$Abandonment->set_spoki_setting_by_meta("plugin_activated", "false");
		if ($spokiDomain != null || $spokiDomain != "")
			$Abandonment->disable_webhook_url($spokiDomain);
	}

	/**
	 * Uninstall Plugin
	 */
	public function uninstall_plugin()
	{
		if (!class_exists('WooCommerce')) {
			return;
		}
		$Abandonment = Spoki_Abandoned_Carts::instance();
		$spokiDomain = $Abandonment->get_spoki_setting_by_meta("spoki_domain");
		$Abandonment->set_spoki_setting_by_meta("plugin_activated", "false");
		if ($spokiDomain != null || $spokiDomain != "")
			$Abandonment->disable_webhook_url($spokiDomain);
	}
}

/**
 * Executed on activation of the plugin.
 * Redirects the user after plugin activation.
 */
function spoki_activation()
{
	if (!((isset($_REQUEST['action']) && 'activate-selected' === $_REQUEST['action']) && (isset($_POST['checked']) && count($_POST['checked']) > 1))) {
		add_option('spoki_activation_redirect', true);
	}
}

/**
 * Redirects the user after plugin activation.
 */
function spoki_activation_redirect()
{
	if (get_option('spoki_activation_redirect', false)) {
		delete_option('spoki_activation_redirect');
		exit(wp_redirect(admin_url('/admin.php?page=' . SPOKI_PLUGIN_NAME)));
	}
}

/**
 * Executed on deactivation of the plugin
 */
function spoki_deactivation()
{
	//	delete_option(SPOKI_OPTIONS);
	$timestamp = wp_next_scheduled('spoki_cron_hook');
	wp_unschedule_event($timestamp, 'spoki_cron_hook');
}

register_deactivation_hook(__FILE__, 'spoki_deactivation');
register_activation_hook(__FILE__, 'spoki_activation');
add_action('admin_init', 'spoki_activation_redirect');
