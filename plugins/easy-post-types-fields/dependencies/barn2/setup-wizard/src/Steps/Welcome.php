<?php

/**
 * @package   Barn2\setup-wizard
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 */
namespace Barn2\Plugin\Easy_Post_Types_Fields\Dependencies\Setup_Wizard\Steps;

use Barn2\Plugin\Easy_Post_Types_Fields\Dependencies\Setup_Wizard\Step;
use Barn2\Plugin\Easy_Post_Types_Fields\Dependencies\Setup_Wizard\Util;
/**
 * Handles the welcome step of the wizard.
 * Displays a license validation field and validates the license.
 */
class Welcome extends Step
{
    /**
     * Initialize the step.
     */
    public function __construct()
    {
        $this->set_id('license_activation');
        $this->set_name(esc_html__('Welcome', 'barn2-setup-wizard'));
    }
    /**
     * {@inheritdoc}
     */
    public function setup_fields()
    {
        $fields = [];
        $fields['license_key'] = ['type' => 'license', 'label' => esc_html__('License key', 'barn2-setup-wizard'), 'description' => esc_html__('Enter your license key to start using the plugin.', 'barn2-setup-wizard'), 'tooltip' => esc_html__('The licence key is contained in your order confirmation email.', 'barn2-setup-wizard')];
        return $fields;
    }
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();
        \add_action("wp_ajax_barn2_wizard_{$this->get_plugin()->get_slug()}_get_license_details", [$this, 'get_license_details']);
    }
    /**
     * Get license details via ajax.
     *
     * @return void
     */
    public function get_license_details()
    {
        \check_ajax_referer('barn2_setup_wizard_nonce', 'nonce');
        if (!\current_user_can('manage_options')) {
            $this->send_error(__('You are not allowed to retrieve license details.', 'barn2-setup-wizard'));
        }
        $wizard = $this->get_wizard();
        \wp_send_json_success(['license_key' => $wizard->get_licensing()->get_license_key(), 'license_status' => $wizard->get_licensing()->get_status(), 'license_status_text' => $wizard->get_licensing()->get_status_help_text()]);
    }
    /**
     * {@inheritdoc}
     */
    public function submit()
    {
        \check_ajax_referer('barn2_setup_wizard_nonce', 'nonce');
        if (!\current_user_can('manage_options')) {
            $this->send_error(__('You are not allowed to validate your license.', 'barn2-setup-wizard'));
        }
        $license_key = isset($_POST['license_key']) && !empty($_POST['license_key']) ? \sanitize_text_field($_POST['license_key']) : \false;
        if (!$license_key) {
            $this->send_error(esc_html__('Please enter a license key.', 'barn2-setup-wizard'));
        }
        $license_handler = $this->get_wizard()->get_licensing();
        $success = \false;
        $type_of_request = isset($_POST['license_action']) && !empty($_POST['license_action']) ? \sanitize_text_field($_POST['license_action']) : 'activate';
        if ($type_of_request === 'activate') {
            $success = $license_handler->activate($license_key);
        } elseif ($type_of_request === 'deactivate') {
            $success = $license_handler->deactivate();
        } elseif ($type_of_request === 'check') {
            $license_handler->refresh();
            $success = \true;
        }
        // Check if the license is an access pass.
        $is_access_pass = Util::license_is_access_pass($this->get_plugin(), $license_key);
        // Delete upsells cache each time the license is processed.
        \delete_transient("barn2_wizard_{$this->get_plugin()->get_slug()}_upsells");
        if ($success) {
            \wp_send_json_success(['status_text' => $license_handler->get_status_help_text(), 'status' => $license_handler->get_status(), 'is_access_pass' => $is_access_pass]);
        } else {
            \wp_send_json_error(['error_message' => $license_handler->get_status_help_text(), 'status' => $license_handler->get_status()], 403);
        }
    }
}
