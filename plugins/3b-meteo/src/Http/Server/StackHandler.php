<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use WP_REST_Request;
use WP_REST_Response;
use function array_merge;
use function array_shift;

final class StackHandler implements RequestHandlerInterface {

	/**
	 * @var RequestHandlerInterface
	 */
	private $request_fallback;

	/**
	 * @var MiddlewareInterface[]
	 */
	private $collection = [];

	/**
	 * @param RequestHandlerInterface|null $fallback
	 */
	public function __construct( RequestHandlerInterface $fallback = null ) {
		$this->request_fallback = $fallback ?? new NoHandlerProvidedHandler();
	}

	/**
	 * @param MiddlewareInterface ...$middleware
	 */
	public function withMiddleware( MiddlewareInterface ...$middleware ): void {
		$this->collection = array_merge( $this->collection, $middleware );
	}

	/**
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function handle( WP_REST_Request $request ): WP_REST_Response {
		if ( 0 === count( $this->collection ) ) {
			return $this->request_fallback->handle( $request );
		}

		$middleware = array_shift( $this->collection );
		return $middleware->process( $request, $this );
	}
}
