<?php
// phpcs:ignoreFile
declare(strict_types=1);

namespace TreBiMeteo\Http\Debug;

use TreBiMeteo\Http\Server\MiddlewareInterface;
use TreBiMeteo\Http\Server\RequestHandlerInterface;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use function add_filter;
use function sprintf;
use function TreBiMeteo\is_development;

final class AddFilterBeforeHttpRemoteCall implements MiddlewareInterface {

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		if ( ! is_development() ) {
			return $handler->handle($request);
		}

//		$new_response = sprintf(
//			'{"status":"new","domain":"www.domain.test","api-key":"md5-key","Returned-from":"%s"}',
//			'AddFilterBeforeHttpRemoteCall'
//		);
//		$refresh_response = sprintf(
//			'{"status":"refresh","domain":"www.domain.test","api-key":"md5-key","Returned-from":"%s"}',
//			'AddFilterBeforeHttpRemoteCall'
//		);

		$filter = /**
		 * @return ((int|string)[]|string)[]
		 *
		 * @psalm-return array{body: '{"status":"refresh","domain":"localhost","api-key":"md5-key"}', response: array{code: 200, message: 'OK'}}
		 */
		static function ( $preempt = false ): array {
			return [
				// or 'Forbidden: domain not allowed'
//				'body'		=> $new_response,
				'body'		=> '{"status":"refresh","domain":"localhost","api-key":"md5-key"}',
				'response'	=> [
					'code'    	=> 200,
					'message' 	=> 'OK',
				]
			];
		};

		add_filter( 'pre_http_request', $filter );

		$request->offsetSet( AddFilterBeforeHttpRemoteCall::class, $filter );

		add_filter( 'rest_post_dispatch', static function (
			WP_HTTP_Response $response,
			WP_REST_Server $server,
			WP_REST_Request $request
		) {
			$request->offsetUnset( self::class );
			return $response;
		}, 10, 3 );

		return $handler->handle( $request );
	}
}
