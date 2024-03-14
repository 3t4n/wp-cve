<?php

namespace MailOptin\NewsmanConnect;

use MailOptin\Core\Repositories\OptinCampaignsRepository;
use function MailOptin\Core\strtotime_utc;

class Subscription extends AbstractNewsmanConnect
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

            $custom_field_mappings = $this->form_custom_field_mappings();

            $properties = ['via' => 'MailOptin'];

            if ( ! empty($custom_field_mappings)) {

                foreach ($custom_field_mappings as $NMKey => $customFieldKey) {
                    // we are checking if $customFieldKey is not empty because if a merge field doesn't have a custom field
                    // selected for it, the default "Select..." value is empty ("")
                    if ( ! empty($customFieldKey) && ! empty($this->extras[$customFieldKey])) {
                        $value = $this->extras[$customFieldKey];

                        // see https://developers.hubspot.com/docs/faq/how-do-i-set-multiple-values-for-checkbox-properties
                        if (OptinCampaignsRepository::get_custom_field_type_by_id($customFieldKey, $this->extras['optin_campaign_id']) == 'checkbox') {
                            if (is_array($value)) {
                                $properties[$NMKey] = implode(';', $value);
                                continue;
                            }
                        }

                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }

                        $properties[$NMKey] = $value;
                    }
                }
            }

            $ip_address = \MailOptin\Core\get_ip_address();

            $payload = [
                'list_id'   => $this->list_id,
                'email'     => $this->email,
                'firstname' => $name_split[0],
                'lastname'  => $name_split[1],
                'ip'        => empty($ip_address) ? '127.0.0.1' : $ip_address,
                'props'     => $properties
            ];

            $payload = apply_filters('mo_connections_newsman_optin_payload', $payload, $this);

            $response = $this->newsmanInstance()->apiRequest('subscriber.saveSubscribe.json', 'POST', $payload);

            if (is_int($response)) {
                return parent::ajax_success();
            }

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'newsman', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}