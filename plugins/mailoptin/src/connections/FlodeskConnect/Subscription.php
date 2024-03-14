<?php

namespace MailOptin\FlodeskConnect;

use function MailOptin\Core\get_ip_address;

class Subscription extends AbstractFlodeskConnect
{
    public $email;
    public $name;
    public $list_id;
    public $extras;

    protected $optin_campaign_id;

    public function __construct($email, $name, $list_id, $extras)
    {
        $this->email   = $email;
        $this->name    = $name;
        $this->list_id = $list_id;
        $this->extras  = $extras;

        $this->optin_campaign_id = absint($this->extras['optin_campaign_id']);

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

        $setting = $this->get_integration_data('FlodeskConnect_enable_double_optin');

        //external forms
        if ($optin_campaign_id == 0) {
            $setting = $this->extras['is_double_optin'];
        }

        $val = ($setting === true);

        return apply_filters('mo_connections_flodesk_is_double_optin', $val, $optin_campaign_id);
    }

    /**
     * @return mixed
     */
    public function subscribe()
    {
        $name_split = self::get_first_last_names($this->name);

        try {

            $lead_data = [
                'double_optin' => $this->is_double_optin(),
                'first_name'   => $name_split[0],
                'last_name'    => $name_split[1],
                'email'        => $this->email,
                'optin_ip'     => get_ip_address()
            ];

            $custom_field_mappings = $this->form_custom_field_mappings();

            if ( ! empty($custom_field_mappings)) {

                foreach ($custom_field_mappings as $FlodeskFieldKey => $customFieldKey) {
                    // we are checking if $customFieldKey is not empty because if a merge field doesnt have a custom field
                    // selected for it, the default "Select..." value is empty ("")
                    if ( ! empty($customFieldKey) && ! empty($this->extras[$customFieldKey])) {

                        $value = $this->extras[$customFieldKey];

                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }

                        $lead_data['custom_fields'][$FlodeskFieldKey] = $value;
                    }
                }
            }

            $lead_data = apply_filters('mo_connections_flodesk_subscription_parameters', $lead_data, $this);

            $response = $this->flodesk_instance()->make_request("subscribers", $lead_data, 'post');

            if (isset($response['body']->id)) {

                $this->flodesk_instance()->make_request(
                    sprintf("subscribers/%s/segments", $response['body']->id),
                    ['segment_ids' => [$this->list_id]],
                    'post'
                );

                return parent::ajax_success();
            }

            self::save_optin_error_log(wp_json_encode($response['body']), 'flodesk', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'flodesk', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}