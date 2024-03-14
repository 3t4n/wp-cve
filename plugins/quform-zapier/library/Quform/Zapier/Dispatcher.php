<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier_Dispatcher
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

        add_action('init', array($container['zapierUpgrader'], 'upgradeCheck'), 1);

        add_filter(apply_filters('quform_zapier_processor_hook', 'quform_post_process'), array($container['zapierIntegrationController'], 'process'), 10, 2);

        if (is_admin() || defined('QUFORM_TESTING')) {
            // Menus and pages
            add_action('quform_admin_menu', array($container['zapierAdminPageController'], 'createMenus'));
            add_action('quform_admin_menu_icon_color', array($container['zapierAdminPageController'], 'getMenuIconColor'));
            add_action('current_screen', array($container['zapierAdminPageController'], 'process'));
            add_filter('admin_title', array($container['zapierAdminPageController'], 'setAdminTitle'));
            add_filter('admin_body_class', array($container['zapierAdminPageController'], 'addBodyClass'));
            add_action('admin_enqueue_scripts', array($container['zapierAdminPageController'], 'enqueueAssets'));
            add_filter('quform_zapier_mdi_icon_prefix', array($container['zapierAdminPageController'], 'mdiIconPrefix'));

            // Integrations
            add_action('wp_ajax_quform_zapier_save_integrations_table_settings', array($container['zapierIntegrationsListSettings'], 'save'));
            add_action('wp_ajax_quform_zapier_add_integration', array($container['zapierIntegrationBuilder'], 'add'));
            add_action('wp_ajax_quform_zapier_save_integration', array($container['zapierIntegrationBuilder'], 'save'));
            add_action('wp_ajax_quform_zapier_get_additional_field_elements', array($container['zapierIntegrationBuilder'], 'getAdditionalFieldElements'));
            add_action('wp_ajax_quform_zapier_get_logic_sources', array($container['zapierIntegrationBuilder'], 'getLogicSources'));

            // Settings
            add_action('wp_ajax_quform_zapier_save_settings', array($container['zapierSettings'], 'saveSettings'));
            add_action('wp_ajax_quform_zapier_uninstall_plugin', array($container['zapierUninstaller'], 'uninstall'));
        }
    }

    /**
     * Show an admin notice if the Quform plugin is not compatible with this add-on
     */
    public function showUpdateQuformNotice()
    {
        printf(
            '<div class="notice notice-error"><p>%s</p></div>',
            esc_html__('Please update the Quform plugin to version 2.6.0 or later to use the Quform Zapier add-on.', 'quform-zapier')
        );
    }
}
