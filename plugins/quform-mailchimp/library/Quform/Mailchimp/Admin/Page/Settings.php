<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Admin_Page_Settings extends Quform_Mailchimp_Admin_Page
{
    /**
     * @var Quform_Mailchimp_Options
     */
    protected $options;

    /**
     * @var Quform_Mailchimp_Permissions
     */
    protected $permissions;

    /**
     * @param  Quform_ViewFactory            $viewFactory
     * @param  Quform_Repository             $repository
     * @param  Quform_Mailchimp_Options      $options
     * @param  Quform_Mailchimp_Permissions  $permissions
     */
    public function __construct(
        Quform_ViewFactory $viewFactory,
        Quform_Repository $repository,
        Quform_Mailchimp_Options $options,
        Quform_Mailchimp_Permissions $permissions
    ) {
        parent::__construct($viewFactory, $repository);

        $this->options = $options;
        $this->permissions = $permissions;
    }

    protected function enqueueStyles()
    {
        parent::enqueueStyles();

        wp_enqueue_style('quform-mailchimp-admin', Quform_Mailchimp::adminUrl('css/admin.min.css'), array(), QUFORM_MAILCHIMP_VERSION, 'all');
    }

    protected function enqueueScripts()
    {
        parent::enqueueScripts();

        wp_enqueue_script('quform-mc-settings', Quform_Mailchimp::adminUrl('js/settings.min.js'), array('jquery'), QUFORM_MAILCHIMP_VERSION, true);

        wp_localize_script('quform-mc-settings', 'quformMailchimpSettingsL10n', array(
            'saveSettingsNonce' => wp_create_nonce('quform_mc_save_settings'),
            'settingsSaved' => __('Settings saved', 'quform-mailchimp'),
            'errorSavingSettings' => __('An error occurred saving the settings.', 'quform-mailchimp'),
            'waitVerifying' => __('Please wait, verification in progress', 'quform-mailchimp'),
            'verifyApiKeyNonce' => wp_create_nonce('quform_mc_verify_api_key'),
            'errorVerifying' => __('An error occurred verifying the license key.', 'quform-mailchimp'),
            'verified' => __('Verified', 'quform-mailchimp'),
            'unverified' => __('Unverified', 'quform-mailchimp'),
            'uninstallAreYouSure' => __('Are you sure you want to uninstall the Quform Mailchimp plugin?', 'quform-mailchimp'),
            'uninstallPluginNonce' => wp_create_nonce('quform_mc_uninstall_plugin'),
            'pluginsUrl' => self_admin_url('plugins.php?deactivate=true'),
            'errorUninstalling' => __('An error occurred uninstalling the plugin.', 'quform-mailchimp')
        ));
    }

    public function init()
    {
        $this->template = QUFORM_MAILCHIMP_TEMPLATE_PATH . '/admin/settings.php';
    }

    /**
     * Process this page and send data to the view
     */
    public function process()
    {
        if ( ! current_user_can('quform_mailchimp_settings')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'quform-mailchimp'), 403);
        }

        $this->view->with(array(
            'options' => $this->options,
            'roles' => get_editable_roles(),
            'caps' => $this->permissions->getAllCapabilitiesWithDescriptions(),
            'mdiPrefix' => apply_filters('quform_mailchimp_mdi_icon_prefix', 'qfb-mdi'),
        ));
    }

    /**
     * Set the page title
     *
     * @return string
     */
    protected function getAdminTitle()
    {
        return __('Mailchimp Settings', 'quform-mailchimp');
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
        $extra[40] = sprintf('<div class="qfb-nav-item qfb-nav-page-info"><i class="qfb-nav-page-icon qfb-nav-mailchimp-icon"></i><span class="qfb-nav-page-title">%s</span></div>', esc_html__('Mailchimp Settings', 'quform-mailchimp'));

        return parent::getNavHtml($currentForm, $extra);
    }
}
