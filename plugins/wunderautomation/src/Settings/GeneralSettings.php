<?php

namespace WunderAuto\Settings;

/**
 * Class GeneralSettings
 */
class GeneralSettings extends BaseSettings
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id        = 'wunderauto-general';
        $this->caption   = 'General';
        $this->sortOrder = 5;
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register()
    {
        $this->registerTab();
        $this->addSection('formatting', __('Formatting', 'wunderauto'));
        $this->addField('formatting', 'datetimeformat', 'Date time format');

        $this->addSection('inboundhttp', __('Inbound http requests', 'wunderauto'));
        $this->addField('inboundhttp', 'confirmationslug', 'Confirmation slug');
        $this->addField('inboundhttp', 'enable_webhook_trigger', 'Enable webhook');
        $this->addField('inboundhttp', 'webhookslug', 'Webhook slug');

        $this->addSection('logging', __('Logging options', 'wunderauto'));
        $this->addField('logging', 'loglevel', 'Log level');
    }

    /**
     * Sanitize user input
     *
     * @return array<string, string|int>
     */
    public function sanitize()
    {
        return [
            'datetimeformat'         => sanitize_text_field($_POST['datetimeformat']),
            'loglevel'               => sanitize_text_field($_POST['loglevel']),
            'confirmationslug'       => sanitize_text_field($_POST['confirmationslug']),
            'enable_webhook_trigger' => (int)($_POST['enable_webhook_trigger']),
            'webhookslug'            => sanitize_text_field($_POST['webhookslug']),
        ];
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
            case 'logging':
                esc_html_e('Settings for WunderAutomation logger', 'wunderauto');
                break;
            case 'inboundhttp':
                esc_html_e('Settings for inbound http requests', 'wunderauto');
                break;
            case 'formatting':
                esc_html_e('Default settings for parameter output formats', 'wunderauto');
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
        switch ($fieldId) {
            case 'datetimeformat':
                $this->renderField(
                    'text',
                    $fieldId,
                    $fieldId,
                    '',
                    __(
                        'Formats the date using PHP date() function. I.e Y-m-d H:i:s If left blank this will ' .
                        'default to WordPress standard settings, currently: ' . get_option('date_format'),
                        'wunderauto'
                    )
                );
                break;
            case 'confirmationslug':
                $this->renderField(
                    'text',
                    $fieldId,
                    $fieldId,
                    'wa-confirm',
                    __(
                        'The base slug for confirmation links. Defaults to \'wa-confirm\'. Please note: changing ' .
                        'this will make links already sent out to your users invalid.',
                        'wunderauto'
                    ) . ' ' . __('Example url: ', 'wunderauto') . site_url() . '/wa-confirm/UNIQUECONFIRMATIONCODE'
                );
                break;
            case 'webhookslug':
                $this->renderField(
                    'text',
                    $fieldId,
                    $fieldId,
                    'wa-hook',
                    __(
                        'The base slug for webhooks. Defaults to \'wa-hook\'. Please note: changing this ' .
                        'may make existing integrations stop working.',
                        'wunderauto'
                    ) . ' ' . __('Example url: ', 'wunderauto') . site_url() . '/wa-hook/UNIQUEHOOKIDENTIFIER'
                );
                break;
            case 'enable_webhook_trigger':
                $this->renderField(
                    'checkbox',
                    $fieldId,
                    $fieldId,
                    0,
                    __('Enable or disable the webook trigger. Disabled by default', 'wunderauto')
                );
                break;
            case 'loglevel':
                $this->renderField(
                    'select',
                    $fieldId,
                    $fieldId,
                    \WunderAuto\Logger::INFO,
                    __('NOTICE = Very few messages, INFO = Normal, DEBUG = Lots of detailed messages', 'wunderauto'),
                    [
                        \WunderAuto\Logger::NOTICE => 'NOTICE',
                        \WunderAuto\Logger::INFO   => 'INFO',
                        \WunderAuto\Logger::DEBUG  => 'DEBUG',
                    ]
                );
                break;
        }
    }
}
