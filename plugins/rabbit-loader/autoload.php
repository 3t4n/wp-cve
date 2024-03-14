<?php

define('RABBITLOADER_PLUG_DIR', plugin_dir_path(__FILE__));
define('RABBITLOADER_CACHE_DIR', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . "rabbitloader");
define('RABBITLOADER_PLUG_URL', plugin_dir_url(__FILE__));
define('RABBITLOADER_PLUG_VERSION', '2.19.18');
define('RABBITLOADER_TEXT_DOMAIN', 'rabbit-loader');
define('RABBITLOADER_PLUG_ENV', 'PROD');
if (!defined('JSON_INVALID_UTF8_IGNORE')) {
    define('JSON_INVALID_UTF8_IGNORE', 0); //@since PHP 7.2
}
include_once(RABBITLOADER_PLUG_DIR . 'inc/core/core.php');
include_once(RABBITLOADER_PLUG_DIR . 'inc/core/util.php');
include_once(RABBITLOADER_PLUG_DIR . 'inc/core/integrations.php');
include_once(RABBITLOADER_PLUG_DIR . 'inc/rl_can_url.php');
include_once(RABBITLOADER_PLUG_DIR . 'inc/public.php');
include_once(RABBITLOADER_PLUG_DIR . 'inc/util_wp.php');

if (is_admin()) {
    include_once(RABBITLOADER_PLUG_DIR . 'inc/ad_ad.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/admin.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/conflicts.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_init.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_home.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_urls.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_usage.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_help.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_settings.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_log.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_images.php');
    include_once(RABBITLOADER_PLUG_DIR . 'inc/tab_css.php');
}

RabbitLoader_21_Public::addActions();
