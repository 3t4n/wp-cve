<?php
/**
 * @package talentlms-wordpress
 */

namespace TalentlmsIntegration\Pages;

use TalentLMS_ApiError;
use TalentlmsIntegration\Helpers\TalentLMSApiIntegrationHelper;
use TalentlmsIntegration\Services\PluginService;
use TalentlmsIntegration\Utils;

class Errors implements PluginService
{
    use TalentLMSApiIntegrationHelper;
    public $talentlmsAdminErrors = array();  // Stores all the errors that need to be displayed to the admin.
    public $screen_id;

    public function register(): void
    {
        add_action(
            'admin_notices',
            array( $this, 'tlms_showWarnings' )
        );
    }

    /**
     * Logs the error and stores it, so it can be displayed to the admin.
     *
     * @param string $message
     */
    public function tlms_logError(string $message): void
    {
        $this->talentlmsAdminErrors[] = $message;
        Utils::tlms_recordLog($message);
    }

    /**
     * Used to display the stored errors to the admin.
     *
     * @return void
     */
    public function tlms_showWarnings(): void
    {
        if (( defined('DOING_AJAX') && DOING_AJAX )
            || ! is_admin()
        ) {
            die();
        }

        $screen_id = get_current_screen()->id;

        if ($screen_id === 'toplevel_page_talentlms'
            || $screen_id == 'talentlms_page_talentlms-setup'
            || $screen_id == 'talentlms_page_talentlms-integrations'
        ) {
            $this->tlms_displayErrors();
        }

        if (! empty($this->talentlmsAdminErrors)) {
            foreach ($this->talentlmsAdminErrors as $message) {
                echo '<div class="error notice is-dismissible">' . wp_kses($message, array('strong'=>array()), array('http', 'https')) . '</div>';
            }
        }
    }

    /**
     * Show warnings (only on Dashboard, Setup and Integrations pages)
     * @return void
     */
    public function tlms_ManualShowWarnings()
    {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
        $screen_id = get_current_screen()->id;

        if (($screen_id === 'toplevel_page_talentlms'
            || $screen_id == 'talentlms_page_talentlms-setup'
            || $screen_id == 'talentlms_page_talentlms-integrations')) {
            if (!empty($this->talentlmsAdminErrors)) {
                foreach ($this->talentlmsAdminErrors as $message) {
                    echo '<div class="error notice is-dismissible">' . wp_kses($message, array('strong' => array()), array('http', 'https')) . '</div>';
                }
            }
        }
    }

    /**
     * @throws TalentLMS_ApiError
     */
    public function tlms_displayErrors(): void
    {
        if ((
                empty($_POST['tlms-domain'])
                && empty($_POST['tlms-apikey'])
            )
            &&
            (
                ! get_option('tlms-domain')
                && ! get_option('tlms-apikey')
            )
        ) {
            $this->tlms_logError(
                '<p><strong>'
                . esc_html__('You need to specify a TalentLMS domain and a TalentLMS API key.', 'talentlms')
                . '</strong>'
                . sprintf(
                    __('You must <a href="%1$s">enter your domain and API key</a> for it to work.', 'talentlms'),
                    esc_url(admin_url('admin.php?page=talentlms-setup'))
                )
                . '</p>'
            );
        } else {
            $this->enableTalentLMSLib();
        }
    }
}
