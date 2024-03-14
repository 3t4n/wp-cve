<?php
/**
 * @package Clean and Simple Contact Form
 */

/*
Plugin Name: Clean and Simple Contact Form
Plugin URI: https://fullworks.net/products/clean-and-simple-contact-form
Description: A clean and simple contact form with Google reCAPTCHA and Twitter Bootstrap markup.
Version: 4.8.0
Requires at least: 4.6
Requires PHP: 5.6
Author: Alan Fuller
Author URI: https://fullworks.net
License: GPLv2 or later
Text Domain: clean-and-simple-contact-form-by-meg-nicholas
Domain Path: /languages
*/

/*
All code up to version 4.7.1 is attributed to
Author: Meghan Nicholas
Author URI: http://www.megnicholas.com
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/*
 * @package Main
*/
require 'shortcodes/contact-form.php';
require 'class.cscf.php';
require 'class.cscf_pluginsettings.php';
require 'class.cscf_settings.php';
require 'class.cscf_contact.php';
require 'class.view.php';
require 'class.cscf_filters.php';
require 'ajax.php';
require 'recaptchav2.php';


if ( ! defined( 'CSCF_THEME_DIR' ) ) {
	define( 'CSCF_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template() );
}

if ( ! defined( 'CSCF_PLUGIN_NAME' ) ) {
	define( 'CSCF_PLUGIN_NAME', 'clean-and-simple-contact-form-by-meg-nicholas' );
}

if ( ! defined( 'CSCF_PLUGIN_DIR' ) ) {
	define( 'CSCF_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . CSCF_PLUGIN_NAME );
}

if ( ! defined( 'CSCF_PLUGIN_URL' ) ) {
	define( 'CSCF_PLUGIN_URL', WP_PLUGIN_URL . '/' . CSCF_PLUGIN_NAME );
}

if ( ! defined( 'CSCF_VERSION_KEY' ) ) {
	define( 'CSCF_VERSION_KEY', 'cscf_version' );
}

if ( ! defined( 'CSCF_VERSION_NUM' ) ) {
	define( 'CSCF_VERSION_NUM', '4.8.0' );
}

if ( ! defined( 'CSCF_OPTIONS_KEY' ) ) {
	define( 'CSCF_OPTIONS_KEY', 'cscf_options' );
}

require_once CSCF_PLUGIN_DIR . '/vendor/autoload.php';

$cscf = new cscf();

/*get the current version and update options to the new option*/
$old_version = get_option( CSCF_VERSION_KEY );
update_option( CSCF_VERSION_KEY, CSCF_VERSION_NUM );

/*If this is a new installation then set some defaults*/
if ( false == $old_version ) {
	$options                          = get_option( CSCF_OPTIONS_KEY );
	$options['use_client_validation'] = true;
	$options['load_stylesheet']       = true;
	$options['confirm-email']         = true;
	update_option( CSCF_OPTIONS_KEY, $options );
}

/*if necessary do an upgrade*/
if ( $old_version < CSCF_VERSION_NUM ) {
	$cscf->Upgrade( $old_version );
}
