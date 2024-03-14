<?php
/**
 * Main Plugin Class.
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2022 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

/*
 * This file is part of Prokerala Astrology WordPress plugin
 *
 * Copyright (c) 2022 Ennexa Technologies Private Limited
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Prokerala\WP\Astrology;

use Prokerala\WP\Astrology\Admin\Admin;
use Prokerala\WP\Astrology\Front\Front;

/**
 * Class Plugin.
 *
 * @since   1.0.0
 */
final class Plugin {

	/**
	 * Plugin slug.
	 *
	 * @since 1.0.0
	 */
	const SLUG = 'astrology';
	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * Plugin main file.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $main_file;

	/**
	 * Plugin configuration object.
	 *
	 * @since 1.0.0
	 *
	 * @var Configuration
	 */
	private $config;

	/**
	 * Plugin constructor.
	 *
	 * @param string $main_file plugin main file.
	 */
	public function __construct( $main_file ) {
		$this->main_file = $main_file;
		$this->config    = new Configuration();
		register_activation_hook( $this->main_file, [ $this, 'activate' ] );
		add_action( 'plugins_loaded', [ $this, 'register' ] );
	}

	/**
	 * Initialize plugin.
	 *
	 * @since 1.0.
	 *
	 * @return void
	 */
	public function do_init() {
		$config     = new Configuration();
		$controller = is_admin() ? new Admin( $config ) : new Front( $config );
		$controller->register();
		$block = new Block( $config );
		$block->register();

		do_action( 'pk_astrology_init' );
	}

	/**
	 * Plugin activation handler.
	 */
	public function activate() {
		if ( $this->config->get_client_status() !== null ) {
			return;
		}

		$controller   = new Admin( new Configuration() );
		$settings_url = $controller->get_settings_url();

		$message = sprintf(
			'Thank you for installing Prokerala Astrology Plugin. Click here to <a href="%s">configure</a>.',
			$settings_url
		);
		$this->config->add_notice( 'client_status', $message, 'success' );
	}

	/**
	 * Plugin uninstallation handler.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function uninstall() {
		$this->config->clear();
	}

	/**
	 * Load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $main_file main plugin file.
	 */
	public static function load( $main_file ) {
		$instance = new self( $main_file );
		$instance->register();
	}

	/**
	 * Register plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', [ $this, 'do_init' ] );
		add_action( 'upgrader_process_complete', [ $this, 'opcache_reset' ] );
		add_action( 'pk_astrology_uninstall', [ $this, 'uninstall' ] );
	}

	/**
	 * Resets opcache if possible.
	 *
	 * @since 1.0.0
	 *
	 * @copyright Google LLC
	 * @license Apache 2.0
	 * @url https://github.com/google/site-kit-wp/
	 */
	public function opcache_reset() {     // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		if ( version_compare( PHP_VERSION, PK_ASTROLOGY_PHP_MINIMUM, '<' ) ) {
			return;
		}

		if ( ! function_exists( 'opcache_reset' ) ) {
			return;
		}

		if ( ! empty( ini_get( 'opcache.restrict_api' ) ) && 0 !== strpos( __FILE__, ini_get( 'opcache.restrict_api' ) ) ) {
			return;
		}

		// `opcache_reset` is prohibited on the WordPress VIP platform due to memory corruption.
		if ( defined( 'WPCOM_IS_VIP_ENV' ) && WPCOM_IS_VIP_ENV ) {
			return;
		}

		opcache_reset(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.opcache_opcache_reset
	}
}
