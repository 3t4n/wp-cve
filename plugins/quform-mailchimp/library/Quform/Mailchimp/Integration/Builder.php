<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Integration_Builder
{
    /**
     * @var Quform_Mailchimp_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Mailchimp_Options
     */
    protected $options;

    /**
     * @var Quform_Repository
     */
    protected $repository;

    /**
     * @var Quform_Form_Factory
     */
    protected $formFactory;

    /**
     * @param  Quform_Mailchimp_Integration_Repository $integrationRepository
     * @param  Quform_Mailchimp_Options $options
     * @param  Quform_Repository $repository
     * @param  Quform_Form_Factory $formFactory
     */
    public function __construct(
        Quform_Mailchimp_Integration_Repository $integrationRepository,
        Quform_Mailchimp_Options $options,
        Quform_Repository $repository,
        Quform_Form_Factory $formFactory
    ) {
        $this->integrationRepository = $integrationRepository;
        $this->options = $options;
        $this->repository = $repository;
        $this->formFactory = $formFactory;
    }

    /**
     * Handle the request to add a new integration
     */
    public function add()
    {
        $this->validateAddRequest();

        $name = wp_unslash($_POST['name']);

        $nameLength = Quform::strlen($name);

        if ($nameLength == 0) {
            wp_send_json(array(
                'type' => 'error',
                'errors' => array(
                    'qfb-add-new-mc-integration-name' => __('This field is required', 'quform-mailchimp')
                )
            ));
        } elseif ($nameLength > 64) {
            wp_send_json(array(
                'type' => 'error',
                'errors' => array(
                    'qfb-add-new-mc-integration-name' => __('The integration name must be no longer than 64 characters', 'quform-mailchimp')
                )
            ));
        }

        $config = Quform_Mailchimp_Integration::getDefaultConfig();
        $config['name'] = $name;

        $config = $this->integrationRepository->add($config);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => wp_kses(sprintf(
                    /* translators: %1$s: open link tag, %2$s: close link tag */
                    __('Failed to insert into database, check the %1$serror log%2$s for more information', 'quform-mailchimp'),
                    '<a href="http://support.themecatcher.net/quform-wordpress-v2/guides/advanced/enabling-debug-logging">',
                    '</a>'
                ), array('a' => array('href' => array())))
            ));
        }

        wp_send_json(array(
            'type' => 'success',
            'url' => admin_url('admin.php?page=quform.mailchimp&sp=edit&id=' . $config['id'])
        ));
    }

    /**
     * Validate the request to add a new integration
     */
    protected function validateAddRequest()
    {
        if ( ! Quform::isPostRequest() || ! isset($_POST['name']) || ! is_string($_POST['name'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_add_integrations')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }

        if ( ! check_ajax_referer('quform_add_mc_integration', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-mailchimp')
            ));
        }
    }

    /**
     * Get the integration config value with the given key or the default value if it does not exist
     *
     * @param   array   $integration
     * @param   string  $key
     * @return  mixed
     */
    public function getIntegrationConfigValue(array $integration, $key)
    {
        $value = Quform::get($integration, $key);

        if ($value === null) {
            $value = Quform::get(Quform_Mailchimp_Integration::getDefaultConfig(), $key);
        }

        return $value;
    }

    /**
     * Handle the Ajax request to save an integration
     */
    public function save()
    {
        $this->validateSaveRequest();

        $config = json_decode(stripslashes($_POST['integration']), true);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Malformed integration configuration', 'quform-mailchimp')
            ));
        }

        $config = $this->sanitizeIntegration($config);

        $this->validateIntegration($config);

        $this->integrationRepository->save($config);

        wp_send_json(array(
            'type' => 'success'
        ));
    }

    /**
     * Validate the request to save an integration
     */
    protected function validateSaveRequest()
    {
        if ( ! Quform::isPostRequest() || ! isset($_POST['integration'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }

        if ( ! check_ajax_referer('quform_mc_save_integration', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-mailchimp')
            ));
        }
    }

    /**
     * Sanitize the given integration config and return it
     *
     * @param   array  $config
     * @return  array
     */
    protected function sanitizeIntegration(array $config)
    {
        $config['name'] = isset($config['name']) && is_string($config['name']) ? sanitize_text_field($config['name']) : '';
        $config['active'] = isset($config['active']) && is_bool($config['active']) ? $config['active'] : true;
        $config['formId'] = isset($config['formId']) && is_numeric($config['formId']) ? (string) (int) $config['formId'] : null;
        $config['listId'] = isset($config['listId']) && is_string($config['listId']) ? sanitize_text_field($config['listId']) : '';
        $config['listName'] = isset($config['listName']) && is_string($config['listName']) ? sanitize_text_field($config['listName']) : '';
        $config['emailElement'] = isset($config['emailElement']) && is_string($config['emailElement']) ? sanitize_text_field($config['emailElement']) : '';
        $config['mergeFields'] = isset($config['mergeFields']) && is_array($config['mergeFields']) ? $this->sanitizeMergeFields($config['mergeFields']) : array();
        $config['groups'] = isset($config['groups']) && is_array($config['groups']) ? $this->sanitizeGroups($config['groups']) : array();
        $config['tags'] = isset($config['tags']) && is_string($config['tags']) ? sanitize_text_field($config['tags']) : '';
        $config['logicEnabled'] = isset($config['logicEnabled']) && is_bool($config['logicEnabled']) ? $config['logicEnabled'] : false;
        $config['logicAction'] = isset($config['logicAction']) && is_bool($config['logicAction']) ? $config['logicAction'] : true;
        $config['logicMatch'] = isset($config['logicMatch']) && is_string($config['logicMatch']) ? sanitize_text_field($config['logicMatch']) : 'all';
        $config['logicRules'] = isset($config['logicRules']) && is_array($config['logicRules']) ? $this->sanitizeLogicRules($config['logicRules']) : array();

        return $config;
    }

    /**
     * Sanitize the given merge fields array and return it
     *
     * @param   array  $mergeFields
     * @return  array
     */
    protected function sanitizeMergeFields(array $mergeFields)
    {
        foreach ($mergeFields as $key => $mergeField) {
            $mergeFields[$key]['tag'] = isset($mergeField['tag']) && is_string($mergeField['tag']) ? sanitize_text_field($mergeField['tag']) : '';
            $mergeFields[$key]['value'] = isset($mergeField['value']) && is_string($mergeField['value']) ? sanitize_text_field($mergeField['value']) : '';
        }

        return $mergeFields;
    }

    /**
     * Sanitize the given groups array and return it
     *
     * @param   array  $groups
     * @return  array
     */
    protected function sanitizeGroups(array $groups)
    {
        foreach ($groups as $key => $group) {
            $groups[$key] = is_string($group) ? sanitize_text_field($group) : '';
        }

        return $groups;
    }

    /**
     * Sanitize the settings for the given logic rules
     *
     * @param   array  $rules  The logic rules to sanitize
     * @return  array          The sanitized logic rules
     */
    protected function sanitizeLogicRules(array $rules)
    {
        foreach ($rules as $key => $rule) {
            $rules[$key]['elementId'] = isset($rule['elementId']) && is_numeric($rule['elementId']) ? (string) (int) $rule['elementId'] : '';
            $rules[$key]['operator'] = isset($rule['operator']) && is_string($rule['operator']) ? sanitize_text_field($rule['operator']) : 'eq';
            $rules[$key]['optionId'] = isset($rule['optionId']) && is_numeric($rule['optionId']) ? (string) (int) $rule['optionId'] : null;
            $rules[$key]['value'] = isset($rule['value']) && is_string($rule['value']) ? wp_kses_no_null($rule['value'], array('slash_zero' => 'keep')) : '';
        }

        return $rules;
    }

    /**
     * Validate the given integration config
     *
     * @param array $config
     */
    protected function validateIntegration(array $config)
    {
        if ( ! Quform::isNonEmptyString($config['name'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Please enter a name for the integration.', 'quform-mailchimp')
            ));
        }

        if ( ! Quform::isNonEmptyString($config['formId'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Please select a form.', 'quform-mailchimp')
            ));
        }

        if ( ! Quform::isNonEmptyString($config['listId'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Please select a list.', 'quform-mailchimp')
            ));
        }

        if ( ! Quform::isNonEmptyString($config['emailElement'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Please select an Email address field.', 'quform-mailchimp')
            ));
        }

        foreach ($config['mergeFields'] as $mergeField) {
            if ( ! Quform::isNonEmptyString($mergeField['tag'])) {
                wp_send_json(array(
                    'type'    => 'error',
                    'message' => __('Please select a merge field Mailchimp tag.', 'quform-mailchimp')
                ));
            }  elseif ( ! Quform::isNonEmptyString($mergeField['value'])) {
                wp_send_json(array(
                    'type'    => 'error',
                    'message' => __('Please select a merge field value.', 'quform-mailchimp')
                ));
            }
        }
    }

    /**
     * Handle the Ajax request to get the Mailchimp lists
     */
    public function getLists()
    {
        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }

        $apiKey = $this->options->get('apiKey');

        if ( ! Quform::isNonEmptyString($apiKey)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => wp_kses(sprintf(
                    /* translators: %1$s: open link tag, %2$s: close link tag */
                    __('Please verify a Mailchimp API key on the %1$splugin settings page%2$s.', 'quform-mailchimp'),
                    sprintf('<a href="%s">', esc_url(admin_url('admin.php?page=quform.mailchimp&sp=settings'))),
                    '</a>'
                ), array('a' => array('href' => array())))
            ));
        }

        $api = new Quform_Mailchimp_Client($apiKey);
        $response = $api->get('lists', array('count' => 10000));

        if (is_wp_error($response)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => $response->get_error_message()
            ));
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code === 200 && Quform::isNonEmptyString($body)) {
            $data = json_decode($body, true);

            if (isset($data['lists']) && is_array($data['lists'])) {
                $lists = array();

                foreach ($data['lists'] as $list) {
                    $lists[] = array(
                        'id' => $list['id'],
                        'name' => $list['name']
                    );
                }

                wp_send_json(array(
                    'type' => 'success',
                    'lists' => $lists
                ));
            }
        }

        wp_send_json(array(
            'type' => 'error',
            'message' => __('An error occurred fetching the lists', 'quform-mailchimp')
        ));
    }

    /**
     * Handle the Ajax request to get the Quform Email elements
     */
    public function getEmailElements()
    {
        $this->validateGetEmailElementsRequest();

        $config = $this->repository->getConfig((int) $_POST['form_id']);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Form not found', 'quform-mailchimp')
            ));
        }

        $form = $this->formFactory->create($config);

        $elements = array();

        foreach ($form->getRecursiveIterator() as $element) {
            if ($element instanceof Quform_Element_Email) {
                /* translators: %1$s: element admin label, %2$s: element unique ID */
                $label = sprintf(__('%1$s (%2$s)', 'quform-mailchimp'), $element->getAdminLabel(), $element->getIdentifier());

                $elements[] = array(
                    'name' => $element->getName(),
                    'label' => $label
                );
            }
        }

        wp_send_json(array(
            'type' => 'success',
            'elements' => $elements
        ));
    }

    /**
     * Validate the Ajax request to get the Quform Email elements
     */
    protected function validateGetEmailElementsRequest()
    {
        if ( ! Quform::isPostRequest() || ! isset($_POST['form_id']) || ! is_numeric($_POST['form_id'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }
    }

    /**
     * Handle the Ajax request to get the merge fields
     */
    public function getMergeFields()
    {
        $this->validateGetMergeFieldsRequest();

        wp_send_json(array(
            'type' => 'success',
            'elements' => $this->getMergeFieldElements((int) $_POST['form_id']),
            'tags' => $this->getMergeFieldTags($_POST['list_id'])
        ));
    }

    /**
     * Validate the Ajax request to get the merge fields
     */
    protected function validateGetMergeFieldsRequest()
    {
        if ( ! Quform::isPostRequest() ||
            ! isset($_POST['form_id'], $_POST['list_id']) ||
            ! is_numeric($_POST['form_id']) ||
            ! Quform::isNonEmptyString($_POST['list_id'])
        ) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }
    }

    /**
     * Get the list of form elements that can be used in merge fields
     *
     * @param   int    $formId
     * @return  array
     */
    protected function getMergeFieldElements($formId)
    {
        $config = $this->repository->getConfig($formId);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Form not found', 'quform-mailchimp')
            ));
        }

        $form = $this->formFactory->create($config);

        $elements = array();

        foreach ($form->getRecursiveIterator() as $element) {
            if ($element instanceof Quform_Element_Field) {
                if ($element instanceof Quform_Element_Captcha || $element instanceof Quform_Element_Honeypot || $element instanceof Quform_Element_Recaptcha) {
                    continue;
                }

                $elements[] = array(
                    'id' => $element->getId(),
                    'identifier' => $element->getIdentifier(),
                    'label' => $element->getAdminLabel()
                );

                if ($element instanceof Quform_Element_Name) {
                    foreach (Quform_Element_Name::$partKeys as $partKey => $partName) {
                        $part = $element->getPart($partKey);

                        if ($part instanceof Quform_Element_Field) {
                            $namePartLabel = sprintf(
                                /* translators: %1$s: element admin label, %2$s: name of the part */
                                __('%1$s [%2$s]', 'quform-mailchimp'),
                                $element->getAdminLabel(),
                                $this->getNameElementPartName($partKey)
                            );

                            $elements[] = array(
                                'id' => $element->getId() . '.' . $part->getId(),
                                'identifier' => $element->getIdentifier(),
                                'label' => $namePartLabel
                            );
                        }
                    }
                }
            }
        }

        return $elements;
    }

    /**
     * Get the name of the part of the name element with the given key
     *
     * @param   int     $partKey
     * @return  string
     */
    protected function getNameElementPartName($partKey)
    {
        $name = '';

        switch ($partKey) {
            case 1:
                $name = __('Prefix', 'quform-mailchimp');
                break;
            case 2:
                $name = __('First', 'quform-mailchimp');
                break;
            case 3:
                $name = __('Middle', 'quform-mailchimp');
                break;
            case 4:
                $name = __('Last', 'quform-mailchimp');
                break;
            case 5:
                $name = __('Suffix', 'quform-mailchimp');
                break;
        }

        return $name;
    }

    /**
     * Get the list of merge field tags from the Mailchimp API
     *
     * @param   $listId
     * @return  array
     */
    protected function getMergeFieldTags($listId)
    {
        $apiKey = $this->options->get('apiKey');

        if ( ! Quform::isNonEmptyString($apiKey)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => wp_kses(sprintf(
                    /* translators: %1$s: open link tag, %2$s: close link tag */
                    __('Please verify a Mailchimp API key on the %1$splugin settings page%2$s.', 'quform-mailchimp'),
                    sprintf('<a href="%s">', esc_url(admin_url('admin.php?page=quform.mailchimp&sp=settings'))),
                    '</a>'
                    ), array('a' => array('href' => array())))
            ));
        }

        $api = new Quform_Mailchimp_Client($apiKey);
        $endpoint = sprintf('lists/%s/merge-fields', $listId);
        $response = $api->get($endpoint, array('count' => 1000));

        if (is_wp_error($response)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => $response->get_error_message()
            ));
        }

        if (wp_remote_retrieve_response_code($response) !== 200) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('The response from the Mailchimp API was invalid', 'quform-mailchimp')
            ));
        }

        $body = wp_remote_retrieve_body($response);

        if ( ! Quform::isNonEmptyString($body)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('The response from the Mailchimp API was invalid', 'quform-mailchimp')
            ));
        }

        $data = json_decode($body, true);

        if ( ! is_array($data) || ! isset($data['merge_fields']) || ! is_array($data['merge_fields'])) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('The response from the Mailchimp API was invalid', 'quform-mailchimp')
            ));
        }

        $tags = array();

        foreach ($data['merge_fields'] as $mergeField) {
            /* translators: %1$s: merge field name, %2$s: merge field tag */
            $label = sprintf(_x('%1$s (%2$s)', 'mailchimp merge field label', 'quform-mailchimp'), $mergeField['name'], $mergeField['tag']);

            $tags[] = array(
                'tag' => $mergeField['tag'],
                'label' => $label
            );
        }

        return $tags;
    }

    /**
     * Get the HTML for a merge field
     *
     * @return string
     */
    public function getMergeFieldHtml()
    {
        $mdiPrefix = apply_filters('quform_mailchimp_mdi_icon_prefix', 'qfb-mdi');

        ob_start();
        ?>
        <div class="qfb-mc-merge-field qfb-box">
            <div class="qfb-mc-merge-field-columns qfb-cf">
                <div class="qfb-mc-merge-field-column qfb-mc-merge-field-column-tag"></div>
                <div class="qfb-mc-merge-field-column qfb-mc-merge-field-column-value">
                    <div class="qfb-mc-input-variable">
                        <input type="text" class="qfb-mc-merge-field-value" placeholder="<?php esc_attr_e('Select a value', 'quform-mailchimp'); ?>">
                        <span class="qfb-mc-insert-variable" title="<?php esc_attr_e('Insert variable...', 'quform-mailchimp'); ?>"><i class="<?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-code')); ?>"></i></span>
                    </div>
                </div>
            </div>
            <span class="qfb-small-add-button <?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle" title="<?php esc_attr_e('Add new merge field', 'quform-mailchimp'); ?>"></span>
            <span class="qfb-small-remove-button <?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-trash')); ?>" title="<?php esc_attr_e('Remove merge field', 'quform-mailchimp'); ?>"></span>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get the HTML for a blank logic rule
     *
     * @return string
     */
    public function getLogicRuleHtml()
    {
        $mdiPrefix = apply_filters('quform_mailchimp_mdi_icon_prefix', 'qfb-mdi');

        ob_start();
        ?>
        <div class="qfb-logic-rule qfb-box">
            <div class="qfb-logic-rule-columns qfb-cf">
                <div class="qfb-logic-rule-column qfb-logic-rule-column-element"></div>
                <div class="qfb-logic-rule-column qfb-logic-rule-column-operator"></div>
                <div class="qfb-logic-rule-column qfb-logic-rule-column-value"></div>
            </div>
            <span class="qfb-small-add-button <?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle" title="<?php esc_attr_e('Add new logic rule', 'quform-mailchimp'); ?>"></span>
            <span class="qfb-small-remove-button <?php echo esc_attr(Quform_Mailchimp::icon('qfb-icon qfb-icon-trash')); ?>" title="<?php esc_attr_e('Remove logic rule', 'quform-mailchimp'); ?>"></span>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get the available variables for the insert variable menu
     *
     * @return array
     */
    public function getVariables()
    {
        $variables = array(
            'general' => array(
                'heading' => __('General', 'quform-mailchimp'),
                'variables' => array(
                    '{url}' => __('Form URL', 'quform-mailchimp'),
                    '{referring_url}' => __('Referring URL', 'quform-mailchimp'),
                    '{post|ID}' => __('Post ID', 'quform-mailchimp'),
                    '{post|post_title}' => __('Post Title', 'quform-mailchimp'),
                    '{custom_field|my_custom_field}' => __('Custom Field', 'quform-mailchimp'),
                    '{date}' => __('Date', 'quform-mailchimp'),
                    '{time}' => __('Time', 'quform-mailchimp'),
                    '{datetime}' => __('DateTime', 'quform-mailchimp'),
                    '{site_title}' => __('Site Title', 'quform-mailchimp'),
                    '{site_tagline}' => __('Site Description', 'quform-mailchimp'),
                    '{uniqid}' => __('Random Unique ID', 'quform-mailchimp'),
                    '{entry_id}' => __('Entry ID', 'quform-mailchimp'),
                    '{form_name}' => __('Form Name', 'quform-mailchimp'),
                    '{all_form_data}' => __('All Form Data', 'quform-mailchimp'),
                    '{default_email_address}' => __('Default Email Address', 'quform-mailchimp'),
                    '{default_email_name}' => __('Default Email Name', 'quform-mailchimp'),
                    '{default_from_email_address}' => __('Default "From" Email Address', 'quform-mailchimp'),
                    '{default_from_email_name}' => __('Default "From" Email Name', 'quform-mailchimp'),
                    '{admin_email}' => __('Admin Email', 'quform-mailchimp')
                )
            ),
            'user' => array(
                'heading' => __('User', 'quform-mailchimp'),
                'variables' => array(
                    '{ip}' => __('IP Address', 'quform-mailchimp'),
                    '{user_agent}' => __('User Agent', 'quform-mailchimp'),
                    '{user|display_name}' => __('Display Name', 'quform-mailchimp'),
                    '{user|user_email}' => __('Email', 'quform-mailchimp'),
                    '{user|user_login}' => __('Login', 'quform-mailchimp'),
                    '{user_meta|my_user_meta_key}' => __('User Metadata', 'quform-mailchimp')
                )
            )
        );

        return apply_filters('quform_mailchimp_variables', $variables);
    }

    /**
     * Handle the Ajax request to get the groups
     */
    public function getGroups()
    {
        $this->validateGetGroupsRequest();

        $apiKey = $this->options->get('apiKey');

        if ( ! Quform::isNonEmptyString($apiKey)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => wp_kses(sprintf(
                    /* translators: %1$s: open link tag, %2$s: close link tag */
                    __('Please verify a Mailchimp API key on the %1$splugin settings page%2$s.', 'quform-mailchimp'),
                    sprintf('<a href="%s">', esc_url(admin_url('admin.php?page=quform.mailchimp&sp=settings'))),
                    '</a>'
                ), array('a' => array('href' => array())))
            ));
        }

        $api = new Quform_Mailchimp_Client($apiKey);
        $listId = $_POST['list_id'];

        $response = $api->get(sprintf('lists/%s/interest-categories', $listId));

        if (is_wp_error($response)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => $response->get_error_message()
            ));
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code === 200 && Quform::isNonEmptyString($body)) {
            $data = json_decode($body, true);

            if (isset($data['categories']) && is_array($data['categories'])) {
                $categories = array();

                foreach ($data['categories'] as $category) {
                    $categories[] = array(
                        'title' => $category['title'],
                        'interests' => $this->getCategoryInterests($api, $listId, $category['id'])
                    );
                }

                wp_send_json(array(
                    'type' => 'success',
                    'categories' => $categories
                ));
            }
        }

        wp_send_json(array(
            'type' => 'error',
            'message' => __('An error occurred fetching the lists', 'quform-mailchimp')
        ));
    }

    /**
     * Validate the Ajax request to get the groups
     */
    protected function validateGetGroupsRequest()
    {
        if ( ! Quform::isPostRequest() ||
            ! isset($_POST['list_id']) ||
            ! is_string($_POST['list_id'])
        ) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }
    }

    /**
     * Get the interests for the given category ID
     *
     * @param   Quform_Mailchimp_Client  $api
     * @param   string                   $listId
     * @param   string                   $categoryId
     * @return  array
     */
    protected function getCategoryInterests(Quform_Mailchimp_Client $api, $listId, $categoryId)
    {
        $response = $api->get(sprintf('lists/%s/interest-categories/%s/interests', $listId, $categoryId));

        if (is_wp_error($response)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => $response->get_error_message()
            ));
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code === 200 && Quform::isNonEmptyString($body)) {
            $data = json_decode($body, true);

            if (isset($data['interests']) && is_array($data['interests'])) {
                $interests = array();

                foreach ($data['interests'] as $interest) {
                    $interests[] = array(
                        'id' => $interest['id'],
                        'name' => $interest['name']
                    );
                }

                return $interests;
            }
        }

        wp_send_json(array(
            'type' => 'error',
            'message' => __('An error occurred fetching the groups', 'quform-mailchimp')
        ));
    }

    /**
     * Handle the Ajax request to get the logic source elements
     */
    public function getLogicSources()
    {
        $this->validateGetLogicSourcesRequest();

        wp_send_json(array(
            'type' => 'success',
            'logicSources' => $this->getLogicSourceElements((int) $_POST['form_id']),
        ));
    }

    /**
     * Validate the Ajax request to get the logic source elements
     */
    protected function validateGetLogicSourcesRequest()
    {
        if ( ! Quform::isPostRequest() ||
            ! isset($_POST['form_id']) ||
            ! is_numeric($_POST['form_id'])
        ) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-mailchimp')
            ));
        }

        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Insufficient permissions', 'quform-mailchimp')
            ));
        }
    }

    /**
     * Get the list of elements that can be used as sources for conditional logic
     *
     * @param   int    $formId
     * @return  array
     */
    protected function getLogicSourceElements($formId)
    {
        $config = $this->repository->getConfig($formId);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Form not found', 'quform-mailchimp')
            ));
        }

        $form = $this->formFactory->create($config);

        $elements = array();

        foreach ($form->getRecursiveIterator() as $element) {
            if ($element instanceof Quform_Element_Field) {
                $type = $element->config('type');

                if (in_array($type, array('text', 'textarea', 'email', 'select', 'radio', 'checkbox', 'multiselect', 'file', 'date', 'time', 'hidden', 'password'))) {
                    $e = array(
                        'id' => $element->getId(),
                        'identifier' => $element->getIdentifier(),
                        'type' => $type,
                        'label' => $element->config('label'),
                        'adminLabel' => $element->config('adminLabel')
                    );

                    if ($element instanceof Quform_Element_Multi) {
                        $e['options'] = $element->getOptions();
                    }

                    $elements[] = $e;
                }
            }
        }

        return $elements;
    }
}
