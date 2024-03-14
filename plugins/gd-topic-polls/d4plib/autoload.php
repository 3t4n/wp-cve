<?php
/**
 * Name:    Dev4Press Core Autoloader
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

if ( ! function_exists( 'dev4press_core_library_autoloader_43' ) ) {
	function dev4press_core_library_autoloader_43( $class ) {
		$path = dirname( __FILE__ ) . '/';
		$base = 'Dev4Press\\v43\\';

		if ( substr( $class, 0, strlen( $base ) ) == $base ) {
			$clean = substr( $class, strlen( $base ) );

			$parts = explode( '\\', $clean );

			$class_name = $parts[ count( $parts ) - 1 ];
			unset( $parts[ count( $parts ) - 1 ] );

			$class_namespace = join( '/', $parts );
			$class_namespace = strtolower( $class_namespace );

			$path .= 'dev4press/' . $class_namespace . '/' . $class_name . '.php';

			if ( file_exists( $path ) ) {
				include $path;
			}
		}
	}

	spl_autoload_register( 'dev4press_core_library_autoloader_43' );
}
