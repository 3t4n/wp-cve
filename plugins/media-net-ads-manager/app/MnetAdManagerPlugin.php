<?php

namespace Mnet;

use Mnet\Admin\MnetPluginUtils;
use Mnet\Admin\MnetLogManager;
use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetAuthManager;
use Mnet\Admin\MnetOptions;
use Mnet\MnetDbManager;

class MnetAdManagerPlugin
{
    public static function activate()
    {
        MnetDbManager::createTablesOnActivate();
    }

    public static function deactivate()
    {
        MnetLogManager::logEvent('Deactivate');
        MnetOptions::clearOptions();
    }

    public static function uninstall()
    {
        MnetLogManager::logEvent('Uninstall');
        MnetOptions::clearOptions();
        MnetDbManager::clearDatabase();
        \delete_option('MNET_PLUGIN_VERSION');
    }

    public static function onPluginUpgrade($upgrader_object, $options)
    {
        $mnetPlugin = \plugin_basename(__FILE__);
        if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {
            foreach ($options['plugins'] as $plugin) {
                if ($plugin == $mnetPlugin) {
                    // MnetDbManager::createUpdateDatabase();
                    static::resetOpcache();
                }
            }
        }
    }

    public static function resetOpcache()
    {
        if (!function_exists('opcache_reset')) {
            return;
        }
        try {
            opcache_reset();
        } catch (\Exception $e) { }
    }

    public static function update()
    {
        $current_plugin_version = MnetOptions::getOption('PLUGIN_VERSION');
        if (\is_admin() && MNET_PLUGIN_VERSION != $current_plugin_version) {
            MnetDbManager::createUpdateDatabase();
            MnetOptions::saveOption('PLUGIN_VERSION', MNET_PLUGIN_VERSION);
            MnetLogManager::logWPDetails();
            MnetDbManager::checkTablesSchema();
            if (!empty(\mnet_user()->token)) {
                MnetAdTag::fetchAdTags(\mnet_user()->token, false);
            }
        }
    }

    public static function run()
    {
        // Verify plugin configuration
        \add_action('wp_ajax_mnet_database_check', array('Mnet\MnetDbManager', 'checkDatabaseConfiguration'));

        // Admin page setup
        // add_action('init', array('MnetSessionManager', 'start'));
        \add_action('admin_menu', array('Mnet\MnetAdminPageManager', 'createAdminPage'));
        \add_action('admin_head', array('Mnet\MnetAdminPageManager', 'injectAdminGlobalStyles'));
        \add_action('admin_enqueue_scripts', array('Mnet\MnetAdminPageManager', 'enqueueScripts'));
        \add_action('admin_enqueue_scripts', array('Mnet\MnetAdminPageManager', 'enqueueScriptsLate'), 99999);

        // Admin Ajax call handlers
        \add_action('wp_ajax_mnet_admin_ui_error', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'logAdminPageError'));
        // Notices
        \add_action('wp_ajax_mnet_get_notices', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'getAllNotices'));
        \add_action('wp_ajax_mnet_dismiss_notice', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'dismissNotice'));

        // User Handlers
        \add_action('wp_ajax_mnet_auth_user', array('Mnet\Admin\MnetAuthManager', 'authenticateUser'));
        \add_action('wp_ajax_mnet_get_customer_name', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'getCustomerName'));
        \add_action('wp_ajax_mnet_logout', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'logout'));
        \add_action('wp_ajax_mnet_api_logout', array('Mnet\Admin\MnetAuthManager', 'logout'));
        \add_action('wp_ajax_mnet_forgot_password', array('Mnet\Admin\MnetPluginUtils', 'forgotPassword'));

        // Slot configurations
        \add_action('wp_ajax_mnet_get_no_of_ad_slots', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'getNoOfAdSlots'));
        \add_action('wp_ajax_mnet_remove_all_slot', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'removeAllSlots'));

        // Basic configurations
        \add_action('wp_ajax_mnet_get_ad_slots', array('Mnet\Admin\MnetAdBasicConfiguration', 'getAdSlots'));
        \add_action('wp_ajax_mnet_save_ad_slots', array('Mnet\Admin\MnetAdBasicConfiguration', 'saveAdSlots'));

        // Advance configurations
        \add_action('wp_ajax_mnet_get_ad_data', array('Mnet\Admin\MnetAdAdvanceConfiguration', 'getAdData'));
        \add_action('wp_ajax_mnet_save_ad_slot', array('Mnet\Admin\MnetAdAdvanceConfiguration', 'saveAdSlot'));
        \add_action('wp_ajax_mnet_remove_ad_slot', array('Mnet\Admin\MnetAdAdvanceConfiguration', 'removeAdSlot'));

        // Ad Tags
        \add_action('wp_ajax_mnet_refresh_ad_tags', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'refreshAdtags'));
        \add_action('wp_ajax_mnet_get_ad_tags', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'getAdTags'));

        // Dashboard - Reports
        \add_action('wp_ajax_mnet_fetch_report', array('Mnet\Admin\MnetReportManager', 'getReport'));
        \add_action('wp_ajax_mnet_dashboard_revenue_details', array('Mnet\Admin\MnetReportManager', 'getDashboardRevenueAndHeaderStats'));
        \add_action('wp_ajax_mnet_last_audited_date', array('Mnet\Admin\MnetReportManager', 'fetchLastAuditedDate'));

        // Mail
        \add_action('wp_ajax_mnet_send_feedback_mail', array('Mnet\Admin\MnetPluginUtils', 'sendTroubleFeedbackMail'));
        \add_action('wp_ajax_mnet_get_max_upload_size', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'getUploadMaxSize'));
        \add_action('wp_ajax_mnet_send_mail', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'sendMail'));
        \add_action('wp_mail_failed', 'action_wp_mail_failed', 10, 1);

        // URL blocking
        \add_action('wp_ajax_mnet_get_urls', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'getBlockedAndAllUrls'));
        \add_action('wp_ajax_mnet_get_page_urls', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'getPageBlockedAndAllUrls'));
        \add_action('wp_ajax_mnet_block_urls', array('Mnet\Admin\MnetAdHandleAjaxCalls', 'blockUrls'));

        // FAQs
        \add_action('wp_ajax_mnet_get_faqs', array('Mnet\Admin\MnetFaqs', 'index'));
        \add_action('wp_ajax_mnet_get_troubleshoot_faqs', array('Mnet\Admin\MnetFaqs', 'getTroubleshootFaqs'));

        // Adstxt
        \add_action('wp_ajax_mnet_get_adstxt_details', array('Mnet\Admin\MnetAdstxtManager', 'getAdstxtDetails'));

        // Public hooks
        // inject media.net's script in head
        \add_action('wp_head', array('Mnet\PublicViews\MnetAdPublicHooks', 'publicInjectInHead'));

        \add_action('loop_start', array('Mnet\PublicViews\MnetAdPublicHooks', 'publicInjectLoopStart'));
        \add_action('loop_end', array('Mnet\PublicViews\MnetAdPublicHooks', 'publicInjectLoopEnd'));

        \add_action('the_post', array('Mnet\PublicViews\MnetAdPublicHooks', 'publicInjectBetweenPosts'));
        \add_action('wp_enqueue_scripts', array('Mnet\PublicViews\MnetAdPublicHooks', 'enqueueScripts'));
        \add_filter('the_content', array('Mnet\PublicViews\MnetAdPublicHooks', 'mnetPublicInjectInContent'));

        // Sidebar widgets
        \add_action('widgets_init', array('Mnet\PublicViews\MnetAdPublicHooks', 'mnet_widgets_init'));

        //adunits 
        \add_action('wp_ajax_mnet_get_ad_units', array('Mnet\Admin\MnetAdUnits', 'getAdUnits'));
        \add_action('wp_ajax_mnet_create_ad_unit', array('Mnet\Admin\MnetAdUnits', 'createAdUnit'));
        \add_action('wp_ajax_mnet_get_adunit_sizes', array('Mnet\Admin\MnetAdUnits', 'getAdUnitSizes'));
        \add_action('wp_ajax_mnet_get_slots_data_for_adunits', array('Mnet\Admin\MnetAdUnits', 'getSlotsData'));

        // Login page
        \add_action('wp_ajax_mnet_get_encryption_key', array('Mnet\Admin\MnetAuthManager', 'getEncryptionKey'));
    }

    public static function action_wp_mail_failed($wp_error)
    {
        return error_log("Mail sending error: " . print_r($wp_error, true));
    }
}
