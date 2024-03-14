<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

use ShopMagicVendor\WPDesk_Plugin_Info;

class PluginBag {

	/** @var bool */
	private $pro = false;

	/** @var bool */
	private $debug = false;

	/** @var WPDesk_Plugin_Info */
	private $plugin_info;

	public function set_plugin_info( WPDesk_Plugin_Info $plugin_info ): void {
		$this->plugin_info = $plugin_info;
	}

	public function pro_enabled(): bool {
		return $this->pro;
	}

	public function set_pro( bool $pro ): void {
		$this->pro = $pro;
	}

	public function get_version(): string {
		return $this->plugin_info->get_version();
	}

	public function get_filename(): string {
		return $this->plugin_info->get_plugin_file_name();
	}

	public function debug_enabled(): bool {
		return $this->debug;
	}

	public function set_debug( bool $debug ): void {
		$this->debug = $debug;
	}

	public function get_url(): string {
		return $this->plugin_info->get_plugin_url();
	}

	public function get_assets_url(): string {
		return $this->plugin_info->get_plugin_url() . '/assets/';
	}

	public function get_directory(): string {
		return $this->plugin_info->get_plugin_dir();
	}

	public function get_admin_assets_url(): string {
		return $this->plugin_info->get_plugin_url() . '/dist/admin/';
	}

	public function get_manifest_path(): string {
		return $this->plugin_info->get_plugin_dir() . '/dist/admin/.vite/manifest.json';
	}

	public function get_migrations_path(): string {
		return $this->plugin_info->get_plugin_dir() . '/migrations';
	}

}
