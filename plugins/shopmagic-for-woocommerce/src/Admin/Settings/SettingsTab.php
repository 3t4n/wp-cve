<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Admin\Settings;

use ShopMagicVendor\Psr\Container\ContainerInterface;

/**
 * Tab than can be rendered on settings page.
 *
 * Tab have to know how:
 * - to save its data ::save_tab_data
 * And should know how it's called ::get_tab_name
 */
interface SettingsTab {
	/**
	 * Slug name used for unique url and settings in db.
	 */
	public static function get_tab_slug(): string;

	/**
	 * Tab name to show on settings page.
	 */
	public function get_tab_name(): string;

	/**
	 * Use to set settings from database or defaults.
	 *
	 * @param ContainerInterface $data Data to render.
	 *
	 * @return void
	 */
	public function set_data( ContainerInterface $data ): void;

	/**
	 * Use to handle request data from POST.
	 * Data in POST request should be prefixed with slug.
	 * For example if slug is 'stefan' and the input has name 'color' and value 'red' then the data should be sent as
	 * $_POST = [ 'stefan' => [ 'color' => 'red' ] ].
	 *
	 * @param array $request Data retrieved from POST request.
	 *
	 * @return void
	 */
	public function handle_request( array $request ): void;

	/**
	 * Returns valid data from Tab. Can be used after ::handle_request or ::set_data.
	 */
	public function get_data(): array;
}
