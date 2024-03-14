<?php
/*
Plugin Name: GF Mollie by Indigo
Plugin URI: http://www.indigowebstudio.nl
Description: Integrates Gravity Forms with Mollie, enabling end users to purchase goods and services through Gravity Forms.
Version: 2.0.2
Author: Indigo webstudio
Author URI: http://www.indigowebstudio.nl
Text Domain: gf-mollie-by-indigo
Domain Path: /languages

------------------------------------------------------------------------
Copyright 2009-2016 

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/



define( 'GF_MOLLIE_BY_INDIGO_VERSION', '2.0.2' );

add_action( 'gform_loaded', array( 'GF_Mollie_Bootstrap', 'load' ), 5 );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.1
 */
function gf_mollie_load_textdomain() {
	load_plugin_textdomain( 'gf-mollie-by-indigo', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'gform_loaded', array( 'GF_Mollie_Bootstrap', 'load' ), 5 );

class GF_Mollie_Bootstrap {

	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_payment_addon_framework' ) ) {
			return;
		}
		$load_mollie = apply_filters( 'gf_mollie_load_api', true );
		if ( $load_mollie ) {

			require_once plugin_dir_path( __FILE__ ) . 'Mollie/vendor/autoload.php';
		}

		require_once( 'class-gf-mollie.php' );
		require_once( 'notices.php' );

		GFAddOn::register( 'GFMollie' );
	}
}

function gf_mollie() {
	return GFMollie::get_instance();
}
