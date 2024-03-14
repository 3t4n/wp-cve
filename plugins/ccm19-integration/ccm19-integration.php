<?php
/**
	Plugin Name: CCM19 Integration
	Plugin URI: https://www.ccm19.de
	Description: Integrates the CCM19 Cookie Consent Solution into WordPress
	Version: 1.1.5
	Author: Papoo Software &amp; Media GmbH
	Author URI: https://papoo-media.de
	License: GPLv2 or later
	Text Domain: ccm19-integration
	
	Copyright (C) 2020 Papoo Software & Media GmbH

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License along
	with this program; if not, write to the Free Software Foundation, Inc.,
		51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

// Don't expose anything if called directly
if ( !function_exists( 'add_action' ) ) {
	exit;
}

require_once( __DIR__ . '/class-ccm19-integration.php' );

$ccm19integration = CCM19Integration::getInstance();

#register_activation_hook(__FILE__, ['CCM19Integration', 'plugin_activation']);
#register_deactivation_hook(__FILE__, ['CCM19Integration', 'plugin_deactivation']);


add_action( 'init', ['CCM19Integration', 'staticInit']);
