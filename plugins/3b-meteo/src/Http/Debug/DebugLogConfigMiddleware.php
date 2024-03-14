<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Debug;

use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Server\MiddlewareInterface;
use TreBiMeteo\Http\Server\RequestHandlerInterface;
use WP_REST_Request;
use WP_REST_Response;
use function TreBiMeteo\is_development;

final class DebugLogConfigMiddleware implements MiddlewareInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 */
	public function __construct( ConfigInterface $config ) {
		$this->config = $config;
	}

	/**
	 * @param WP_REST_Request $request
	 * @param RequestHandlerInterface $handler
	 * @return WP_REST_Response
	 */
	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		if ( ! is_development() ) {
			return $handler->handle( $request );
		}

		\TreBiMeteo\log( \get_class( $this->config ), $this->config->toArray() );

		return $handler->handle( $request );
	}
}
