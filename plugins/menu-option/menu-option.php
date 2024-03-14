<?php
/*
Plugin Name: Menu Option
Plugin URI: http://amplebrain.com/plugins/menu-option/
Description: The easiest way to control which menu items your site’s visitors will see. i.e. Everyone, Logged In Users, Logged Out Users.
Version: 1.1
Author: Tushar Kapdi
Author URI: http://amplebrain.com/
Text Domain: menuoption
Domain Path: /languages/
Copyright 2019 Amplebrain Technologies (email : amplebrain@gmail.com)
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Menu Option is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Menu Option is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Menu Option. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'MENU_OPTION_PLUGIN_URI', plugins_url( '' , __FILE__ ) );
define( 'MENU_OPTION_VERSION', 1.0 );


require_once 'includes/base.php';
require_once 'includes/init.php';

/**
 * Plugin deactivate hook
 *
 * @since 1.0
 */
register_deactivation_hook(__FILE__, 'MENU_OPTION_Admin::deactivation');