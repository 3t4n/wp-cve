<?php
/*
 Plugin Name: TalentLMS
 Plugin URI: http://wordpress.org/extend/plugins/talentlms/
 Description: This plugin integrates TalentLMS with WordPress. Promote your TalentLMS content through your WordPress site.
 Version: 7.1
 Author: Epignosis LLC
 Author URI: www.epignosishq.com
 License: GPL2
 */

/**
 * Require once the Composer Autoload
 */
if (file_exists(plugin_dir_path(__FILE__) . 'vendor/autoload.php')) {
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
}

/**
 * Define constants
 */
define('TLMS_BASEPATH', dirname(__FILE__));
define('TLMS_BASEURL', plugin_dir_url(__FILE__));
define('TLMS_VERSION', '7.1');
define('TLMS_UPLOAD_DIR', 'talentlmswpplugin');
/**
 * The code that runs during plugin activation
 */
function activate(): void
{
    TalentlmsIntegration\Activate::tlms_activate();
}
register_activation_hook(__FILE__, 'activate');

register_uninstall_hook(__FILE__, 'tlms_uninstall');

if (file_exists(TLMS_BASEPATH . '/TalentLMSLib/lib/TalentLMS.php')) {
    require_once TLMS_BASEPATH . '/TalentLMSLib/lib/TalentLMS.php';
}

if (class_exists('TalentlmsIntegration\Plugin')) {
    TalentlmsIntegration\Plugin::init();
}

function tlms_isWoocommerceActive()
{
    if (is_plugin_active('woocommerce/woocommerce.php')) {
        update_option('tlms-woocommerce-active', 1);
    } else {
        update_option('tlms-woocommerce-active', 0);
    }
    if (empty(get_option('tlms-enroll-user-to-courses'))) {
        update_option('tlms-enroll-user-to-courses', 'submission');
    }
}
add_action('admin_init', 'tlms_isWoocommerceActive');
