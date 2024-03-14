<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

use WPDesk\ShopMagic\Components\Routing\Controller\ArgumentResolver;
use WPDesk\ShopMagic\Components\Routing\Controller\ControllerResolver;

class WpRoutesRegistry implements \WPDesk\ShopMagic\Components\HookProvider\HookProvider {

	/** @var RoutesConfigurator */
	private $configurator;

	/** @var ControllerResolver */
	private $resolver;

	/** @var ArgumentResolver */
	private $argument_resolver;

	private $flush_required = false;

	public function __construct(
		RoutesConfigurator $configurator,
		ControllerResolver $resolver,
		ArgumentResolver $argument_resolver
	) {
		$this->configurator = $configurator;
		$this->resolver = $resolver;
		$this->argument_resolver = $argument_resolver;
	}

	public function hooks(): void {
		add_action(
			'init',
			function (): void {
				$this->register_routes();
			}
		);

		add_filter(
			'query_vars',
			function ( array $vars ): array {
				return $this->add_query_vars( $vars );
			}
		);

		add_action(
			'template_redirect',
			function (): void {
				$this->handle_route();
			}
		);
	}

	private function register_routes(): void {
		$rules = get_option( 'rewrite_rules' );
		foreach ( $this->configurator as $route ) {
			add_rewrite_endpoint( $route->path, EP_ROOT | EP_PAGES );
			$route_rule = $route->path . '(/(.*))?/?$';
			if ( ! isset( $rules[ $route_rule ] ) ) {
				$this->flush_required = true;
			}
		}
		if ( $this->flush_required ) {
			$this->flush_rewrite_rules();
		}
	}

	private function flush_rewrite_rules(): void {
		flush_rewrite_rules( false );
	}

	private function add_query_vars( array $vars ): array {
		foreach ( $this->configurator as $route ) {
			$vars[] = $route->path;
		}

		return $vars;
	}

	private function handle_route(): void {
		global $wp_query;

		foreach ( $this->configurator as $route ) {
			if ( ! \array_key_exists( $route->path, $wp_query->query_vars ) ) {
				continue;
			}

			if ( $wp_query->queried_object_id !== null ) {
				continue;
			}

			if ( ! ( $route->authorize )() ) {
				continue;
			}

			$controller = $this->resolver->get_controller( $route->controller );

			if ( empty( $path ) ) {
				if ( isset( $_SERVER['PATH_INFO'] ) ) {
					$path = $_SERVER['PATH_INFO'];
				} else {
					$path = '/';
				}
			}
			// Mimic WordPress rest request creation.
			$request = new \WP_REST_Request( $_SERVER['REQUEST_METHOD'], $path );

			$request->set_query_params( wp_unslash( $_GET ) );
			$request->set_body_params( wp_unslash( $_POST ) );
			$request->set_file_params( $_FILES );
//			$request->set_headers( $this->get_headers( wp_unslash( $_SERVER ) ) );
//			$request->set_body( self::get_raw_data() );
			$arguments  = $this->argument_resolver->get_arguments( $request, $controller );

			$response = ( $controller )( ...$arguments );
			if ( ! $response instanceof \WP_HTTP_Response ) {
				throw new \RuntimeException(
					sprintf(
						'Controller %s::%s() must return %s as response.',
						get_class( $controller[0] ),
						$controller[1],
						\WP_HTTP_Response::class
					)
				);
			}

			foreach ( $response->get_headers() as $header => $value ) {
				header( $header . ': ' . $value );
			}

			status_header( $response->get_status() );

			if ( $response->get_status() === 204 || $response->get_data() === null ) {
				die;
			}

			echo $response->get_data();
			die;
		}
	}

}
