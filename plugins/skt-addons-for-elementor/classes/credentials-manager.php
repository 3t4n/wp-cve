<?php

namespace Skt_Addons_Elementor\Elementor;

defined('ABSPATH') || die();

class Credentials_Manager {
	const CREDENTIALS_DB_KEY = 'sktaddonselementor_credentials';

	/**
	 * Initialize
	 */
	public static function init() {}

	public static function get_credentials_map() {
		$credentials_map = [];

		$local_credentials_map = self::get_local_credentials_map();
		$credentials_map = array_merge($credentials_map, $local_credentials_map);

		return apply_filters('sktaddonselementor_get_credentials_map', $credentials_map);
	}

	public static function get_saved_credentials() {
		return get_option(self::CREDENTIALS_DB_KEY, []);
	}

	public static function save_credentials($credentials = []) {
		update_option(self::CREDENTIALS_DB_KEY, $credentials);
	}

	/**
	 * Get the pro credentials map for dashboard only
	 *
	 * @return array
	 */
	public static function get_pro_credentials_map() {
		return [];
	}

	/**
	 * Get the free credentials map
	 *
	 * @return array
	 */
	public static function get_local_credentials_map() {
		return [
			'mailchimp' => [
				'title' => __('MailChimp', 'skt-addons-elementor'),
				'icon' => 'skti skti-mail-chimp',
				'fiels' => [
					[
						'label' => esc_html__('Enter API Key. ', 'skt-addons-elementor'),
						'type' => 'text',
						'name' => 'api',
						'help' => [
							'instruction' => esc_html__('Get your api key here', 'skt-addons-elementor'),
							'link' => 'https://admin.mailchimp.com/account/api/'
						],
					],
				],
				'demo' => '',
				'is_pro' => false,
			],
		];
	}
}

Credentials_Manager::init();