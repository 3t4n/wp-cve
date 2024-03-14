<?php

namespace MailOptin\IContactConnect;

use MailOptin\Core\Repositories\OptinCampaignsRepository as OCR;
use function MailOptin\Core\strtotime_utc;

class Subscription extends AbstractIContactConnect
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

    public function subscribe()
    {
        $name_split = self::get_first_last_names($this->name);

        try {

            $lead_data = [
                'firstName' => $name_split[0],
                'lastName'  => $name_split[1],
                'email'     => $this->email
            ];

            $custom_field_mappings = $this->form_custom_field_mappings();

            if ( ! empty($custom_field_mappings)) {

                foreach ($custom_field_mappings as $IcontactFieldKey => $customFieldKey) {
                    // we are checking if $customFieldKey is not empty because if a merge field doesnt have a custom field
                    // selected for it, the default "Select..." value is empty ("")
                    if ( ! empty($customFieldKey) && ! empty($this->extras[$customFieldKey])) {

                        $value = $this->extras[$customFieldKey];

                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }

                        if (OCR::get_custom_field_type_by_id($customFieldKey, $this->extras['optin_campaign_id']) == 'date') {
                            $value = gmdate('Y-m-d', strtotime_utc($value));
                        }

                        $lead_data[$IcontactFieldKey] = $value;
                    }
                }
            }

            $lead_data = apply_filters('mo_connections_icontact_subscription_parameters', $lead_data, $this);

            $response = $this->icontact_instance()->make_request("contacts", [$lead_data], 'post');

            if (isset($response['body']['contacts'][0]['contactId'])) {

                $contact_id = $response['body']['contacts'][0]['contactId'];

                $this->icontact_instance()->make_request(
                    "subscriptions",
                    [['contactId' => $contact_id, 'listId' => $this->list_id, 'status' => 'normal']],
                    'post'
                );

                return parent::ajax_success();
            }

            self::save_optin_error_log(wp_json_encode($response['body']), 'icontact', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'icontact', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}