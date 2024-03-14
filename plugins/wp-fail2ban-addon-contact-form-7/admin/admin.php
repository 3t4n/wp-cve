<?php declare(strict_types=1);
/**
 * Admin
 *
 * @package wp-fail2ban-addon-contact-form-7
 * @since   1.1.0
 */
namespace com\wp_fail2ban\addons\ContactForm7;

use function org\lecklider\charles\wordpress\wp_fail2ban\add_wpf2b_addon_page;
use function org\lecklider\charles\wordpress\wp_fail2ban\wf_fs;

defined('ABSPATH') or exit;

/**
 * Hook: admin_menu
 *
 * @since  1.3.0    Simplify for newer WPf2b
 * @since  1.2.0    Refactor to support WPf2b/4.3.4
 * @since  1.1.0
 *
 * @return void
 */
function admin_menu()
{
    if (!wf_fs()->is_activation_mode() && (!is_multisite() || is_network_admin())) {
        if ($hook = add_wpf2b_addon_page('Contact Form 7', null, 'wp-fail2ban_addon_contactform7', __NAMESPACE__.'\splash')) {
            add_action("load-$hook", function () {
                wp_enqueue_style('wpf2b-admin', plugins_url('admin/css/admin.css', WP_FAIL2BAN_FILE));
                require_once __DIR__.'/splash.php';
            });
        }
    }
}
add_action('admin_menu', __NAMESPACE__.'\admin_menu', PHP_INT_MAX);
add_action('network_admin_menu', __NAMESPACE__.'\admin_menu', PHP_INT_MAX);

