<?php

namespace ZPOS\Admin\Stations\Tabs;

use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\Input\ActionConfirmLink;
use ZPOS\Admin\Setting\Input\Description;
use ZPOS\Admin\Setting\Input\Input;
use ZPOS\Admin\Setting\Input\DropdownSelect;
use ZPOS\Admin\Setting\Input\NotificationsSettings;
use ZPOS\Admin\Setting\PostTab;
use ZPOS\Admin\Setting\Sanitize\Boolean;
use ZPOS\Admin\Stations\Post;
use ZPOS\Admin\Stations\Setting;
use ZPOS\Station;

class General extends PostTab
{
	use Boolean;

	public $exact = true;
	public $name;
	public $path = '/general';

	public function __construct()
	{
		parent::__construct();
		$this->name = __('General', 'zpos-wp-api');
	}

	public function getBoxes()
	{
		return [
			new Box(
				__('Notifications', 'zpos-wp-api'),
				null,
				new NotificationsSettings(
					null,
					'pos_notifications',
					$this->getValue('pos_notifications'),
					$this->getValuesForNotifications(),
					[
						'radio_input' => [
							'name' => 'pos_enable_notifications',
							'values' => [
								[
									'label' => __('All Enabled', 'zpos-wp-api'),
									'value' => 'all_enabled',
								],
								[
									'label' => __('Enable by Type', 'zpos-wp-api'),
									'value' => 'enable_by_type',
								],
							],
						],
						'multiple_switches' => [
							[
								'label' => __('Product notifications', 'zpos-wp-api'),
								'name' => 'pos_product_notifications',
							],
							[
								'label' => __('Cart notifications', 'zpos-wp-api'),
								'name' => 'pos_cart_notifications',
							],
							[
								'label' => __('Customer notifications', 'zpos-wp-api'),
								'name' => 'pos_customer_notifications',
							],
						],
					],
					['sanitize' => [$this, 'sanitizeNotifications']]
				)
			),
			new Box(
				__('POS Base Address', 'zpos-wp-api'),
				['ignore' => Setting::isWCStationEdit()],
				new Input(
					__('Address line 1', 'zpos-wp-api'),
					'pos_address_1',
					$this->getValue('pos_address_1')
				),
				new Input(
					__('Address line 2', 'zpos-wp-api'),
					'pos_address_2',
					$this->getValue('pos_address_2')
				),
				new Input(__('City', 'zpos-wp-api'), 'pos_city', $this->getValue('pos_city')),
				new Input(
					__('Postcode / ZIP', 'zpos-wp-api'),
					'pos_postcode',
					$this->getValue('pos_postcode')
				),
				new DropdownSelect(
					__('Country / State', 'zpos-wp-api'),
					'pos_country_state',
					[$this, 'getCountryState'],
					[$this, 'get_countries'],
					[
						'savePost' => [$this, 'setCountryState'],
					]
				)
			),
			new Box(
				__('POS Base Address', 'zpos-wp-api'),
				['ignore' => !Setting::isWCStationEdit()],
				new Description(
					sprintf(
						'%s <a href="%s">%s</a>',
						__('Station uses', 'zpos-wp-api'),
						add_query_arg('page', 'wc-settings', admin_url('admin.php')),
						__('Shop Base Store Address Settings', 'zpos-wp-api')
					)
				)
			),
			new Box(
				null,
				[
					'ignore' => !self::isDeletableStation(),
					'withoutBreak' => true,
				],
				new ActionConfirmLink(
					__('Delete Permanently', 'zpos-wp-api'),
					[$this, 'deleteURL'],
					'button submitdelete delete-button-station aria-button-if-js',
					__(
						"!Important the action of deleting a POS Station can't be undone and all orders placed by the station will no longer open in the POS view",
						'zpos-wp-api'
					)
				)
			),
		];
	}

	public function deleteURL()
	{
		global $post;
		return str_replace(
			'&amp;',
			'&',
			admin_url(wp_nonce_url("post.php?action=delete&post=$post->ID", 'delete-post_' . $post->ID))
		);
	}

	public function get_countries()
	{
		$countries = WC()->countries->get_countries();
		$states = array_reduce(
			array_keys($countries),
			function ($acc, $country) use ($countries) {
				$country_name = $countries[$country];
				$states = WC()->countries->get_states($country);

				if ($states) {
					$states = array_map(function ($state) use ($country_name) {
						return $country_name . ' - ' . $state;
					}, $states);
					$states_keys = array_map(function ($state) use ($country) {
						return $country . ':' . $state;
					}, array_keys($states));
					$states = array_combine($states_keys, $states);

					$acc = array_merge($acc, $states);
				} else {
					$acc[$country] = $country_name;
				}
				return $acc;
			},
			[]
		);
		return array_map(
			function ($value, $label) {
				return compact('value', 'label');
			},
			array_keys($states),
			$states
		);
	}

	public function mediaGetValue($post)
	{
		$id = $this->getValue('pos_logo')($post);
		$src = null;
		if ($id) {
			$src_data = wp_get_attachment_image_src($id, 'full');
			$src = $src_data[0];
		}

		return compact('id', 'src');
	}

	public function getCountryState($post)
	{
		$country = $this->getValue('pos_country')($post);
		$state = $this->getValue('pos_state')($post);
		$country_state = $country . ($state ? ':' . $state : '');
		return $country_state;
	}

	public function setCountryState($post)
	{
		$country_state = explode(':', esc_sql($_REQUEST['pos_country_state']));
		update_post_meta($post->ID, 'pos_country', $country_state[0]);
		update_post_meta($post->ID, 'pos_state', $country_state[1]);
	}

	public static function isDeletableStation()
	{
		if (!is_admin() || !isset($_GET['post'])) {
			return false;
		}

		if (get_post_status() === 'auto-draft') {
			return false;
		}

		if (is_multisite() && is_super_admin(get_current_user_id())) {
			$pos = (int) $_GET['post'];
			if (in_array($pos, [Station::getWCStationID(), Station::getDefaultStationID()])) {
				return false;
			}
		}

		return current_user_can('delete_woocommerce_pos', $_GET['post']);
	}

	public function getValuesForNotifications()
	{
		return [
			[
				'key' => 'all',
				'label' => __('All', 'zpos-wp-api'),
				'selectionColor' => '#53C692',
			],
			[
				'key' => 'errors',
				'label' => __('Only Errors', 'zpos-wp-api'),
				'selectionColor' => '#8B0000',
			],
			[
				'key' => 'disable',
				'label' => __('Disable', 'zpos-wp-api'),
				'selectionColor' => '#396EA1',
			],
		];
	}

	public static function getNotificationsDefaultValues()
	{
		return [
			'pos_enable_notifications' => 'all_enabled',
			'pos_product_notifications' => 'all',
			'pos_cart_notifications' => 'all',
			'pos_customer_notifications' => 'all',
		];
	}

	public static function sanitizeNotifications($data)
	{
		return $data;
	}

	public static function getDefaultValue($value, $post, $name)
	{
		switch ($name) {
			case 'pos_notifications':
				return self::getNotificationsDefaultValues();
			default:
				return $value;
		}
	}
}
