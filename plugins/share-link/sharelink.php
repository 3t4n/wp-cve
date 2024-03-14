<?php
/**
 * Plugin Name: Share Link
 * Plugin URI: https://sharelinktechnologies.com
 * Description: Plugin for use with Share Link Application
 * Version: 2.0.9
 */

define('SHARELINK_VERSION', '2.0.9');
define('SHARELINK_FILE__', __FILE__);
define('SHARELINK_PLUGIN_BASE', plugin_basename(SHARELINK_FILE__));
define('SHARELINK_PATH', plugin_dir_path(SHARELINK_FILE__));
define('SHARELINK_URL', plugins_url('/', SHARELINK_FILE__));
define('SHARELINK_ASSETS_URL', SHARELINK_URL . 'assets/');

define('SHARELINK_APP_BASE_URL', 'https://app.sharelinktechnologies.com');
define('SHARELINK_WEB_PAGE', 'https://sharelinktechnologies.com');
define('SHARELINK_DOCUMENTATION_WEB_PAGE', 'https://sharelinktechnologies.com/docs');
define('SHARELINK_PRICING_WEB_PAGE', 'https://sharelinktechnologies.com#pricing');

define('SHARELINK_WIDGET_BASE_URL', 'https://app.sharelinktechnologies.com/widget');
define('SHARELINK_WIDGET_JS', 'https://app.sharelinktechnologies.com/widget/js');

foreach (glob(__DIR__ . '/includes/*.php') as $filename) {
    include $filename;
}

// Add new block categories
add_filter('block_categories', function ($categories, $post) {
    return array_merge(
        $categories,
        [
            [
                'slug' => 'sharelink-widgets',
                'title' => 'Share Link Widgets',
            ],
        ]
    );
}, 10, 2);

// Register our widget
add_action('widgets_init', 'wpb_load_widget');
function wpb_load_widget() {
    register_widget('SharelinkWpWidget');
}

// Register custom blocks in gutenberg
$widgets = (new SharelinkWidgets())->getAll();
if ($widgets && SharelinkCore::isInstalled()) {
    foreach ($widgets as $widget) {
        if (isset($widget['uuid'])) {
            add_action('init', function () use ($widget) {
                wp_register_script(
                'sharelink-widget-gutenberg-block-' . $widget['uuid'],
                plugins_url('assets/js/' . $widget['uuid'] . '.js', __FILE__),
                ['wp-blocks', 'wp-element']
            );

                if (function_exists('register_block_type')) {
                    register_block_type('sharelink-gutenberg/widget-' . $widget['uuid'], [
                        'editor_script' => 'sharelink-widget-gutenberg-block-' . $widget['uuid'],
                    ]);
                }
            });
        }
    }
}

if (is_admin()) {
    add_action('admin_menu', 'sharelinkMenu');
    add_action('admin_enqueue_scripts', ['SharelinkAdmin', 'stylesAndScripts']);
    add_action('admin_enqueue_scripts', ['SharelinkCore', 'frontEndJs']);
    add_filter('script_loader_tag', ['SharelinkCore', 'addAddAttributesForFrontendJs'], 10, 2);

    function sharelinkAdmin() {
        $admin = new SharelinkAdmin();
        $admin->sharelinkAdminPage();
    }

    function sharelinkSetting() {
        $admin = new SharelinkAdmin();
        $admin->settingPage();
    }

    function sharelink404ErrorPage() {
        $admin = new SharelinkAdmin();
        $admin->loginError(404);
    }

    function sharelink403ErrorPage() {
        $admin = new SharelinkAdmin();
        $admin->loginError(403);
    }

    function sharelinkMenu() {
        $sharelinkIcon = base64_encode(
           '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 112.19 98.64">
               <title>Share Link Logo</title>
               <g id="Layer_2" data-name="Layer 2">
                   <g id="Layer_1-2" data-name="Layer 1">
                       <path d="M104.59,17l7.6-2.85-8.08-.78,5.79-5.7-7.71,2.57,3-7.56-6,5.48L98.81,0,95.56,7.44,91.94.18,92,8.3,85.73,3.14l3.36,7.39L81.27,8.36l4.83,4.29H11.34A11.38,11.38,0,0,0,0,24.07v42A11.39,11.39,0,0,0,11.34,77.49H21.4l.07,21.15,17-21.15H88.66A11.39,11.39,0,0,0,100,66.06V27.53l2.91,3.66-1.75-7.93,7.2,3.74-4.82-6.53,8.11.49Z" style="fill:#1b75bc"/>
                       <path d="M100,11.39l-14.75,4L90,19.64,76,35.45,60.62,28.84l-21.18,22-12.63-5L2.58,70.77a6.38,6.38,0,0,0,1.25,1.92A8,8,0,0,0,5.46,74L27.88,50.8l12.64,5,21.12-22,15.55,6.66,16-18.1,4.71,4.15Z" style="fill:#fff"/>
                   </g>
               </g>
           </svg> '
        );

        add_menu_page('Share Link', 'Share Link', 'manage_options', 'sharelink', 'sharelinkAdmin', 'data:image/svg+xml;base64,' . $sharelinkIcon);
        add_submenu_page('sharelink', 'Widgets', 'Widgets', 'manage_options', 'sharelink');
        add_submenu_page('sharelink', 'License', 'License', 'manage_options', 'sharelink-setting', 'sharelinkSetting');
        add_submenu_page('options-writing.php', 'Setting', 'Setting', 'manage_options', 'sharelink-error-404', 'sharelink404ErrorPage');
        add_submenu_page('options-writing.php', 'Setting', 'Setting', 'manage_options', 'sharelink-error-403', 'sharelink403ErrorPage');
    }

    if (isset($_POST['sharelink-license'])) {
        SharelinkCore::setLicense($_POST['license-key']);
    }

    add_action('wp_ajax_sharelink-check-key', 'check_key');
    function check_key() {
        $license = SharelinkOptions::getLicense();

        $api = new SharelinkApi();
        $response = $api->get($license);

        if ($response == 200) {
            update_option('sharelink-license-activated', 1);
            echo 'success';
        } else {
            update_option('sharelink-license-activated', 0);
            echo 'error';
        }

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    add_action('wp_ajax_sharelink-check-domain', 'check_domain');
    function check_domain() {
        $license = SharelinkOptions::getLicense();

        $api = new SharelinkApi();
        $response = $api->get($license);

        if ($response == 200) {
            echo 'success';
            update_option('sharelink-license-activated', 1);
        } else {
            echo 'error';
        }

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    add_action('wp_ajax_sharelink-refresh-widget', 'refresh_widget');
    function refresh_widget() {
        $widgets = (new SharelinkWidgets())->getAll();
        SharelinkHelpers::render('widgets', ['widgets' => $widgets]);
        wp_die(); // this is required to terminate immediately and return a proper response
    }
}

add_filter('script_loader_tag', ['SharelinkCore', 'addAddAttributesForFrontendJs'], 10, 2);
add_action('the_posts', ['SharelinkCore', 'prefix_enqueue']);
add_shortcode('sharelink', ['SharelinkCore', 'initiateShortcode']);
