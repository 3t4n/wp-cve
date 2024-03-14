<?php
/**
 * Assets.
 */

namespace Octolize\Shipping\Notices\WooCommerceSettings;

use Octolize\Shipping\Notices\Helpers\WooCommerceSettingsPageChecker;
use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class SettingsAssets implements Hookable {
	private const HANDLE = 'shipping-notices';

	/**
	 * @var string
	 */
	private $assets_url;

	/**
	 * @var string
	 */
	private $scripts_version;

	/**
	 * @var WooCommerceSettingsPageChecker
	 */
	private $settings_page_checker;

	/**
	 * @param string $assets_url .
	 */
	public function __construct( string $assets_url, string $scripts_version, WooCommerceSettingsPageChecker $settings_page_checker ) {
		$this->assets_url            = $assets_url;
		$this->scripts_version       = $scripts_version;
		$this->settings_page_checker = $settings_page_checker;
	}

	public function hooks(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ] );
	}

	/**
	 * @return void
	 */
	public function register_admin_scripts(): void {
		if ( ! $this->should_register_scripts() ) {
			return;
		}

		wp_enqueue_script(
			self::HANDLE,
			$this->assets_url . 'dist/app.js',
			[
				'jquery',
				'jquery-ui-sortable',
			],
			$this->scripts_version,
			true
		);

		wp_enqueue_editor();
	}

	/**
	 * @return bool
	 * @codeCoverageIgnore
	 */
	protected function should_register_scripts(): bool {
		return $this->settings_page_checker->is_settings_page_section( WooCommerceSettingsPage::SECTION_ID );
	}
}
