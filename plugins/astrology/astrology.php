<?php
/**
 * Plugin Main File.
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2022 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 * @wordpress-plugin
 *
 * Plugin Name: Astrology
 * Plugin URI:  https://api.prokerala.com
 * Description: Integrate astrology calculators powered by Prokerala's Astrology API
 * Version:     1.3.1
 * Author:      Prokerala
 * Author URI:  https://www.prokerala.com
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.en.html
 * Text Domain: astrology
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

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Prokerala\WP\Astrology\Plugin;

const PK_ASTROLOGY_VERSION          = '1.3.1';
const PK_ASTROLOGY_PHP_MINIMUM      = '7.2.0';
const PK_ASTROLOGY_PLUGIN_MAIN_FILE = __FILE__;

if ( version_compare( phpversion(), PK_ASTROLOGY_PHP_MINIMUM, '<=' ) ) {
	/**
	 * Disallow activation on unsupported PHP versions.
	 *
	 * @see https://github.com/wppunk/WPPlugin
	 * @since 1.0.0
	 */
	function astrology_php_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
				// translators: %s: The current PHP version number.
				$message = __( 'The minimum supported PHP version is <strong>%s</strong>.', 'astrology' );
				echo wp_kses( sprintf( $message, PK_ASTROLOGY_PHP_MINIMUM ), [ 'strong' => [] ] );
				?>
			</p>
		</div>

		<?php
		// In case this is on plugin activation.
        if (isset($_GET['activate'])) { //phpcs:ignore
            unset($_GET['activate']); //phpcs:ignore
		}
	}

	add_action( 'admin_notices', 'astrology_php_notice' );

	// Don't process the plugin code further.
	return;
}

if ( ! defined( 'ASTROLOGY_DEBUG' ) ) {
	define( 'ASTROLOGY_DEBUG', false );
}
define( 'PK_ASTROLOGY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PK_ASTROLOGY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PK_ASTROLOGY_PLUGIN_BASENAME', plugin_basename( PK_ASTROLOGY_PLUGIN_MAIN_FILE ) );

/**
 * Run plugin function.
 *
 * @since 1.0.0
 *
 * @throws Exception If something went wrong.
 */
function run_astrology() {
	require_once __DIR__ . '/src/vendor/autoload.php';
	require_once __DIR__ . '/dependencies/vendor/autoload.php';

	// Third-party files.
	// Based on https://github.com/google/site-kit-wp .
	$files = require PK_ASTROLOGY_PLUGIN_PATH . 'dependencies/vendor/composer/autoload_files.php';
	foreach ( $files as $file_identifier => $file ) {
		require_once $file;
	}

	Plugin::load( PK_ASTROLOGY_PLUGIN_MAIN_FILE );
}
run_astrology();
