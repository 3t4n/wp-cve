<?php 

/*
Plugin Name: Simple SEO
Plugin URI: https://wordpress.org/plugins/cds-simple-seo/
Description: A great plugin to modify the META information of your website, Google Analytics 4, Google Webmaster Tools, Yandex, Facebook, Twitter, and more! Please <a href="https://checkout.square.site/merchant/CGD6KJ0N7YECM/checkout/BN3726JNC6C6P6HL3JKNX3LC" target="_blank">Donate</a> if you find this plugin useful.
Version: 2.0.26
Author: David Cole
Author URI: http://coleds.com
Text Domain: cds-simple-seo
License: GPL2
*/

/*
Copyright (C) 2022 Cole Design Studios, LLC, coleds.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

define('SSEO_TXTDOMAIN', 'cds-simple-seo');
define('SSEO_VERSION', '2.0.26');
define('SSEO_PATH', plugin_dir_path(__FILE__));

require_once(dirname( __FILE__ ).'/autoloader.php');

$SimpleSEO = new app\SimpleSEO();

?>