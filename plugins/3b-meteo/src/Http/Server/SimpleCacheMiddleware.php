<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use Exception;
use ItalyStrap\Cache\SimpleCache;
use Psr\SimpleCache\InvalidArgumentException;
use TreBiMeteo\Http\Message\ResponseFactory;
use WP_REST_Request;
use WP_REST_Response;
use function TreBiMeteo\is_development;

final class SimpleCacheMiddleware implements MiddlewareInterface {

	const SIMPLE_CHACHE_KEY = 'trebimeteo_weather_data';

	/**
	 * @var SimpleCache
	 */
	private $cache;
	/**
	 * @var ResponseFactory
	 */
	private $factory;

	/**
	 * @param SimpleCache $cache
	 */
	public function __construct( SimpleCache $cache, ResponseFactory $factory ) {
		$this->cache = $cache;
		$this->factory = $factory;
	}

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		try {
			$ttl = 5 * MINUTE_IN_SECONDS;

			if ( is_development() ) {
				// First we need to call here the handler
				$response = $handler->handle( $request );
				// Then we return the response from the handler
				return $response;
			}

			$cache_key = $this->createCacheKey( $request );

			if ( ! $this->cache->has( $cache_key ) ) {
				$response = $handler->handle( $request );
				$this->cache->set( $cache_key, $response->get_data(), $ttl );
				return $response;
			}

			$new_respose = $this->factory->createResponse( 200 );
			$new_respose->set_data( $this->cache->get( $cache_key, '' ) );

			return $new_respose;
		} catch (InvalidArgumentException | Exception $e) {
			return $this->factory->createResponse( 500, $e->getMessage() );
		}
	}

	private function createCacheKey( WP_REST_Request $request ): string {
		return self::SIMPLE_CHACHE_KEY . '_' . \md5( \serialize( $request->get_params() ) );
	}
}
