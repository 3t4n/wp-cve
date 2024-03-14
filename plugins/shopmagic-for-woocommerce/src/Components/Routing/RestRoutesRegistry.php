<?php

namespace WPDesk\ShopMagic\Components\Routing;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\Components\Routing\Controller\ArgumentResolver;
use WPDesk\ShopMagic\Components\Routing\Controller\ContainerControllerResolver;
use WPDesk\ShopMagic\Components\Routing\Controller\ControllerResolver;
use WPDesk\ShopMagic\Components\Routing\Controller\SimpleControllerResolver;

class RestRoutesRegistry implements LoggerAwareInterface {
	use LoggerAwareTrait;

	/** @var RoutesConfigurator */
	private $configurator;

	/** @var ArgumentResolver */
	private $argument_resolver;

	/** @var ControllerResolver */
	private $resolver;

	public function __construct(
		RoutesConfigurator $configurator,
		ControllerResolver $controller_resolver,
		ArgumentResolver $resolver
	) {
		$this->configurator      = $configurator;
		$this->resolver          = $controller_resolver;
		$this->argument_resolver = $resolver;
	}

	public static function with_defaults(
		RoutesConfigurator $configurator,
		?ContainerInterface $container = null
	): self {
		$value_resolvers = [
			new ArgumentResolver\RequestValueResolver(),
		];

		if ( $container ) {
			$value_resolvers[] = new ArgumentResolver\ContainerValueResolver( $container );
		}

		array_push(
			$value_resolvers,
			new ArgumentResolver\ParameterValueResolver(),
			new ArgumentResolver\RawRequestValueResolver(),
			new ArgumentResolver\DefaultValueResolver()
		);

		return new RestRoutesRegistry(
			$configurator,
			$container
				?
				new ContainerControllerResolver( $container )
				:
				new SimpleControllerResolver(),
			new ArgumentResolver( $value_resolvers )
		);
	}

	public function hooks(): void {
		$this->register_routes();
		add_filter( 'rest_pre_dispatch', [ $this, 'set_language_from_header' ], 10, 3 );
		add_filter( 'rest_pre_serve_request', [ $this, 'serve_request' ], 10, 3 );
	}

	private function register_routes(): void {
		foreach ( $this->configurator as $route ) {
			$this->register( $route );
		}
	}

	private function register( Route $route ): void {
		register_rest_route(
			$route->prefix,
			$route->path,
			[
				'methods'             => $route->methods ?: [ 'GET' ],
				'callback'            => function ( \WP_REST_Request $request ) use ( $route ) {
					$controller = $this->resolver->get_controller( $route->controller );
					$arguments  = $this->argument_resolver->get_arguments( $request, $controller );

					try {
						$response = ( $controller )( ...$arguments );
					} catch ( HttpProblemException $e ) {
						return $e->to_http_response();
					} catch ( \Throwable $e ) {
						// Additionally, think about displaying to user generic
						// error message, to keep internals uncovered.
						if ( $this->logger ) {
							$this->logger->critical(
								'Controller `{controller}` failed to process request. Error message: {message}',
								[
									'controller' => get_class( $controller[0] ) . '::' . $controller[1],
									'path'       => $route->path,
									'message'    => $e->getMessage(),
									'file'       => $e->getFile(),
									'line'       => $e->getLine(),
								]
							);
						}
						return HttpProblemException::from_throwable( $e )
													->to_http_response();
					}

					if ( ! $response instanceof \WP_HTTP_Response ) {
						throw new \RuntimeException(
							sprintf(
								'Controller %s::%s() must return %s as response.',
								get_class( $controller[0] ),
								$controller[1],
								\WP_REST_Response::class
							)
						);
					}

					return $response;
				},
				'args'                => $route->args ?? [],
				'permission_callback' => $route->authorize,
			]
		);
	}

	/**
	 * @template T of mixed
	 *
	 * @param T $result
	 * @param \WP_REST_Request<mixed[]> $request
	 *
	 * @return T
	 */
	public function set_language_from_header(
		$result,
		\WP_REST_Server $server,
		\WP_REST_Request $request
	) {
		if ( $lang = $request->get_header( 'Accept-Language' ) ) {
			switch_to_locale( $lang );
		}

		return $result;
	}

	/**
	 * @param bool $_ Unused. We either return own value or exit immediately, so we don't need to respect filter parameter.
	 * @param \WP_REST_Request<mixed[]> $request
	 *
	 * @return bool True if request have been served. False otherwise. Returning false will restore WordPress processing of REST route.
	 */
	public function serve_request(
		$_,
		\WP_REST_Response $response,
		\WP_REST_Request $request
	): bool {
		// Serve requests only for shopmagic routes.
		if ( ! str_starts_with( $request->get_route(), '/shopmagic/' ) ) {
			return false;
		}

		if (
			'HEAD' === $request->get_method() ||
			\WP_Http::NO_CONTENT === $response->get_status() ||
			$response->get_data() === null
		) {
			return true;
		}

		$headers = $response->get_headers();
		if ( isset( $headers['Content-Type'] ) && ! str_contains( $headers['Content-Type'], 'json' ) ) {
			$result = $response->get_data();
		} else {
			$result = json_encode( $response->get_data(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
		}

		// $json_error_message = $this->get_json_last_error();
		//
		// if ( $json_error_message ) {
		// $this->set_status( 500 );
		// $json_error_obj = new WP_Error(
		// 'rest_encode_error',
		// $json_error_message,
		// [ 'status' => 500 ]
		// );
		//
		// $result = $this->error_to_response( $json_error_obj );
		// $result = wp_json_encode( $result->data );
		// }

		echo $result; // phpcs:ignore WordPress.Security.EscapeOutput
		die; // As response is served, quit immediately. Don't allow other hooks to run.
	}
}
