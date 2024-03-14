<?php
/**
 * Name:    Dev4Press\v43\Core\Admin\Help
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

namespace Dev4Press\v43\Core\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Help {
	protected $admin;

	public function __construct( $admin ) {
		$this->admin = $admin;
	}

	/** @return \Dev4Press\v43\Core\Admin\Plugin|\Dev4Press\v43\Core\Admin\Menu\Plugin|\Dev4Press\v43\Core\Admin\Submenu\Plugin */
	protected function a() {
		return $this->admin;
	}

	protected function panel() : string {
		return $this->a()->panel;
	}

	protected function subpanel() : string {
		return $this->a()->subpanel;
	}

	protected function tab( $code, $title, $content ) {
		$this->a()->screen()->add_help_tab(
			array(
				'id'      => $this->a()->plugin . '-' . $this->a()->panel . '-' . $code,
				'title'   => $title,
				'content' => '<h2>' . $title . '</h2>' . $content,
			)
		);
	}
}
