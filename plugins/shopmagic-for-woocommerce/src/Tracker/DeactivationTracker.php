<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Tracker;

use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Helper\PluginBag;

/**
 * Tracks data about deactivations.
 */
class DeactivationTracker implements HookProvider {

	/** @var string */
	private $plugin_file_name;

	public function __construct( PluginBag $plugin_bag ) {
		$this->plugin_file_name = $plugin_bag->get_filename();
	}

	public function hooks(): void {
		$tracker_factory = new \WPDesk_Tracker_Factory();
		$tracker_factory->create_tracker( $this->plugin_file_name );

		add_filter( 'wpdesk_track_plugin_deactivation', [ $this, 'wpdesk_track_plugin_deactivation' ] );
	}

	/**
	 * @param array $plugins
	 *
	 * @return array
	 */
	public function wpdesk_track_plugin_deactivation( array $plugins ): array {
		$plugins['shopmagic-for-woocommerce/shopMagic.php'] = 'shopmagic-for-woocommerce/shopMagic.php';

		return $plugins;
	}
}

