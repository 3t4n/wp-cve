<?php
/**
 * Plugin Name:       Mega Elements - Addons for Elementor
 * Plugin URI:        https://kraftplugins.com/mega-elements/
 * Description:       The most advanced frontend drag & drop page builder addons for Elementor. Create high-end, beautiful, and pixel perfect websites in less time.
 * Version:           1.2.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            kraftplugins
 * Author URI:        https://kraftplugins.com/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       mega-elements-addons-for-elementor
 * Domain Path:       /languages
 * Elementor tested up to: 3.14.0
 * Elementor Pro tested up to: 3.9.0
 *
 * Mega Elements - Addons for Elementor is distributed under the terms of the GNU
 * General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * Mega Elements - Addons for Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Mega Elements - Addons for Elementor. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants
define('MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION', '1.2.0');
define('MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL', plugins_url( '/', __FILE__ ) );
define('MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH', plugin_dir_path( __FILE__ ) );
define('MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_FILE', __FILE__);
define('MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_BASENAME', plugin_basename( MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_FILE ) );

define( 'MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION', '2.5.0' );
define( 'MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_MINIMUM_PHP_VERSION', '5.6' );

add_image_size( 'meafe-featured-page', 500, 410, true );
add_image_size( 'meafe-testimonial-two', 272, 316, true );
add_image_size( 'meafe-blog-one', 370, 247, true );
add_image_size( 'meafe-blog-two', 197, 278, true );
add_image_size( 'meafe-category-tab', 355, 355, true );
add_image_size( 'meafe-category-grid', 370, 370, true );
add_image_size( 'meafe-category-grid-one', 570, 530, true );
add_image_size( 'meafe-category-grid-two', 270, 180, true );
add_image_size( 'meafe-category-grid-three', 570, 320, true );
add_image_size( 'meafe-category-grid-lay-three', 170, 170, true );
add_image_size( 'meafe-post-modules-small-img', 300, 300, true );

require_once dirname( __FILE__ ) . '/plugin.php';
