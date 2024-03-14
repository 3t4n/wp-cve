<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use TreBiMeteo\Progress;
use WP_REST_Request;
use WP_REST_Response;

final class RegisteredMiddleware implements MiddlewareInterface {

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		if ( $request->get_param( 'progress' ) !== Progress::REGISTERED ) {
			return $handler->handle($request); // Go to the registered handler
		}

		return new WP_REST_Response( [
			'code'		=> '200',
			//					'message'	=> '{"status":"new","domain":"www.domain.test","api-key":"md5-key"}',
			'message'	=> [
				'progress'	=> (string) $request->get_param( 'progress' ),
				'extra'		=> self::class
			],
		], 200 );
	}
}
