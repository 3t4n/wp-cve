<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Admin_Page_Settings extends Quform_Zapier_Admin_Page
{
    /**
     * @var Quform_Zapier_Options
     */
    protected $options;

    /**
     * @var Quform_Zapier_Permissions
     */
    protected $permissions;

    /**
     * @param  Quform_ViewFactory            $viewFactory
     * @param  Quform_Repository             $repository
     * @param  Quform_Zapier_Options      $options
     * @param  Quform_Zapier_Permissions  $permissions
     */
    public function __construct(
        Quform_ViewFactory $viewFactory,
        Quform_Repository $repository,
        Quform_Zapier_Options $options,
        Quform_Zapier_Permissions $permissions
    ) {
        parent::__construct($viewFactory, $repository);

        $this->options = $options;
        $this->permissions = $permissions;
    }

    protected function enqueueStyles()
    {
        parent::enqueueStyles();

        wp_enqueue_style('quform-zapier-admin', Quform_Zapier::adminUrl('css/admin.min.css'), array(), QUFORM_ZAPIER_VERSION, 'all');
    }

    protected function enqueueScripts()
    {
        parent::enqueueScripts();

        wp_enqueue_script('quform-zapier-settings', Quform_Zapier::adminUrl('js/settings.min.js'), array('jquery'), QUFORM_ZAPIER_VERSION, true);

        wp_localize_script('quform-zapier-settings', 'quformZapierSettingsL10n', array(
            'saveSettingsNonce' => wp_create_nonce('quform_zapier_save_settings'),
            'settingsSaved' => __('Settings saved', 'quform-zapier'),
            'errorSavingSettings' => __('An error occurred saving the settings.', 'quform-zapier'),
            'uninstallAreYouSure' => __('Are you sure you want to uninstall the Quform Zapier plugin?', 'quform-zapier'),
            'uninstallPluginNonce' => wp_create_nonce('quform_zapier_uninstall_plugin'),
            'pluginsUrl' => self_admin_url('plugins.php?deactivate=true'),
            'errorUninstalling' => __('An error occurred uninstalling the plugin.', 'quform-zapier')
        ));
    }

    public function init()
    {
        $this->template = QUFORM_ZAPIER_TEMPLATE_PATH . '/admin/settings.php';
    }

    public function process()
    {
        if ( ! current_user_can('quform_zapier_settings')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'quform-zapier'), 403);
        }

        $this->view->with(array(
            'options' => $this->options,
            'roles' => get_editable_roles(),
            'caps' => $this->permissions->getAllCapabilitiesWithDescriptions(),
            'mdiPrefix' => apply_filters('quform_zapier_mdi_icon_prefix', 'qfb-mdi'),
        ));
    }

    /**
     * Set the page title
     *
     * @return string
     */
    protected function getAdminTitle()
    {
        return __('Zapier Settings', 'quform-zapier');
    }

    /**
     * Get the HTML for the admin navigation menu
     *
     * @param   array|null  $currentForm  The data for the current form (if any)
     * @param   array       $extra        Extra HTML to add to the nav, the array key is the hook position
     * @return  string
     */
    public function getNavHtml(array $currentForm = null, array $extra = array())
    {
        $extra[40] = sprintf('<div class="qfb-nav-item qfb-nav-page-info"><i class="qfb-nav-page-icon qfb-nav-zapier-icon"></i><span class="qfb-nav-page-title">%s</span></div>', esc_html__('Zapier Settings', 'quform-zapier'));

        return parent::getNavHtml($currentForm, $extra);
    }
}
