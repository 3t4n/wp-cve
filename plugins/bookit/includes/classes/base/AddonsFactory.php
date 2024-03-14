<?php

namespace Bookit\Classes\Base;

use Bookit\Classes\Base\Addon;
use Bookit\Helpers\FreemiusHelper;

class FakeAddon extends Addon {

	public function __construct( $name, $settingTab, $link ) {
		self::$title      = $this->generateTitleFromName( $name );
		self::$link       = $link;
		self::$settingTab = $settingTab;

		$getSettingsFunction = lcfirst(
			str_replace( '-', '', ucwords( $name . 'Settings', '-' ) )
		);

		$settings = array();
		if ( method_exists( __CLASS__, $getSettingsFunction ) ) {
			$settings = call_user_func( array( __CLASS__, $getSettingsFunction ) );
		}

		self::$settings = $settings;
	}

	public function generateTitleFromName( $name ) {
		return implode( ' ', array_map( 'ucfirst', explode( '-', $name ) ) );
	}

	/**
	 * Default addon info for plugin
	 *
	 * @return array
	 */
	public function getAddonData() {
		return array(
			'tab'            => self::$settingTab,
			'title'          => self::$title,
			'active'         => self::$active,
			'link'           => self::$link,
			'settings'       => self::$settings,
			'installed'      => false, // always false
			'isCanUse'       => ( self::$is_premium && self::$is_paying || ! self::$is_premium ),
			'activationLink' => self::$activationLink,
		);
	}

	private static function googleCalendarSettings() {
		return array(
			'enabled'               => true,
			'redirect_url'          => get_site_url() . '/wp-admin/admin.php?page=bookit-staff',
			'client_id'             => '',
			'client_secret'         => '',
			'send_pending'          => false,
			'rm_busy_slots'         => false,
			'customer_as_attendees' => false,
			'events_limit'          => null,
			'template'              => array(
				'title' => __( 'Appointment #[appointment_id]' ),
				'body'  => __(
					'Service: [service_title]' . PHP_EOL . // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
					'Customer: [customer_name]' . PHP_EOL .
					'Customer phone: [customer_phone]' . PHP_EOL .
					'Customer email: [customer_email]' . PHP_EOL .
					'Start time: [start_time]' . PHP_EOL .
					'Payment Method: [payment_method]' . PHP_EOL .
					'Payment Status: [payment_status]' . PHP_EOL .
					'Total: [total]' . PHP_EOL . 'Status: [status]'
				),
			),
		);
	}

	private static function proSettings() {
		return array(
			'payments' => array(
				array(
					'name'     => 'paypal',
					'settings' => array(),
				),
				array(
					'name'     => 'stripe',
					'settings' => array(),
				),
				array(
					'name'     => 'woocommerce',
					'settings' => array(),
				),
			),
		);
	}
}

/**
 * Class AddonsFactory
 * generate data for addons which
 * is not installed
 */
class AddonsFactory {

	/**
	 * Bookit Addons List
	 *
	 * @var string[]
	 */
	public static $existAddons = array(
		array(
			'name' => 'google-calendar',
			'tab'  => 'self',
			'link' => 'https://stylemixthemes.com/wordpress-appointment-plugin/?utm_source=admin&utm_medium=promo&utm_campaign=2020',
		),
		array(
			'name' => 'pro',
			'tab'  => 'payments',
			'link' => 'https://stylemixthemes.com/wordpress-appointment-plugin/?utm_source=admin&utm_medium=promo&utm_campaign=2020',
		),
	);


	/**
	 * Generate empty data for exist addons
	 * used to show all addon abilites to user
	 *
	 * @param array $installedAddons
	 *
	 * @return array
	 */
	public static function getExistAddonsList( $installedAddons = array() ) {
		/** clean self::$existAddons from installed */
		self::removeInstalledAddonsFromExistList( $installedAddons );

		$addons = array();
		foreach ( self::$existAddons as $addon ) {
			$fakeAddon = new FakeAddon( $addon['name'], $addon['tab'], $addon['link'] );
			$addons[]  = array(
				'name'         => $addon['name'],
				'data'         => $fakeAddon->getAddonData(),
				'translations' => array(),
				'freemius'     => FreemiusHelper::get_addon_info( Plugin::$prefix . $addon['name'] ),
			);
		}
		return $addons;
	}

	/**
	 * remove already exist addons from list
	 *
	 * @param $installedAddons
	 */
	private static function removeInstalledAddonsFromExistList( $installedAddons ) {
		self::$existAddons = array_filter(
			self::$existAddons,
			function ( $addon ) use ( $installedAddons ) {
				return array_search( $addon['name'], $installedAddons ) === false;
			}
		);
	}
}
