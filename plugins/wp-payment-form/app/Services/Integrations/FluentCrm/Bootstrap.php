<?php

namespace WPPayForm\App\Services\Integrations\FluentCrm;

use FluentCrm\App\Models\CustomContactField;
use FluentCrm\App\Models\Lists;
use FluentCrm\App\Models\Subscriber;
use FluentCrm\App\Models\Tag;
use FluentCrm\Framework\Support\Arr;
use WPPayForm\App\Services\ConditionAssesor;
use WPPayForm\App\Services\Integrations\IntegrationManager;
use WPPayForm\Framework\Foundation\App;

class Bootstrap extends IntegrationManager
{
    public $hasGlobalMenu = false;

    public $disableGlobalSettings = 'yes';

    public function __construct()
    {
        parent::__construct(
            App::getInstance(),
            'FluentCRM',
            'fluentcrm',
            '_wppayform_fluentcrm_settings',
            'fluentcrm_feeds',
            10
        );

        $this->logo = WPPAYFORM_URL . 'assets/images/integrations/fluentcrm-logo.png';

        $this->description = __('Connect FluentCRM with Paymattic and subscribe a contact when a form is submitted.', 'wp-payment-form');

        $this->registerAdminHooks();

        // add_filter('wppayform_notifying_async_fluentcrm', '__return_false');
    }

    public function pushIntegration($integrations, $formId)
    {
        $integrations[$this->integrationKey] = [
            'title' => $this->title . ' Integration',
            'logo' => $this->logo,
            'is_active' => $this->isConfigured(),
            'configure_title' => __('Configuration required!', 'wp-payment-form'),
            'global_configure_url' => '#',
            'configure_message' => __('FluentCRM is not configured yet! Please configure your FluentCRM api first', 'wp-payment-form'),
            'configure_button_text' => __('Set FluentCRM', 'wp-payment-form')
        ];
        return $integrations;
    }

    public function getIntegrationDefaults($settings, $formId)
    {
        return [
            'name' => '',
            'full_name' => '',
            'email' => '',
            'other_fields' => [
                [
                    'item_value' => '',
                    'label' => ''
                ]
            ],
            'list_id' => '',
            'tag_ids' => [],
            'tag_ids_selection_type' => 'simple',
            'tag_routers' => [],
            'skip_if_exists' => false,
            'double_opt_in' => false,
            'conditionals' => [
                'conditions' => [],
                'status' => false,
                'type' => 'all'
            ],
            'enabled' => true
        ];
    }

    public function getSettingsFields($settings, $formId)
    {
        $fieldOptions = [];
        foreach (Subscriber::mappables() as $key => $column) {
            $fieldOptions[$key] = $column;
        }

        foreach ((new CustomContactField)->getGlobalFields()['fields'] as $field) {
            $fieldOptions[$field['slug']] = $field['label'];
        }

        unset($fieldOptions['email']);
        unset($fieldOptions['first_name']);
        unset($fieldOptions['last_name']);

        return [
            'fields' => [
                [
                    'key' => 'name',
                    'label' => __('Feed Name', 'wp-payment-form'),
                    'required' => true,
                    'placeholder' => __('Your Feed Name', 'wp-payment-form'),
                    'component' => 'text'
                ],
                [
                    'key' => 'list_id',
                    'label' => __('FluentCRM List', 'wp-payment-form'),
                    'placeholder' => __('Select FluentCRM List', 'wp-payment-form'),
                    'tips' => __('Select the FluentCRM List you would like to add your contacts to.', 'wp-payment-form'),
                    'component' => 'select',
                    'required' => true,
                    'options' => $this->getLists(),
                ],
                [
                    'key' => 'CustomFields',
                    'require_list' => false,
                    'label' => __('Primary Fields', 'wp-payment-form'),
                    'tips' => __('Associate your FluentCRM merge tags to the appropriate WPPayForm fields by selecting the appropriate form field from the list.', 'wp-payment-form'),
                    'component' => 'map_fields',
                    'field_label_remote' => __('FluentCRM Field', 'wp-payment-form'),
                    'field_label_local' => __('Form Field', 'wp-payment-form'),
                    'primary_fileds' => [
                        [
                            'key' => 'email',
                            'label' => __('Email Address', 'wp-payment-form'),
                            'required' => true,
                            'input_options' => 'emails'
                        ],
                        [
                            'key' => 'full_name',
                            'label' => __('Full Name', 'wp-payment-form'),
                            'help_text' => __('If First Name & Last Name is not available full name will be used to get first name and last name', 'wp-payment-form')
                        ]
                    ]
                ],
                [
                    'key' => 'other_fields',
                    'require_list' => false,
                    'label' => __('Other Fields', 'wp-payment-form'),
                    'tips' => 'Select which WPPayForm fields pair with their<br /> respective FlunentCRM fields.',
                    'component' => 'dropdown_many_fields',
                    'field_label_remote' => __('FluentCRM Field', 'wp-payment-form'),
                    'field_label_local' => __('Form Field', 'wp-payment-form'),
                    'options' => $fieldOptions
                ],
                [
                    'key' => 'tag_ids',
                    'require_list' => false,
                    'label' => __('Contact Tags', 'wp-payment-form'),
                    'placeholder' => __('Select Tags', 'wp-payment-form'),
                    'component' => 'selection_routing',
                    'simple_component' => 'select',
                    'routing_input_type' => 'select',
                    'routing_key' => 'tag_ids_selection_type',
                    'settings_key' => 'tag_routers',
                    'is_multiple' => true,
                    'labels' => [
                        'choice_label' => __('Enable Dynamic Tag Selection', 'wp-payment-form'),
                        'input_label' => '',
                        'input_placeholder' => __('Set Tag', 'wp-payment-form')
                    ],
                    'options' => $this->getTags()
                ],
                [
                    'key' => 'skip_if_exists',
                    'require_list' => false,
                    'checkbox_label' => __('Skip if contact already exist in FluentCRM', 'wp-payment-form'),
                    'component' => 'checkbox-single'
                ],
                [
                    'key' => 'double_opt_in',
                    'require_list' => false,
                    'checkbox_label' => __('Enable Double Option for new contacts', 'wp-payment-form'),
                    'component' => 'checkbox-single'
                ],
                [
                    'require_list' => false,
                    'key'          => 'conditionals',
                    'label'        => __('Conditional Logics', 'wp-payment-form'),
                    'tips'         => __('Allow FluentCRM integration conditionally based on your submission values', 'wp-payment-form'),
                    'component'    => 'conditional_block'
                ],
                [
                    'require_list' => false,
                    'key' => 'enabled',
                    'label' => 'Status',
                    'component' => 'checkbox-single',
                    'checkbox_label' => __('Enable This feed', 'wp-payment-form')
                ]
            ],
            'button_require_list' => false,
            'integration_title' => $this->title
        ];
    }

    public function getMergeFields($list, $listId, $formId)
    {
        return [];
    }

    protected function getLists()
    {
        $lists = Lists::get();
        $formattedLists = [];
        foreach ($lists as $list) {
            $formattedLists[$list->id] = $list->title;
        }
        return $formattedLists;
    }

    protected function getTags()
    {
        $tags = Tag::get();
        $formattedTags = [];
        foreach ($tags as $tag) {
            $formattedTags[strval($tag->id)] = $tag->title;
        }
        return $formattedTags;
    }

    /*
     * Form Submission Hooks Here
     */
    public function notify($feed, $formData, $entry, $formId)
    {
        // especially for asynchronous notifications
        if( null == gettype($formData) || !$formData) {
            $formData = Arr::get($entry, 'form_data_formatted');
        }

        $data = $feed['processedValues'];
        $contact = Arr::only($data, ['email']);

        if (!is_email(Arr::get($contact, 'email'))) {
            $contact['email'] = Arr::get($formData, 'customer_email');
        }

        $fullName = Arr::get($data, 'full_name');
        if ($fullName) {
            $nameArray = explode(' ', $fullName);
            if (count($nameArray) > 1) {
                $contact['last_name'] = array_pop($nameArray);
                $contact['first_name'] = implode(' ', $nameArray);
            } else {
                $contact['first_name'] = $fullName;
            }
        }

        foreach (Arr::get($data, 'other_fields') as $field) {
            if ($field['item_value']) {
                $contact[$field['label']] = $field['item_value'];
            }
        }

        if ($entry->ip) {
            $contact['ip'] = $entry->ip;
        }

        if (!is_email($contact['email'])) {
            $this->addLog(
                __('FluentCRM API called skipped because no valid email available', 'wp-payment-form'),
                $formId,
                $entry->id,
                'failed'
            );
            return false;
        }

        $subscriber = Subscriber::where('email', $contact['email'])->first();

        if ($subscriber && Arr::isTrue($data, 'skip_if_exists')) {
            $this->addLog(
                __('Contact creation has been skipped because contact already exist in the database, Subscriber #', 'wp-payment-form') . $subscriber->id,
                $formId,
                $entry->id,
                'failed'
            );
            return false;
        }

        if ($subscriber) {
            if ($subscriber->ip && isset($contact['ip'])) {
                unset($contact['ip']);
            }
        }

        $user = get_user_by('email', $contact['email']);

        if ($user) {
            $contact['user_id'] = $user->ID;
        }

        $tags = $this->getSelectedTagIds($data, $formData, 'tag_ids');

        if ($tags) {
            $contact['tags'] = $tags;
        }

        if (!$subscriber) {
            if (empty($contact['source'])) {
                $contact['source'] = 'Paymattic';
            }

            if (Arr::isTrue($data, 'double_opt_in')) {
                $contact['status'] = 'pending';
            } else {
                $contact['status'] = 'subscribed';
            }

            if ($listId = Arr::get($data, 'list_id')) {
                $contact['lists'] = [$listId];
            }

            $subscriber = FluentCrmApi('contacts')->createOrUpdate($contact, false, false);
            if ($subscriber->status == 'pending') {
                $subscriber->sendDoubleOptinEmail();
            }

            $contactUrl = admin_url('admin.php?page=fluentcrm-admin#/subscribers/' . $subscriber->id);
            $content = __('Contact has been created in FluentCRM. Contact ID: ', 'wp-payment-form') . "<a href='$contactUrl' >$subscriber->id</a>";
            $this->addLog(
                $content,
                $formId,
                $entry->id,
                'success'
            );

            do_action('fluentcrm_contact_added_by_wppayform', $subscriber, $entry, $formId, $feed);
        } else {
            if ($listId = Arr::get($data, 'list_id')) {
                $contact['lists'] = [$listId];
            }

            $hasDouBleOptIn = Arr::isTrue($data, 'double_opt_in');

            $forceSubscribed = !$hasDouBleOptIn && ($subscriber->status != 'subscribed');

            if ($forceSubscribed) {
                $contact['status'] = 'subscribed';
            }

            $subscriber = FluentCrmApi('contacts')->createOrUpdate($contact, $forceSubscribed, false);

            if ($hasDouBleOptIn && ($subscriber->status == 'pending' || $subscriber->status == 'unsubscribed')) {
                $subscriber->sendDoubleOptinEmail();
            }

            do_action('fluentcrm_contact_updated_by_wppayform', $subscriber, $entry, $formId, $feed);

            $this->addLog(
                __('FleuntCRM Contact added Successfully on', 'wp-payment-form') . $feed['settings']['name'],
                $formId,
                $entry->id,
                'success'
            );
        }
    }

    public function isConfigured()
    {
        return true;
    }

    public function isEnabled()
    {
        return true;
    }

    /*
     * We will remove this in future
     */
    protected function getSelectedTagIds($data, $inputData, $simpleKey = 'tag_ids', $routingId = 'tag_ids_selection_type', $routersKey = 'tag_routers')
    {
        // dd("hitt");
        $routing = Arr::get($data, $routingId, 'simple');
        if (!$routing || $routing == 'simple') {
            return Arr::get($data, $simpleKey, []);
        }

        $routers = Arr::get($data, $routersKey);
        if (empty($routers)) {
            return [];
        }
        
        return $this->evaluateRoutings($routers, $inputData);
    }

    /*
     * We will remove this in future
     */
    protected function evaluateRoutings($routings, $inputData)
    {
        $validInputs = [];
        foreach ($routings as $routing) {
            $inputValue = Arr::get($routing, 'input_value');
            if (!$inputValue) {
                continue;
            }
            $condition = [
                'conditionals' => [
                    'status' => true,
                    'is_test' => true,
                    'type' => 'any',
                    'conditions' => [
                        $routing
                    ]
                ]
            ];

            $array_string = print_r($condition, true);

            if (ConditionAssesor::evaluate($condition, $inputData)) {
                $validInputs[] = $inputValue;
            }
        }

        return $validInputs;
    }
}
