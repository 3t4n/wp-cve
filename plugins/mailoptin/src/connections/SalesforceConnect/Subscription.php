<?php

namespace MailOptin\SalesforceConnect;

class Subscription extends AbstractSalesforceConnect
{
    public $email;
    public $name;
    public $list_id;
    public $extras;

    public function __construct($email, $name, $list_id, $extras)
    {
        $this->email   = $email;
        $this->name    = $name;
        $this->list_id = $list_id;
        $this->extras  = $extras;

        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function subscribe()
    {
        try {

            $name_split = self::get_first_last_names($this->name);

            $allObjectFields = Connect::get_instance()->getObjectFields($this->list_id, true, true);

            $properties = [];

            if (in_array('FirstName', $allObjectFields)) {
                $properties['FirstName'] = $name_split[0];
            }

            if (in_array('LastName', $allObjectFields)) {
                $properties['LastName'] = $name_split[1];
            }

            if (in_array('Email', $allObjectFields)) {
                $properties['Email'] = $this->email;
            }

            $custom_field_mappings = $this->form_custom_field_mappings();

            if ( ! empty($custom_field_mappings)) {

                foreach ($custom_field_mappings as $ISKey => $customFieldKey) {
                    // we are checking if $customFieldKey is not empty because if a merge field doesn't have a custom field
                    // selected for it, the default "Select..." value is empty ("")
                    if ( ! empty($customFieldKey) && ! empty($this->extras[$customFieldKey])) {
                        $value = $this->extras[$customFieldKey];

                        // ensures any value that gets here that is an array becomes a string
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }

                        // https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/intro_valid_date_formats.htm

                        $properties[$ISKey] = $value;
                    }
                }
            }

            $properties = apply_filters('mo_connections_salesforce_optin_payload', array_filter($properties, [$this, 'data_filter']), $this);

            // https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/dome_sobject_create.htm
            $response = $this->makeRequest(
                'sobjects/' . $this->list_id,
                'POST',
                $properties,
                ['Content-Type' => 'application/json']
            );

            if (isset($response->success) && $response->success === true) {
                return parent::ajax_success();
            }

            self::save_optin_error_log(is_array($response) ? wp_json_encode($response) : $response, 'salesforce', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            $decoded_message = json_decode($e->getMessage(), true);

            if (isset($decoded_message[0]['errorCode']) && $decoded_message[0]['errorCode'] == 'DUPLICATES_DETECTED') {
                return parent::ajax_success();
            }

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'salesforce', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}