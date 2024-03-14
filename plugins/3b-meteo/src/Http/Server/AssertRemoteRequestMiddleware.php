<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Http\Message\ResponseFactoryInterface;
use WP_REST_Request;
use WP_REST_Response;

final class AssertRemoteRequestMiddleware implements MiddlewareInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;

	/**
	 * @var ResponseFactoryInterface
	 */
	private $response_factory;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 * @param ResponseFactoryInterface|null $factory
	 */
	public function __construct(
		ConfigInterface $config,
		ResponseFactoryInterface $factory = null
	) {
		$this->config = $config;
		$this->response_factory = $factory ?? new ResponseFactory();
	}

	/**
	 * @inheritDoc
	 */
	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		$remote_response = $request->get_param(WeatherDataMiddleware::class );

		/**
		 * @var string $body
		 */
		$body = wp_remote_retrieve_body( $remote_response );
		$code = ( int ) \wp_remote_retrieve_response_code( $remote_response );

		$response = $this->response_factory
			->createResponse( $code );

		if ( $code < 300 ) {
			$body = json_decode( $body, true );
		}

		$response->set_data( $body );
		return $response;
	}
}
