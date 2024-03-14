<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use WP_REST_Request;
use WP_REST_Response;

interface RequestHandlerInterface {

	/**
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function handle( WP_REST_Request $request ): WP_REST_Response;
}
