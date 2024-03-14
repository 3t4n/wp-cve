<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Settings
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
     * @param  Quform_Mailchimp_Options      $options
     * @param  Quform_Mailchimp_Permissions  $permissions
     */
    public function __construct(Quform_Mailchimp_Options $options, Quform_Mailchimp_Permissions $permissions)
    {
        $this->options = $options;
        $this->permissions = $permissions;
    }

    /**
     * Handle the request to verify the Mailchimp API key
     */
    public function verifyApiKey()
    {
        $this->validateVerifyApiKeyRequest();
        $apiKey = sanitize_text_field($_POST['api_key']);

        $api = new Quform_Mailchimp_Client($apiKey);
        $response = $api->get('lists');

        if (is_wp_error($response)) {
            $this->options->set('apiKey', '');

            wp_send_json(array(
                'type' => 'error',
                'message' => $response->get_error_message()
            ));
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code == 200 && Quform::isNonEmptyString($body)) {
            $this->options->set('apiKey', $apiKey);

            wp_send_json(array(
                'type' => 'success',
                'message' => __('The API key was successfully verified', 'quform-mailchimp')
            ));
        }

        if (Quform::isNonEmptyString($body)) {
            $data = json_decode($body, true);

            if (is_array($data) && isset($data['detail']) && Quform::isNonEmptyString($data['detail'])) {
                $this->options->set('apiKey', '');

                wp_send_json(array(
                    'type' => 'invalid',
                    'message' => $data['detail']
                ));
            }
        }

        $this->options->set('apiKey', '');

        wp_send_json(array(
            'type' => 'error',
            'message' => __('An error occurred verifying the API key', 'quform-mailchimp')
        ));
    }

    /**
     * Validate the request to verify the API key
     */
    protected function validateVerifyApiKeyRequest()
    {
        if ( ! Quform::isPostRequest() || ! isset($_POST['api_key']) || ! is_string($_POST['api_key'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_settings')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }

        if ( ! check_ajax_referer('quform_mc_verify_api_key', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-mailchimp')
            ));
        }
    }

    /**
     * Handle the request to save the plugins settings
     */
    public function saveSettings()
    {
        $this->validateSaveSettingsRequest();

        $options = json_decode(stripslashes($_POST['options']), true);

        if ( ! is_array($options)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
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
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_settings')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }

        if ( ! check_ajax_referer('quform_mc_save_settings', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-mailchimp')
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
