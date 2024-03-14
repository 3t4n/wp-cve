<?php
namespace Valued\WordPress;

class PhpCompatibilityCheck {
    public static function isCompatible($plugin_name) {
        if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
            return true;
        }

        add_action('admin_notices', function () use ($plugin_name) {
            $class = 'notice notice-error';
            $message = sprintf(
                __('The %s plugin is not compatible with PHP %s. Please upgrade to PHP 7.0.0 or higher.', 'webwinkelkeur'),
                $plugin_name,
                PHP_VERSION
            );
            printf('<div class="%s"><p>%s</p></div>', esc_attr($class), esc_html($message));
        });

        return false;
    }
}