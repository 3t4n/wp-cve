<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * Class Description: Provide API key input
 */

namespace S123\Includes\Pages;

use S123\Includes\Base\S123_BaseController;
use S123\Includes\Helpers\S123_ResponseHelpers;
use S123\Includes\Requests\S123_ApiRequest;

if (!defined('ABSPATH')) exit;

class S123_ApiKey extends S123_BaseController
{
    public function s123_register()
    {
        add_action('wp_ajax_s123_submit_api_key', array($this, 's123_submit_api_key'));
    }

    /*
     * Validate and save provided api key
     */
    public function s123_submit_api_key()
    {
        if (isset($_POST['s123_security']) && wp_verify_nonce($_POST['s123_security'], 's123_security')) {
            $keys = ['api_key'];
            $data = [];

            foreach ($keys as $key) {
                $data[$key] = isset($_POST[$key]) ? sanitize_text_field(trim($_POST[$key])) : null;
            }

            if (!isset($_POST["api_key"]) || $_POST["api_key"] === '') {
                S123_ResponseHelpers::s123_sendErrorResponse(__('API Key cannot be empty!', 's123-invoices'));
            }

            $this->saveApiKey($data);

            // send versions and validate api key
            $this->makeRequestToValidateKey();
        } else {
            S123_ResponseHelpers::s123_sendErrorResponse(__('Invalid secret key specified.', 's123-invoices'));
        }
    }

    public function saveApiKey($data)
    {
        $options = array_merge($this->s123_get_options(), $data);
        $this->s123_update_options($options);
    }

    private function makeRequestToValidateKey()
    {
        $request = new S123_ApiRequest();

        // send versions and validate api key
        $response = $request->s123_makeRequest($request->getApiUrl('validation'), ['versions' => $this->versions()], 'POST');

        if ($response['code'] === 200) {
            S123_ResponseHelpers::s123_sendSuccessResponse(__('API Key saved successfully!', 's123-invoices'));
        } else {
            S123_ResponseHelpers::s123_sendErrorResponse(sprintf(__('Cannot connect provided API key. Error code: %s', 's123-invoices'), $response['code']));
        }
    }
}