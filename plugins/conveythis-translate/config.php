<?php

if(
    !defined( 'ABSPATH' )
)
{
    exit;
}

// Off on test
define('CONVEYTHIS_LOADER', true);

define('CONVEYTHIS_PLUGIN_VERSION', 221);


define('CONVEY_PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ));

define('CONVEY_PLUGIN_PATH', plugin_dir_url(__FILE__));

define('CONVEY_PLUGIN_DIR', plugins_url('', __FILE__));

define('CONVEYTHIS_URL', 'https://www.conveythis.com/');

define('CONVEYTHIS_APP_URL', 'https://app.conveythis.com');

define('CONVEYTHIS_API_URL', 'https://api.conveythis.com'); //https://api.conveythis.com

define('CONVEYTHIS_API_PROXY_URL', 'https://api-proxy.conveythis.com'); //https://api-proxy.conveythis.com
define('CONVEYTHIS_API_PROXY_URL_FOR_EU', 'https://proxy-eu.conveythis.com'); //https://proxy-eu.conveythis.com

define('CONVEYTHIS_JAVASCRIPT_PLUGIN_URL', '//cdn.conveythis.com/javascript/65');

define('CONVEYTHIS_JAVASCRIPT_LIGHT_PLUGIN_URL', '//cdn.conveythis.com/javascriptLight/3');


if (
    !defined('CONVEYTHIS_CACHE_ROOT_PATH')
)
{
    define('CONVEYTHIS_CACHE_ROOT_PATH', WP_CONTENT_DIR . '/cache/');
}

define('CONVEYTHIS_CACHE_PATH', CONVEYTHIS_CACHE_ROOT_PATH . 'conveythis/');

define('CONVEYTHIS_CACHE_SLUG_PATH', CONVEYTHIS_CACHE_PATH . 'slug.json');

define('CONVEYTHIS_CACHE_TRANSLATIONS_PATH', CONVEYTHIS_CACHE_PATH . 'translations/');

define('API_AUTH_TOKEN', '85T8DGNtV88g4wvceVyHym69Yu3v5ZmN');
