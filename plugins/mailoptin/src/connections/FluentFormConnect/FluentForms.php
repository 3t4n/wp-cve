<?php

namespace MailOptin\FluentFormConnect;

use FluentForm\App\Http\Controllers\IntegrationManagerController;
use FluentForm\Framework\Helpers\ArrayHelper;
use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use function MailOptin\Core\moVar;

class FluentForms extends IntegrationManagerController
{
    public function __construct()
    {
        parent::__construct(
            false,
            'MailOptin',
            'mailoptin',
            '_fluentform_mailoptin_settings',
            'fluentform_mailoptin_feed',
            99
        );

        $this->logo = MAILOPTIN_ASSETS_URL . 'images/mailoptin-fluentforms.png';

        $this->description = esc_html__('Sync Fluent Forms submissions with your email marketing software.', 'mailoptin');

        $this->registerAdminHooks();
    }

    public function isConfigured()
    {
        return true;
    }

    public function getGlobalSettings($settings)
    {
        $globalSettings = get_option($this->optionKey);

        if ( ! $globalSettings) {
            $globalSettings = [];
        }

        $defaults = ['status' => true];

        return wp_parse_args($globalSettings, $defaults);
    }

    public function getGlobalFields($fields)
    {
        return [
            'logo'             => $this->logo,
            'menu_title'       => __('MailOptin Settings', 'mailoptin'),
            'menu_description' => sprintf(__('Fluent Forms is already connected with MailOptin. There is nothing to configure here. You can set up MailOptin in your forms under Settings & Integrations. For more information <a href="https://mailoptin.io/article/fluent-forms-email-marketing-crm/" target="_blank">see the documentation</a>.', 'mailoptin')),
            'valid_message'    => __('Your MailOptin Connection is valid', 'mailoptin'),
            'invalid_message'  => ' ',
            'save_button_text' => ' ',
        ];
    }

    public function getIntegrationDefaults($settings, $formId)
    {
        $settings = [
            'conditionals'      => [
                'conditions' => [],
                'status'     => false,
                'type'       => 'all',
            ],
            'enabled'           => true,
            'list_id'           => '',
            'name'              => '',
            'merge_fields'      => (object)[],
            'fieldEmailAddress' => '',
            'fieldFullName'     => '',
            'fieldFirstName'    => '',
            'fieldLastName'     => ''
        ];

        return $settings;
    }

    public function getMergeFields($list, $listId, $formId)
    {

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {

            $exploded = explode('|', sanitize_text_field($listId));

            $instance = ConnectionFactory::make($exploded[0]);

            if (is_object($instance) && method_exists($instance, 'get_optin_fields')) {

                if (in_array($instance::OPTIN_CUSTOM_FIELD_SUPPORT, $instance::features_support())) {

                    $custom_fields = $instance->get_optin_fields($exploded[1]);

                    if ( ! empty($custom_fields)) {
                        return $custom_fields;
                    }
                }
            }
        }

        return [];
    }

    public function getSettingsFields($settings, $formId)
    {
        $args = [
            'button_require_list' => false,
            'integration_title'   => $this->title,
            'fields'              => [
                [
                    'key'         => 'name',
                    'label'       => __('Name', 'mailoptin'),
                    'required'    => true,
                    'placeholder' => __('Your Feed Name', 'mailoptin'),
                    'component'   => 'text',
                ],
                [
                    'key'         => 'list_id',
                    'label'       => __('Select List', 'mailoptin'),
                    'placeholder' => __('Select List', 'mailoptin'),
                    'tips'        => __('Select the list you would like to add your contacts to.', 'mailoptin'),
                    'component'   => 'list_ajax_options',
                    'options'     => $this->email_providers_and_lists(),
                ],
                [
                    'key'                => 'merge_fields',
                    'require_list'       => true,
                    'label'              => __('Map Fields', 'mailoptin'),
                    'tips'               => __('Associate your CRM/Email marketing software custom fields to the appropriate Fluent Forms fields by selecting the appropriate form field from the list.', 'mailoptin'),
                    'component'          => 'map_fields',
                    'field_label_remote' => __('CRM Field', 'mailoptin'),
                    'field_label_local'  => __('Form Field', 'mailoptin'),
                    'primary_fileds'     => [
                        [
                            'key'           => 'fieldEmailAddress',
                            'label'         => __('Email Address', 'mailoptin'),
                            'required'      => true,
                            'input_options' => 'emails',
                        ],
                        [
                            'key'      => 'fieldFullName',
                            'label'    => __('Full Name', 'mailoptin'),
                            'required' => false
                        ],
                        [
                            'key'      => 'fieldFirstName',
                            'label'    => __('First Name', 'mailoptin'),
                            'required' => false
                        ],
                        [
                            'key'      => 'fieldLastName',
                            'label'    => __('Last Name', 'mailoptin'),
                            'required' => false
                        ],
                    ],
                ]
            ]
        ];

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {

            // https://github.com/fluentform/fluentform/blob/master/resources/assets/admin/components/settings/GeneralIntegration/IntegrationEditor.vue

            foreach ($this->email_service_providers() as $email_service_provider_id => $email_service_provider_label) {

                if ( ! empty($email_service_provider_id) && in_array($email_service_provider_id, Init::select2_tag_connections())) {
                    $tags     = [];
                    $instance = ConnectionFactory::make($email_service_provider_id);
                    if (is_object($instance) && method_exists($instance, 'get_tags')) {
                        $tags = $instance->get_tags();
                    }

                    $args['fields'][] = [
                        'key'          => $email_service_provider_id . '_tags',
                        'label'        => $email_service_provider_label . ' ' . __('Tags', 'mailoptin'),
                        'component'    => 'select',
                        'require_list' => true,
                        'tips'         => __('Select tags to assign to subscribers.', 'mailoptin'),
                        'is_multiple'  => true,
                        'options'      => $tags
                    ];
                }

                if ( ! empty($email_service_provider_id) && in_array($email_service_provider_id, Init::text_tag_connections())) {

                    $args['fields'][] = [
                        'key'       => $email_service_provider_id . '_tags',
                        'label'     => $email_service_provider_label . ' ' . __('Tags', 'mailoptin'),
                        'tips'      => __('Enter a comma-separated list of tags to assign to subscribers.', 'mailoptin'),
                        'component' => 'text'
                    ];
                }

                if (in_array($email_service_provider_id, Init::double_optin_support_connections(true))) {

                    $default_double_optin = Init::double_optin_support_connections()[$email_service_provider_id] ?? false;

                    $args['fields'][] = [
                        'key'            => $email_service_provider_id . '_doubleOptIn',
                        'require_list'   => true,
                        'label'          => $email_service_provider_label . ' ' . __('Double Opt-in', 'mailoptin'),
                        'component'      => 'checkbox-single',
                        'checkbox_label' => $default_double_optin === false ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                    ];
                }
            }

        } else {

            ?>
            <?php
            $upgrade_url   = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=fluentforms_builder_settings';
            $learnmore_url = 'https://mailoptin.io/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=fluentforms_builder_settings';
            $output        = '<p>' . sprintf(esc_html__('Upgrade to %s to remove the 500 subscribers monthly limit, add support for custom field mapping and assign tags to subscribers.', 'mailoptin'), '<strong>MailOptin premium</strong>') . '</p>';
            $output        .= '<p><a href="' . $upgrade_url . '" style="margin-right: 10px;" class="button-primary" target="_blank">' . esc_html__('Upgrade to MailOptin Premium', 'mailoptin') . '</a>';
            $output        .= sprintf(esc_html__('%sLearn more about us%s', 'mailoptin'), '<a href="' . $learnmore_url . '" target="_blank">', '</a>') . '</p>';


            $args['fields'][] = [
                'key'       => 'pro_upgrade',
                'html_info' => '<div style="background-color: #d9edf7;border: 1px solid #bce8f1;box-sizing: border-box;color: #31708f;outline: 0;padding: 15px 10px">' . $output . '</div>',
                'component' => 'html_info',
            ];
        }

        $args['fields'][] = [
            'require_list' => true,
            'key'          => 'conditionals',
            'label'        => __('Conditional Logics', 'mailoptin'),
            'tips'         => __('Allow MailOptin integration conditionally based on your submission values', 'mailoptin'),
            'component'    => 'conditional_block',
        ];

        $args['fields'][] = [
            'require_list'   => true,
            'key'            => 'enabled',
            'label'          => __('Status', 'mailoptin'),
            'component'      => 'checkbox-single',
            'checkbox_label' => __('Enable This feed', 'mailoptin'),
        ];

        return $args;
    }

    public function email_service_providers()
    {
        $connections = ConnectionsRepository::get_connections();

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $connections['leadbank'] = __('MailOptin Leads', 'mailoptin');
        }

        unset($connections['WebHookConnect']);
        unset($connections['WordPressUserRegistrationConnect']);

        return $connections;
    }

    public function email_providers_and_lists()
    {
        $data = [];

        foreach ($this->email_service_providers() as $key => $value) {

            if ($key == 'leadbank') continue;

            $lists = ConnectionsRepository::connection_email_list($key);

            foreach ($lists as $list_id => $list_label) {
                $data[sprintf('%s|%s', $key, $list_id)] = sprintf('%s – %s', $value, $list_label);
            }
        }

        return $data;
    }

    public function pushIntegration($integrations, $formId)
    {
        $integrations[$this->integrationKey] = [
            'title'                 => $this->title,
            'logo'                  => $this->logo,
            'is_active'             => true,
            'configure_title'       => __('Configuration required!', 'mailoptin'),
            'global_configure_url'  => admin_url('admin.php?page=fluent_forms_settings#general-mailoptin-settings'),
            'configure_message'     => __('MailOptin is not configured yet! Please configure MailOptin first', 'mailoptin'),
            'configure_button_text' => __('Set MailOptin API', 'mailoptin'),
        ];

        return $integrations;
    }

    public function notify($feed, $formData, $entry, $form)
    {
        $feedData = $feed['processedValues'];

        if ( ! is_email($feedData['fieldEmailAddress'])) {
            $feedData['fieldEmailAddress'] = ArrayHelper::get($formData, $feedData['fieldEmailAddress']);
        }

        if ( ! is_email($feedData['fieldEmailAddress'])) return false;

        $exploaded = explode('|', $feedData['list_id']);

        $merge_fields = moVar($feedData, 'merge_fields', []);

        $name       = moVar($feedData, 'fieldFullName');
        $first_name = moVar($feedData, 'fieldFirstName');
        $last_name  = moVar($feedData, 'fieldLastName');

        $connection_service = $exploaded[0];

        $double_optin = false;
        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = moVar($feedData, $connection_service . '_doubleOptIn') == "1";
        }

        $optin_data = new ConversionDataBuilder();
        // since it's non mailoptin form, set it to zero.
        $optin_data->optin_campaign_id   = 0;
        $optin_data->payload             = $merge_fields;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $feedData['fieldEmailAddress'];
        $optin_data->optin_campaign_type = 'Fluent Forms';

        $optin_data->connection_service    = $connection_service;
        $optin_data->connection_email_list = $exploaded[1];

        $optin_data->user_agent                = esc_html($_SERVER['HTTP_USER_AGENT']);
        $optin_data->is_timestamp_check_active = false;
        $optin_data->is_double_optin           = $double_optin;

        if ( ! empty($formData['_wp_http_referer'])) {
            $optin_data->conversion_page = home_url($formData['_wp_http_referer']);
        }

        $optin_data->form_tags = moVar($feedData, $connection_service . '_tags');

        foreach ($merge_fields as $key => $field_value) {

            $field_value = moVar($merge_fields, $key);

            if ( ! empty($field_value)) {
                $optin_data->form_custom_field_mappings[$key] = $key;
            }
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        if (AbstractConnect::is_ajax_success($response)) {
            $message = __('MailOptin feed has been successfully initialed and pushed data', 'mailoptin');
            do_action('fluentform/integration_action_result', $feed, 'success', $message);
        } else {
            $message = __('MailOptin feed has been failed to deliver feed', 'mailoptin');
            do_action('fluentform/integration_action_result', $feed, 'failed', $message);
        }
    }

    /**
     * @return self
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