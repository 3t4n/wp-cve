<?php

/**
 * Plugin Name:     kk Star Ratings
 * Plugin Slug:     kk-star-ratings
 * Plugin Nick:     kksr
 * Plugin URI:      https://feedbackwp.com
 * Description:     Allow blog visitors to involve and interact more effectively with your website by rating posts.
 * Author:          FeedbackWP
 * Author URI:      https://feedbackwp.com
 * Text Domain:     kk-star-ratings
 * Domain Path:     /languages
 * Version:         5.4.7
 * License:         GPLv2 or later
 */

use function Bhittani\StarRating\core\functions\action;

if (! defined('ABSPATH')) {
    http_response_code(404);
    exit();
}

define('KK_STAR_RATINGS', __FILE__);

add_action( 'admin_menu', function () {
    global $submenu;
    $submenu[ 'kk-star-ratings' ][] = [
        '<span style="color: #fff;display:block;background-color:#d63638;padding:4px 0 4px 4px;width: 100%;">' . esc_html__( 'Upgrade to Pro', 'kk-star-ratings' ) . '</span>',
        'manage_options',
        'https://feedbackwp.com/pricing/?utm_source=liteplugin&utm_medium=menu-link&utm_campaign=menu-upsell'
    ];
}, 99 );


$basename = plugin_basename(__FILE__);
$prefix   = is_network_admin() ? 'network_admin_' : '';
add_filter("{$prefix}plugin_action_links_$basename", function($actions, $plugin_file, $plugin_data, $context)
{
    $custom_actions['kksr_upgrade'] = sprintf(
        '<a style="color:#d54e21;font-weight:bold" href="%s" target="_blank">%s</a>', 'https://feedbackwp.com/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=action_link',
        __('Go Premium', 'kk-star-ratings')
    );

    // add the links to the front of the actions list
    return array_merge($custom_actions, $actions);

}, 10, 4);

add_action('admin_notices', function () {
    if(isset($_GET['page']) && $_GET['page'] == 'kk-star-ratings' && class_exists('\Rate_My_Post_CPT')) {
    ?>
    <div class="notice notice-success">
        <p>
            <?php printf(
            esc_html__('%1$sFeedbackWP Premium%2$s, a better upgrade to KK Star Ratings has been detected. Migrate now by %3$sclicking on "Tools" menu in FeedbackWP Settings to Migrate%4$s.', 'kk-star-ratings' ),
                '<strong>', '</strong>', '<a href="'.admin_url('admin.php?page=rate-my-post').'">', '</a>'
            ); ?>
        </p>
    </div>
    <?php
    }
});


require_once __DIR__ . '/lib/Installer/KKStar_PluginSilentUpgrader.php';
require_once __DIR__ . '/lib/Installer/KKStar_Install_Skin.php';

require_once __DIR__ . '/lib/FuseWP.php';
require_once __DIR__ . '/lib/ProfilePress.php';

define('KK_STAR_ASSETS_URL', plugin_dir_url(KK_STAR_RATINGS) . 'lib/');

add_action('init', function() {
    KKSTAR_FuseWP::get_instance();
    KKSTAR_ProfilePress::get_instance();
}, 999);

if (function_exists('kksr_freemius')) {
    kksr_freemius()->set_basename(true, __FILE__);
} else {
    if (! function_exists('kksr_freemius')) {
        require_once __DIR__.'/freemius.php';
    }

    require_once __DIR__.'/src/index.php';
    require_once __DIR__.'/src/core/index.php';

    // Let everyone know that the plugin is loaded.
    action('init', kksr());
}