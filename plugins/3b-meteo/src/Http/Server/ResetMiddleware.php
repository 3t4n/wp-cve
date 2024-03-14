<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Progress;
use WP_REST_Request;
use WP_REST_Response;

final class ResetMiddleware implements MiddlewareInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;
	/**
	 * @var ResponseFactory
	 */
	private $factory;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 */
	public function __construct( ConfigInterface $config, ResponseFactory $factory = null ) {
		$this->config = $config;
		$this->factory = $factory ?? new ResponseFactory();
	}

	/**
	 * @param WP_REST_Request $request
	 * @param RequestHandlerInterface $handler
	 * @return WP_REST_Response
	 */
	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		if ( $request->get_param( 'progress' ) !== Progress::RESET ) {
			return $handler->handle($request);
		}

		$this->config->add( 'progress', Progress::TO_REGISTER );
		$request->set_param( 'progress', Progress::TO_REGISTER );

		$this->config->remove( ...\array_keys( $this->config->toArray() ) );

		$response = $this->factory->createResponse();

		$response->set_data( [
			'code'		=> '200',
			'message'	=> [
				'progress'	=> (string) $request->get_param( 'progress' ),
				'extra'		=> self::class
			],
		] );

		return $response;
	}
}
