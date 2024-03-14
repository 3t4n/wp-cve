<?php

namespace MailOptin\Mailerlitev2Connect;

class Subscription extends AbstractMailerlitev2Connect
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

            $lead_data = [
                'email'  => $this->email,
                'fields' => ['name' => $name_split[0], 'last_name' => $name_split[1]],
                'groups' => [$this->list_id]
            ];

            $custom_field_mappings = $this->form_custom_field_mappings();

            if ( ! empty($custom_field_mappings)) {
                foreach ($custom_field_mappings as $MailerLiteFieldKey => $customFieldKey) {
                    // we are checking if $customFieldKey is not empty because if a merge field doesnt have a custom field
                    // selected for it, the default "Select..." value is empty ("")
                    if ( ! empty($customFieldKey) && ! empty($this->extras[$customFieldKey])) {
                        $value = $this->extras[$customFieldKey];
                        // note for date field, ML accept one in this format 2020-01-31 which is the default for pikaday. No multiselect field.
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
                        $lead_data['fields'][$MailerLiteFieldKey] = esc_attr($value);
                    }
                }
            }

            $lead_data = apply_filters('mo_connections_mailerlitev2_optin_payload', array_filter($lead_data, [$this, 'data_filter']), $this);

            $response = $this->mailerlitev2_instance()->make_request('subscribers', $lead_data, 'post');

            if (isset($response['body']['data']['id'])) {
                return parent::ajax_success();
            }

            self::save_optin_error_log(json_encode($response['body']), 'mailerlitev2', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            // if already subscribed, return success.
            if (strpos($e->getMessage(), 'Member Exists')) {
                return parent::ajax_success();
            }

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'mailerlitev2', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}