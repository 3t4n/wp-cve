<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier_Uninstaller
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
     * @var Quform_Zapier_Upgrader
     */
    protected $upgrader;

    /**
     * @var Quform_Zapier_Integration_Repository
     */
    protected $repository;

    /**
     * @param  Quform_Zapier_Options                 $options
     * @param  Quform_Zapier_Permissions             $permissions
     * @param  Quform_Zapier_Upgrader                $upgrader
     * @param  Quform_Zapier_Integration_Repository  $repository
     */
    public function __construct(
        Quform_Zapier_Options $options,
        Quform_Zapier_Permissions $permissions,
        Quform_Zapier_Upgrader $upgrader,
        Quform_Zapier_Integration_Repository $repository
    ) {
        $this->options = $options;
        $this->permissions = $permissions;
        $this->upgrader = $upgrader;
        $this->repository = $repository;
    }

    /**
     * Handle the Ajax request to uninstall the plugin
     */
    public function uninstall()
    {
        $this->validateUninstallRequest();

        deactivate_plugins(QUFORM_ZAPIER_BASENAME);

        $this->options->uninstall();
        $this->permissions->uninstall();
        $this->upgrader->uninstall();
        $this->repository->uninstall();

        do_action('quform_zapier_uninstall');

        wp_send_json(array('type' => 'success'));
    }

    /**
     * Validate the Ajax request to uninstall the plugin
     */
    protected function validateUninstallRequest()
    {
        if ( ! Quform::isPostRequest()) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        if ( ! current_user_can('activate_plugins')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-zapier')
            ));
        }

        if ( ! check_ajax_referer('quform_zapier_uninstall_plugin', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-zapier')
            ));
        }
    }
}
