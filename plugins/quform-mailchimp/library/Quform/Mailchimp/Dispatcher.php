<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Mailchimp_Dispatcher
{
    /**
     * @param Quform_Container $container
     */
    public function __construct(Quform_Container $container)
    {
        if (defined('QUFORM_VERSION') && version_compare(QUFORM_VERSION, '2.6.0', '<')) {
            add_action('admin_notices', array($this, 'showUpdateQuformNotice'));
            return;
        }

        add_action('init', array($container['mailchimpUpgrader'], 'upgradeCheck'), 1);

        add_filter('quform_post_process', array($container['mailchimpIntegrationController'], 'process'), 10, 2);

        if (is_admin() || defined('QUFORM_TESTING')) {
            // Menus and pages
            add_action('quform_admin_menu', array($container['mailchimpAdminPageController'], 'createMenus'));
            add_action('quform_admin_menu_icon_color', array($container['mailchimpAdminPageController'], 'getMenuIconColor'));
            add_action('current_screen', array($container['mailchimpAdminPageController'], 'process'));
            add_filter('admin_title', array($container['mailchimpAdminPageController'], 'setAdminTitle'));
            add_filter('admin_body_class', array($container['mailchimpAdminPageController'], 'addBodyClass'));
            add_action('admin_enqueue_scripts', array($container['mailchimpAdminPageController'], 'enqueueAssets'));
            add_filter('quform_mailchimp_mdi_icon_prefix', array($container['mailchimpAdminPageController'], 'mdiIconPrefix'));

            // Integrations
            add_action('wp_ajax_quform_mc_save_integrations_table_settings', array($container['mailchimpIntegrationsListSettings'], 'save'));
            add_action('wp_ajax_quform_mc_add_integration', array($container['mailchimpIntegrationBuilder'], 'add'));
            add_action('wp_ajax_quform_mc_save_integration', array($container['mailchimpIntegrationBuilder'], 'save'));
            add_action('wp_ajax_quform_mc_get_lists', array($container['mailchimpIntegrationBuilder'], 'getLists'));
            add_action('wp_ajax_quform_mc_get_form_email_elements', array($container['mailchimpIntegrationBuilder'], 'getEmailElements'));
            add_action('wp_ajax_quform_mc_get_merge_fields', array($container['mailchimpIntegrationBuilder'], 'getMergeFields'));
            add_action('wp_ajax_quform_mc_get_groups', array($container['mailchimpIntegrationBuilder'], 'getGroups'));
            add_action('wp_ajax_quform_mc_get_logic_sources', array($container['mailchimpIntegrationBuilder'], 'getLogicSources'));

            // Settings
            add_action('wp_ajax_quform_mc_verify_api_key', array($container['mailchimpSettings'], 'verifyApiKey'));
            add_action('wp_ajax_quform_mc_save_settings', array($container['mailchimpSettings'], 'saveSettings'));
            add_action('wp_ajax_quform_mc_uninstall_plugin', array($container['mailchimpUninstaller'], 'uninstall'));
        }
    }

    /**
     * Show an admin notice if the Quform plugin is not compatible with this add-on
     */
    public function showUpdateQuformNotice()
    {
        printf(
            '<div class="notice notice-error"><p>%s</p></div>',
            esc_html__('Please update the Quform plugin to version 2.6.0 or later to use the Quform Mailchimp add-on.', 'quform-mailchimp')
        );
    }
}
