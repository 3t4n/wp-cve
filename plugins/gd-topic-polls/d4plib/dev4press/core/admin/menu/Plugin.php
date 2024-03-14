<?php
/**
 * Name:    Dev4Press\v43\Core\Admin\Menu\Plugin
 * Version: v4.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4Press Library
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v43\Core\Admin\Menu;

use Dev4Press\v43\Core\Admin\Plugin as BasePlugin;
use Dev4Press\v43\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Plugin extends BasePlugin {
	public $variant = 'menu';

	public function main_url( $network = null ) : string {
		$path = 'admin.php?page=' . $this->plugin . '-dashboard';

		if ( is_null( $network ) ) {
			$network = $this->is_multisite && is_network_admin();
		}

		if ( $network ) {
			return network_admin_url( $path );
		} else {
			return admin_url( $path );
		}
	}

	public function current_url( $with_subpanel = true ) : string {
		$page = 'admin.php?page=' . $this->plugin . '-' . $this->panel;

		if ( $with_subpanel && isset( $this->subpanel ) && $this->subpanel !== false && $this->subpanel != '' ) {
			$page .= '&subpanel=' . $this->subpanel;
		}

		return self_admin_url( $page );
	}

	public function subpanel_url( $subpanel = '', $args = '', $network = null ) : string {
		return $this->panel_url( '', $subpanel, $args, $network );
	}

	public function panel_url( $panel = 'dashboard', $subpanel = '', $args = '', $network = null ) : string {
		$panel = empty( $panel ) ? $this->panel : $panel;
		$url   = 'admin.php?page=' . $this->plugin . '-' . $panel;

		if ( ! empty( $subpanel ) && $subpanel != 'index' ) {
			$url .= '&subpanel=' . $subpanel;
		}

		if ( ! empty( $args ) ) {
			$url .= '&' . trim( $args, '&' );
		}

		if ( is_null( $network ) ) {
			$network = $this->is_multisite && is_network_admin();
		}

		if ( $network ) {
			return network_admin_url( $url );
		} else {
			return admin_url( $url );
		}
	}

	public function admin_menu() {
		$parent = $this->plugin . '-dashboard';

		add_menu_page(
			$this->plugin_title,
			$this->plugin_menu,
			$this->menu_cap,
			$parent,
			array( $this, 'admin_panel' ),
			$this->svg_icon()
		);

		foreach ( $this->menu_items as $item => $data ) {
			$scope = $data['scope'] ?? array();
			$add   = true;

			if ( ! empty( $scope ) && $this->is_multisite ) {
				$current = is_network_admin() ? 'network' : 'blog';
				$add     = in_array( $current, $scope );
			}

			if ( $add ) {
				$this->page_ids[] = add_submenu_page(
					$parent,
					$this->plugin_title . ': ' . $data['title'],
					$data['title'],
					$data['cap'] ?? $this->menu_cap,
					$this->plugin . '-' . $item,
					array( $this, 'admin_panel' )
				);
			}
		}

		$this->admin_load_hooks();
	}

	public function current_screen( $screen ) {
		$this->screen_id = $screen->id;

		$parts = explode( '_page_', $this->screen_id, 2 );
		$panel = isset( $parts[1] ) && substr( $parts[1], 0, strlen( $this->plugin ) ) == $this->plugin ? substr( $parts[1], strlen( $this->plugin ) + 1 ) : '';

		if ( ! empty( $panel ) ) {
			if ( isset( $this->menu_items[ $panel ] ) ) {
				$this->page  = true;
				$this->panel = $panel;

				if ( ! empty( $_GET['subpanel'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$this->subpanel = Sanitize::slug( $_GET['subpanel'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
				}

				$this->screen_setup();
			}
		}

		$this->global_admin_notices();
	}

	public function settings_blog() {
		return null;
	}
}
