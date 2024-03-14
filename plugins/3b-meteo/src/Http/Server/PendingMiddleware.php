<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use Fig\Http\Message\StatusCodeInterface;
use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Client\ClientInterface;
use TreBiMeteo\Http\Client\WeatherDataClient;
use TreBiMeteo\Progress;
use WP_REST_Request;
use WP_REST_Response;
use function sanitize_text_field;

final class PendingMiddleware implements MiddlewareInterface {

	const PENDING = 'pending';

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;

	/**
	 * @var WeatherDataClient
	 */
	private $remote_client;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 */
	public function __construct( ConfigInterface $config, ClientInterface $remote_client ) {
		$this->config = $config;
		$this->remote_client = $remote_client;
	}

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		if ( $request->get_param( 'progress' ) !== Progress::PENDING ) {
			return $handler->handle($request); // Go to the registered handler
		}

		/**
		 * @var string $secret
		 */
		$secret = $request->get_param( 'secret' );

		if ( ! (bool) $secret ) {
			return new WP_REST_Response( [
				'code'		=> 'required_secret_parameters',
				//					'message'	=> '{"status":"new","domain":"www.domain.test","api-key":"md5-key"}',
				'message'	=> [
					'progress'	=> (string) $request->get_param( 'progress' ),
					'extra'		=> self::class
				],
			], StatusCodeInterface::STATUS_BAD_REQUEST );
		}

		$this->config->add(
			'secret',
			sanitize_text_field( $secret )
		);

		$remote_response = $this->remote_client->sendRequest( $request );

		/**
		 * @var string $body
		 */
		$body = wp_remote_retrieve_body( $remote_response );
		$code = ( int ) \wp_remote_retrieve_response_code( $remote_response );

		if ( $code > 299 ) {
			return new WP_REST_Response( [
				'code'		=> $code,
				//					'message'	=> '{"status":"new","domain":"www.domain.test","api-key":"md5-key"}',
				'message'	=> $body,
				'data'	=> [
					'progress'	=> (string) $request->get_param( 'progress' ),
					'extra'		=> self::class
				]
			], $code );
		}

		$this->config->add( 'progress', Progress::REGISTERED );
		$request->set_param( 'progress', Progress::REGISTERED );

		return $handler->handle( $request );
	}
}
