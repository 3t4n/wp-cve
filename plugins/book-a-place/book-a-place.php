<?php
/**
 * @package   Book a Place
 * @author    ArtkanMedia
 * @license   GPL-2.0+
 * @copyright 2018 ArtkanMedia
 *
 * @wordpress-plugin
 * Plugin Name: Book a Place
 * Description: Booking places, seats, tickets… In theatres, cinemas, restaurants etc. It’s really convenient, when people are able to book a place online. With our plugin it’s possible.
 * Version:     0.7.1
 * Author:      ArtkanMedia
 * Text Domain: book-a-place
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt *
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

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

date_default_timezone_set('UTC');

define('BAP_DIR_PATH', plugin_dir_path(__FILE__));
define('BAP_DIR_URL', plugin_dir_url(__FILE__));
define('BAP_OPTIONS', 'bap_plugin_options');
define('BAP_EMAIL_NEW_ORDER_ADMIN', 'bap_email_new_order_admin');
define('BAP_EMAIL_NEW_ORDER_USER', 'bap_email_new_order_user');

require_once(plugin_dir_path(__FILE__) . 'class-book-a-place.php');

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array('Book_A_Place', 'activate'));
register_deactivation_hook(__FILE__, array('Book_A_Place', 'deactivate'));

Book_A_Place::get_instance();

add_action( 'plugins_loaded', 'bap_include_plugin_files' );

function bap_include_plugin_files()
{
    require_once(plugin_dir_path(__FILE__) . 'lib/Bap_PreFormValidation.php');
}