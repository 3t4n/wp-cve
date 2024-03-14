<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Blocker\Exclusions;

/**
 * Provide Import and Export of the settings of the plugin
 */
class Compatibility extends \WP_Meteor\Blocker\Base
{
    public $adminPriority = -1;
    public $priority = 100;
    public $defaultEnabled = true;
    // public $rocket_delay_js_script_regexp = '';

    public function __construct()
    {
        parent::__construct();

        add_filter('wpmeteor_exclude', function ($exclude, $content) {
            if ($exclude) {
                return $exclude;
            }

            $scripts = [
                'function fvmuag\(', // fast velocity minify
                // 'function fvmloadscripts\(',  // fast velocity minify
                'lazyLoadOptions', // autoptimize
                'lazySizesConfig', // lazysizes config
                'lazyLoadThumb', // rocket lazy load
                'eio_lazy_vars', // easy image optimizer
                'ewww_webp_supported', // ewww image optimizer
                '(?<!wpsol-addon-)lazyload\.min\.js', // most of the lazyloaders
                '(?<!wpsol-addon-ajax-)lazyload\.min\.js', // most of the lazyloaders
                '(?<!Avada/includes/lib/assets/min/js/library/)lazysizes\.min\.js',  // most of the lazyloaders
                '(?<!Avada/includes/lib/assets/min/js/library/)lazysizes\.js',  // most of the lazyloaders
                'wp-rocket/assets/js/lazyload/(.*)', // wp rocket
                'wprRemoveCPCSS', //
                'wp-rocketassetsjslazyload(.*)', // wp rocket
                'function et_core_page_resource_fallback\(', // divi
                'window.\$us === undefined',
                'fusionNavIsCollapsed', // Avada
                'smush-lazy-load\.min\.js', // smush
                'jetpack-lazy-images-js-enabled', // Jetpack Lazy Images
                'lazy-images\.js', // Jetpack Lazy Images
                'document\.body\.classList\.remove\("no-js"\)',
                'document\.documentElement\.className\.replace\(\s*\'no-js\',\s*\'js\'\s*\)',
                'document\.documentElement\.className = \'js\'', // divi
                'js-agent\.newrelic\.com', // newrelic
                'data-swift-image-lazyload', // Swift Performance Lazyload
                'c.replace(/woocommerce-no-js/, \'woocommerce-js\')', // WooCommerce
                // 'navigator.serviceWorker', // serviceWorker
                "var wpforms_settings",
                // "-js-extra$",
            ];

            $regexp = '#(' . join("|", array_map(function ($script) {
                return str_replace('#', '\#', $script);
            }, $scripts)) . ')#';

            if (preg_match($regexp, $content)) {
                return true;
            }

            // WP Rocket delay_js support
            // WP Rocket delays all the scripts but those listed
            /*
            if (
                $this->rocket_delay_js_script_regexp
                && !preg_match($this->rocket_delay_js_script_regexp, $content)
            ) {
                return true;
            }
            */

            return $exclude;
        }, null, 2);

        /*
        add_filter('rocket_delay_js_exclusions', function ($delay_js_scripts) {
            if (!empty($delay_js_scripts)) {
                foreach ((array) $delay_js_scripts as $i => $delay_js_script) {
                    $delay_js_scripts[$i] = preg_quote(str_replace('#', '\#', $delay_js_script), '#');
                }
                $this->rocket_delay_js_script_regexp = '#(' . join('|', $delay_js_scripts) . ')#';
            }
            return $delay_js_scripts;
        });
        */
    }

    public function backend_display_settings()
    {
    }

    public function backend_adjust_wpmeteor($wpmeteor, $settings)
    {
        return $wpmeteor;
    }

    public function backend_save_settings($sanitized, $settings)
    {
        return $sanitized;
    }

    /* triggered from wpmeteor_load_settings */
    public function load_settings($settings)
    {
        return $settings;
    }

    public function frontend_adjust_wpmeteor($wpmeteor, $settings)
    {
        return $wpmeteor;
    }
}
