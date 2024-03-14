<?php

class RabbitLoader_21_Conflicts
{

    private static $messages = null;

    public static function &getMessages()
    {
        if (self::$messages == null) {
            self::$messages = [];
            self::runSystemChecks();
            self::runConflictsCheck();
        }
        return self::$messages;
    }

    private static function runSystemChecks()
    {
        if (defined('PHP_VERSION') && version_compare(PHP_VERSION, '5.6.0') < 0) {
            self::$messages[] = sprintf(RL21UtilWP::__("RabbitLoader requires PHP 5.6 or higher. You're still on version %s which may expose your site to security vulnerabilities. <a href='%s' target='_blank'>Learn more</a>."), PHP_VERSION, "https://wordpress.org/support/update-php/");
        }
    }

    private static function runConflictsCheck()
    {

        $otherConflictPluginNames = [];
        $constants_to_check = [
            'AUTOPTIMIZE_PLUGIN_VERSION' => 'Autoptimize',
            'BREEZE_PLUGIN_DIR' => 'Breeze',
            'COMET_CACHE_PLUGIN_FILE' => 'Comet Cache',
            'CYBVC_PLUGIN_DIR' => 'ViperCache',
            'DEBLOAT_PLUGIN_FILE' => 'Debloat',
            'JETPACK_BOOST_VERSION' => 'Jetpack Boost',
            'LSCACHE_ADV_CACHE' => 'LiteSpeed Cache',
            'LSCWP_DIR' => 'LiteSpeed Cache',
            'LSCWP_V' => 'LiteSpeed Cache',
            'PEGASAAS_ACCELERATOR_VERSION' => 'Pegasaas Accelerator WP',
            'PERFMATTERS_VERSION' => 'Perfmatters',
            'PERFMATTERS_CACHE_DIR' => 'Perfmatters',
            'PHASTPRESS_VERSION' => 'PhastPress',
            'SW_CLOUDFLARE_PAGECACHE' => 'WP Cloudflare Super Page Cache',
            'TENWEB_SO_VERSION' => '10Web Booster',
            'NITROPACK_VERSION' => 'NitroPack',
            'W3TC' => 'W3 Total Cache',
            'WMAC_PLUGIN_VERSION' => 'Clearfy Cache',
            'WP_ROCKET_VERSION' => 'WP-Rocket',
            'WP_SMUSH_VERSION' => 'Smush',
            'WPFC_WP_PLUGIN_DIR' => 'WP Fastest Cache',
            'WPFC_MAIN_PATH' => 'WP Fastest Cache',
            'WPHB_VERSION' => 'Hummingbird',
            'WPMETEOR_VERSION' => 'WP Meteor'

        ];

        $classes_to_check = [
            'BJLL' => 'BJ Lazy Load',
            'PagespeedNinja' => 'PageSpeed Ninja',
            'Swift_Performance' => 'Swift Performance',
            'Swift_Performance_Lite' => 'Swift Performance',
            'SWCFPC_Backend' => 'WP Cloudflare Super Page Cache',
            'WPO_Cache_Config' => 'WP Optimize page caching'
        ];

        foreach ($constants_to_check as $plugConst => $plugName) {
            if (defined($plugConst)) {
                $otherConflictPluginNames[] = $plugName;
            }
        }

        foreach ($classes_to_check as $className => $plugName) {
            if (class_exists($className)) {
                $otherConflictPluginNames[] = $plugName;
            }
        }
        if (!empty($otherConflictPluginNames)) {
            $otherConflictPluginNames = array_unique($otherConflictPluginNames);
            foreach ($otherConflictPluginNames as $plugName) {
                self::$messages[] = sprintf(RL21UtilWP::__("It seems you are also using %s plugin which conflicts with RabbitLoader optimizations. We suggest deactivating %s and hit the 'Purge All Pages' button on the RabbitLoader home tab."), $plugName, $plugName);
            }
        }

        if (defined('WPCACHEHOME') && function_exists("wp_cache_phase2")) {
            //WP Super Cache (also comes default with HostGator)
            self::$messages[] = sprintf(RL21UtilWP::__("It seems you are using %s plugin which conflicts with RabbitLoader optimizations. We suggest deactivating it and hit the 'Purge All Pages' button on the RabbitLoader home tab. If you think it is an error, please follow <a href='%s' target='_blank'>this guide</a>."), "WP Super Cache", "https://rabbitloader.com/kb/wordpress-plugin-activation-errors/");
        }

        try {
            if (defined('JETPACK__VERSION')) {
                $jp_options = get_option('jetpack_active_modules');
                if (is_array($jp_options) && in_array('lazy-images', $jp_options)) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "image lazy loading option in Jetpack");
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (defined('POWERKIT')) {
                $powerkit_enabled_lazyload = get_option('powerkit_enabled_lazyload');
                if (!empty($powerkit_enabled_lazyload)) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "image lazy loading option in Powerkit");
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (class_exists('Flatsome_Default')) {
                $theme_mods_flatsome = [];
                if (function_exists('of_get_options')) {
                    $theme_mods_flatsome = of_get_options();
                } else {
                    $theme_mods_flatsome = get_option('theme_mods_flatsome-child');
                }
                if (!empty($theme_mods_flatsome)) {
                    if (!empty($theme_mods_flatsome['lazy_load_images'])) {
                        self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "IMAGE lazy loading options in Flatsome Advanced Options");
                    }
                    if (!empty($theme_mods_flatsome['lazy_load_backgrounds'])) {
                        self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "BANNER AND SECTION BACKGROUNDS lazy loading options in Flatsome Advanced Options");
                    }
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (defined('ET_BUILDER_THEME') && defined('TEMPLATEPATH')) {
                $themeName = explode('/', TEMPLATEPATH);
                if (!empty($themeName)) {
                    $themeName_c = end($themeName);
                    $themeName_s = strtolower($themeName_c);
                    $theme_options = get_option('et_' . $themeName_s);
                    if (!empty($theme_options)) {
                        $et_check_options = ['critical_css', 'dynamic_css'];
                        $is_on = false;
                        foreach ($et_check_options as $et_check_option) {
                            $et_current_option = $themeName_s . '_' . $et_check_option;
                            $is_on = !empty($theme_options[$et_current_option]) && $theme_options[$et_current_option] == "on";
                            if ($is_on) {
                                break;
                            }
                        }
                        if ($is_on) {
                            self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader <a href='%s' target='_blank'>Check screenshots</a>."), "all performance optimizations in $themeName_c theme", "https://rabbitloader.com/kb/settings-for-wordpress-divi-theme-users/");
                        }
                    }

                    if (!empty($theme_options['et_pb_static_css_file']) && strcasecmp($theme_options['et_pb_static_css_file'], "on") === 0) {
                        self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader. <a href='%s' target='_blank'>Check screenshots</a>."), "Static CSS File Generation option in $themeName_c theme", "https://rabbitloader.com/kb/settings-for-wordpress-divi-theme-users/");
                    }
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (defined('WPO_VERSION')) {
                if (class_exists('WPO_Cache_Config')) {
                    $wpo_inst = WPO_Cache_Config::instance();
                    if ($wpo_inst->get_option('enable_page_caching', false)) {
                        self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "page caching option in WP Optimize plugin");
                    }
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (function_exists('ewww_image_optimizer_get_option')) {
                if (ewww_image_optimizer_get_option('ewww_image_optimizer_lazy_load')) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "image lazy loading in EWWW Image Optimizer");
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (defined('WOOCS_VERSION')) {
                if (empty(get_option('woocs_shop_is_cached'))) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please enable %s to be compatible with RabbitLoader. <a href='%s' target='_blank'>Check screenshot</a>"), "<i><b>I am using cache plugin</b></i> option in WOOCS Currency Switcher Settings", "https://rabbitloader.com/kb/woocs-currency-switcher-for-woocommerce/");
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (defined('WOOMULTI_CURRENCY_F_VERSION') && class_exists('WOOMULTI_CURRENCY_F_Admin_Settings')) {
                if (empty(WOOMULTI_CURRENCY_F_Admin_Settings::get_field('cache_compatible'))) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please enable %s to be compatible with RabbitLoader. <a href='%s' target='_blank'>Check screenshot</a>"), "<i><b>Use Cache Plugin</b></i> option in CURCY - Multi Currency for WooCommerce", "https://rabbitloader.com/kb/curcy-multi-currency-for-woocommerce/");
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (defined('FLYING_PRESS_VERSION')) {
                $fp_options = get_option('FLYING_PRESS_CONFIG');
                if (!empty($fp_options['css_extract_used'])) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "CSS optimizations in Flying Press");
                }
                if (!empty($fp_options['js_preload_links']) || !empty($fp_options['js_defer']) || !empty($fp_options['js_interaction'])) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "all JavaScript optimizations such as Preload, Defer, Interaction etc in Flying Press");
                }
                if (!empty($fp_options['cache'])) {
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader."), "cache option in Flying Press");
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        if (defined('SiteGround_Optimizer\VERSION')) {
            $sg_combine_css = get_option('siteground_optimizer_combine_css');
            if (!empty($sg_combine_css)) {
                self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s in %s to avoid conflict with RabbitLoader."), "Combine CSS option", "Siteground Optimizer");
            }
        }

        if (defined('EZOIC_INTEGRATION_VERSION')) {
            self::$messages[] = sprintf(RL21UtilWP::__("Currently RabbitLoader is not compatible with %s."), "Ezoic");
        }

        if (defined('WPACU_PLUGIN_ID') && defined('WPACU_PLUGIN_TITLE')) {
            $wpassetcleanup_settings = get_option('wpassetcleanup_settings');
            if (!empty($wpassetcleanup_settings)) {
                if (!empty($wpassetcleanup_settings['combine_loaded_css_for']) && strcmp($wpassetcleanup_settings['combine_loaded_css_for'], 'guests') === 0) {
                    //CSS combination is for all guests
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s in %s to avoid conflict with RabbitLoader."), "Combine CSS option", WPACU_PLUGIN_TITLE);
                }
                if (!empty($wpassetcleanup_settings['critical_css_status']) && strcmp($wpassetcleanup_settings['critical_css_status'], 'off') !== 0) {
                    //Critical css is on all guests
                    self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s in %s to avoid conflict with RabbitLoader."), "Critical CSS option", WPACU_PLUGIN_TITLE);
                }
            }
        }

        try {
            if (defined('FUSION_BUILDER_VERSION')) {
                $template = explode('/', TEMPLATEPATH);
                if (!empty($template)) {
                    $themeName = end($template);
                } else {
                    $themeName = 'current';
                }
                $fb_options = get_option('fusion_options');
                $empty_checks = [
                    'lazy_load' => ['none', 'off'],
                    'js_compiler' => ['0'],
                    'defer_jquery' => ['0'],
                    'defer_styles' => ['0'],
                    'css_cache_method' => ['none', 'off'],
                    'css_combine_third_party_assets' => ['0'],
                    'media_queries_async' => ['0'],
                    'critical_css' => ['0']
                ];
                foreach ($empty_checks as $key => $expValues) {
                    if (empty($fb_options[$key])) {
                        continue;
                    } else if (in_array($fb_options[$key], $expValues)) {
                        continue;
                    } else {
                        self::$messages[] = sprintf(RL21UtilWP::__("Please disable %s to avoid conflict with RabbitLoader. For help, refer <a href='%s' target='_blank'>this guide</a>."), " all performance options in $themeName theme performance settings such as \"" . str_ireplace("_", " ", $key), "\" https://rabbitloader.com/kb/settings-required-for-themefusion-users/");
                        break;
                    }
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }

        try {
            if (defined('ASP_PLUGIN_NAME')) {
                $asp_compatibility = get_option('asp_compatibility');
                $script_loading_method = empty($asp_compatibility['script_loading_method']) ? '' : $asp_compatibility['script_loading_method'];
                $css_loading_method = empty($asp_compatibility['css_loading_method']) ? '' : $asp_compatibility['css_loading_method'];
                if ($script_loading_method != 'classic' || $css_loading_method != 'file') {
                    self::$messages[] = sprintf(RL21UtilWP::__("In the Ajax Search Pro plugin, under the Compatibility and Other Settings, go to CSS and JS loading option. Please choose 'classic' option for Script loading method and file option for Style (CSS) loading method. <a href='%s' target='_blank'>Check screenshot</a>"), "https://rabbitloader.com/kb/settings-required-for-ajax-search-pro-users/");
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }
}
