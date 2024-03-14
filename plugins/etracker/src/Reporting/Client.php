<?php
/**
 * Client for etrackers report API.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting;

use Etracker\Reporting\Exceptions\ClientSessionException;
use RestClient;

/**
 * Client for etrackers report API.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Client {
	/**
	 * The etracker Reporting base_url
	 *
	 * @var string
	 */
	private $base_url = 'https://ws.etracker.com/api/v6';

	/**
	 * The etracker token used in X-ET-Token request header.
	 *
	 * @var string
	 */
	private $token = '';

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 *
	 * @access   private
	 *
	 * @var string $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 *
	 * @access   private
	 *
	 * @var string $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * API RestClient object.
	 *
	 * @var RestClient
	 */
	private $api = null;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// create RestClient object.
		$this->api = new \RestClient(
			array(
				'base_url'   => $this->base_url,
				'user_agent' => $this->get_user_agent(),
			)
		);
	}

	/**
	 * Returns formatted User-Agent usable by RestClient.
	 *
	 * @return string
	 */
	public function get_user_agent(): string {
		return sprintf( 'WordPress %s/%s', $this->plugin_name, $this->version );
	}

	/**
	 * Setter for token.
	 *
	 * @param string $token The etracker X-ET-Token.
	 *
	 * @return void
	 */
	public function set_token( string $token ) {
		$this->token = $token;
		// Update API Object to apply token as request header.
		$this->api->set_option(
			'headers',
			array(
				'X-ET-Token' => $this->token,
			)
		);
	}

	/**
	 * Validate etracker token return connected API object.
	 *
	 * @throws ClientSessionException Will be thrown if API access failed.
	 *
	 * @return Client
	 */
	public function ensure_connected(): Client {
		$result_meta_data = $this->api->get(
			// relative url to report metadata.
			'report/EAPage/metaData',
			// get parameters.
			array()
		);

		if ( 200 == $result_meta_data->info->http_code ) {
			// successfully connected.
			return $this;
		} else {
			throw new ClientSessionException( 'Client failed: ', $result_meta_data->info, $result_meta_data->response );
		}
	}

	/**
	 * GET request against reporting API.
	 *
	 * @param string $url        Relative URL to request.
	 * @param array  $parameters GET-Parameters.
	 * @param array  $headers    HTTP-Headers to send.
	 *
	 * @return \RestClient
	 */
	public function get( string $url, $parameters = array(), array $headers = array() ): \RestClient {
		return $this->api->get( $url, $parameters, $headers );
	}

	/**
	 * Returns RestClient API.
	 *
	 * Use only for UnitTests.
	 *
	 * @return \RestClient
	 */
	protected function get_api(): \RestClient {
		return $this->api;
	}
}
