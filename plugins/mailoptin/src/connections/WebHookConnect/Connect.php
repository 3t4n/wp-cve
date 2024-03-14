<?php

namespace MailOptin\WebHookConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Integration_Repeater_Control;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionInterface;
use function MailOptin\Core\moVar;
use function MailOptin\Core\system_form_fields;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_WEBHOOK_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/WebHookConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_WEBHOOK_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class Connect extends AbstractConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'WebHookConnect';

    public function __construct()
    {
        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_filter('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'), 10, 4);

        add_action('mo_optin_integration_control_enqueue', function () {
            wp_enqueue_script(
                'mailoptin-webhook-optin',
                MAILOPTIN_WEBHOOK_CONNECT_ASSETS_URL . 'webhook.js',
                array('jquery', 'underscore', 'customize-controls', 'wp-util'),
                MAILOPTIN_VERSION_NUMBER
            );

            wp_localize_script('mailoptin-webhook-optin', 'moWebhookGlobals',
                [
                    'name_label'      => esc_html__('Full Name', 'mailoptin'),
                    'firstname_label' => esc_html__('First Name', 'mailoptin'),
                    'lastname_label'  => esc_html__('Last Name', 'mailoptin'),
                    'email_label'     => esc_html__('Email Address', 'mailoptin'),
                    'system_fields'   => json_encode(system_form_fields()),
                ]
            );
        });

        add_action('mo_optin_integration_control_template', [$this, 'script_templates']);

        parent::__construct();
    }

    public static function features_support()
    {
        return [self::OPTIN_CAMPAIGN_SUPPORT];
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['WebHookConnect_request_url']    = apply_filters('mailoptin_customizer_optin_campaign_WebHookConnect_request_url', '');
        $settings['WebHookConnect_request_format'] = apply_filters('mailoptin_customizer_optin_campaign_WebHookConnect_request_format', '');

        return $settings;
    }

    /**
     * @param array $controls
     *
     * @return mixed
     */
    public function integration_customizer_controls($controls, $optin_campaign_id, $index, $saved_values)
    {
        $controls[] = [
            'field'       => 'text',
            'name'        => 'WebHookConnect_request_url',
            'placeholder' => __('Enter Request URL', 'mailoptin'),
            'label'       => __('Request URL (Required)', 'mailoptin')
        ];

        $controls[] = [
            'field'   => 'select',
            'name'    => 'WebHookConnect_request_format',
            'choices' => $this->request_format(),
            'label'   => __('Request Format (Required)', 'mailoptin')
        ];

        $controls[] = [
            'name'    => 'WebHookConnect_request_header_fields',
            'field'   => 'custom_content',
            'content' => $this->render_header_fields_content($saved_values, $index),
        ];

        $controls[] = [
            'name'    => 'WebHookConnect_request_body_fields',
            'field'   => 'custom_content',
            'content' => $this->render_body_fields_content($saved_values, $index),
        ];

        return $controls;
    }

    public function script_templates($classInstance)
    {
        ?>
        <script type="text/html" id="tmpl-mo-webhook-header-template">
            <?php $this->request_header_template($classInstance) ?>
        </script>
        <script type="text/html" id="tmpl-mo-webhook-body-template">
            <?php $this->request_body_template($classInstance) ?>
        </script>
        <?php
    }

    /**
     * @param WP_Customize_Integration_Repeater_Control $classInstance
     */
    public function request_header_template($classInstance)
    {
        $header_fields_label = [
            ''                    => __('Select a Name', 'mailoptin'),
            'Accept'              => __('Accept', 'mailoptin'),
            'Accept-Charset'      => __('Accept-Charset', 'mailoptin'),
            'Accept-Encoding'     => __('Accept-Encoding', 'mailoptin'),
            'Accept-Language'     => __('Accept-Language', 'mailoptin'),
            'Accept-Datetime'     => __('Accept-Datetime', 'mailoptin'),
            'Authorization'       => __('Authorization', 'mailoptin'),
            'Cache-Control'       => __('Cache-Control', 'mailoptin'),
            'Connection'          => __('Connection', 'mailoptin'),
            'Content-Length'      => __('Content-Length', 'mailoptin'),
            'Date'                => __('Date', 'mailoptin'),
            'Expect'              => __('Expect', 'mailoptin'),
            'From'                => __('From', 'mailoptin'),
            'Host'                => __('Host', 'mailoptin'),
            'If-Match'            => __('If-Match', 'mailoptin'),
            'If-Modified-Since'   => __('If-Modified-Since', 'mailoptin'),
            'If-None-Match'       => __('If-None-Match', 'mailoptin'),
            'If-Range'            => __('If-Range', 'mailoptin'),
            'If-Unmodified-Since' => __('If-Unmodified-Since', 'mailoptin'),
            'Max-Forwards'        => __('Max-Forwards', 'mailoptin'),
            'Origin'              => __('Origin', 'mailoptin'),
            'Pragma'              => __('Pragma', 'mailoptin'),
            'Proxy-Authorization' => __('Proxy-Authorization', 'mailoptin'),
            'Range'               => __('Range', 'mailoptin'),
            'Referer'             => __('Referer', 'mailoptin'),
            'TE'                  => __('TE', 'mailoptin'),
            'User-Agent'          => __('User-Agent', 'mailoptin'),
            'Upgrade'             => __('Upgrade', 'mailoptin'),
            'Via'                 => __('Via', 'mailoptin'),
            'Warning'             => __('Warning', 'mailoptin'),
            'mo_custom_header'    => __('Add Custom Header', 'mailoptin'),
        ];
        ?>
        <div class="mo-integration-widget-wrap mo-webhook-header-wrap" data-webhook-data-type="header">
            <?php $classInstance->select_field('', 'dropdown_key', $header_fields_label, 'mo-webhook-field mo-webhook-header-name-select-field', __('Name', 'mailoptin')); ?>
            <?php $classInstance->text_field('', 'key', 'mo-webhook-field mo-webhook-header-name-text-field', __('Name', 'mailoptin')); ?>
            <?php $classInstance->text_field('', 'value', 'mo-webhook-field', __('Value', 'mailoptin')); ?>
            <button type="button" class="button mo-integration-webhook__remove">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        </div>
        <?php
    }

    /**
     * @param WP_Customize_Integration_Repeater_Control $classInstance
     */
    public function request_body_template($classInstance)
    {
        ?>
        <div class="mo-integration-widget-wrap mo-webhook-body-wrap" data-webhook-data-type="body">
            <?php $classInstance->text_field('', 'key', 'mo-webhook-field', __('Key', 'mailoptin')); ?>
            <?php $classInstance->select_field('', 'value', ['' => '–––––––––'], 'mo-webhook-field mo-webhook-cf-dropdown', __('Value', 'mailoptin')); ?>
            <button type="button" class="button mo-integration-webhook__remove">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        </div>
        <?php
    }

    /**
     * @return string[]
     */
    public function request_format()
    {
        return [
            'json' => 'JSON',
            'form' => 'FORM'
        ];
    }

    public function render_header_fields_content($saved_values, $index)
    {
        $field_content = '<label class="customize-control-title mo-field-header">' . __('Request Headers', 'mailoptin') . '</label>';
        $field_content .= '<div class="mo-integration-header-group-fields mo-webhook-integration-widget mo-webhook-integration-header">';

        $saved_value = "[]";
        if (isset($saved_values[$index])) {
            $saved_value = moVar($saved_values[$index], 'WebHookConnect_request_header_fields', "[]");
        }

        $field_content .= '</div>';
        $field_content .= '<div class="mo-webhook-add-btn-wrap mo-integration-webhook__add_header_new">';
        $field_content .= '<button type="button" class="button button-primary"><span class="dashicons dashicons-plus-alt2"></span></button>';
        $field_content .= '</div>';
        $field_content .= '<input class="WebHookConnect_request_header_fields" type="hidden" name="WebHookConnect_request_header_fields" value="' . esc_attr($saved_value) . '">';

        return $field_content;
    }

    public function render_body_fields_content($saved_values, $index)
    {
        $field_content = '<label class="customize-control-title mo-field-header">' . __('Request Body', 'mailoptin') . '</label>';
        $field_content .= '<div class="mo-integration-header-group-fields mo-webhook-integration-widget mo-webhook-integration-body">';

        $saved_value = "[]";
        if (isset($saved_values[$index])) {
            $saved_value = moVar($saved_values[$index], 'WebHookConnect_request_body_fields', "[]");
        }

        $field_content .= '</div>';
        $field_content .= '<div class="mo-webhook-add-btn-wrap mo-integration-webhook__add_body_new">';
        $field_content .= '<button type="button" class="button button-primary"><span class="dashicons dashicons-plus-alt2"></button>';
        $field_content .= '</div>';
        $field_content .= '<input class="WebHookConnect_request_body_fields" type="hidden" name="WebHookConnect_request_body_fields" value="' . esc_attr($saved_value) . '">';

        return $field_content;
    }

    /**
     * Fulfill interface contract.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        return $this->replace_footer_placeholder_tags($content);
    }

    /**
     * {@inherit_doc}
     *
     * Return array of request for webhook
     *
     * @return mixed
     */
    public function get_email_list()
    {
        return [
            'GET'    => 'GET',
            'POST'   => 'POST',
            'PUT'    => 'PUT',
            'PATCH'  => 'PATCH',
            'DELETE' => 'DELETE'
        ];
    }

    /**
     * {@inherit_doc}
     *
     * Return array of email list
     *
     * @return mixed
     */
    public function get_optin_fields($list_id = '')
    {
        return [];
    }

    /**
     * Register WebHook Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $connections[self::$connectionName] = __('Webhook', 'mailoptin');
        }

        return $connections;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @param int $email_campaign_id
     * @param int $campaign_log_id
     * @param string $subject
     * @param string $content_html
     * @param string $content_text
     *
     * @return array
     * @throws \Exception
     *
     */
    public function send_newsletter($email_campaign_id, $campaign_log_id, $subject, $content_html, $content_text)
    {
        return [];
    }

    /**
     * @param string $name
     * @param string $email
     * @param $request_method
     * @param mixed|null $extras
     *
     * @return mixed
     */
    public function subscribe($name, $email, $request_method, $extras = null)
    {
        return (new SendWebhookRequest($name, $email, $request_method, $extras))->trigger();
    }

    /**
     * @return Connect|null
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}