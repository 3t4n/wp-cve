<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Settings
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
     * @param  Quform_Zapier_Options      $options
     * @param  Quform_Zapier_Permissions  $permissions
     */
    public function __construct(Quform_Zapier_Options $options, Quform_Zapier_Permissions $permissions)
    {
        $this->options = $options;
        $this->permissions = $permissions;
    }

    /**
     * Handle the request to save the plugins settings
     */
    public function saveSettings()
    {
        $this->validateSaveSettingsRequest();

        $options = json_decode(wp_unslash($_POST['options']), true);

        if ( ! is_array($options)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        $options = $this->sanitizeOptions($options);

        if (array_key_exists('permissions', $options)) {
            if (is_array($options['permissions'])) {
                $this->permissions->update($options['permissions']);
            }

            unset($options['permissions']);
        }

        $this->options->set($options);

        wp_send_json(array(
            'type'    => 'success'
        ));
    }

    /**
     * Validate the request to save the plugins settings
     */
    protected function validateSaveSettingsRequest()
    {
        if ( ! Quform::isPostRequest() || ! isset($_POST['options']) || ! is_string($_POST['options'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        if ( ! current_user_can('quform_zapier_settings')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-zapier')
            ));
        }

        if ( ! check_ajax_referer('quform_zapier_save_settings', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-zapier')
            ));
        }
    }

    /**
     * Sanitize the given options and return them
     *
     * @param   array  $options
     * @return  array  $options
     */
    protected function sanitizeOptions(array $options)
    {
        if (isset($options['enabled'])) {
            $options['enabled'] = is_bool($options['enabled']) ? $options['enabled'] : true;
        }

        return $options;
    }
}
