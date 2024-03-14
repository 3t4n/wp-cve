<?php
/*
Plugin Name: Booster Pack for Divi
Description: Adds more advanced widgets to Divi Page Builder
Version:     1.1.0
Author:      WebTechStreet
Author URI:  https://webtechstreet.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: bpd-booster-pack-divi
Domain Path: /languages

Booster Pack for Divi is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Booster Pack for Divi is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Booster Pack for Divi. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


if ( ! function_exists( 'bpd_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function bpd_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/BoosterPackDivi.php';
}
add_action( 'divi_extensions_init', 'bpd_initialize_extension' );
endif;
