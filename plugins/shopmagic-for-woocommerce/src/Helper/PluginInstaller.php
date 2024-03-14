<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

final class PluginInstaller {

	/** @var string */
	private $plugin_slug;

	public function __construct(string $plugin_slug) {
		$this->plugin_slug = $plugin_slug;
	}

	/**
	 * @internal
	 */
	public function install(): void {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$api = plugins_api(
			'plugin_information',
			[
				'slug' => explode( '/', $this->plugin_slug )[0],
				'fields' => [ 'sections' => false ],
			]
		);

		$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
		$upgrader->install( $api->download_link );
		$result = activate_plugin( $this->plugin_slug );
		if ( is_wp_error( $result ) ) {
			throw new \RuntimeException( $result->get_error_message() );
		}
	}
}
