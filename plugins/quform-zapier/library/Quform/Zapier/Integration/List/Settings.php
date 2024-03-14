<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier_Integration_List_Settings
{
    /**
     * Hook entry point for saving the integration list settings
     */
    public function save()
    {
        $this->validateSaveRequest();
        $this->handleSaveRequest();
    }

    /**
     * Validate the request for saving the integration list settings
     */
    protected function validateSaveRequest()
    {
        if ( ! Quform::isPostRequest() || ! isset($_POST['per_page']) || ! is_string($_POST['per_page'])) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        if ( ! check_ajax_referer('quform_zapier_save_integrations_table_settings', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-zapier')
            ));
        }
    }

    /**
     * Handle the request for saving forms list settings
     */
    protected function handleSaveRequest()
    {
        if ( $_POST['per_page'] === '') {
            wp_send_json(array(
                'type' => 'error',
                'errors' => array('qfb_zapier_integrations_per_page' => __('This field is required', 'quform-zapier'))
            ));
        }

        if ( ! is_numeric($_POST['per_page'])) {
            wp_send_json(array(
                'type' => 'error',
                'errors' => array('qfb_zapier_integrations_per_page' => __('Value must be numeric', 'quform-zapier'))
            ));
        }

        $perPage = (int) $_POST['per_page'];

        if ($perPage < 1) {
            wp_send_json(array(
                'type' => 'error',
                'errors' => array('qfb_zapier_integrations_per_page' => __('Value must be greater than 1', 'quform-zapier'))
            ));
        }

        if ($perPage > 1000000) {
            wp_send_json(array(
                'type' => 'error',
                'errors' => array('qfb_zapier_integrations_per_page' => __('Value must be less than 1000000', 'quform-zapier'))
            ));
        }

        update_user_meta(get_current_user_id(), 'quform_zapier_integrations_per_page', $perPage);

        wp_send_json(array(
            'type' => 'success'
        ));
    }
}

