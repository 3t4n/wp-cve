<?php
/**
 * Name:    Dev4Press\v43\Core\Admin\Submenu\Plugin
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

namespace Dev4Press\v43\Core\Admin\Submenu;

use Dev4Press\v43\Core\Admin\Plugin as BasePlugin;
use Dev4Press\v43\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Plugin extends BasePlugin {
	public $variant = 'submenu';

	protected $menu = 'options-general.php';

	public function main_url() : string {
		return self_admin_url( $this->menu . '?page=' . $this->plugin );
	}

	public function current_url( $with_subpanel = true ) : string {
		$url = $this->main_url();

		if ( $this->panel !== false && $this->panel != '' ) {
			$url .= '&panel=' . $this->panel;
		}

		if ( $with_subpanel && isset( $this->subpanel ) && $this->subpanel !== false && $this->subpanel != '' ) {
			$url .= '&subpanel=' . $this->subpanel;
		}

		return $url;
	}

	public function subpanel_url( $subpanel = '', $args = '', $network = null ) : string {
		return $this->panel_url( '', $subpanel, $args, $network );
	}

	public function panel_url( $panel = 'dashboard', $subpanel = '', $args = '', $network = null ) : string {
		$panel = empty( $panel ) ? $this->panel : $panel;
		$url   = $this->main_url();

		$url .= '&panel=' . $panel;

		if ( ! empty( $subpanel ) && $subpanel != 'index' ) {
			$url .= '&subpanel=' . $subpanel;
		}

		if ( ! empty( $args ) ) {
			$url .= '&' . trim( $args, '&' );
		}

		return $url;
	}

	public function admin_menu() {
		$this->page_ids[] = add_submenu_page(
			$this->menu,
			$this->plugin_title,
			$this->plugin_menu,
			$this->menu_cap,
			$this->plugin,
			array( $this, 'admin_panel' )
		);

		$this->admin_load_hooks();
	}

	public function current_screen( $screen ) {
		if ( ! empty( $this->page_ids[0] ) && $screen->id == $this->page_ids[0] ) {
			$this->page = true;
		}

		if ( $this->page ) {
			if ( ! empty( $_GET['panel'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->panel = Sanitize::slug( $_GET['panel'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
			} else {
				$this->panel = 'dashboard';
			}

			if ( ! empty( $_GET['subpanel'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->subpanel = Sanitize::slug( $_GET['subpanel'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
			}

			$this->screen_setup();
		}
	}

	public function settings_blog() {
		return null;
	}
}
