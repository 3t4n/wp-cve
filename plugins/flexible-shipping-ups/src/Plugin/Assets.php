<?php
/**
 * Class Assets
 */

namespace WPDesk\FlexibleShippingUps;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService;

/**
 * Register assets.
 */
class Assets implements Hookable {

	/**
	 * @var string
	 */
	private $assets_url;
	/**
	 * @var string
	 */
	private $version;

	/**
	 * @param string $assets_url .
	 * @param string $version    .
	 */
	public function __construct( string $assets_url, string $version ) {
		$this->assets_url = $assets_url;
		$this->version    = $version;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', [ $this, 'add_scripts' ] );
	}

	/**
	 * @return void
	 */
	public function add_scripts(): void {
		wp_register_style( UpsShippingService::UNIQUE_ID, $this->assets_url . 'app.css', [], $this->version );
		wp_register_script( UpsShippingService::UNIQUE_ID, $this->assets_url . 'app.js', [], $this->version, true );
	}
}
