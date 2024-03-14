<?php
/**
 * Plugin Name: Faire for WooCommerce
 * Description: Faire for WooCommerce
 * Author: Faire
 * Author URI: https://faire.com/
 * Version: 1.7.0
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Faire for WooCommerce
 */

namespace Faire\Wc;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

const VERSION = '1.7.0';

if ( ! defined( 'FAIRE_WC_PLUGIN_FILE' ) ) {
	define( 'FAIRE_WC_PLUGIN_FILE', __FILE__ );
}

/**
 * Registers plugin auto-loader.
 *
 * @see https://www.php-fig.org/psr/psr-4/
 *
 * @param string $class The class name.
 */
spl_autoload_register(
	function ( $class_name ) {
		// Only autoload classes from this namespace.
		if ( false === strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		// Remove namespace from class name.
		$class_file = str_replace( __NAMESPACE__ . '\\', '', $class_name );

		// Convert class name format to file name format.
		$class_file = strtolower( $class_file );
		$class_file = str_replace( '_', '-', $class_file );

		// Convert sub-namespaces into directories.
		$class_path = explode( '\\', $class_file );
		$class_file = array_pop( $class_path );
		$class_path = implode( '/', $class_path );

		$file = realpath( __DIR__ . '/src' . ( $class_path ? "/$class_path" : '' ) . '/class-' . $class_file . '.php' );

		// If the file exists, require it.
		if ( file_exists( $file ) ) {
			require_once $file;
		} else {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( sprintf( 'File not found: %s', $file ), true );
		}
	}
);

require_once 'src/class-faire.php';

/**
 * Main Plugin Instance.
 *
 * This is an alias of Plugin::instance() accessible directly from the namespace.
 *
 * @since 1.0.0
 * @return Plugin - Main instance.
 */
function instance() {
	return Faire::instance();
}

instance();
