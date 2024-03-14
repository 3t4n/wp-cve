<?php

/**
 * Initializes the client.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Client;

use Dotdigital_WordPress_Vendor\Dotdigital\AbstractClient;
use Dotdigital_WordPress_Vendor\Dotdigital\V2\Client;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
/**
 * Class Dotdigital_WordPress_Lists
 */
class Dotdigital_WordPress_Client
{
    /**
     * @deprecated
     *
     * Preserved for backwards compatibility.
     */
    private const LEGACY_CONFIG_PATH = 'dotdigital-signup-form';
    /**
     * The plugin name.
     *
     * @var string $plugin_name
     */
    protected $plugin_name;
    /**
     * The client.
     *
     * @var Client $client
     */
    protected $client;
    /**
     * The credentials.
     *
     * @var array $credentials
     */
    protected $credentials;
    /**
     * Construct.
     *
     * @param string $client The client.
     */
    public function __construct(string $client = Client::class)
    {
        $this->plugin_name = DOTDIGITAL_WORDPRESS_PLUGIN_NAME;
        $this->client = new $client();
        $this->credentials = get_option(Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH) ?? array();
        $this->setup_client();
    }
    /**
     * Get client.
     *
     * @return Client
     */
    public function get_client() : AbstractClient
    {
        return $this->client;
    }
    /**
     * Store the API endpoint in the database
     *
     * @param string|null $api_endpoint The API endpoint.
     * @return void
     */
    public function store_api_endpoint($api_endpoint)
    {
        update_option(self::LEGACY_CONFIG_PATH . '_api_endpoint', $api_endpoint);
    }
    /**
     * Get the API endpoint from the passed credentials array or the database
     *
     * @return string
     */
    public function get_api_endpoint() : string
    {
        $host = get_option(self::LEGACY_CONFIG_PATH . '_api_endpoint');
        if (!empty($host)) {
            return $host;
        }
        return Dotdigital_WordPress_Config::API_ENDPOINT;
    }
    /**
     * Get the API user from the passed credentials array or the database
     *
     * @return string|null
     */
    public function get_api_user()
    {
        if (!empty($this->credentials) && !empty($this->credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME])) {
            return $this->credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME];
        }
        return null;
    }
    /**
     * Get the API password from the passed credentials array or the database
     *
     * @return bool|string|null
     */
    public function get_api_password()
    {
        if (!empty($this->credentials) && !empty($this->credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_PASSWORD])) {
            return $this->credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_PASSWORD];
        }
        return null;
    }
    /**
     * Set credentials.
     *
     * @param array $credentials dotdigital creds.
     */
    public function set_credentials(array $credentials)
    {
        $this->credentials = $credentials;
        $this->setup_client();
    }
    /**
     * Set up client.
     *
     * @return void
     */
    private function setup_client()
    {
        $this->client::setApiUser((string) $this->get_api_user());
        $this->client::setApiPassword((string) $this->get_api_password());
        $this->client::setApiEndpoint((string) $this->get_api_endpoint());
    }
}
