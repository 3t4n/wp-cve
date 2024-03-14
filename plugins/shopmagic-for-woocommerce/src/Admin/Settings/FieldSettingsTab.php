<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Settings;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use ShopMagicVendor\WPDesk\Forms\Form\FormWithFields;
use ShopMagicVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;

/**
 * Tab than can be rendered on settings page.
 * This abstraction should be used by tabs that want to use Form Fields to render its content.
 */
abstract class FieldSettingsTab implements SettingTab, LoggerAwareInterface {
	use LoggerAwareTrait;

	/** @var FormWithFields */
	private $form;

	/**
	 * Simple access to settings. Just like WordPress style get_option.
	 *
	 * @return mixed
	 */
	public static function get_option( string $key, $default = false ) {
		$persistence = static::get_settings_persistence();
		if ( $persistence->has( $key ) ) {
			return $persistence->get( $key );
		}

		return $default;
	}

	public static function get_settings_persistence(): PersistentContainer {
		// TODO: deferred option container with serialization.
		return new WordpressOptionsContainer( 'shopmagic-settings-' . static::get_tab_slug() );
	}

	public function set_data( ContainerInterface $data ): void {
		$this->get_form()->set_data( $data );
	}

	public function get_form(): FormWithFields {
		if ( $this->form === null ) {
			$fields     = $this->get_fields();
			$this->form = new FormWithFields( $fields, $this->get_tab_slug() );
		}

		return $this->form;
	}

	public function handle_request( array $request ): void {
		$this->get_form()->handle_request( $request );
	}

	/**
	 * @return int[]|string[]
	 */
	public function get_data(): array {
		return $this->get_form()->get_data();
	}
}
