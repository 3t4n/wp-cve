<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Tracker;

use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Helper\PluginBag;

/**
 * Tracks data about usages.
 */
class UsageDataTracker implements HookProvider {

	/** @var string */
	private $plugin_file_name;

	/** @var array */
	private $providers = [];

	public function __construct( PluginBag $plugin_bag ) {
		$this->plugin_file_name = $plugin_bag->get_filename();
	}

	/**
	 * @note Used by DI container
	 *
	 * @param array $providers
	 *
	 * @return void
	 */
	public function set_providers( array $providers ): void {
		$this->providers = $providers;
	}

	public function hooks(): void {
		$tracker_factory = new \WPDesk_Tracker_Factory();
		/** @var \WPDesk_Tracker_Interface $tracker */
		$tracker = $tracker_factory->create_tracker( $this->plugin_file_name );

		foreach ( $this->providers as $provider ) {
			$tracker->add_data_provider( $provider );
		}

		add_filter(
			'wpdesk_tracker_enabled',
			static function () {
				return true;
			}
		);
	}
}
