<?php

namespace MailOptin\MicrosoftDynamic365Connect;

use MailOptin\Core\Connections\AbstractConnect;

class Subscription extends AbstractMicrosoftDynamic365Connect
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

            $properties = [
                'emailaddress1' => $this->email,
                'firstname'     => $name_split[0],
                'lastname'      => $name_split[1]
            ];

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

                        $properties[$ISKey] = $value;
                    }
                }
            }

            $properties = apply_filters('mo_connections_microsoftdynamic365_optin_payload', array_filter($properties, [$this, 'data_filter']), $this);

            $headers = [
                'Prefer'                           => 'return=representation', // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/webapi/compose-http-requests-handle-errors#prefer-headers
                'MSCRM.SuppressDuplicateDetection' => 'false' // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/webapi/manage-duplicate-detection-create-update#bkmk_create
            ];

            // https://developer.microsoftdynamic365.com/docs/atlas.en-us.api_rest.meta/api_rest/dome_sobject_create.htm
            // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/webapi/reference/contact?view=dataverse-latest
            $response = $this->makeRequest('contacts', 'POST', $properties, $headers);

            if (AbstractConnect::is_http_code_success($response['status'])) {

                if (isset($response['body']->contactid)) {
                    $this->add_contact_to_list($response['body']->contactid);
                }

                return parent::ajax_success();
            }

            self::save_optin_error_log(is_array($response) ? wp_json_encode($response) : $response, 'microsoftdynamic365', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            $decoded_message = json_decode($e->getMessage(), true);

            // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/reference/web-service-error-codes
            if (isset($decoded_message['error']['code']) && $decoded_message['error']['code'] == '0x80040333') {
                return parent::ajax_success();
            }

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'microsoftdynamic365', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }

    /**
     * @param $contact_id
     *
     * @see https://butenko.pro/2018/03/01/how-to-add-records-to-marketing-list-using-webapi/
     */
    public function add_contact_to_list($contact_id)
    {
        try {

            $payload = [
                'List'    => [
                    'listid'      => $this->list_id,
                    '@odata.type' => 'Microsoft.Dynamics.CRM.list'
                ],
                'Members' => [
                    [
                        'contactid'   => $contact_id,
                        '@odata.type' => 'Microsoft.Dynamics.CRM.contact'
                    ]
                ]
            ];

            $this->makeRequest(
                'AddListMembersList',
                'POST',
                $payload,
                ['Prefer' => 'return=representation']
            );

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'microsoftdynamic365', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);
        }
    }
}