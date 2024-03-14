<?php

namespace MailOptin\KlaviyoConnect;

use MailOptin\Core\Repositories\OptinCampaignsRepository;

class Subscription extends AbstractKlaviyoConnect
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
     * @return mixed
     */
    public function subscribe()
    {
        try {

            $name_split = self::get_first_last_names($this->name);

            $properties = apply_filters('mo_connections_klaviyo_properties', [
                'main' => [
                    'email'      => $this->email,
                    'first_name' => $name_split[0],
                    'last_name'  => $name_split[1],
                ]
            ], $this->optin_campaign_id);

            if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
                $properties['extra']['optin_campaign'] = OptinCampaignsRepository::get_optin_campaign_name($this->optin_campaign_id);
                $properties['extra']['conversion_url'] = $this->extras['conversion_page'];
                $properties['extra']['referrer_url']   = $this->extras['referrer'];
                $properties['extra']['user_agent']     = $this->extras['user_agent'];
            }

            $form_custom_fields    = $this->form_custom_fields();
            $custom_field_mappings = $this->form_custom_field_mappings();

            if (is_array($form_custom_fields) && is_array($custom_field_mappings)) {

                $mapped_custom_fields = array_filter($custom_field_mappings, function ($field) {
                    return ! empty($field);
                });

                foreach ($mapped_custom_fields as $KlaviyoFieldKey => $customFieldKey) {
                    $value = $this->extras[$customFieldKey];
                    //klaviyo doesn't have ui to add custom fields nor support date, checkbox etc. only text as properties is supported.
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }

                    if (in_array($KlaviyoFieldKey, ['$address1', '$address2', '$city', '$country', '$region', '$zip'])) {
                        $properties['main']['location'][str_replace('$', '', $KlaviyoFieldKey)] = esc_attr($value);
                        continue;
                    }

                    $properties['main'][str_replace('$', '', $KlaviyoFieldKey)] = esc_attr($value);
                }

                $properties['main']['location']['ip'] = \MailOptin\Core\get_ip_address();

                foreach ($form_custom_fields as $field) {
                    $field_id    = $field['cid'];
                    $placeholder = $field['placeholder'];

                    if ( ! in_array($field_id, $mapped_custom_fields)) {
                        $properties['extra'][$placeholder] = esc_attr($this->extras[$field_id]);
                    }
                }
            }

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $gdpr_tag                       = apply_filters('mo_connections_klaviyo_acceptance_tag', 'gdpr');
                $properties['extra'][$gdpr_tag] = 'true';
            }

            $response = $this->klaviyo_instance()->add_subscriber(
                $this->list_id,
                $properties
            );

            if ($response['status_code'] >= 200 && $response['status_code'] <= 299) {
                return parent::ajax_success();
            }

            // contact already exist, so success
            if (isset($response['body']->errors[0]->code) && $response['body']->errors[0]->code == 'duplicate_profile') {
                return parent::ajax_success();
            }

            self::save_optin_error_log($response['body']->error . ': ' . $response['body']->message, 'klaviyo', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'klaviyo', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}