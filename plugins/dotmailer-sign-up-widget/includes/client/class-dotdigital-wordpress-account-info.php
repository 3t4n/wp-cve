<?php

/**
 * Sanitization handlers for admin settings.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Client;

use Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WordPress\Includes\Exceptions\Dotdigital_WordPress_Password_Validation_Exception;
use Dotdigital_WordPress\Includes\Exceptions\Dotdigital_WordPress_Username_Validation_Exception;
use Dotdigital_WordPress\Includes\Exceptions\Dotdigital_WordPress_Validation_Exception;
class Dotdigital_WordPress_Account_Info
{
    /**
     * Plugin name.
     *
     * @var string $plugin_name
     */
    private $plugin_name;
    /**
     * Dotdigital client.
     *
     * @var Dotdigital_WordPress_Client $dotdigital_client
     */
    private $dotdigital_client;
    /**
     * Construct.
     */
    public function __construct()
    {
        $this->plugin_name = DOTDIGITAL_WORDPRESS_PLUGIN_NAME;
        $this->dotdigital_client = new \Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Client();
    }
    /**
     * Validate the credentials and display a notice
     *
     * @param mixed|null $credentials The credentials.
     *
     * @throws Dotdigital_WordPress_Password_Validation_Exception Password validation exception.
     * @throws Dotdigital_WordPress_Username_Validation_Exception Username validation exception.
     * @throws Dotdigital_WordPress_Validation_Exception Validation exception.
     * @throws ResponseValidationException Response validation exception.
     */
    public function validate_credentials($credentials = null)
    {
        if (!\is_null($credentials)) {
            $this->dotdigital_client->set_credentials($credentials);
        }
        if (empty($this->dotdigital_client->get_api_user()) && empty($this->dotdigital_client->get_api_password())) {
            throw new Dotdigital_WordPress_Validation_Exception('', 200);
        }
        if (empty($this->dotdigital_client->get_api_user()) && !empty($this->dotdigital_client->get_api_password())) {
            throw new Dotdigital_WordPress_Username_Validation_Exception('Please enter a valid API username', 422);
        }
        if (empty($this->dotdigital_client->get_api_password()) && !empty($this->dotdigital_client->get_api_user())) {
            throw new Dotdigital_WordPress_Password_Validation_Exception('Please enter a valid API password', 422);
        }
        try {
            $response = $this->dotdigital_client->get_client()->accountInfo->show();
            $account_properties = $response->getProperties();
            $api_endpoint_index = \array_search('ApiEndpoint', \array_column($account_properties, 'name'));
            $this->dotdigital_client->store_api_endpoint($account_properties[$api_endpoint_index]['value']);
            do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', \sprintf('Your credentials are valid, connected to %s using account (%s)', $this->dotdigital_client->get_api_endpoint(), $this->dotdigital_client->get_api_user()), 'success');
        } catch (ResponseValidationException $exception) {
            $this->dotdigital_client->store_api_endpoint(null);
            throw $exception;
        }
    }
    /**
     * Check if the credentials are valid
     *
     * @return bool
     */
    public function is_connected()
    {
        return (bool) $this->dotdigital_client->get_client()->accountInfo->show();
    }
}
