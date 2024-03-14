<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Http\Message\ResponseFactoryInterface;
use function TreBiMeteo\is_development;

final class LocalitiesFixturesMiddleware implements MiddlewareInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;

	/**
	 * @var ResponseFactoryInterface
	 */
	private $response_factory;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 * @param ResponseFactoryInterface|null $factory
	 */
	public function __construct(
		ConfigInterface $config,
		ResponseFactoryInterface $factory = null
	) {
		$this->config = $config;
		$this->response_factory = $factory ?? new ResponseFactory();
	}

	public function process( \WP_REST_Request $request, RequestHandlerInterface $handler ): \WP_REST_Response {

		if ( ! is_development()  ) {
			return $handler->handle( $request );
		}

		/**
		 * @var string $search
		 */
		$search = $request->get_param( 'search' );

		return $this->apiFilter( $search );
	}

	private function apiFilter( string $inputSearch = '' ): \WP_REST_Response {

		/**
		 * @var string $plugin_path
		 */
		$plugin_path = $this->config->get( 'plugin_dir_path' );

		$path = $plugin_path . 'tests/_data/fixtures/api/';
		$file = 'localities.json';

		$file = (string) \file_get_contents( $path . $file );

		/** @var object $decoded */
		$decoded = (object) json_decode( $file );

		if ( ! \property_exists( $decoded, 'localita' ) ) {
			return $this->response_factory->createResponse(500, \sprintf(
				'Member località on %s does not exists',
				self::class
			));
		}

		$decoded->localita = \array_values( \array_filter( $decoded->localita, function ( $item ) use ( $inputSearch ) {
			if ( empty( $inputSearch ) ) {
				return true;
			}

			/**
			 * Non trova la città secca, ma non importa perché è solo per test
			 */
			return (bool) \strpos( \strtolower( $item->localita ), \strtolower( $inputSearch ) );
		} ) );

		\sort( $decoded->localita );

		$response = $this->response_factory->createResponse();

		$response->set_data([
			'code'		=> 200,
			'message'	=> $decoded,
		]);

		return $response;
	}
}
