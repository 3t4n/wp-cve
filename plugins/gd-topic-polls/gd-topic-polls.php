<?php
/**
 * Plugin Name:       GD Topic Polls: plugin for WordPress and bbPress forums
 * Plugin URI:        https://plugins.dev4press.com/gd-topic-polls/
 * Description:       Implement polls system for bbPress powered forums, for users to add polls to topics, with settings to control voting, poll closing, display of results...
 * Author:            Milan Petrovic
 * Author URI:        https://www.dev4press.com/
 * Text Domain:       gd-topic-polls
 * Version:           2.3
 * Requires at least: 5.7
 * Tested up to:      6.4
 * Requires PHP:      7.3
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package GDTopicPollsLite
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

use Dev4Press\v43\WordPress;

$gdpol_dirname_basic = dirname( __FILE__ ) . '/';
$gdpol_urlname_basic = plugins_url( '/', __FILE__ );

define( 'GDPOL_PATH', $gdpol_dirname_basic );
define( 'GDPOL_URL', $gdpol_urlname_basic );
define( 'GDPOL_D4PLIB', $gdpol_dirname_basic . 'd4plib/' );

require_once( GDPOL_D4PLIB . 'core.php' );

require_once( GDPOL_PATH . 'core/autoload.php' );
require_once( GDPOL_PATH . 'core/bridge.php' );
require_once( GDPOL_PATH . 'core/functions.php' );

gdpol_db();
gdpol_settings();

gdpol();

if ( WordPress::instance()->is_admin() ) {
	gdpol_admin();
}
