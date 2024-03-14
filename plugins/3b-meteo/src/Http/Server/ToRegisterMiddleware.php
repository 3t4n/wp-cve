<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Server;

use ItalyStrap\Config\ConfigInterface;
use TreBiMeteo\Http\Message\OutputSchemaForResponseHelper;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Http\Message\ResponseFactoryInterface;
use TreBiMeteo\Progress;
use TreBiMeteo\UrlBuilderInterface;
use WP_REST_Request;
use WP_REST_Response;
use function filter_var;
use function in_array;
use function intval;
use function is_wp_error;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use function sanitize_email;
use function sanitize_text_field;
use function sprintf;
use function strlen;
use function strval;
use function TreBiMeteo\assert_host_name;
use function wp_safe_remote_post;

final class ToRegisterMiddleware implements MiddlewareInterface {

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $config;

	private $url;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @var ConfigInterface<int|string, int|string>
	 */
	private $api_response;
	/**
	 * @var ResponseFactoryInterface
	 */
	private $response_factory;

	/**
	 * @var string
	 */
	private $error_message;

	/**
	 * @var int
	 */
	private $error_code;

	/**
	 * @var array<string, string>
	 */
	private $remote_response_body_array;

	/**
	 * @var string
	 */
	private $progress;

	/**
	 * @psalm-suppress TooManyTemplateParams
	 * @param ConfigInterface<int|string, int|string> $config
	 * @param UrlBuilderInterface $url
	 * @param ResponseFactoryInterface|null $factory
	 */
	public function __construct(
		ConfigInterface $config,
		UrlBuilderInterface $url,
		ResponseFactoryInterface $factory = null
	) {
		$this->config = $config;
		$this->url = $url;
		$this->response_factory = $factory ?? new ResponseFactory();

		// Empty collection for internal use
		$this->api_response = clone $this->config;
	}

	public function process( WP_REST_Request $request, RequestHandlerInterface $handler ): WP_REST_Response {

		/** @var string $progress */
		$progress = $request->get_param( 'progress' );

		/**
		 * toRegister
		 * pending
		 * registered
		 */
		if ( $progress !== Progress::TO_REGISTER ) {
			return $handler->handle($request); // Go to the pending handler
		}

		/** @var string $email */
		$email = $request->get_param( 'email' );

		// The email is checked at route level validation.
		// So we can add it to database
		$this->config->add( 'email', sanitize_text_field( $email ) );

		/** @var string $domain */
		$domain = $this->config->get('domain');

		/** @var string $ip_list */
		$ip_list = $this->config->get('ip_list');

		$body_request_for_remote = [
			'domain'		=> sanitize_text_field( $domain ),
			'email'			=> sanitize_email( $email ),
			'ip_list'		=> filter_var( $_SERVER[ 'SERVER_ADDR' ], FILTER_VALIDATE_IP )
				? sanitize_text_field( $_SERVER[ 'SERVER_ADDR' ] )
				: 'invalid_ip',
		];

		$api_response = wp_safe_remote_post( $this->url->render(), [
			'headers'	=> [
				'Accept: application/json'
			],
			'body'	=> $body_request_for_remote,
		] );

		/**
		 * This will happen with a wrong url
		 * or inaccessible url so in case bail out.
		 * in WP 5.6 we don't have \rest_convert_error_to_response() function added on 5.7
		 * so we convert the error our self.
		 */
		if ( is_wp_error( $api_response ) ) {
			$response = $this->response_factory
				->createResponse(500, $api_response->get_error_message() );

			$output_schema = ( new OutputSchemaForResponseHelper() )(
				$api_response->get_error_code(),
				[
					OutputSchemaForResponseHelper::ERROR	=> $api_response->get_error_code(),
					OutputSchemaForResponseHelper::ERROR_MESSAGE	=> $api_response->get_error_message(),
				]
			);

			$response->set_data( $output_schema );

			return $response;
		}

		$this->api_response->merge( (array) $api_response );

		$code = intval( $this->api_response->get( 'response.code' ) );
		$body = strval( $this->api_response->get( 'body' ) );

		if ( $code > 399 ) {
			$response = $this->response_factory
				->createResponse( $code, $body );

			$output_schema = new OutputSchemaForResponseHelper();
			$output_schema
				->setCode( $code )
				->setMessage( $body )
				->setData( $body_request_for_remote );

			$response->set_data( $output_schema->toArray() );

			return $response;
		}

		if ( ! $this->isValidData( $body ) ) {
			return $this->response_factory
				->createResponse( $this->error_code, $this->error_message );
		}

		$this->registerApiResponseToDatabase( $request );

		/**
		 * If the url is valid but for some reason a response is not
		 * this will return an error from the remote server
		 *
		 * 'ip_list'	=> '84.33.147.208xxx'
		 *
		 * $api_response['response'] = [
		 * 	'code'		=> 403,
		 * 	'message'	=> 'Forbidden'
		 * ];
		 *
		 * $api_response['body'] = 'Forbidden: ip not allowed'
		 *
		 * 'domain'	=> get_bloginfo( 'url' ).'55555'
		 * $api_response['body'] = 'Forbidden: domain not allowed'
		 *
		 * Schema $api_response['body']:
		 * '{"status":"refresh","domain":"localhost","api-key":"md5-key"}'
		 * '{"status":"new","domain":"www.domain.test","api-key":"md5-key"}'
		 */
		$response = $this->response_factory
			->createResponse( $code );

		$output_schema = new OutputSchemaForResponseHelper();
		$output_schema
			->setCode( $code )
			->setMessage( [
				'progress'	=> $this->progress,
				'extra'		=> self::class
			] )
			->setData( $body_request_for_remote );

		$response->set_data( $output_schema->toArray() );

		return $response;
	}

	private function isValidData( string $message ): bool {

		/**
		 * @var array<string, string>
		 */
		$message = (array) json_decode( $message );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$this->error_message = json_last_error_msg();
			$this->error_code = json_last_error();
			return false;
		}

		$this->remote_response_body_array = $message;

		foreach ( ['status','domain','api-key'] as $value ) {
			if ( empty( $message[ $value ] ) ) {
				$this->error_message = sprintf(
					'Invalid data provided for: %s | Length: %s | Empty: %s | %s',
					$value,
					strlen( strval( $message[ $value ] ) ),
					empty( $message[ $value ] ) ? 'True' : 'False',
					json_encode( $message )
				);
				return false;
			}
		}

		return true;
	}

	/**
	 * @param WP_REST_Request $request
	 */
	private function registerApiResponseToDatabase( WP_REST_Request $request ): void {
		$status = $this->remote_response_body_array[ 'status' ];
		$api_key = $this->remote_response_body_array[ 'api-key' ];

		/**
		 * This happens when you delete the key but
		 * the registration is still valid so
		 * we remove preregistered api-key
		 * and force the registration of the api-key if
		 * new o refresh are in status.
		 */
		$this->config->remove( 'api-key' );

		if ( in_array( $status, ['new', 'refresh'] ) ) {
			$this->config->add( 'api-key', sanitize_text_field( $api_key ) );
			$this->changeProgressToPending( $request );
		}
	}

	/**
	 * @param WP_REST_Request $request
	 */
	private function changeProgressToPending( WP_REST_Request $request ): void {

		/**
		 * Change the state of the progress
		 */
		$this->config->add( 'progress', Progress::PENDING );
		$request->set_param( 'progress', Progress::PENDING );
		$this->progress = Progress::PENDING;
	}
}
