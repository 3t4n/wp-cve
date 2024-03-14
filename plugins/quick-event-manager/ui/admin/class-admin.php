<?php
/**
 * @copyright (c) 2020.
 * @author            Alan Fuller (support@fullworks)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworks.net
 *
 * This file is part of  a Fullworks plugin.
 *
 *   This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with  this plugin.  https://www.gnu.org/licenses/gpl-3.0.en.html
 */

namespace Quick_Event_Manager\Plugin\UI\Admin;

class Admin {

	private $plugin_name;
	private $version;
	/**
	 * @param \Freemius $freemius Object for freemius.
	 */
	private $freemius;

	public function __construct( $plugin_name, $version, $freemius ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->freemius = $freemius;
	}

	public function hooks() {
		// @TODO check if style / js actually needed.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, QUICK_EVENT_MANAGER_PLUGIN_URL . 'ui/admin/css/admin.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, QUICK_EVENT_MANAGER_PLUGIN_URL . 'ui/admin/js/admin.js', array( 'jquery' ), $this->version, false );
	}

	public function admin_notices() {
		// Don't display notices to users that can't do anything about it.
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		// Notices are only displayed on the dashboard, plugins, tools, and settings admin pages.

		$cs             = get_current_screen();
		$display_on_pages = array(
			/*
			'dashboard',
			'plugins',
			'tools',
			'options-general',
			*/
		);
		$display          = false;
		if ( preg_match( '#qem#i', $cs->base ) ) {
			$display = true;
		}
		if ( preg_match( '#qem#i', $cs->id ) ) {
			$display = true;
		}

		if ( in_array( $cs->base, $display_on_pages, true ) ) {
			$display = true;
		}
		if ( ! $display ) {
			return;
		}

		if (version_compare(phpversion(), '7.1.0', '<')) {
			$notice = esc_html__('Quick Event Manager:  your PHP version: ', 'quick-event-manager').
			          phpversion() .
			          esc_html__(' is no longer supported, to enjoy future versions of this plugin upgrade your PHP ', 'quick-event-manager')
			;
		}

		// Output notice HTML.
		if ( ! empty( $notice ) ) {
			printf( '<div id="message" class="notice notice-warning" style="overflow:hidden;font-size: 150%%;"><p>%1$s</p></div>', qem_wp_kses_post($notice) );
		}

	}
}
