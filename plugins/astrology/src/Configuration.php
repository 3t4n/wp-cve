<?php
/**
 * Plugin Configuration Class
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

/**
 * Configuration class.
 *
 * @since 1.0.0
 */
class Configuration {
	/**
	 * Plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string,string>
	 */
	private $options;
	/**
	 * Client validation status.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $client_status;
	/**
	 * List of admin notices.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string,string>
	 */
	private $notices;

	/**
	 * Get plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,string>
	 */
	public function get_options() {
		if ( ! isset( $this->options ) ) {
			$this->options = $this->load_options();
		}

		return $this->options;
	}

	/**
	 * Get option by name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option name.
	 *
	 * @return string
	 */
	public function get_option( $key ) {
		return $this->get_options()[ $key ];
	}

	/**
	 * Get HTTP origin for current site.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_origin() {
		$parsed = wp_parse_url( network_site_url() );
		$proto  = $parsed['scheme'];
		$port   = null;
		if ( isset( $parsed['port'] ) ) {
			$port = $parsed['port'];
		}

		$url = "{$proto}://{$parsed['host']}";

		if ( $port && ( 'http' === $proto && 80 !== $parsed['port'] || 'https' === $proto && 443 !== $port ) ) {
			$url .= ":{$port}";
		}

		return $url;
	}

	/**
	 * Set validation status for the client.
	 *
	 * @since 1.0.0
	 *
	 * @param string $status Client status.
	 *
	 * @return void
	 */
	public function set_client_status( $status ) {
		add_site_option( 'astrology_client_status', $status );
	}


	/**
	 * Get validation status for the client.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_client_status() {
		if ( is_null( $this->client_status ) ) {
			$this->client_status = get_site_option( 'astrology_client_status', null );
		}

		return $this->client_status;
	}

	/**
	 * Retrieve all admin notices.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,string>
	 */
	public function get_notices() {
		if ( ! $this->notices ) {
			$this->notices = get_site_option( 'astrology_admin_notices', [] );
		}

		return $this->notices;
	}

	/**
	 * Add new admin notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $msg_id   Id for a new message.
	 * @param string $message  Message content.
	 * @param int    $priority Message priority.
	 * @param string $type Message type.
	 *
	 * @return void
	 */
	public function add_notice( $msg_id, $message, $priority = 0, $type = 'info' ) {
		$notices            = $this->get_notices();
		$notices[ $msg_id ] = [
			'message'  => $message,
			'priority' => $priority,
			'type'     => $type,
		];
		uasort(
			$notices,
			function ( $a, $b ) {
				return $a['priority'] <= $b['priority'];
			}
		);
		$this->save_notices( $notices );
	}

	/**
	 * Remove notice from admin interface.
	 *
	 * @since 1.0.0
	 *
	 * @param string $msg_id Message id to remove.
	 *
	 * @return void
	 */
	public function remove_notice( $msg_id ) {
		$notices = $this->get_notices();
		unset( $notices[ $msg_id ] );
		$this->save_notices( $notices );
	}

	/**
	 * Clear all save options.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function clear() {
		delete_site_option( 'astrology_admin_notices' );
		delete_site_option( 'astrology_plugin_options' );
		delete_site_option( 'astrology_client_status' );
	}

	/**
	 * Load options from database.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string,string>
	 */
	private function load_options() {
		return get_site_option(
			'astrology_plugin_options',
			[
				'attribution'   => '1',
				'ayanamsa'      => '1',
				'client_id'     => '',
				'client_secret' => '',
				'theme'         => '',
				'timezone'      => date_default_timezone_get(),
			]
		);
	}

	/**
	 * Persist notices to database.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,string> $notices Notices list.
	 *
	 * @return void
	 */
	private function save_notices( $notices ) {
		$this->notices = $notices;

		update_site_option( 'astrology_admin_notices', $this->notices );
	}
}
