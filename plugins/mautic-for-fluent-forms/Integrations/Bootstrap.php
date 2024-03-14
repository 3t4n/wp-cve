<?php

namespace FluentFormMautic\Integrations;

use FluentForm\App\Services\Integrations\IntegrationManager;
use FluentForm\Framework\Foundation\Application;
use FluentForm\Framework\Helpers\ArrayHelper;

class Bootstrap extends IntegrationManager
{
    public function __construct(Application $app)
    {
        parent::__construct(
            $app,
            'Mautic',
            'mautic',
            '_fluentform_mautic_settings',
            'mautic_feed',
            36
        );

        $this->logo = fluentFormMix('img/integrations/mautic.png');

        $this->description = 'Mautic is a fully-featured marketing automation platform that enables organizations of all sizes to send multi-channel communications at scale.';

        $this->registerAdminHooks();

//        add_filter('fluentform_notifying_async_mautic', '__return_false');

        add_action('admin_init', function() {
            if (isset($_REQUEST['ff_mautic_auth'])) {
                $client = $this->getRemoteClient();
                if (isset($_REQUEST['code'])) {
                    // Get the access token now
                    $code = sanitize_text_field($_REQUEST['code']);
                    $settings = $this->getGlobalSettings([]);
                    $settings = $client->generateAccessToken($code, $settings);

                    if (!is_wp_error($settings)) {
                        $settings['status'] = true;
                        update_option($this->optionKey, $settings, 'no');
                    }

                    wp_redirect(admin_url('admin.php?page=fluent_forms_settings#general-mautic-settings'));
                    exit();
                } else {
                    $client->redirectToAuthServer();
                }
                die();
            }

        });

    }

    public function getGlobalFields($fields)
    {
        return [
            'logo'               => $this->logo,
            'menu_title'         => __('Mautic Settings', 'ffmauticaddon'),
            'menu_description'   => __($this->description,'ffmauticaddon'),
            'valid_message'      => __('Your Mautic API Key is valid', 'ffmauticaddon'),
            'invalid_message'    => __('Your Mautic API Key is not valid', 'ffmauticaddon'),
            'save_button_text'   => __('Save Settings', 'ffmauticaddon'),
            'config_instruction' => $this->getConfigInstractions(),
            'fields'             => [
                'apiUrl'        => [
                    'type'        => 'text',
                    'placeholder' => __('Your Mautic Installation URL', 'ffmauticaddon'),
                    'label_tips'  => __("Please provide your Mautic Installation URL", 'ffmauticaddon'),
                    'label'       => __('Your Mautic API URL', 'ffmauticaddon'),
                ],
                'client_id'     => [
                    'type'        => 'text',
                    'placeholder' => __('Mautic App Client ID', 'ffmauticaddon'),
                    'label_tips'  => __("Enter your Mautic Client ID, if you do not have <br>Please login to your Mautic account and go to<br>Settings -> Integrations -> API key",
                        'ffmauticaddon'),
                    'label'       => __('Mautic Client ID', 'ffmauticaddon'),
                ],
                'client_secret' => [
                    'type'        => 'password',
                    'placeholder' => __('Mautic App Client Secret', 'ffmauticaddon'),
                    'label_tips'  => __("Enter your Mautic API Key, if you do not have <br>Please login to your Mautic account and go to<br>Settings -> Integrations -> API key",
                        'ffmauticaddon'),
                    'label'       => __('Mautic Client Secret', 'ffmauticaddon'),
                ],
            ],
            'hide_on_valid'      => true,
            'discard_settings'   => [
                'section_description' => __('Your Mautic API integration is up and running', 'ffmauticaddon'),
                'button_text'         => __('Disconnect Mautic', 'ffmauticaddon'),
                'data'                => [
                    'apiUrl'        => '',
                    'client_id'     => '',
                    'client_secret' => ''
                ],
                'show_verify'         => true
            ]
        ];
    }

    public function getGlobalSettings($settings)
    {
        $globalSettings = get_option($this->optionKey);
        if (!$globalSettings) {
            $globalSettings = [];
        }
        $defaults = [
            'apiUrl'        => '',
            'client_id'     => '',
            'client_secret' => '',
            'status'        => '',
            'access_token'  => '',
            'refresh_token' => '',
            'expire_at'     => false
        ];

        return wp_parse_args($globalSettings, $defaults);
    }

    public function saveGlobalSettings($settings)
    {
        if (empty($settings['apiUrl'])) {
            $integrationSettings = [
                'apiUrl'        => '',
                'client_id'     => '',
                'client_secret' => '',
                'status'        => false
            ];
            // Update the details with siteKey & secretKey.
            update_option($this->optionKey, $integrationSettings, 'no');
            wp_send_json_success([
                'message' => __('Your settings has been updated', 'ffmauticaddon'),
                'status'  => false
            ], 200);
        }

        // Verify API key now
        try {
            $oldSettings = $this->getGlobalSettings([]);
            $oldSettings['apiUrl'] = esc_url_raw($settings['apiUrl']);
            $oldSettings['client_id'] = sanitize_text_field($settings['client_id']);
            $oldSettings['client_secret'] = sanitize_text_field($settings['client_secret']);
            $oldSettings['status'] = false;

            update_option($this->optionKey, $oldSettings, 'no');
            wp_send_json_success([
                'message'      => __('You are redirect to authenticate', 'ffmauticaddon'),
                'redirect_url' => admin_url('?ff_mautic_auth=1')
            ], 200);
        } catch (\Exception $exception) {
            wp_send_json_error([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function pushIntegration($integrations, $formId)
    {
        $integrations[$this->integrationKey] = [
            'title'                 => $this->title . ' Integration',
            'logo'                  => $this->logo,
            'is_active'             => $this->isConfigured(),
            'configure_title'       => __('Configuration required!','ffmauticaddon'),
            'global_configure_url'  => admin_url('admin.php?page=fluent_forms_settings#general-mautic-settings'),
            'configure_message'     => __('Mautic is not configured yet! Please configure your Mautic api first', 'ffmauticaddon'),
            'configure_button_text' => __('Set Mautic API', 'ffmauticaddon')
        ];
        return $integrations;
    }

    public function getIntegrationDefaults($settings, $formId)
    {
        return [
            'name'                 => '',
            'list_id'              => '',
            'fields'               => (object)[],
            'other_fields_mapping' => [
                [
                    'item_value' => '',
                    'label'      => ''
                ]
            ],
            'conditionals'         => [
                'conditions' => [],
                'status'     => false,
                'type'       => 'all'
            ],
            'resubscribe'          => false,
            'enabled'              => true
        ];
    }

    public function getSettingsFields($settings, $formId)
    {
        return [
            'fields'            => [
                [
                    'key'         => 'name',
                    'label'       => __('Feed Name', 'ffmauticaddon'),
                    'required'    => true,
                    'placeholder' => __('Your Feed Name', 'ffmauticaddon'),
                    'component'   => 'text'
                ],
                [
                    'key'                => 'fields',
                    'label'              => 'Map Fields',
                    'tips'               => __('Select which Fluent Form fields pair with their<br /> respective Mautic fields.', 'ffmauticaddon'),
                    'component'          => 'map_fields',
                    'field_label_remote' => __('Mautic Fields', 'ffmauticaddon'),
                    'field_label_local'  => 'Form Field',
                    'primary_fileds'     => [
                        [
                            'key'           => 'email',
                            'label'         => __('Email Address', 'ffmauticaddon'),
                            'required'      => true,
                            'input_options' => 'emails'
                        ]
                    ]
                ],
                [
                    'key'                => 'other_fields_mapping',
                    'require_list'       => false,
                    'label'              => __('Other Fields', 'ffmauticaddon'),
                    'tips'               => __('Select which Fluent Form fields pair with their<br /> respective Mautic fields.', 'ffmauticaddon'),
                    'component'          => 'dropdown_many_fields',
                    'field_label_remote' => __('Mautic Field', 'ffmauticaddon'),
                    'field_label_local'  => __('Mautic Field', 'ffmauticaddon'),
                    'options'            => $this->otherFields()
                ],
                [
                    'key'         => 'tags',
                    'label'       => __('Lead Tags', 'ffmauticaddon'),
                    'required'    => false,
                    'placeholder' => __('Tags', 'ffmauticaddon'),
                    'component'   => 'value_text',
                    'inline_tip'  => __('Use comma separated value. You can use smart tags here', 'ffmauticaddon')
                ],
                [
                    'key'            => 'last_active',
                    'label'          => __('Last Active', 'ffmauticaddon'),
                    'tips'           => __('When this option is enabled, FluentForm will pass the lead creation time to Mautic lead', 'ffmauticaddon'),
                    'component'      => 'checkbox-single',
                    'checkbox_label' => __('Enable Last Active', 'ffmauticaddon')
                ],
                [
                    'key'            => 'last_seen_ip',
                    'label'          => __('Push IP Address', 'ffmauticaddon'),
                    'tips'           => __('When this option is enabled, FluentForm will pass the ipAddress to Mautic', 'ffmauticaddon'),
                    'component'      => 'checkbox-single',
                    'checkbox_label' => __('Enable IP address', 'ffmauticaddon')
                ],
                [
                    'key'       => 'conditionals',
                    'label'     => __('Conditional Logics', 'ffmauticaddon'),
                    'tips'      => __('Allow Mautic integration conditionally based on your submission values', 'ffmauticaddon'),
                    'component' => 'conditional_block'
                ],
                [
                    'key'            => 'enabled',
                    'label'          => __('Status', 'ffmauticaddon'),
                    'component'      => 'checkbox-single',
                    'checkbox_label' => __('Enable This feed', 'ffmauticaddon')
                ]
            ],
            'integration_title' => $this->title
        ];
    }

    protected function getLists()
    {
        return [];
    }

    public function getMergeFields($list = false, $listId = false, $formId = false)
    {
        return [];
    }

    public function otherFields()
    {
        $api = $this->getRemoteClient();
        $fields = $api->listAvailableFields();  //get available fields from mautic including custom fields

        if (!$fields) {
            return [];
        }

        //sorting by id for standard ordered list
        usort($fields, function($a, $b) {
            return $a['id'] - $b['id'];
        });

        $fieldsFormatted = [];
        foreach ($fields as $field) {
            $fieldsFormatted[$field['alias']] = $field['label'];
        }

        unset($fieldsFormatted['email']);
        return $fieldsFormatted;
    }

    /*
     * Form Submission Hooks Here
     */
    public function notify($feed, $formData, $entry, $form)
    {
        $feedData = $feed['processedValues'];

        $subscriber = [
            'name'         => ArrayHelper::get($feedData, 'lead_name'),
            'email'        => ArrayHelper::get($feedData, 'email'),
            'phone'        => ArrayHelper::get($feedData, 'phone'),
            'created_at'   => time(),
            'last_seen_at' => time()
        ];

        $tags = ArrayHelper::get($feedData, 'tags');
        if ($tags) {
            $tags = explode(',', $tags);
            $formtedTags = [];
            foreach ($tags as $tag) {
                $formtedTags[] = wp_strip_all_tags(trim($tag));
            }
            $subscriber['tags'] = $formtedTags;
        }

        if (ArrayHelper::isTrue($feedData, 'last_active')) {
            $subscriber['lastActive'] = $entry->created_at;
        }

        if (ArrayHelper::isTrue($feedData, 'last_seen_ip')) {
            $subscriber['ipAddress'] = $entry->ip;
        }

        $subscriber = array_filter($subscriber);

        if (!empty($subscriber['email']) && !is_email($subscriber['email'])) {
            $subscriber['email'] = ArrayHelper::get($formData, $subscriber['email']);
        }

        foreach (ArrayHelper::get($feedData, 'other_fields_mapping') as $item) {
            $subscriber[$item['label']] = $item['item_value'];
        }

        if (!is_email($subscriber['email'])) {
            return;
        }

        $api = $this->getRemoteClient();
        $response = $api->subscribe($subscriber);

        if (is_wp_error($response)) {
            // it's failed
            do_action('ff_log_data', [
                'parent_source_id' => $form->id,
                'source_type'      => 'submission_item',
                'source_id'        => $entry->id,
                'component'        => $this->integrationKey,
                'status'           => 'failed',
                'title'            => $feed['settings']['name'],
                'description'      => $response->errors['error'][0][0]['message']
            ]);
        } else {
            // It's success
            do_action('ff_log_data', [
                'parent_source_id' => $form->id,
                'source_type'      => 'submission_item',
                'source_id'        => $entry->id,
                'component'        => $this->integrationKey,
                'status'           => 'success',
                'title'            => $feed['settings']['name'],
                'description'      => __('Mautic feed has been successfully initialed and pushed data', 'ffmauticaddon')
            ]);
        }
    }

    protected function getConfigInstractions()
    {
        ob_start();
        ?>
        <div><h4>To Authenticate Mautic you have to enable your API first</h4>
            <ol>
                <li>Go to Your Mautic account dashboard, Click on the gear icon next to the username on top right
                    corner.
                    Click on Configuration settings >> Api settings and enable the Api
                </li>
                <li>Then go to "Api Credentials" and create a new oAuth 2 credentials with a redirect url (Your site
                    dashboard url with this slug /?ff_mautic_auth=1)<br/>
                    Your app redirect url will be <b><?php
                        echo admin_url('?ff_mautic_auth=1'); ?></b>

                </li>
                <li>Paste your Mautic account URL on Mautic API URL, also paste the Client Id and Secret Id. Then click
                    save settings.
                </li>
            </ol>
        </div>
        <?php
        return ob_get_clean();
    }

    public function getRemoteClient()
    {
        $settings = $this->getGlobalSettings([]);
        return new API(
            $settings['apiUrl'],
            $settings
        );
    }
}
