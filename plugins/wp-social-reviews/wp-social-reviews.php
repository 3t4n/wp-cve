<?php
/*
Plugin Name:  WP Social Ninja
Plugin URI:   https://wpsocialninja.com/
Description:  Display your social feeds, reviews and chat widgets automatically and easily on your website with the all-in-one social media plugin.
Version:      3.13.0
Author:       WP Social Ninja Team - WPManageNinja LLC
Author URI:   https://wpsocialninja.com/
License:      GPLv2 or later
Text Domain:  wp-social-reviews
Domain Path:  /language
*/

defined('ABSPATH') or die;

define('WPSOCIALREVIEWS_VERSION', '3.13.0');
define('WPSOCIALREVIEWS_DB_VERSION', 120);
define('WPSOCIALREVIEWS_MAIN_FILE', __FILE__);
define('WPSOCIALREVIEWS_BASENAME', plugin_basename(__FILE__));
define('WPSOCIALREVIEWS_URL', plugin_dir_url(__FILE__));
define('WPSOCIALREVIEWS_DIR', plugin_dir_path(__FILE__));
define('WPSOCIALREVIEWS_UPLOAD_DIR_NAME', 'wp-social-ninja');

if (!defined( 'WPSOCIALREVIEWS_INSTAGRAM_MAX_RECORDS')) {
    define('WPSOCIALREVIEWS_INSTAGRAM_MAX_RECORDS', 600);
}

require __DIR__.'/vendor/autoload.php';

call_user_func(function($bootstrap) {
    $bootstrap(__FILE__);
}, require(__DIR__.'/boot/app.php'));

// Handle Network new Site Activation
add_action('wp_insert_site', function ($blog) {
    switch_to_blog($blog->blog_id);

    if(!class_exists('\WPSocialReviews\App\Hooks\Handlers\ActivationHandler')) {
        include_once plugin_dir_path(__FILE__) . 'app/Hooks/Handlers/ActivationHandler.php';
    }

    (new \WPSocialReviews\App\Hooks\Handlers\ActivationHandler())->handle();
    restore_current_blog();
});
