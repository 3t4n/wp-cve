<?php

namespace WPPayForm\App\Services\Integrations\MailChimp;

use WPPayForm\App\Services\Integrations\MailChimp\MailChimpSubscriber as Subscriber;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Services\ConditionAssesor;
use WPPayForm\App\Services\Integrations\IntegrationManager;
use WPPayForm\Framework\Foundation\App;

class MailChimpIntegration extends IntegrationManager
{
    /**
     * MailChimp Subscriber that handles & process all the subscribing logics.
     */
    use Subscriber;

    public function __construct($application)
    {
        parent::__construct(
            $application,
            'MailChimp',
            'mailchimp',
            '_wppayform_mailchimp_details',
            'mailchimp_feeds',
            12
        );

        $this->description = 'Paymattic Mailchimp module allows you to create Mailchimp newsletter signup forms in WordPress';
        $this->logo = WPPAYFORM_URL . 'assets/images/integrations/mailchimp.png';
        $this->registerAdminHooks();

        add_action('wppayform_chained_mailchimp_interest_groups', array($this, 'fetchInterestGroups'));

        // add_filter('wppayform_notifying_async_mailchimp', '__return_false');
    }

    public function getGlobalFields($fields)
    {
        return [
            'logo'             => $this->logo,
            'menu_title'       => __('Mailchimp Settings', 'wp-payment-form'),
            'menu_description' => __('Mailchimp is a marketing platform for small businesses. Send beautiful emails, connect your e-commerce store, advertise, and build your brand. Use Paymattic to collect customer information and automatically add it to your Mailchimp campaign list. If you don\'t have a Mailchimp account, you can <a href="http://www.mailchimp.com/" target="_blank">sign up for one here.</a>', 'wp-payment-form'),
            'valid_message'    => __('Your Mailchimp API Key is valid', 'wp-payment-form'),
            'invalid_message'  => __('Your Mailchimp API Key is not valid', 'wp-payment-form'),
            'save_button_text' => __('Save Settings', 'wp-payment-form'),
            'fields'           => [
                'apiKey' => [
                    'placeholder' => "Your mailchimp api key",
                    'type'       => 'text',
                    'label_tips' => __("Enter your Mailchimp API Key, if you do not have <br>Please login to your MailChimp account and go to<br>Profile -> Extras -> Api Keys", 'wp-payment-form'),
                    'label'      => __('Mailchimp API Key', 'wp-payment-form'),
                ]
            ],
            'hide_on_valid'    => true,
            'discard_settings' => [
                'section_description' => 'Your Mailchimp API integration is up and running',
                'button_text'         => 'Disconnect Mailchimp',
                'data'                => [
                    'apiKey' => ''
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
            'apiKey' => '',
            'status' => ''
        ];

        return wp_parse_args($globalSettings, $defaults);
    }

    public function saveGlobalSettings($mailChimp)
    {
        if (!$mailChimp['apiKey']) {
            $mailChimpSettings = [
                'apiKey' => '',
                'status' => false
            ];
            // Update the reCAPTCHA details with siteKey & secretKey.
            update_option($this->optionKey, $mailChimpSettings, 'no');
            wp_send_json_success([
                'message' => __('Your settings has been updated and disconnected', 'wp-payment-form'),
                'status'  => false
            ], 200);
        }

        // Verify API key now
        try {
            $MailChimp = new MailChimp($mailChimp['apiKey']);
            $result = $MailChimp->get('lists');
            if (!$MailChimp->success()) {
                throw new \Exception($MailChimp->getLastError());
            }
        } catch (\Exception $exception) {
            wp_send_json_error([
                'message' => $exception->getMessage()
            ], 400);
        }

        // MailChimp key is verified now, Proceed now

        $mailChimpSettings = [
            'apiKey' => sanitize_text_field($mailChimp['apiKey']),
            'status' => true
        ];

        // Update the reCaptcha details with siteKey & secretKey.
        update_option($this->optionKey, $mailChimpSettings, 'no`');

        wp_send_json_success([
            'message' => __('Your mailchimp api key has been verified and successfully set', 'wp-payment-form'),
            'status'  => true
        ], 200);
    }

    public function pushIntegration($integrations, $formId)
    {
        $integrations['mailchimp'] = [
            'title' => __('Mailchimp Feed', 'wp-payment-form'),
            'logo' => $this->logo,
            'is_active' => $this->isConfigured(),
            'configure_title' => __('Configuration required!', 'wp-payment-form'),
            'global_configure_url' => admin_url('admin.php?page=wppayform_settings#general-mailchimp-settings'),
            'configure_message' => __('Mailchimp is not configured yet! Please configure your mailchimp api first', 'wp-payment-form'),
            'configure_button_text' => __('Set Mailchimp API', 'wp-payment-form')
        ];

        return $integrations;
    }

    public function getIntegrationDefaults($settings, $formId)
    {
        $settings = [
            'conditionals' => [
                'conditions' => [],
                'status' => false,
                'type' => 'all'
            ],
            'enabled' => true,
            'list_id' => '',
            'list_name' => '',
            'name' => '',
            'merge_fields' => (object)[],
            'tags' => '',
            'tag_routers' => [],
            'tag_ids_selection_type' => 'simple',
            'markAsVIP' => false,
            'fieldEmailAddress' => '',
            'doubleOptIn' => false,
            'resubscribe' => false,
            'note' => ''
        ];

        return $settings;
    }

    public function getSettingsFields($settings, $formId)
    {
        return [
            'fields' => [
                [
                    'key' => 'name',
                    'label' => 'Name',
                    'required' => true,
                    'placeholder' => 'Your   Name',
                    'component' => 'text'
                ],
                [
                    'key' => 'list_id',
                    'label' => '  List',
                    'placeholder' => 'Select Mailchimp List',
                    'tips' => 'Select the Mailchimp list you <br/> would like to add your contacts to.',
                    'component' => 'list_ajax_options',
                    'options' => $this->getLists(),
                ],
                [
                    'key' => 'merge_fields',
                    'require_list' => true,
                    'label' => 'Map Fields',
                    'tips' => 'Associate your Mailchimp merge tags <br/> to the appropriate Paymattic fields <br/> by selecting the appropriate form <br/> field from the list.',
                    'component' => 'map_fields',
                    'field_label_remote'  => 'Mailchimp Field',
                    'field_label_local' => 'Form Field',
                    'primary_fileds' => [
                        [
                            'key' => 'fieldEmailAddress',
                            'label' => 'Email Address',
                            'required' => true,
                            'input_options' => 'emails'
                        ]
                    ]
                ],
                [
                    'key' => 'interest_group',
                    'require_list' => true,
                    'label' => 'Interest Group',
                    'tips' => 'You can map your mailchimp <br/> interest group for this contact',
                    'component' => 'chained_fields',
                    'sub_type' => 'radio',
                    'category_label' => 'Select Interest Category',
                    'subcategory_label' => 'Select Interest',
                    // 'remote_url'   => admin_url('admin-ajax.php?action=wppayform_mailchimp_interest_groups'),
                    'remote_url' => 'mailchimp_interest_groups',
                    'inline_tip' => 'Select the mailchimp interest category and interest'
                ],
                [
                    'key' => 'tags',
                    'require_list' => true,
                    'label' => 'Tags',
                    'tips' => 'Associate tags to your MailChimp contacts <br/> with a comma separated list (e.g. new lead, Paymattic, web source). <br/>Commas within a merge tag value will be created as a single tag.',
                    'component' => 'selection_routing',
                    'simple_component' => 'value_text',
                    'routing_input_type' => 'text',
                    'routing_key'  => 'tag_ids_selection_type',
                    'settings_key' => 'tag_routers',
                    'labels'       => [
                        'choice_label' => 'Enable Dynamic Tag Input',
                        'input_label' => '',
                        'input_placeholder' => 'Tag'
                    ],
                    'inline_tip' => 'Please provide each tag by comma separated value, You can use dynamic smart codes'
                ],
                [
                    'key' => 'note',
                    'require_list' => true,
                    'label' => 'Note',
                    'tips' => 'You can write a note for this contact',
                    'component' => 'value_textarea'
                ],
                [
                    'key' => 'doubleOptIn',
                    'require_list' => true,
                    'label' => 'Double Opt-in',
                    'tips' => 'When the double opt-in option is enabled,<br />Mailchimp will send a confirmation email<br />to the user and will only add them to your <br /Mailchimp list upon confirmation.',
                    'component' => 'checkbox-single',
                    'checkbox_label' => 'Enable Double Opt-in'
                ],
                [
                    'key' => 'resubscribe',
                    'require_list' => true,
                    'label' => 'ReSubscribe',
                    'tips' => 'When this option is enabled, if the subscriber is in an inactive state or<br />has previously been unsubscribed, they will be re-added to the active list.<br />Therefore, this option should be used with caution and only when appropriate.',
                    'component' => 'checkbox-single',
                    'checkbox_label' => 'Enable ReSubscription'
                ],
                [
                    'key' => 'markAsVIP',
                    'require_list' => true,
                    'label' => 'VIP',
                    'tips' => 'When enabled,<br /> This contact will be marked as VIP.',
                    'component' => 'checkbox-single',
                    'checkbox_label' => 'Mark as VIP Contact'
                ],
                [
                    'require_list' => true,
                    'key' => 'conditionals',
                    'label' => 'Conditional Logics',
                    'tips' => 'Allow mailchimp integration conditionally <br/> based on your submission values',
                    'component' => 'conditional_block'
                ],
                [
                    'require_list' => true,
                    'key' => 'enabled',
                    'label' => 'Status',
                    'component' => 'checkbox-single',
                    'checkbox_label' => 'Enable This feed'
                ]
            ],
            'button_require_list' => true,
            'integration_title' => 'Mailchimp'
        ];
    }

    public function setFeedAtributes($feed, $formId)
    {
        $feed['provider'] = 'mailchimp';
        $feed['provider_logo'] = $this->logo;
        return $feed;
    }

    public function prepareIntegrationFeed($setting, $feed, $formId)
    {
        $defaults = $this->getIntegrationDefaults([], $formId);

        foreach ($setting as $settingKey => $settingValue) {
            if ($settingValue == 'true') {
                $setting[$settingKey] = true;
            } elseif ($settingValue == 'false') {
                $setting[$settingKey] = false;
            } elseif ($settingKey == 'conditionals') {
                if ($settingValue['status'] == 'true') {
                    $settingValue['status'] = true;
                } elseif ($settingValue['status'] == 'false') {
                    $settingValue['status'] = false;
                }
                $setting['conditionals'] = $settingValue;
            }
        }

        if (!empty($setting['list_id'])) {
            $setting['list_id'] = (string)$setting['list_id'];
        }

        $settings['markAsVIP'] = Arr::isTrue($setting, 'markAsVIP');
        $settings['doubleOptIn'] = Arr::isTrue($setting, 'doubleOptIn');

        return wp_parse_args($setting, $defaults);
    }

    private function getLists()
    {
        $settings = get_option('_wppayform_mailchimp_details');
        try {
            $MailChimp = new MailChimp($settings['apiKey']);
            $lists = $MailChimp->get('lists', array('count' => 9999));
            if (!$MailChimp->success()) {
                return [];
            }
        } catch (\Exception $exception) {
            return [];
        }

        $formattedLists = [];
        foreach ($lists['lists'] as $list) {
            $formattedLists[$list['id']] = $list['name'];
        }

        return $formattedLists;
    }

    public function getMergeFields($list, $listId, $formId)
    {
        if (!$this->isConfigured()) {
            return false;
        }
        $settings = get_option('_wppayform_mailchimp_details');

        try {
            $MailChimp = new MailChimp($settings['apiKey']);
            $list = $MailChimp->get('lists/' . $listId . '/merge-fields', array('count' => 9999));
            if (!$MailChimp->success()) {
                return false;
            }
        } catch (\Exception $exception) {
            return false;
        }

        $mergedFields = $list['merge_fields'];
        $fields = array();

        foreach ($mergedFields as $merged_field) {
            $fields[$merged_field['tag']] = $merged_field['name'];
        }

        return $fields;
    }

    public function fetchInterestGroups($request)
    {
        $settings = wp_unslash(Arr::get($request, 'settings'));

        $listId = Arr::get($settings, 'list_id');
        if (!$listId) {
            wp_send_json_success([
                'categories' => [],
                'subcategories' => [],
                'reset_values' => true
            ]);
        }

        $categoryId = Arr::get($settings, 'interest_group.category');
        $categories = $this->getInterestCategories($listId);

        $subCategories = [];
        if ($categoryId) {
            $subCategories = $this->getInterestSubCategories($listId, $categoryId);
        }

        wp_send_json_success([
            'categories' => $categories,
            'subcategories' => $subCategories,
            'reset_values' => !$categories && !$subCategories
        ]);
    }

    private function getInterestCategories($listId)
    {
        $settings = get_option('_wppayform_mailchimp_details');
        try {
            $MailChimp = new MailChimp($settings['apiKey']);
            $categories = $MailChimp->get('/lists/'.$listId.'/interest-categories', array(
                'count' => 9999,
                'fields' => 'categories.id,categories.title'
            ));
            if (!$MailChimp->success()) {
                return [];
            }
        } catch (\Exception $exception) {
            return [];
        }
        $categories = Arr::get($categories, 'categories', []);
        $formattedLists = [];
        foreach ($categories as $list) {
            $formattedLists[] = [
              'value' => $list['id'],
              'label' =>   $list['title']
            ];
        }
        return $formattedLists;
    }

    private function getInterestSubCategories($listId, $categoryId)
    {
        $settings = get_option('_wppayform_mailchimp_details');
        try {
            $MailChimp = new MailChimp($settings['apiKey']);
            $categories = $MailChimp->get('/lists/'.$listId.'/interest-categories/'.$categoryId.'/interests', array(
                'count' => 9999,
                'fields' => 'interests.id,interests.name'
            ));
            if (!$MailChimp->success()) {
                return [];
            }
        } catch (\Exception $exception) {
            return [];
        }
        $categories = Arr::get($categories, 'interests', []);
        $formattedLists = [];
        foreach ($categories as $list) {
            $formattedLists[] = [
                'value' => $list['id'],
                'label' =>   $list['name']
            ];
        }
        return $formattedLists;
    }


    /*
    * For Handling Notifications broadcast
    */
    public function notify($feed, $formData, $entry, $formId)
    {
        // especially for asynchronous notifications
        if( null == gettype($formData) || !$formData) {
            $formData = Arr::get($entry, 'form_data_formatted');
        }
        
        $response = $this->subscribe($feed, $formData, $entry, $formId);
       
        if ($response === true) {
            $this->addLog('MailChimp feed has been successfully initiated and pushed data', $formId, $entry->id);
        } else {
            $message = 'Mailchimp feed has been failed to deliver feed';
            if (Arr::get($response, 'status') === 400) {
                $message = 'Mailchimp feed failed-' .  Arr::get($response, 'detail');
            }
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            }
            $this->addLog($message, $formId, $entry->id, 'failed');
        }
    }
}
