<?php

/**
 * Plugin Name:           MemberSpace
 * Plugin URI:            http://www.memberspace.com/
 * Description:           The official MemberSpace plugin allows you to protect your WordPress content using MemberSpace memberships.
 * Version:               2.1.10
 * Author:                MemberSpace
 * Author URI:            http://www.memberspace.com/
 * Text Domain:           memberspace
 * Domain Path:           /languages
 * License:               GPL v3
 * License URI:           http://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least:     5.8
 * Tested up to:          6.4
 * Requires PHP:          7.4
 * Update URI:            https://wordpress.org/plugins/memberspace/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) || exit;

define( 'MEMBERSPACE_PLUGIN_VERSION', '2.1.10' );
define( 'MEMBERSPACE_PLUGIN_BUILD_ID', '20231106T192825X680880321' );
define( 'MEMBERSPACE_PLUGIN_MIN_PHP_VERSION', '7.4' );

function activate_memberspace() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/memberspace.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/memberspace-activator.php';
	MemberSpace_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_memberspace' );

// Deactivate plugin and bail if min system requirements are not met
require_once plugin_dir_path( __FILE__ ) . 'includes/memberspace-verify-requirements.php';
$requirement_verifier = new MemberSpace_Verify_Requirements();
if ( !$requirement_verifier->verify() ) {
	return;
}

// Load and run plugin
require_once plugin_dir_path( __FILE__ ) . 'includes/memberspace.php';
$memberspace = new MemberSpace();
$memberspace->run();
