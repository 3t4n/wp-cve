<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use WP_REST_Request;
use WP_REST_Response;

interface MiddlewareInterface {

	/**
	 * @param WP_REST_Request $request
	 * @param RequestHandlerInterface $handler
	 * @return WP_REST_Response
	 */
	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response;
}
