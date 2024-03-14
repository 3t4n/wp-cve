<?php declare(strict_types=1);
/**
 * Init
 *
 * @package wp-fail2ban-addon-contact-form-7
 * @since   1.1.0
 */
namespace    com\wp_fail2ban\addons\ContactForm7;

use          com\wp_fail2ban\lib\Activation\VersionCheck;

defined('ABSPATH') or exit; // @codeCoverageIgnore

/**
 * Activation hook
 *
 * @since  1.3.0
 *
 * @return void
 *
 * @codeCoverageIgnore
 */
function activation_hook(): void
{
    require_once __DIR__.'/vendor/wp-fail2ban/lib-activation/version-check.php';

    VersionCheck::check([
        '4.3.0.9',
        '4.4.0'
    ], 'Add-on for Contact Form 7', WP_FAIL2BAN_ADDON_CF7_FILE);
}
register_activation_hook(WP_FAIL2BAN_ADDON_CF7_FILE, __NAMESPACE__.'\activation_hook'); // @codeCoverageIgnore

/**
 * Hook: init
 *
 * @since  1.3.0    Add return type
 * @since  1.1.0
 *
 * @return void
 */
function init(): void
{
    add_action('wp_fail2ban_register', __NAMESPACE__.'\wp_fail2ban_register_plugin');
    add_filter('wpcf7_spam', __NAMESPACE__.'\wpcf7_spam', 999);

    if (is_admin()) {
        require_once __DIR__.'/admin/admin.php';
    }
}

