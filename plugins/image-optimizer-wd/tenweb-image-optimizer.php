<?php

/**
 * Plugin Name: 10Web Image Optimizer
 * Plugin URI: https://10web.io/services/image-optimizer/
 * Description: 10Web Image Optimizer WordPress plugin enables you to resize, compress and optimize PNG, JPG, GIF files while maintaining image quality.
 * Version: 6.0.67
 * Author: 10Web - Image Optimizer team
 * Author URI: https://10web.io
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: tenweb-image-optimizer
 */

if (!defined('TENWEBIO_MAIN_FILE')) {
    define('TENWEBIO_MAIN_FILE', plugin_basename(__FILE__));
}

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/env.php';
include_once __DIR__ . '/TenWebIOClass.php';

include_once __DIR__ . '/vendor/autoload.php';
\TenWebIO\PreInit::check();
\TenWebWpBenchmark\Init::getInstance();

// check PHP version
\TenWebPluginIO\TenWebIOClass::checkPHPVersion();

if (is_admin() || (defined('DOING_CRON') && DOING_CRON)) {
    register_activation_hook(__FILE__, array('\TenWebPluginIO\TenWebIOClass', 'activate'));
    add_action('plugins_loaded', array('\TenWebPluginIO\TenWebIOClass', 'getInstance'));

    /* added deactivate/update hooks to send site_state*/
    add_action('upgrader_process_complete', array('\TenWebPluginIO\TenWebIOClass', 'siteState'), 10, 2);
    register_deactivation_hook(__FILE__, array('\TenWebPluginIO\TenWebIOClass', 'ioDeactivate'));
}

if (class_exists("\WP_REST_Controller")) {
    add_action('rest_api_init', function () {
        $rest = new \TenWebPluginIO\TenWebIORest();
        $rest->registerRoutes();
    });
}
