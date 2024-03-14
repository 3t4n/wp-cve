<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Debug;

use TreBiMeteo\Http\Server\MiddlewareInterface;
use TreBiMeteo\Http\Server\RequestHandlerInterface;
use WP_REST_Request;
use WP_REST_Response;
use function TreBiMeteo\is_development;

final class DebugLogRequestMiddleware implements MiddlewareInterface {

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		if ( ! is_development() ) {
			return $handler->handle( $request );
		}

		\TreBiMeteo\log( \get_class( $request ), $request );

		return $handler->handle( $request );
	}
}
