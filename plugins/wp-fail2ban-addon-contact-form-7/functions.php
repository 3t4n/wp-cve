<?php declare(strict_types=1);
/**
 * Functions
 *
 * @package wp-fail2ban-addon-contact-form-7
 * @since 1.1.0
 */
namespace com\wp_fail2ban\addons\ContactForm7;

defined('ABSPATH') or exit;

/**
 * Register the messages with WP fail2ban
 *
 * @since 1.0.0
 *
 * @return void
 *
 * @wp-f2b-extra \(WPf2b\+\+/contact-form-7\) Spam form submission
 */
function wp_fail2ban_register_plugin()
{
    try {
        do_action('wp_fail2ban_register_plugin', WP_FAIL2BAN_ADDON_CF7_PLUGIN_SLUG, '<b>WPf2b++</b> | Contact Form 7');
        do_action('wp_fail2ban_register_message', WP_FAIL2BAN_ADDON_CF7_PLUGIN_SLUG, [
            'slug'          => 'wpcf7_spam',
            'fail'          => 'soft',
            'priority'      => LOG_NOTICE,
            'event_class'   => 'Spam',
            'event_desc'    => __('Spam form', WP_FAIL2BAN_ADDON_CF7_I18N),
            'event_id'      => 0x0001,
            'message'       => 'Spam form submission',
            'vars'          => []
        ]);
    } catch (\RuntimeException $e) {
        // @todo decide what to do
    }
}

/**
 * Log spam message.
 *
 * @since 1.0.0
 *
 * @param mixed $spam
 *
 * @return mixed
 */
function wpcf7_spam($spam)
{
    if ($spam) {
        try {
            do_action('wp_fail2ban_log_message', WP_FAIL2BAN_ADDON_CF7_PLUGIN_SLUG, 'wpcf7_spam', []);
        } catch (\InvalidArgumentException $e) { // @codeCoverageIgnore
            // failed to register previously
        }
    }
    return $spam;
}

