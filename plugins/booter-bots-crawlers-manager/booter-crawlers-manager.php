<?php
/*
  Plugin Name: Booter - Bots & Crawlers Manager
  Plugin URI: https://booter.app
  Description: The easy way to correctly manage crawlers and bots.
  Author: uPress
  Author URI: https://www.upress.io
  Text Domain: booter
  Domain Path: /languages
  Version: 1.5.6
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html

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

if ( ! defined( 'ABSPATH' ) ) {
	die( 'NO direct access!' );
}

require_once __DIR__ . '/booter-contstants.php';
require_once __DIR__ . '/includes/Plugin.php';

\Upress\Booter\Plugin::initialize();
