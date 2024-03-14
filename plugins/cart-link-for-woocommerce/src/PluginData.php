<?php

namespace IC\Plugin\CartLinkWooCommerce;

class PluginData {

	/**
	 * @var string .
	 */
	private $file;

	/**
	 * @var string .
	 */
	private $version;

	/**
	 * @var string .
	 */
	private $plugin_name;

	/**
	 * @var string
	 */
	private $text_domain;

	/**
	 * @var int
	 */
	private $script_version;

	/**
	 * @param string $file        .
	 * @param string $plugin_name .
	 * @param string $version     .
	 * @param string $text_domain .
	 */
	public function __construct( string $file, string $plugin_name, string $version, string $text_domain, int $script_version = 1 ) {
		$this->file           = $file;
		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->text_domain    = $text_domain;
		$this->script_version = $script_version;
	}

	/**
	 * @param string $path .
	 *
	 * @return string
	 */
	public function get_plugin_absolute_path( string $path = '' ): string {
		return wp_normalize_path( plugin_dir_path( $this->file ) . '/' . $path );
	}

	/**
	 * @param string $file .
	 *
	 * @return string
	 */
	public function get_plugin_url( string $file = '' ): string {
		return plugins_url( $file, $this->file );
	}

	/**
	 * @return string
	 */
	public function get_plugin_file(): string {
		return plugin_basename( $this->file );
	}

	/**
	 * @return string
	 */
	public function get_plugin_slug(): string {
		return wp_basename( $this->get_plugin_absolute_path() );
	}

	/**
	 * @return string
	 */
	public function get_plugin_name(): string {
		return __( $this->plugin_name, $this->text_domain ); //phpcs:ignore
	}

	/**
	 * @return string
	 */
	public function get_text_domain(): string {
		return $this->text_domain;
	}

	/**
	 * @return string
	 */
	public function get_version(): string {
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function get_script_version(): string {
		return $this->version . '-' . $this->script_version;
	}
}
