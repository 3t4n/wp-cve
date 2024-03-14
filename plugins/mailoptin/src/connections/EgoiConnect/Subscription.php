<?php

namespace MailOptin\EgoiConnect;

use MailOptin\Core\Repositories\OptinCampaignsRepository as OCR;
use function MailOptin\Core\strtotime_utc;

class Subscription extends AbstractEgoiConnect
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
     * True if double optin is not disabled.
     *
     * @return bool
     */
    public function is_double_optin()
    {
        $optin_campaign_id = absint($this->extras['optin_campaign_id']);

        $setting = $this->get_integration_data('EgoiConnect_enable_double_optin');

        //external forms
        if ($optin_campaign_id == 0) {
            $setting = $this->extras['is_double_optin'];
        }

        $val = ($setting === true);

        return apply_filters('mo_connections_egoi_is_double_optin', $val, $optin_campaign_id);
    }

    public function subscribe()
    {
        $name_split = self::get_first_last_names($this->name);

        try {

            $lead_data = [
                'base' => [
                    'status'     => $this->is_double_optin() ? 'unconfirmed' : 'active',
                    'first_name' => $name_split[0],
                    'last_name'  => $name_split[1],
                    'email'      => $this->email
                ]
            ];

            $custom_field_mappings = $this->form_custom_field_mappings();

            if ( ! empty($custom_field_mappings)) {

                $base_custom_fields = [
                    'birth_date',
                    'language',
                    'cellphone',
                    'phone'
                ];

                foreach ($custom_field_mappings as $EgoiFieldKey => $customFieldKey) {
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

                        if (in_array($EgoiFieldKey, $base_custom_fields)) {
                            $lead_data[$EgoiFieldKey] = $value;
                        } else {
                            $lead_data['extra'][] = [
                                'field_id' => $EgoiFieldKey,
                                'value'    => $value
                            ];
                        }
                    }
                }
            }

            $lead_data = apply_filters('mo_connections_egoi_subscription_parameters', $lead_data, $this);

            $response = $this->egoi_instance()->make_request("lists/{$this->list_id}/contacts", $lead_data, 'post');

            if ($response['status_code'] === 409) {
                return parent::ajax_success();
            }

            if (isset($response['body']['contact_id'])) {
                $this->assign_subscriber_tags($response['body']['contact_id'], $this->list_id);

                return parent::ajax_success();
            }

            self::save_optin_error_log(wp_json_encode($response['body']), 'egoi', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'egoi', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }

    protected function assign_subscriber_tags($contact_id, $list_id)
    {
        try {

            $tags = $this->get_integration_tags('EgoiConnect_subscriber_tags');

            if (is_array($tags) && ! empty($tags)) {

                foreach ($tags as $tag_id) {

                    $this->egoi_instance()->make_request(
                        "lists/$list_id/contacts/actions/attach-tag",
                        ['contacts' => [$contact_id], 'tag_id' => intval($tag_id)],
                        'post'
                    );
                }
            }

        } catch (\Exception $e) {

        }
    }
}