<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigInterface;
use WP_REST_Request;
use WP_REST_Response;
use function file_get_contents;
use function TreBiMeteo\is_development;

final class WeatherDataFixturesMiddleware implements MiddlewareInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 */
	public function __construct(
		ConfigInterface $config
	) {
		$this->config = $config;
	}

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		if ( ! is_development() ) {
			return $handler->handle( $request );
		}

		/**
		 * @var string $plugin_path
		 */
		$plugin_path = $this->config->get('plugin_dir_path');

		$path = $plugin_path . 'tests/_data/fixtures/api/';
		$files = [
			'esaorario-1-day.json',
			'esaorario-more-days.json',
			'orario-1-day.json',
			'orario-more-days.json',
		];

		//				$file = (string) \file_get_contents( $path . $files[0] );
		$data = (string) file_get_contents( $path . $files[1] );
		//				$file = (string) \file_get_contents( $path . $files[2] );
		//				$file = (string) \file_get_contents( $path . $files[3] );

		\add_filter( 'pre_http_request', function ( $pre ) use ( $data ) {
			return [
				'body' => $data,
				'response' => [
					'code' => 200
				]
			];
		}, 10, 1 );

		return $handler->handle( $request );
	}
}
