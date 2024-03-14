<?php
/*
Plugin Name: Tim's Nextcloud SSO OAuth2
Plugin URI: https://www.timoxendale.co.uk/plugins/wordpress-nextcloud-sso-oauth2/
Description: Enables you to login to your WordPress site with your Nextcloud account with OAuth2
Version: 2.0.2
Author: Tim's Solutions
Author URI: https://www.timoxendale.co.uk/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: tims-nextcloud-sso-oauth2
Domain Path: /languages

Tim's Nextcloud SSO OAuth2 is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Tim's Nextcloud SSO OAuth2 is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Nextcloud SSO OAuth2. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/
    
defined( 'ABSPATH' ) || exit;

if(!defined('TIMS_NSO_OAUTH2_PLUGIN_FILE')){
	define('TIMS_NSO_OAUTH2_PLUGIN_FILE', __FILE__ );
}


if(!defined('TIMS_NSO_OAUTH2_PLUGIN_ASSET_FOLDER')){
	define('TIMS_NSO_OAUTH2_PLUGIN_ASSET_FOLDER', plugins_url('assets',__FILE__) );
}

include_once dirname(TIMS_NSO_OAUTH2_PLUGIN_FILE).'/includes/options-page.php';
include_once dirname(TIMS_NSO_OAUTH2_PLUGIN_FILE).'/includes/button-shortcode.php';
include_once dirname(TIMS_NSO_OAUTH2_PLUGIN_FILE).'/includes/functions.php';
