<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Zapier_Integration_Builder
{
    /**
     * @var Quform_Zapier_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Zapier_Options
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
     * @param  Quform_Zapier_Integration_Repository $integrationRepository
     * @param  Quform_Zapier_Options $options
     * @param  Quform_Repository $repository
     * @param  Quform_Form_Factory $formFactory
     */
    public function __construct(
        Quform_Zapier_Integration_Repository $integrationRepository,
        Quform_Zapier_Options $options,
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
                    'qfb-zapier-add-new-integration-name' => __('This field is required', 'quform-zapier')
                )
            ));
        } elseif ($nameLength > 64) {
            wp_send_json(array(
                'type' => 'error',
                'errors' => array(
                    'qfb-zapier-add-new-integration-name' => __('The integration name must be no longer than 64 characters', 'quform-zapier')
                )
            ));
        }

        $config = Quform_Zapier_Integration::getDefaultConfig();
        $config['name'] = $name;

        $config = $this->integrationRepository->add($config);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => wp_kses(sprintf(
                    /* translators: %1$s: open link tag, %2$s: close link tag */
                    __('Failed to insert into database, check the %1$serror log%2$s for more information', 'quform-zapier'),
                    '<a href="http://support.themecatcher.net/quform-wordpress-v2/guides/advanced/enabling-debug-logging">',
                    '</a>'
                ), array('a' => array('href' => array())))
            ));
        }

        wp_send_json(array(
            'type' => 'success',
            'url' => admin_url('admin.php?page=quform.zapier&sp=edit&id=' . $config['id'])
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
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        if ( ! current_user_can('quform_zapier_add_integrations')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-zapier')
            ));
        }

        if ( ! check_ajax_referer('quform_zapier_add_integration', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-zapier')
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
            $value = Quform::get(Quform_Zapier_Integration::getDefaultConfig(), $key);
        }

        return $value;
    }

    /**
     * Handle the Ajax request to save an integration
     */
    public function save()
    {
        $this->validateSaveRequest();

        $integration = json_decode(wp_unslash($_POST['integration']), true);

        if ( ! is_array($integration)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Malformed integration configuration', 'quform-zapier')
            ));
        }

        $integration = $this->sanitizeIntegration($integration);

        $this->validateIntegration($integration);

        $this->integrationRepository->save($integration);

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
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        if ( ! current_user_can('quform_zapier_edit_integrations')) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Insufficient permissions', 'quform-zapier')
            ));
        }

        if ( ! check_ajax_referer('quform_zapier_save_integration', false, false)) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Nonce check failed', 'quform-zapier')
            ));
        }
    }

    /**
     * Sanitize the given integration config and return it
     *
     * @param   array  $integration
     * @return  array
     */
    protected function sanitizeIntegration(array $integration)
    {
        $integration['name'] = isset($integration['name']) && is_string($integration['name']) ? sanitize_text_field($integration['name']) : '';
        $integration['active'] = isset($integration['active']) && is_bool($integration['active']) ? $integration['active'] : true;
        $integration['formId'] = isset($integration['formId']) && is_numeric($integration['formId']) ? (string) (int) $integration['formId'] : null;
        $integration['additionalFields'] = isset($integration['additionalFields']) && is_array($integration['additionalFields']) ? $this->sanitizeAdditionalFields($integration['additionalFields']) : array();
        $integration['logicEnabled'] = isset($integration['logicEnabled']) && is_bool($integration['logicEnabled']) ? $integration['logicEnabled'] : false;
        $integration['logicAction'] = isset($integration['logicAction']) && is_bool($integration['logicAction']) ? $integration['logicAction'] : true;
        $integration['logicMatch'] = isset($integration['logicMatch']) && is_string($integration['logicMatch']) ? sanitize_text_field($integration['logicMatch']) : 'all';
        $integration['logicRules'] = isset($integration['logicRules']) && is_array($integration['logicRules']) ? $this->sanitizeLogicRules($integration['logicRules']) : array();

        return $integration;
    }

    /**
     * Sanitize the given additional fields array and return it
     *
     * @param   array  $fields
     * @return  array
     */
    protected function sanitizeAdditionalFields(array $fields)
    {
        foreach ($fields as $key => $field) {
            $fields[$key]['key'] = isset($field['key']) && is_string($field['key']) ? sanitize_text_field($field['key']) : '';
            $fields[$key]['value'] = isset($field['value']) && is_string($field['value']) ? sanitize_text_field($field['value']) : '';
        }

        return $fields;
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
     * @param array $integration
     */
    protected function validateIntegration(array $integration)
    {
        if ( ! Quform::isNonEmptyString($integration['name'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Please enter a name for the integration.', 'quform-zapier')
            ));
        }

        if ( ! Quform::isNonEmptyString($integration['formId'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Please select a form.', 'quform-zapier')
            ));
        }

        if ( ! Quform::isNonEmptyString($integration['webhookUrl'])) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Please enter a webhook URL.', 'quform-zapier')
            ));
        }

        foreach ($integration['additionalFields'] as $field) {
            if ( ! Quform::isNonEmptyString($field['key'])) {
                wp_send_json(array(
                    'type'    => 'error',
                    'message' => __('Please enter a key for the additional field.', 'quform-zapier')
                ));
            }  elseif ( ! Quform::isNonEmptyString($field['value'])) {
                wp_send_json(array(
                    'type'    => 'error',
                    'message' => __('Please enter a value for the additional field.', 'quform-zapier')
                ));
            }
        }
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
                'heading' => __('General', 'quform-zapier'),
                'variables' => array(
                    '{url}' => __('Form URL', 'quform-zapier'),
                    '{referring_url}' => __('Referring URL', 'quform-zapier'),
                    '{post|ID}' => __('Post ID', 'quform-zapier'),
                    '{post|post_title}' => __('Post Title', 'quform-zapier'),
                    '{custom_field|my_custom_field}' => __('Custom Field', 'quform-zapier'),
                    '{date}' => __('Date', 'quform-zapier'),
                    '{time}' => __('Time', 'quform-zapier'),
                    '{datetime}' => __('DateTime', 'quform-zapier'),
                    '{site_title}' => __('Site Title', 'quform-zapier'),
                    '{site_tagline}' => __('Site Description', 'quform-zapier'),
                    '{uniqid}' => __('Random Unique ID', 'quform-zapier'),
                    '{entry_id}' => __('Entry ID', 'quform-zapier'),
                    '{form_name}' => __('Form Name', 'quform-zapier'),
                    '{all_form_data}' => __('All Form Data', 'quform-zapier'),
                    '{default_email_address}' => __('Default Email Address', 'quform-zapier'),
                    '{default_email_name}' => __('Default Email Name', 'quform-zapier'),
                    '{default_from_email_address}' => __('Default "From" Email Address', 'quform-zapier'),
                    '{default_from_email_name}' => __('Default "From" Email Name', 'quform-zapier'),
                    '{admin_email}' => __('Admin Email', 'quform-zapier')
                )
            ),
            'user' => array(
                'heading' => __('User', 'quform-zapier'),
                'variables' => array(
                    '{ip}' => __('IP Address', 'quform-zapier'),
                    '{user_agent}' => __('User Agent', 'quform-zapier'),
                    '{user|display_name}' => __('Display Name', 'quform-zapier'),
                    '{user|user_email}' => __('Email', 'quform-zapier'),
                    '{user|user_login}' => __('Login', 'quform-zapier'),
                    '{user_meta|my_user_meta_key}' => __('User Metadata', 'quform-zapier')
                )
            )
        );

        return apply_filters('quform_zapier_variables', $variables);
    }


    /**
     * Handle the Ajax request to get the elements for additional fields
     */
    public function getAdditionalFieldElements()
    {
        $this->validateGetAdditionalFieldElementsRequest();

        $config = $this->repository->getConfig((int) $_POST['form_id']);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Form not found', 'quform-zapier')
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
                                __('%1$s [%2$s]', 'quform-zapier'),
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

        wp_send_json(array(
            'type' => 'success',
            'elements' => $elements
        ));
    }

    /**
     * Validate the Ajax request to get the additional field elements
     */
    protected function validateGetAdditionalFieldElementsRequest()
    {
        if ( ! Quform::isPostRequest() ||
             ! isset($_POST['form_id']) ||
             ! is_numeric($_POST['form_id'])
        ) {
            wp_send_json(array(
                'type'    => 'error',
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        if ( ! current_user_can('quform_zapier_edit_integrations')) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Insufficient permissions', 'quform-zapier')
            ));
        }
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
                $name = __('Prefix', 'quform-zapier');
                break;
            case 2:
                $name = __('First', 'quform-zapier');
                break;
            case 3:
                $name = __('Middle', 'quform-zapier');
                break;
            case 4:
                $name = __('Last', 'quform-zapier');
                break;
            case 5:
                $name = __('Suffix', 'quform-zapier');
                break;
        }

        return $name;
    }

    /**
     * Get the HTML for a blank logic rule
     *
     * @return string
     */
    public function getLogicRuleHtml()
    {
        $mdiPrefix = apply_filters('quform_zapier_mdi_icon_prefix', 'qfb-mdi');

        ob_start();
        ?>
        <div class="qfb-logic-rule qfb-box">
            <div class="qfb-logic-rule-columns qfb-cf">
                <div class="qfb-logic-rule-column qfb-logic-rule-column-element"></div>
                <div class="qfb-logic-rule-column qfb-logic-rule-column-operator"></div>
                <div class="qfb-logic-rule-column qfb-logic-rule-column-value"></div>
            </div>
            <span class="qfb-small-add-button <?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle" title="<?php esc_attr_e('Add new logic rule', 'quform-zapier'); ?>"></span>
            <span class="qfb-small-remove-button <?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-trash')); ?>" title="<?php esc_attr_e('Remove logic rule', 'quform-zapier'); ?>"></span>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get the HTML for an additional field
     *
     * @return string
     */
    public function getAdditionalFieldHtml()
    {
        $mdiPrefix = apply_filters('quform_zapier_mdi_icon_prefix', 'qfb-mdi');

        ob_start(); ?>
        <div class="qfb-zapier-additional-field qfb-box">
            <div class="qfb-zapier-additional-field-columns qfb-cf">
                <div class="qfb-zapier-additional-field-column qfb-zapier-additional-field-column-key">
                    <input type="text" class="qfb-zapier-additional-field-key" placeholder="<?php esc_attr_e('Key', 'quform-zapier'); ?>">
                </div>
                <div class="qfb-zapier-additional-field-column qfb-zapier-additional-field-column-value">
                    <div class="qfb-zapier-input-variable">
                        <input type="text" class="qfb-zapier-additional-field-value" placeholder="<?php esc_attr_e('Value', 'quform-zapier'); ?>">
                        <span class="qfb-zapier-insert-variable" title="<?php esc_attr_e('Insert variable...', 'quform-zapier'); ?>"><i class="<?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-code')); ?>"></i></span>
                    </div>
                </div>
            </div>
            <span class="qfb-small-add-button <?php echo esc_attr("$mdiPrefix $mdiPrefix"); ?>-add_circle" title="<?php esc_attr_e('Add a new additional field', 'quform-zapier'); ?>"></span>
            <span class="qfb-small-remove-button <?php echo esc_attr(Quform_Zapier::icon('qfb-icon qfb-icon-trash')); ?>" title="<?php esc_attr_e('Remove additional field', 'quform-zapier'); ?>"></span>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Handle the Ajax request to get the logic source elements
     */
    public function getLogicSources()
    {
        $this->validateGetLogicSourcesRequest();

        $config = $this->repository->getConfig((int) $_POST['form_id']);

        if ( ! is_array($config)) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Form not found', 'quform-zapier')
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

        wp_send_json(array(
            'type' => 'success',
            'logicSources' => $elements
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
                'message' => __('Bad request', 'quform-zapier')
            ));
        }

        if ( ! current_user_can('quform_zapier_edit_integrations')) {
            wp_send_json(array(
                'type' => 'error',
                'message' => __('Insufficient permissions', 'quform-zapier')
            ));
        }
    }
}
