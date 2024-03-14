<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Client;

use WP_REST_Request;
use WP_REST_Response;

interface ClientInterface {

	/**
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
//	public function sendRequest( WP_REST_Request $request ): WP_REST_Response;
	public function sendRequest( WP_REST_Request $request );
}
