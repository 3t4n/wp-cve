<?php

namespace ImageSeoWP\Actions\Admin\Ajax;

if (!defined('ABSPATH')) {
    exit;
}

class ValidationApiKey
{
	public $optionServices;
	public $clientServices;
	
    public function __construct()
    {
        $this->optionServices = imageseo_get_service('Option');
        $this->clientServices = imageseo_get_service('ClientApi');
    }

    public function hooks()
    {
        add_action('wp_ajax_imageseo_valid_api_key', [$this, 'validate']);
    }

    public function validate()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error([
                'code' => 'not_authorized',
            ]);
            exit;
        }
	    check_ajax_referer( IMAGESEO_OPTION_GROUP . '-options', '_wpnonce' );

        if (!isset($_POST['api_key'])) {
            wp_send_json_error([
                'code' => 'missing_parameters',
            ]);
            exit;
        }

        $apiKey = sanitize_text_field($_POST['api_key']);

        $options['allowed'] = false;
        if (empty($apiKey)) {
            wp_send_json_error([
                'code' => 'missing_parameters',
            ]);
            exit;
        }

        $optionsBdd = $this->optionServices->getOptions();
        $newOptions = wp_parse_args($options, $optionsBdd);

        try {
			$owner = $this->clientServices->validateApiKey($apiKey);

            if ($owner) {
                $newOptions['allowed'] = true;
                $newOptions['api_key'] = $apiKey;
                $this->optionServices->setOptions($newOptions);
            } else {
                $newOptions['allowed'] = false;
                $newOptions['api_key'] = '';
                $this->optionServices->setOptions($newOptions);
            }
        } catch (\Exception $e) {
            wp_send_json_error([
                'code' => 'unknown_error',
            ]);
            exit;
        }

        wp_send_json_success([
            'user'    => $owner,
            'api_key' => $apiKey,
        ]);
    }
}
