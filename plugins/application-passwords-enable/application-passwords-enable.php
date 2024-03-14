<?php 
/*
	Plugin Name: Application Passwords Enable
	Plugin URI: https://erp.banmaerp.com/plugins/application-passwords-enable.html
	Description: Activate this plugin to enabled WP Application Passwords.
	Tags: application-passwords,rest api,application,passwords,authentication
	Author: banmaerp
	Author URI: https://www.banmaerp.com/
	Contributors: sunliang
	Requires at least: 5.7.2
	Tested up to: 7.0.0
	Stable tag: 1.1
	Version: 1.1
	License: GPL v2 or later
*/
/*  Copyright (C) 2021 sunliang (email : sunliang@banmaerp.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, visit: https://www.gnu.org/licenses/
*/

define( 'APPLICATION_PASSWORDS_ENABLE_VERSION', '1.1' );
if (!defined('ABSPATH')) exit; // Exit if accessed directly
add_filter('wp_is_application_passwords_available', '__return_true');