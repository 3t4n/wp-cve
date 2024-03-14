<?php
/**
 * Plugin Name: Top Bar
 * Plugin URI: https://wpdarko.com/top-bar/
 * Description: Simply the easiest way to add a top bar to your website. This plugin adds a simple and clean notification bar at the top of your website, allowing you to show a message to your visitors. Find help and information on our <a href="https://wpdarko.com/support">support site</a>. This is a free plugin, it is NOT limited and does not contain any ad. Check out the <a href='https://wpdarko.com/top-bar/'>PRO version</a> for more great features.
 * Version: 3.0.6
 * Author: WP Darko
 * Author URI: https://wpdarko.com
 * Text Domain: top-bar
 * Domain Path: /lang/
 * License: GPL2.
 */

// Defines plugin's text domain.
define('TPBR_TXTDM', 'top-bar');

/* General. */
require_once 'inc/topbar-text-domain.php';

/* Settings/menus. */
require_once 'inc/topbar-settings.php';

/* Scripts. */
require_once 'inc/topbar-front-scripts.php';
require_once 'inc/topbar-admin-scripts.php';

// Checks for the PRO version.
add_action('admin_init', 'topbar_free_pro_check', 99);
function topbar_free_pro_check()
{
    if (is_plugin_active('top-bar-pro/topbar_pro.php')) {
        add_action('admin_notices', 'tpbr_admin_notice');
        function tpbr_admin_notice()
        {
            echo '<div class="updated">
                  <p><strong>PRO</strong> version is activated.</p>
                  </div>';
        }

        deactivate_plugins(__FILE__);
    }
}
