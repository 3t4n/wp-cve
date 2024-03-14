<?php
declare(strict_types=1);

use ItalyStrap\Cache\SimpleCache;
use ItalyStrap\Config\ConfigFactory;
use TreBiMeteo\Http\Client\WeatherDataClient;
use TreBiMeteo\Http\Message\ResponseFactory;
use TreBiMeteo\Http\Server\AssertRemoteRequestMiddleware;
use TreBiMeteo\Http\Server\AuthMiddleware;
use TreBiMeteo\Http\Server\InjectLabelToResponseObject;
use TreBiMeteo\Http\Server\LocalitiesFixturesMiddleware;
use TreBiMeteo\Http\Server\LocalitiesMiddleware;
use TreBiMeteo\Http\Server\PendingMiddleware;
use TreBiMeteo\Http\Server\RegisteredMiddleware;
use TreBiMeteo\Http\Server\ResetMiddleware;
use TreBiMeteo\Http\Server\SimpleCacheMiddleware;
use TreBiMeteo\Http\Server\StackHandler;
use TreBiMeteo\Http\Server\ToRegisterMiddleware;
use TreBiMeteo\Http\Server\WeatherDataFixturesMiddleware;
use TreBiMeteo\Http\Server\WeatherDataMiddleware;
use TreBiMeteo\Progress;
use TreBiMeteo\UrlBuilder;
use function TreBiMeteo\assert_host_name;
use function TreBiMeteo\is_development;

define( 'TREBIMETEO_URL', plugin_dir_url(__FILE__) );
define( 'TREBIMETEO_PATH', plugin_dir_path(__FILE__) );
define( 'TREBIMETEO_BASENAME', plugin_basename( __FILE__ ) );

define( 'TREBIMETEO_VERSION', '1.0.11' );

/*
 * Plugin activation
 */
$tbm = new TreBiMeteo();
$tbm->boot();
add_action( 'init', [ $tbm, "Enable" ], 1000, 0 );
add_action( 'init', 'trebi_meteo_block_init' );

$config = ConfigFactory::make(
	(array) get_option( 'trebimeteo_config', [] ),
	[
		'api-key'			=> '',
		'secret'			=> '',
		'email'				=> '',
		'progress'			=> Progress::TO_REGISTER,
		'plugin_dir_path'	=> TREBIMETEO_PATH,
		'domain'			=> assert_host_name( \get_bloginfo( 'url' ) ),
		//			'ip_list'			=> sanitize_text_field( $_SERVER[ 'SERVER_ADDR' ] ?? 'invalid_ip' ),
	]
);

add_filter( 'rest_post_dispatch', static function (
	WP_HTTP_Response $response,
	WP_REST_Server   $server,
	WP_REST_Request  $request
) use ( $config ) {

	// Save config at the end of the stack
	$is_updated = update_option( 'trebimeteo_config', $config->toArray() );

	return $response;
}, 10, 3 );

add_action( 'rest_api_init', static function ( WP_REST_Server $wp_rest_server ) use ( $config ) {

	// proxy
	register_rest_route(
		'trebimeteo/v1',
		'/proxy',
		[
			'methods' => WP_REST_Server::READABLE,
			'callback' => static function ( WP_REST_Request $request ) use ( $config ): object {

				$stack = new StackHandler();

				$stack->withMiddleware(
					new AuthMiddleware( $config ),
					// new \TreBiMeteo\Http\Debug\DebugLogSuperGlobalsMiddleware(),
					//					 new \TreBiMeteo\Http\Debug\DebugLogRequestParamsMiddleware(),
					// new \TreBiMeteo\Http\Debug\DebugLogRequestMiddleware(),
					//					new \TreBiMeteo\Http\Debug\DebugResponse(),
					new SimpleCacheMiddleware( new SimpleCache(), new ResponseFactory() ),
					new InjectLabelToResponseObject( new ConfigFactory() ),
					new WeatherDataFixturesMiddleware( $config ), // Test mode
					new WeatherDataMiddleware(
						new WeatherDataClient( $config ),
						new ResponseFactory()
					),
					new AssertRemoteRequestMiddleware( $config )
				);

				return rest_ensure_response( $stack->handle( $request ) );
			},
			'permission_callback' => static function ( WP_REST_Request $request ): bool {
				// We need to return true to allow front end to load data
				return true;
			},
		]
	);

	register_rest_route(
		'trebimeteo/v1',
		'/create',
		[
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => static function ( WP_REST_Request $request ) use ( $config ): object {

				$uri = new UrlBuilder( 'https://wordpress.3bmeteo.com/api/create/' );

				$stack = new StackHandler();

				$stack->withMiddleware(
		//						new \TreBiMeteo\Http\Debug\DebugLogRequestMiddleware(),
		//						new \TreBiMeteo\Http\Debug\AddFilterBeforeHttpRemoteCall(),
		//					new \TreBiMeteo\Http\Debug\DebugLogConfigMiddleware($config),
		//					new \TreBiMeteo\Http\Debug\DebugLogRequestParamsMiddleware(),
					new ResetMiddleware( $config, new ResponseFactory() ),
					new ToRegisterMiddleware( $config, $uri ),
					new PendingMiddleware( $config, new WeatherDataClient( $config ) ),
					new RegisteredMiddleware()
				);

				return rest_ensure_response( $stack->handle( $request ) );
			},
			'permission_callback' => static function ( WP_REST_Request $request ): bool {
				return current_user_can( 'edit_posts' );
			},
			'args'	=> [
				'email'	=> [
					'type'              => 'string',
					'sanitize_callback' => static function ( string $email ) {
						return filter_var( $email, FILTER_SANITIZE_EMAIL );
					},
					'validate_callback'	=> static function ( string $email, WP_REST_Request $request, string $key ) {
						return filter_var( $email, FILTER_VALIDATE_EMAIL );
					},
				],
				'secret'	=> [
					'type'              => 'string',
					'sanitize_callback' => static function ( string $secret ) {
						return sanitize_text_field( $secret );
					},
				],
				'progress'	=> [
					'type'              => 'string',
					'sanitize_callback' => static function ( string $progress ) {
						return sanitize_text_field( $progress );
					},
				],
			],
		]
	);

	register_rest_route(
		'trebimeteo/v1',
		'/site-info',
		[
			'methods' => WP_REST_Server::READABLE,
			'callback' => static function ( WP_REST_Request $request ) use ( $config ): object {

				/**
				 * @var string $email
				 */
				$email = $config->get( 'email' );

				/**
				 * @var string $progress
				 */
				$progress = $config->get( 'progress' );

				/**
				 * @var string $progress
				 */
				$domain = $config->get( 'domain' );

				$info = [
		//					'domain'	=> assert_host_name( esc_url_raw( get_site_url() ) ),
		//					'domain'	=> $domain,
					'domain'	=> sanitize_text_field( $domain ),
					'email'		=> sanitize_text_field( $email ),
					'ip_list'	=> sanitize_text_field(
						$_SERVER[ 'SERVER_ADDR' ] ?? 'invalid_ip'
					),
					'progress'	=> sanitize_text_field( $progress ),
				];

				$response = ( new ResponseFactory() )->createResponse(
					ResponseFactory::STATUS_OK
				);

				$response->set_data(
					[
						'code'		=> '200',
						'message'	=> $info,
					]
				);

				return $response;
			},
			'permission_callback' => static function ( WP_REST_Request $request ): bool {
				return current_user_can( 'edit_posts' );
			},
		]
	);

	register_rest_route(
		'trebimeteo/v1',
		'locality',
		[
			'methods' => WP_REST_Server::READABLE,
			'callback' => static function ( WP_REST_Request $request ) use ( $config ): WP_REST_Response {

				$stack = new StackHandler();

				$stack->withMiddleware(
					new LocalitiesFixturesMiddleware( $config ),
					new LocalitiesMiddleware( $config )
				);

				return rest_ensure_response( $stack->handle( $request ) );
			},
			'permission_callback' => static function ( WP_REST_Request $request ): bool {
				return current_user_can( 'edit_posts' );
			},
		]
	);
} );

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_script('wp-api');
	wp_localize_script(
		'trebimeteo-flex-script',
		'trebimeteoData',
		[
			'url'	=> rest_url('/trebimeteo/v1/proxy'),
		//			'nonce' => \wp_create_nonce( 'trebimeteo' )
			'nonce' => wp_create_nonce( 'wp_rest' )
		]
	);
});
