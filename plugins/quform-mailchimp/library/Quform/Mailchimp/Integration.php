<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Integration
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var Quform_Form
     */
    protected $form;

    /**
     * @var Quform_Mailchimp_Options
     */
    protected $options;

    public function __construct(array $config, Quform_Form $form, Quform_Mailchimp_Options $options)
    {
        $this->setConfig($config);
        $this->form = $form;
        $this->options = $options;
    }

    /**
     * Run the integration
     */
    public function run()
    {
        if (! Quform::isNonEmptyString($this->config('listId')) ||
            ! Quform::isNonEmptyString($this->config('emailElement'))
        ) {
            return;
        }

        $emailElement = $this->form->getElementByName($this->config('emailElement'));

        if ( ! $emailElement instanceof Quform_Element_Email) {
            return;
        }

        $apiKey = $this->options->get('apiKey');

        if ( ! Quform::isNonEmptyString($apiKey)) {
            return;
        }

        $api = new Quform_Mailchimp_Client($apiKey);
        $emailAddress = $emailElement->getValue();
        $hashedEmailAddress = md5(strtolower($emailAddress));

        $endpoint = sprintf(
            '/lists/%s/members/%s',
            $this->config('listId'),
            $hashedEmailAddress
        );

        $data = array(
            'email_address' => $emailAddress,
            'status_if_new' => $this->config('doubleOptIn') ? 'pending' : 'subscribed',
            'ip_signup' => Quform::getClientIp(),
            'timestamp_signup' => current_time('mysql', true)
        );

        $mergeFields = $this->getMergeFields();

        if (count($mergeFields)) {
            $data['merge_fields'] = $mergeFields;
        }

        $groups = $this->getGroups();

        if (count($groups)) {
            $data['interests'] = $groups;
        }

        $data = apply_filters('quform_mailchimp_integration_data', $data, $this->form, $this);
        $data = apply_filters('quform_mailchimp_integration_data_' . $this->config('id'), $data, $this->form, $this);

        $response = $api->put($endpoint, $data);

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code === 200 && Quform::isNonEmptyString($body)) {
            $contact = json_decode($body, true);

            if (is_array($contact) && isset($contact['status']) && $contact['status'] == 'unsubscribed') {
                $api->patch($endpoint, array('status' => $this->config('doubleOptIn') ? 'pending' : 'subscribed'));
            }
        }

        $tags = $this->config('tags');

        if (Quform::isNonEmptyString($tags)) {
            $tags = explode(',', $tags);
            $data = [];

            foreach ($tags as $tag) {
                $tag = trim($tag);

                if (Quform::isNonEmptyString($tag)) {
                    $data[] = array(
                        'name' => $tag,
                        'status' => 'active'
                    );
                }
            }

            if (count($data)) {
                $api->post($endpoint . '/tags', array('tags' => $data));
            }
        }
    }

    /**
     * Get the merge field values
     *
     * @return array
     */
    protected function getMergeFields()
    {
        $mergeFields = array();

        foreach ($this->config('mergeFields') as $mergeField) {
            if (Quform::isNonEmptyString($mergeField['tag']) && Quform::isNonEmptyString($mergeField['value'])) {
                $mergeFields[$mergeField['tag']] = $this->form->replaceVariables($mergeField['value']);
            }
        }

        $mergeFields = apply_filters('quform_mailchimp_integration_merge_fields', $mergeFields, $this->form, $this);
        $mergeFields = apply_filters('quform_mailchimp_integration_merge_fields_' . $this->config('id'), $mergeFields, $this->form, $this);

        return $mergeFields;
    }

    /**
     * Get the interest group values
     *
     * @return array
     */
    protected function getGroups()
    {
        $groups = array();

        foreach ($this->config('groups') as $group) {
            $groups[$group] = true;
        }

        $groups = apply_filters('quform_mailchimp_integration_groups', $groups, $this->form, $this);
        $groups = apply_filters('quform_mailchimp_integration_groups_' . $this->config('id'), $groups, $this->form, $this);

        return $groups;
    }

    /**
     * Returns the config value for the given $key
     *
     * @param   string  $key
     * @param   null    $default
     * @return  mixed   The config value or $default if not set
     */
    public function config($key, $default = null)
    {
        $value = Quform::get($this->config, $key, $default);

        if ($value === null) {
            $value = Quform::get(call_user_func(array(get_class($this), 'getDefaultConfig')), $key, $default);
        }

        return $value;
    }

    /**
     * Set the config value for the given $key or multiple values using an array
     *
     * @param   string|array  $key    Key or array of key/values
     * @param   mixed         $value  Value or null if $key is array
     * @return  $this
     */
    public function setConfig($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->config[$k] = $v;
            }
        } else {
            $this->config[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the default integration configuration
     *
     * @param   string|null  $key  Get the config by key, if omitted the full config is returned
     * @return  array
     */
    public static function getDefaultConfig($key = null)
    {
        $config = apply_filters('quform_mailchimp_default_integration', array(
            'name' => '',
            'active' => true,
            'trashed' => false,
            'formId' => null,
            'listId' => '',
            'listName' => '',
            'emailElement' => '',
            'doubleOptIn' => false,
            'mergeFields' => array(),
            'groups' => array(),
            'tags' => '',
            'logicEnabled' => false,
            'logicAction' => true,
            'logicMatch' => 'all',
            'logicRules' => array(),
        ));

        if (Quform::isNonEmptyString($key)) {
            return Quform::get($config, $key);
        }

        return $config;
    }
}
