<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Http\Message\ResponseFactoryInterface;
use WP_REST_Request;
use WP_REST_Response;
use function http_build_query;
use function implode;
use function is_wp_error;
use function json_decode;
use function md5;
use function sanitize_email;
use function sanitize_text_field;
use function TreBiMeteo\assert_host_name;
use function wp_remote_retrieve_body;
use function wp_safe_remote_get;

final class LocalitiesMiddleware implements MiddlewareInterface {

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

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		/**
		 * @var string $search
		 */
		$search = $request->get_param( 'search' );

		/**
		 * @var string $domain
		 */
		$domain = $this->config->get( 'domain' );

		/**
		 * @var string $email
		 */
		$email = $this->config->get( 'email' );

		/**
		 * @var string $secret
		 */
		$secret = $this->config->get( 'secret' );

		/**
		 * @var string $api_key
		 */
		$api_key = $this->config->get( 'api-key' );

		$args = [
			'tipo'	=> 'ricerca',
			'domain'=> sanitize_text_field( $domain ),
			'email'	=> sanitize_email( $email ),
			'params'	=> implode(
				',',
				[
					empty( $search ) ? 'Abbateggio' : $search,
					'it',
					'it',
					0,
					10
				]
			)
		];

		$query = http_build_query( $args );

		$remote_response = wp_safe_remote_get( 'https://wordpress.3bmeteo.com/api/fetch/?' . $query, [
			'headers'	=> [
				'X-API-KEY'	=> sanitize_text_field( $api_key ),
				'X-SECRET-KEY'	=> md5(
					implode('-', [
						assert_host_name( $domain ),
						sanitize_email( $email ),
						sanitize_text_field( $secret ),
					])
				),
			],
		] );

		/**
		 * This will happen with a wrong url
		 * or inaccessible url so in case bail out.
		 * in WP 5.6 we don't have \rest_convert_error_to_response() function added on 5.7
		 * so we convert the error our self.
		 */
		if ( is_wp_error( $remote_response ) ) {
			$response = $this->response_factory
				->createResponse(500, $remote_response->get_error_message() );

			$response->set_data([
				'error'	=> $remote_response->get_error_code(),
				'error_message'	=> $remote_response->get_error_message(),
			]);

			return $response;
		}

		$body = wp_remote_retrieve_body( $remote_response );

		$response = $this->response_factory
			->createResponse(200 );

		$response->set_data( [
			'code'		=> 200,
			'message'	=> json_decode( $body ),
		] );

		return $response;
	}
}
