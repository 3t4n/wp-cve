<?php
/**
 * Plugin Name: Admin Tweaks
 * Plugin URI: http://wordpress.org/plugins/many-tips-together
 * Description: Tweak, style, remove and modify several aspects of your WordPress administrative interface.
 * Version: 3.1
 * Author: Rodolfo Buaiz
 * Author URI: http://brasofilo.com/
 * Text Domain: mtt
 * Domain Path: /languages
 * License: GPLv2 or later
 */

/**
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( 'ABSPATH' ) || exit;

define( 'ADTW_FILE', __FILE__ );
define( 'ADTW_BASE', plugin_basename( __FILE__ ) );
define( 'ADTW_PATH', untrailingslashit( plugin_dir_path( ADTW_FILE ) ) );
define( 'ADTW_URL', untrailingslashit( plugins_url( '/', ADTW_FILE ) ) );

require_once ADTW_PATH . '/inc/AdminTweaks.php';

if (!class_exists('ReduxFramework') && file_exists(ADTW_PATH . '/inc/redux-core/framework.php')) {
	require_once (ADTW_PATH . '/inc/redux-core/framework.php');
}

if (class_exists('ADTW\AdminTweaks')) {
    function ADTW() {
        return ADTW\AdminTweaks::getInstance();
    }
    add_action( 'plugins_loaded', [ADTW(), 'init'], 99999);
    register_activation_hook(ADTW_FILE, [ADTW(), 'activate']);
    register_deactivation_hook(ADTW_FILE, [ADTW(), 'deactivate']);
}