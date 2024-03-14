<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Settings;

use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;
use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Helper\PluginInstaller;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;

class ModulesInfoContainer implements PersistentContainer {
	const INTERNAL_MODULE_ENABLED = 1;

	/** @var PersistentContainer */
	private $wrapper_container;

	public function __construct( PersistentContainer $wrapper_container ) {
		$this->wrapper_container = $wrapper_container;
	}

	public function get_fallback( string $id, $fallback = null ) {
	}

	public function set( string $id, $value ) {
		if ( empty( $value ) ) {
			$this->delete( $id );

			return;
		}

		if ( $this->is_internal_module( $id ) ) {
			$this->wrapper_container->set( $id, true );

			return;
		}

		if ( WordPressPluggableHelper::is_plugin_active( $id ) ) {
			return;
		} elseif ( key_exists( $id, get_plugins() ) ) {
			activate_plugin( $id );
		} else {
			$installer = new PluginInstaller( $id );
			$installer->install();
		}
	}

	public function delete( string $id ) {
		if ( $this->is_internal_module( $id ) ) {
			$this->wrapper_container->delete( $id );

			return;
		}

		deactivate_plugins( $id, false, false );
	}

	private function is_internal_module( string $id ): bool {
		if ( $id === 'multilingual-module' ) {
			return true;
		}

		return false;
	}

	public function get( $id ) {
		return $this->has( $id );
	}

	public function has( $id ): bool {
		if ( $this->is_internal_module( $id ) ) {
			return $this->is_module_active( $id );
		}

		return WordPressPluggableHelper::is_plugin_active( $id );
	}

	private function is_module_active( string $module_slug ): bool {
		return $this->wrapper_container->has( $module_slug ) &&
		       (int) $this->wrapper_container->get( $module_slug ) === self::INTERNAL_MODULE_ENABLED;
	}

	/**
	 * @return string[]
	 */
	public function get_active_modules(): array {
		$shopmagic_extensions = ( new ArrayCollection( array_keys( get_plugins() ) ) )
			->filter( static function ( string $plugin ) {
				return str_contains( $plugin, 'shopmagic-' ) && WordPressPluggableHelper::is_plugin_active( $plugin );
			} )
			->map( static function ( string $plugin ) {
				$plugin_base = explode( '/', $plugin );

				return $plugin_base[0];
			} );

		if ( $this->is_module_active( 'multilingual-module' ) ) {
			$shopmagic_extensions[] = 'multilingual-module';
		}

		return $shopmagic_extensions->to_array();
	}
}
