<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use WP_REST_Request;
use WP_REST_Response;

final class NoHandlerProvidedHandler implements RequestHandlerInterface {

	public function handle( WP_REST_Request $request ): WP_REST_Response {
		return new WP_REST_Response( [
			'code'		=> 500,
			'message'	=> 'No handler are provided',
			'extra'		=> [
				'route'		=> (string) $request->get_route(),
				'params'	=> $request->get_params()
			],
		], 500 );
	}
}
