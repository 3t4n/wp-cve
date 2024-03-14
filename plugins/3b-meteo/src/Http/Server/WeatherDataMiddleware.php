<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use TreBiMeteo\Http\Client\WeatherDataClient;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Http\Message\ResponseFactoryInterface;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use function is_wp_error;
use function json_decode;

final class WeatherDataMiddleware implements MiddlewareInterface {

	/**
	 * @var WeatherDataClient
	 */
	private $remote_client;

	/**
	 * @var ResponseFactoryInterface
	 */
	private $response_factory;

	/**
	 * @param ResponseFactoryInterface|null $factory
	 */
	public function __construct(
		WeatherDataClient $remote_client,
		ResponseFactoryInterface $factory = null
	) {
		$this->response_factory = $factory ?? new ResponseFactory();
		$this->remote_client = $remote_client;
	}

	/**
	 * @param WP_REST_Request $request
	 * @param RequestHandlerInterface $handler
	 * @return WP_REST_Response
	 */
	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		$remote_response = $this->remote_client->sendRequest( $request );

		/**
		 * This will happen with a wrong url
		 * or inaccessible url so in case bail out.
		 * in WP 5.6 we don't have \rest_convert_error_to_response() function added on 5.7
		 * so we convert the error our self.
		 * @psalm-suppress PossiblyInvalidMethodCall
		 * @psalm-suppress PossiblyInvalidArgument
		 */
		if ( is_wp_error( $remote_response ) ) {
			return $this->createErrorResponse( $remote_response );
		}

		$request->set_param( self::class, $remote_response );

		return $handler->handle( $request );
	}

	/**
	 * @param WP_Error $remote_response
	 * @return WP_REST_Response
	 */
	private function createErrorResponse( WP_Error $remote_response ): WP_REST_Response {
		$response = $this->response_factory->createResponse( 500 );
		$response->set_data( [
			'error' => $remote_response->get_error_code(),
			'error_message' => $remote_response->get_error_message(),
		] );

		return $response;
	}
}
