<?php
/**
 * Plugin Name: Expanding Archives
 * Plugin URI: https://shop.nosegraze.com/product/expanding-archives/
 * Description: A widget showing old posts that you can expand by year and month.
 * Version: 2.0.2
 * Author: Ashley Gibson
 * Author URI: https://www.nosegraze.com
 * License: GPL2
 * Text Domain: expanding-archives
 * Domain Path: lang
 *
 * Requires at least: 3.0
 * Requires PHP: 7.4
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

use Ashleyfae\ExpandingArchives\Plugin;

if (version_compare(phpversion(), '7.4', '<')) {
    return;
}

const EXPANDING_ARCHIVES_FILE    = __FILE__;
const EXPANDING_ARCHIVES_VERSION = '2.0.2';

require_once dirname(__FILE__).'/vendor/autoload.php';

/**
 * Loads the whole plugin.
 *
 * @since 1.0.0
 * @return Plugin
 */
function NG_Expanding_Archives() {
    return Plugin::instance();
}

NG_Expanding_Archives()->boot();
