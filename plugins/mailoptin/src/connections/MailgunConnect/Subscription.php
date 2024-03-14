<?php

namespace MailOptin\MailgunConnect;

class Subscription extends AbstractMailgunConnect
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
        try {

            $lead_data = [
                'address'    => $this->email,
                'name'       => $this->name,
                'subscribed' => 'yes',
                'upsert'     => 'yes'
            ];

            $form_custom_fields = $this->form_custom_fields();

            if (is_array($form_custom_fields)) {

                foreach ($form_custom_fields as $field) {
                    $field_id    = $field['cid'];
                    $placeholder = $field['placeholder'];

                    $lead_data['vars'][$placeholder] = esc_attr($this->extras[$field_id]);
                }
            }

            if ( ! empty($lead_data['vars'])) {
                $lead_data['vars'] = wp_json_encode($lead_data['vars']);
            }

            $lead_data = apply_filters('mo_connections_mailgun_subscription_parameters', $lead_data, $this);

            $response = $this->mailgun_instance()->make_request(sprintf('lists/%s/members', $this->list_id), $lead_data, 'post');

            if (self::is_http_code_success($response['status_code'])) {
                return parent::ajax_success();
            }

            self::save_optin_error_log(json_encode($response['body']), 'mailgun', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'mailgun', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}