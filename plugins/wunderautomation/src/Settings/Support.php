<?php

namespace WunderAuto\Settings;

use WP_Debug_Data;

/**
 * Class GeneralSettings
 */
class Support extends BaseSettings
{
    /**
     * @var array<string, string>
     */
    private $result = [];

    /**
     * @var int
     */
    private $sanitizeCount = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id        = 'wunderauto-support';
        $this->caption   = 'Support';
        $this->sortOrder = 99;
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register()
    {
        $this->registerTab();
        $this->addSection('get-support', __('Get support', 'wunderauto'));
    }

    /**
     * Sanitize user input
     *
     * @return array<string, string|int>
     */
    public function sanitize()
    {
        $this->sanitizeCount++;
        $includeDiagnostics = (bool)($_REQUEST['include']);
        if ($this->sanitizeCount < 2) {
            $args = [
                'body' => [
                    'email'       => sanitize_email($_REQUEST['email']),
                    'subject'     => sanitize_text_field($_REQUEST['subject']),
                    'message'     => sanitize_textarea_field($_REQUEST['message']),
                    'diagnostics' => $includeDiagnostics ? base64_encode($this->getDiagnostics()) : '',
                ],
            ];

            $url      = WUNDERAUTO_UPDATE_URL . '/support.php';
            $response = wp_remote_post($url, $args);

            if ($response instanceof \WP_Error) {
                $status   = 'FAIL';
                $ticketId = -1;
                $message  = 'Unknown error';
            } else {
                $response = json_decode($response['body']);
                $status   = isset($response->status) ? $response->status : 'FAIL';
                $ticketId = isset($response->ticketId) ? $response->ticketId : -1;
                $message  = isset($response->message) ? $response->message : 'Unknown error';
            }

            if ($status === 'FAIL') {
                $message = "Support request submit failed. Error message: " . $message;
                add_settings_error('general', 'settings_updated', $message, 'error');
                $this->result = [
                    'status'   => $status,
                    'ticketId' => $ticketId,
                    'error'    => $message,
                ];
            } else {
                $message = "Support request submitted. Your issue id is " . $ticketId;
                add_settings_error('general', 'settings_updated', $message, 'success');
                $this->result = [
                    'status'   => $status,
                    'ticketId' => $ticketId,
                    'error'    => $message,
                ];
            }
        }

        return $this->result;
    }

    /**
     * Display settings
     *
     * @return void
     */
    public function display()
    {
    }

    /**
     * Render section name
     *
     * @param array<string, string> $section
     *
     * @return void
     */
    public function displaySection($section)
    {
        switch ($section['id']) {
            case 'get-support':
                $this->result = get_option('wunderauto-support');
                include WUNDERAUTO_BASE . '/admin/support/form.php';
                delete_option('wunderauto-support');
                break;
        }
    }

    /**
     * Render field
     *
     * @param string $fieldId
     * @param string $field
     *
     * @return void
     */
    public function displayField($fieldId, $field)
    {
    }

    /**
     * Return a PRE formatted string containing diagnostics
     *
     * @return string
     */
    public function getDiagnostics()
    {
        if (!class_exists('WP_Debug_Data')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
        }

        WP_Debug_Data::check_for_updates();
        $info      = WP_Debug_Data::debug_data();
        $debugData = WP_Debug_Data::format($info, 'debug');

        $debugData .= "\n\n### workflows ###\n";
        $wunderAuto = wa_wa();
        $workflows  = $wunderAuto->getWorkflows();
        foreach ($workflows as $workflow) {
            if ($workflow->isActive()) {
                $debugData .= "Workflow: " . $workflow->getName() . "\n";
                $debugData .= json_encode($workflow->getState(), JSON_PRETTY_PRINT) . "\n\n";
            }
        }

        $debugData .= "\n\n### retriggers ###\n";
        $reTriggers = $wunderAuto->getReTriggers();
        foreach ($reTriggers as $reTrigger) {
            $debugData .= "Re-Triggers: " . $reTrigger->getName() . "\n";
            $debugData .= json_encode($reTrigger->getState(), JSON_PRETTY_PRINT) . "\n\n";
        }

        return $debugData;
    }
}
