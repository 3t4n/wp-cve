<?php

/**
 * Plugin Name: Livemesh SiteOrigin Widgets
 * Plugin URI: https://livemeshwp.com/siteorigin-widgets
 * Description: A collection of premium widgets for use in any widgetized area or in SiteOrigin page builder. SiteOrigin Widgets Bundle is required.
 * Author: Livemesh
 * Author URI: https://livemeshwp.com/siteorigin-widgets
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 3.8.1
 * Text Domain: livemesh-so-widgets
 * Domain Path: languages
 *
 * Livemesh SiteOrigin Widgets is distributed under the terms of the GNU
 * General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * Livemesh SiteOrigin Widgets is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Livemesh SiteOrigin Widgets. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Ensure the free version is deactivated if premium is running

if ( !function_exists( 'lsow_fs' ) ) {
    define( 'LSOW_VERSION', '3.8.1' );
    // Plugin Folder Path
    define( 'LSOW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    define( 'LSOW_PLUGIN_SLUG', dirname( plugin_basename( __FILE__ ) ) );
    // Plugin Folder URL
    define( 'LSOW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    // Plugin Root File
    define( 'LSOW_PLUGIN_FILE', __FILE__ );
    // Plugin Widgets Folder Path
    define( 'LSOW_WIDGETS_DIR', plugin_dir_path( __FILE__ ) . 'includes/widgets/' );
    // Plugin Folder URL
    define( 'LSOW_WIDGETS_URL', plugin_dir_url( __FILE__ ) . 'includes/widgets/' );
    // Plugin Premium Widgets Folder Path
    define( 'LSOW_PREMIUM_WIDGETS_DIR', plugin_dir_path( __FILE__ ) . 'includes/widgets/premium/' );
    // Plugin Folder URL
    define( 'LSOW_PREMIUM_WIDGETS_URL', plugin_dir_url( __FILE__ ) . 'includes/widgets/premium/' );
    // Plugin Help Page URL
    define( 'LSOW_PLUGIN_HELP_URL', admin_url() . 'admin.php?page=livemesh_so_widgets_documentation' );
    // Create a helper function for easy SDK access.
    function lsow_fs()
    {
        global  $lsow_fs ;
        
        if ( !isset( $lsow_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $lsow_fs = fs_dynamic_init( array(
                'id'             => '2181',
                'slug'           => 'livemesh-siteorigin-widgets',
                'type'           => 'plugin',
                'public_key'     => 'pk_2aea13291408db02386483997de7e',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'    => 'livemesh_so_widgets',
                'support' => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $lsow_fs;
    }
    
    // Init Freemius.
    lsow_fs();
    // Signal that SDK was initiated.
    do_action( 'lsow_fs_loaded' );
    function lsow_fs_add_licensing_helper()
    {
        ?>
        <script type="text/javascript">
            (function () {
                window.lsow_fs = {can_use_premium_code: <?php 
        echo  json_encode( lsow_fs()->can_use_premium_code() ) ;
        ?>};
            })();
        </script>
        <?php 
    }
    
    add_action( 'wp_head', 'lsow_fs_add_licensing_helper' );
    require_once dirname( __FILE__ ) . '/plugin.php';
}
