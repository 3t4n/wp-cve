<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Mailchimp_Container
{
    public function __construct(Quform_Container $container)
    {
        $container['mailchimpAdminPageFactory'] = new JuiceDefinition('Quform_Mailchimp_Admin_Page_Factory', array('@viewFactory', '@mailchimpIntegrationRepository', '@repository', '@options', '@formFactory', '@builder', '@mailchimpOptions', '@mailchimpIntegrationBuilder', '@mailchimpPermissions'));
        $container['mailchimpAdminPageController'] = new JuiceDefinition('Quform_Mailchimp_Admin_Page_Controller', array('@mailchimpAdminPageFactory'));
        $container['mailchimpIntegrationRepository'] = new JuiceDefinition('Quform_Mailchimp_Integration_Repository', array('@repository'));
        $container['mailchimpUpgrader'] = new JuiceDefinition('Quform_Mailchimp_Upgrader', array('@mailchimpIntegrationRepository', '@mailchimpPermissions'));
        $container['mailchimpPermissions'] = new JuiceDefinition('Quform_Mailchimp_Permissions');
        $container['mailchimpIntegrationBuilder'] = new JuiceDefinition('Quform_Mailchimp_Integration_Builder', array('@mailchimpIntegrationRepository', '@mailchimpOptions', '@repository', '@formFactory'));
        $container['mailchimpIntegrationController'] = new JuiceDefinition('Quform_Mailchimp_Integration_Controller', array('@mailchimpIntegrationRepository', '@mailchimpIntegrationFactory', '@mailchimpOptions'));
        $container['mailchimpSettings'] = new JuiceDefinition('Quform_Mailchimp_Settings', array('@mailchimpOptions', '@mailchimpPermissions'));
        $container['mailchimpOptions'] = new JuiceDefinition('Quform_Mailchimp_Options', array('quform_mailchimp_options'));
        $container['mailchimpIntegrationFactory'] = new JuiceDefinition('Quform_Mailchimp_Integration_Factory', array('@mailchimpOptions'));
        $container['mailchimpIntegrationsListSettings'] = new JuiceDefinition('Quform_Mailchimp_Integration_List_Settings');
        $container['mailchimpUninstaller'] = new JuiceDefinition('Quform_Mailchimp_Uninstaller', array('@mailchimpOptions', '@mailchimpPermissions', '@mailchimpUpgrader', '@mailchimpIntegrationRepository'));
    }
}
