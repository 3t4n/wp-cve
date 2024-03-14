<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier_Container
{
    public function __construct(Quform_Container $container)
    {
        $container['zapierAdminPageFactory'] = new JuiceDefinition('Quform_Zapier_Admin_Page_Factory', array('@viewFactory', '@zapierIntegrationRepository', '@repository', '@options', '@formFactory', '@builder', '@zapierOptions', '@zapierIntegrationBuilder', '@zapierPermissions'));
        $container['zapierAdminPageController'] = new JuiceDefinition('Quform_Zapier_Admin_Page_Controller', array('@zapierAdminPageFactory'));
        $container['zapierIntegrationRepository'] = new JuiceDefinition('Quform_Zapier_Integration_Repository', array('@repository'));
        $container['zapierUpgrader'] = new JuiceDefinition('Quform_Zapier_Upgrader', array('@zapierIntegrationRepository', '@zapierPermissions'));
        $container['zapierPermissions'] = new JuiceDefinition('Quform_Zapier_Permissions');
        $container['zapierIntegrationBuilder'] = new JuiceDefinition('Quform_Zapier_Integration_Builder', array('@zapierIntegrationRepository', '@zapierOptions', '@repository', '@formFactory'));
        $container['zapierIntegrationController'] = new JuiceDefinition('Quform_Zapier_Integration_Controller', array('@zapierIntegrationRepository', '@zapierIntegrationFactory', '@zapierOptions'));
        $container['zapierSettings'] = new JuiceDefinition('Quform_Zapier_Settings', array('@zapierOptions', '@zapierPermissions'));
        $container['zapierOptions'] = new JuiceDefinition('Quform_Zapier_Options', array('quform_zapier_options'));
        $container['zapierIntegrationFactory'] = new JuiceDefinition('Quform_Zapier_Integration_Factory', array('@zapierOptions'));
        $container['zapierIntegrationsListSettings'] = new JuiceDefinition('Quform_Zapier_Integration_List_Settings');
        $container['zapierUninstaller'] = new JuiceDefinition('Quform_Zapier_Uninstaller', array('@zapierOptions', '@zapierPermissions', '@zapierUpgrader', '@zapierIntegrationRepository'));
    }
}
