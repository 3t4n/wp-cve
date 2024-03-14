<?php

namespace MailOptin\BenchmarkEmailConnect;

use MailOptin\Core\Repositories\OptinCampaignsRepository as OCR;
use function MailOptin\Core\strtotime_utc;

class Subscription extends AbstractBenchmarkEmailConnect
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

            $args = [
                'Data' => [
                    'Email'     => $this->email,
                    'FirstName' => $name_split[0],
                    'LastName'  => $name_split[1],
                    'EmailPerm' => 1
                ]
            ];

            $custom_field_mappings = $this->form_custom_field_mappings();

            if ( ! empty($custom_field_mappings)) {

                foreach ($custom_field_mappings as $BenchmarkEmailKey => $customFieldKey) {
                    // we are checking if $customFieldKey is not empty because if a merge field doesn't have a custom field
                    // selected for it, the default "Select..." value is empty ("")
                    if ( ! empty($customFieldKey) && ! empty($this->extras[$customFieldKey])) {
                        $value = $this->extras[$customFieldKey];
                        // date field just works. no multi-select support
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }

                        if (OCR::get_custom_field_type_by_id($customFieldKey, $this->extras['optin_campaign_id']) == 'date') {
                            $value = gmdate('m/d/Y', strtotime_utc($value));
                        }

                        $args['Data'][$BenchmarkEmailKey] = $value;
                    }
                }
            }

            $args = apply_filters('mo_connections_benchmarkemail_optin_payload', array_filter($args, [$this, 'data_filter']), $this);

            $response = $this->benchmarkemail_instance()->post(
                sprintf('Contact/%s/ContactDetails', $this->list_id),
                $args
            );

            if ( ! empty($response['body']->Response->Data->ContactMasterID)) {
                return parent::ajax_success();
            }

            if (isset($response['body']->Response->Errors)) {

                if (isset($response['body']->Response->Errors[0]->Extra) && $response['body']->Response->Errors[0]->Extra == '102_ALREADY_SUBSCRIBED') {
                    return parent::ajax_success();
                }

                self::save_optin_error_log(wp_json_encode($response['body']->Response->Errors), 'benchmarkemail', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);
            }

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'benchmarkemail', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}