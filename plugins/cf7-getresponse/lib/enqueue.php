<?php
/*  Copyright 2013-2017 Renzo Johnson (email: renzojohnson at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function wpcf7_vcgr_admin_enqueue_scripts() {

	global $plugin_page;

	if ( ! isset( $plugin_page ) || 'wpcf7' != $plugin_page )
		return;

	wp_enqueue_style( 'wpcf7-vcgr-admin', SPARTAN_VCGR_PLUGIN_URL . '/assets/css/style-spartan.css', array(), SPARTAN_VCGR_VERSION, 'all' );

	wp_enqueue_script( 'wpcf7-vcgr-admin', SPARTAN_VCGR_PLUGIN_URL . '/assets/js/scripts-spartan.js', array( 'jquery', 'wpcf7-admin' ), SPARTAN_VCGR_VERSION, true );


}
add_action( 'admin_print_scripts', 'wpcf7_vcgr_admin_enqueue_scripts' );



function vcgr_admin_scripts() {

  wp_register_style( 'wpcf7-vcgr-wp-admin-css', SPARTAN_VCGR_PLUGIN_URL . '/assets/css/vcgr-admin.css', array(), SPARTAN_VCGR_VERSION, 'all' );

  wp_enqueue_style( 'wpcf7-vcgr-wp-admin-css' );

}
add_action( 'admin_enqueue_scripts', 'vcgr_admin_scripts' );