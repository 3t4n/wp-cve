<?php
/*

Plugin Name: Embed Video Thumbnail
Plugin URI: https://www.ikanaweb.fr
Description: Customize and automatically replace embed videos with their thumbnail
Version: 2.0.3
Author: ikanaweb
Author URI: https://www.ikanaweb.fr
Text Domain: embed-video-thumbnail
Domain Path: lang
License: GPL2

*/

\defined('ABSPATH') or die();

require_once __DIR__ . '/const.php';

$locale = get_locale();
$locale = apply_filters('plugin_locale', $locale, IKANAWEB_EVT_TEXT_DOMAIN);
load_plugin_textdomain(IKANAWEB_EVT_TEXT_DOMAIN, false, \dirname(IKANAWEB_EVT_BASENAME) . '/lang');

if (version_compare(PHP_VERSION, IKANAWEB_EVT_PHP_VERSION) < 0) {
    add_action('admin_notices', 'ikevt_php_version_error');
    function ikevt_php_version_error()
    {
        $errorMessage = sprintf(
            '<strong>%s</strong> requires PHP ' . IKANAWEB_EVT_PHP_VERSION . ' or higher. You’re still on %s.',
            IKANAWEB_EVT_NAME,
            PHP_VERSION
        );
        echo '
            <div class="notice notice-warning">
                <p> ' . $errorMessage . ' </p>
            </div>
        ';
    }

    return;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/admin/admin-init.php';

use Ikana\EmbedVideoThumbnail\PluginReview;

global $wpdb;

Redux::init(IKANAWEB_EVT_SLUG);

$evt = new Ikana\EmbedVideoThumbnail\EmbedVideoThumbnail($wpdb);
$evt->boot();

new PluginReview(
    IKANAWEB_EVT_TEXT_DOMAIN,
    IKANAWEB_EVT_NAME,
    IKANAWEB_EVT_REVIEW_URL
);
