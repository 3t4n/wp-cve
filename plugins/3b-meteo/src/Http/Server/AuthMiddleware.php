<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Http\Message\ResponseFactoryInterface;
use TreBiMeteo\Progress;

final class AuthMiddleware implements MiddlewareInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;
	/**
	 * @var ResponseFactoryInterface
	 */
	private $factory;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 * @param ResponseFactoryInterface $factory
	 */
	public function __construct( ConfigInterface $config, ResponseFactoryInterface $factory = null ) {
		$this->config = $config;
		$this->factory = $factory ?? new ResponseFactory();
	}

	public function process( \WP_REST_Request $request, RequestHandlerInterface $handler ): \WP_REST_Response {

		if ( $progress = $this->config->get( 'progress' ) === Progress::REGISTERED ) {
			$request->set_param( 'progress', $progress );
			return $handler->handle( $request );
		}

		return $this->factory->createResponse(
			401,
			\esc_html__( 'You are not registered to 3bMeteo API.', 'trebimeteo' )
		);
	}
}
