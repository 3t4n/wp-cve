<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Client;

use ItalyStrap\Config\ConfigInterface;
use WP_REST_Request;
use function TreBiMeteo\assert_host_name;

final class WeatherDataClient implements ClientInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 */
	public function __construct(
		ConfigInterface $config
	) {
		$this->config = $config;
	}

	/**
	 */
	public function sendRequest( WP_REST_Request $request ) {

		/**
		 * @var string $domain
		 */
		$domain = $this->config->get( 'domain' );

		/**
		 * @var string $email
		 */
		$email = $this->config->get( 'email' );

		/**
		 * @var string $secret
		 */
		$secret = $this->config->get( 'secret' );

		/**
		 * @var string $api_key
		 */
		$api_key = $this->config->get( 'api-key' );


		/**
		 * daily (tempo medio giorno), 6h (dati esaorari), current (orario ora corrente), hourly (dati orari)
		 */
		$query_args = [
			'tipo'	=> 'previsioni',
			'domain'=> \sanitize_text_field( $domain ),
			'email'	=> sanitize_email( $email ),
			'params'	=> implode(
				',',
				[
					$request->get_param('loc'), // ID locality
					$request->get_param('items'), // n° items
					$request->get_param('lang'), // Lang
					$request->get_param('weatherType') // Type daily
				]
			)
		];

		$query = \http_build_query( $query_args );

		$remote_request_args = [
			'headers'	=> [
				'X-API-KEY'	=> \sanitize_text_field( $api_key ),
				'X-SECRET-KEY'	=> \md5(
				/** @psalm-suppress MixedArgumentTypeCoercion */
					\implode('-', [
						assert_host_name( $domain ),
						\sanitize_email( $email ),
						\sanitize_text_field( $secret ),
					])
				),
			],
		];

		/**
		 * @psalm-suppress UndefinedDocblockClass
		 * @returns  array<string, string>|\WP_Error $remote_response
		 */
		return wp_safe_remote_get(
			'https://wordpress.3bmeteo.com/api/fetch/?' . $query,
			$remote_request_args
		);
	}
}
