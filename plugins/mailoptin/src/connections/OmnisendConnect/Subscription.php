<?php

namespace MailOptin\OmnisendConnect;

use function MailOptin\Core\strtotime_utc;

class Subscription extends AbstractOmnisendConnect
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

            $lead_tags = $this->get_integration_tags('OmnisendConnect_lead_tags');

            $sendWelcomeEmail = $this->get_integration_tags('OmnisendConnect_sendWelcomeEmail');

            $name_split = self::get_first_last_names($this->name);

            $args = [
                'identifiers'      => [
                    [
                        'type'     => 'email',
                        'id'       => $this->email,
                        'channels' => [
                            'email' => [
                                'status' => 'subscribed'
                            ]
                        ]
                    ]
                ],
                'firstName'        => $name_split[0],
                'lastName'         => $name_split[1],
                'sendWelcomeEmail' => (bool)$sendWelcomeEmail
            ];

            if ( ! empty($lead_tags)) {
                $args['tags'] = array_map('trim', explode(',', $lead_tags));
            }

            $form_custom_fields    = $this->form_custom_fields();
            $custom_field_mappings = $this->form_custom_field_mappings();

            if ( ! empty($custom_field_mappings)) {

                foreach ($custom_field_mappings as $OmnisendKey => $customFieldKey) {
                    // we are checking if $customFieldKey is not empty because if a merge field doesn't have a custom field
                    // selected for it, the default "Select..." value is empty ("")
                    if ( ! empty($customFieldKey) && ! empty($this->extras[$customFieldKey])) {
                        $value = $this->extras[$customFieldKey];
                        // date field just works. no multi-select support
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }

                        if ($OmnisendKey == 'birthdate') {
                            $value = gmdate('Y-m-d', strtotime_utc($value));
                        }

                        $args[$OmnisendKey] = $value;
                    }
                }
            }

            $mapped_custom_fields = array_filter($custom_field_mappings, function ($field) {
                return ! empty($field);
            });

            foreach ($form_custom_fields as $field) {
                $field_id    = $field['cid'];
                $placeholder = $field['placeholder'];

                if ( ! in_array($field_id, $mapped_custom_fields)) {
                    $args['customProperties'][$placeholder] = $this->extras[$field_id];
                }
            }

            $args = apply_filters('mo_connections_omnisend_optin_payload', array_filter($args, [$this, 'data_filter']), $this);

            $response = $this->omnisend_instance()->post(
                'contacts',
                $args
            );

            if (self::is_http_code_success($response['status'])) {
                return parent::ajax_success();
            }

            self::save_optin_error_log($response['body'], 'omnisend', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'omnisend', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}