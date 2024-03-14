<?php
/**
 * Name:    Dev4Press\v43\Core\Plugins\License
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

namespace Dev4Press\v43\Core\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class License {
	public $plugin = '';
	public $data = array();

	public function __construct() {
		$this->data = get_site_option( 'dev4press-core-storage' );

		if ( ! isset( $this->data[ $this->plugin ] ) ) {
			$this->data[ $this->plugin ] = array();

			update_site_option( 'dev4press-core-storage', $this->data );
		}
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

}
