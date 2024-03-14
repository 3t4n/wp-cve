<?php
/**
 * This Package is based on Appsero/client (v1.0) by weDevs
 * @see https://github.com/Appsero/client/releases/tag/v1.0
 *
 * AbsolutePlugins Services Client
 * @link https://github.com/AbsolutePlugins/AbsolutePluginsServices
 * @version 1.0.0
 * @package AbsolutePluginsServices
 * @license MIT
 */

namespace AbsoluteAddons\AbsolutePluginsServices;;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Client
 */
class Client {

	/**
	 * The client version.
	 *
	 * @var string
	 */
	protected $clientVersion = '1.0.0';

	/**
	 * API EndPoint.
	 *
	 * @var string
	 */
	protected $service_endpoints = [
		'tracker' => 'https://go.absoluteplugins.com/api',
		'license' => 'https://absoluteplugins.com/?wc-api=wc-am-api'
	];

	/**
	 * API Version.
	 *
	 * @var string
	 */
	protected $apiVersion = 'v1';

	/**
	 * Hash identifier of the Plugin/Theme.
	 *
	 * @var string
	 */
	protected $hash;

	/**
	 * Name of the Plugin/Theme.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The Plugin/Theme file path.
	 * Example .../wp-content/Plugin/test-slug/test-slug.php.
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * Main Plugin/Theme file.
	 * Example: test-slug/test-slug.php.
	 *
	 * @var string
	 */
	protected $basename;

	/**
	 * Slug of the Plugin/Theme.
	 * Example: test-slug.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * The project version.
	 *
	 * @var string
	 */
	protected $project_version;

	/**
	 * The project type.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Store Product (unique) id for current Product
	 * Required by WooCommerce API Manager > 2.0.
	 *
	 * @var bool|int
	 */
	protected $product_id;

	/**
	 * Instance of Insights class.
	 *
	 * @var Insights
	 */
	private $insights;

	/**
	 * Instance of Promotions class.
	 *
	 * @var Promotions
	 */
	private $promotions;

	/**
	 * Instance of License class.
	 *
	 * @var License
	 */
	private $license;

	/**
	 * Instance of Updater class.
	 *
	 * @var Updater
	 */
	private $updater;

	/**
	 * Debug Mode Flag.
	 *
	 * @var bool
	 */
	protected $is_debug = false;

	/**
	 * Flag for allowing local request.
	 *
	 * @var bool
	 */
	protected $allow_local = false;

	/**
	 * Initialize the class.
	 *
	 * @param string $hash       hash of the Plugin/Theme.
	 * @param string $name       Name of the Plugin/Theme.
	 * @param string $file       Main Plugin/Theme file path.
	 * @param int    $product_id Store Product id for pro product.
	 *                           If null license page will show field for product id input.
	 * @param array $args {
	 *     Optional Args.
	 *                                    If null license page will show field for product id input.
	 *      @type string $slug            Theme/Plugin Slug.
	 *                                    Default null (autodetect).
	 *      @type string $basename        File Basename.
	 *                                    Default null (autodetect).
	 *      @type string $project_type    Project Type Plugin/Theme.
	 *                                    Default null (autodetect).
	 *      @type string $project_version Project Version. Theme/Plugin Version.
	 *                                    Default null (autodetect).
	 * }
	 *
	 * @return void
	 */
	public function __construct( $hash, $name, $file, $product_id = null, $args = [] ) {

		if ( ! is_string( $file ) ) {
			$message = sprintf(
				/* translators: 1. Argument Type. */
				esc_html__( 'The \'$file\' argument expected be a valid file path string. Got %s.', 'absolute-addons' ),
				gettype( $file )
			);
			_doing_it_wrong( __METHOD__, $message, '1.0.0' );
			return;
		}

		if ( ! file_exists( $file ) || ! is_file( $file ) ) {
			$message = sprintf(
				/* translators: 1. Current Class Name. */
				esc_html__( 'Invalid Argument. The \'$file\' argument needs to be a valid file path for initializing "%s" class', 'absolute-addons' ),
				__CLASS__
			);
			_doing_it_wrong( __METHOD__, $message, '1.0.0' );
			return;
		}

		// Required Data.
		$this->hash = $hash;
		$this->name = $name;
		$this->file = $file;
		$this->product_id = ! empty( $product_id ) ? (int) $product_id : false;


		// Optional Params.
		$args = wp_parse_args(
			$args,
			[
				'product_id'      => false,
				'slug'            => null,
				'basename'        => null,
				'project_type'    => null,
				'project_version' => null,
			]
		);

		$this->product_id      = isset( $args['product_id'] ) && $args['product_id'] ? absint( $args['product_id'] ) : false;
		$this->basename        = $args['basename'];
		$this->slug            = $args['slug'];
		$this->type            = $args['project_type'];
		$this->project_version = $args['project_version'];

		if ( ! $this->basename || ! $this->slug || ! $this->type || ! $this->project_version ) {
			$this->set_basename_and_slug();
		}
	}

	/**
	 * Initialize insights class.
	 *
	 * @return Insights
	 */
	public function insights() {
		if ( ! is_null( $this->insights ) ) {
			return $this->insights;
		}

		if ( ! class_exists( __NAMESPACE__ . '\Insights' ) ) {
			require_once __DIR__ . '/class-insights.php';
		}
		$this->insights = new Insights( $this );

		return $this->insights;
	}

	/**
	 * Initialize Promotions class.
	 *
	 * @return Promotions
	 */
	public function promotions() {
		if ( ! is_null( $this->promotions ) ) {
			return $this->promotions;
		}
		if ( ! class_exists( __NAMESPACE__ . '\Promotions' ) ) {
			require_once __DIR__ . '/class-promotions.php';
		}
		$this->promotions = new Promotions( $this );

		return $this->promotions;
	}

	/**
	 * Initialize license checker.
	 *
	 * @return License
	 */
	public function license() {
		if ( ! is_null( $this->license ) ) {
			return $this->license;
		}

		if ( ! class_exists( __NAMESPACE__ . '\License') ) {
			require_once __DIR__ . '/class-license.php';
		}
		$this->license = new License( $this );

		return $this->license;
	}

	/**
	 * Initialize Plugin/Theme updater.
	 *
	 * @return Updater|void
	 */
	public function updater() {
		if ( ! is_null( $this->updater ) ) {
			return $this->updater;
		}

		// Check if license instance is created.
		if ( is_null( $this->license ) ) {
			$message = sprintf(
				/* translators: 1. Class Method Name. */
				esc_html__( 'Updater needs License instance to be created before it. Please call "%s" first.', 'absolute-addons' ),
				__CLASS__ . '::license'
			);
			_doing_it_wrong( __METHOD__, $message, '1.0.0' );
			return;
		}

		if ( ! class_exists( __NAMESPACE__ . '\Updater') ) {
			require_once __DIR__ . '/class-updater.php';
		}

		$this->updater = new Updater( $this, $this->license );

		return $this->updater;
	}

	/**
	 * API Endpoint.
	 *
	 * @param string $route  Route to send the request.
	 * @param string $server Which Endpoint to request
	 *
	 * @return string
	 */
	private function endpoint( $route = '', $server = '' ) {

		/**
		 * Filter Request Route string
		 * @param string    $route
		 * @param array     $params
		 */
		$route = apply_filters( 'absp_service_api_' . $this->slug . '_request_route', $route );

		// Server Endpoint.
		$endpoint = $this->getServiceEndpoint( $server );

		// Clean Route Slug.
		$route = rtrim( $route, '/\\' );
		$route = ltrim( $route, '/\\' );

		if ( 'license' !== $server ) {
			$endpoint = rtrim( $endpoint, '/\\' ) . '/' . $this->apiVersion . '/' . $route;
		}

		/**
		 * Filter Final API URL for request
		 *
		 * @param string $URL
		 * @param string $service_endpoints
		 * @param string $route
		 * @param string $apiVersion
		 * @param string $clientVersion
		 */
		$endpoint = apply_filters( 'absp_service_api_' . $this->slug . '_request_endpoint', $endpoint, $route, $this->apiVersion, $this->clientVersion );

		return trailingslashit( $endpoint );
	}

	/**
	 * Set project basename, slug and version.
	 *
	 * @return void
	 */
	protected function set_basename_and_slug() {

		if ( false === strpos( $this->file, WP_CONTENT_DIR . '/themes/' ) ) {

			$this->basename = plugin_basename( $this->file );
			list( $this->slug, ) = explode( '/', $this->basename );

			// Plugin Data Function
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_data = get_plugin_data( $this->file );
			$this->project_version = $plugin_data['Version'];
			$this->type = 'plugin';

		} else {

			$this->basename = str_replace( WP_CONTENT_DIR . '/themes/', '', $this->file );
			list( $this->slug, ) = explode( '/', $this->basename );

			$theme = wp_get_theme( $this->slug );
			/** @noinspection PhpUndefinedFieldInspection */
			$this->project_version = $theme->version;
			$this->type            = 'theme';
		}
	}

	public function set_debug_mode( $mode = false ) {
		$this->is_debug = (bool) $mode;
	}

	public function is_debug() {
		return apply_filters( 'absp_service_api_is_debugging', $this->is_debug );
	}

	public function allow_local_request() {
		$this->allow_local = true;

		return $this;
	}

	/**
	 * Check if the current server is localhost
	 *
	 * @return boolean
	 */
	public function is_local_request() {

		// if local is allowed, then local request should return false in all cases.

		if ( $this->allow_local ) {
			return false;
		}

		return apply_filters( 'absp_service_api_is_local', in_array( $_SERVER['REMOTE_ADDR'], [ '127.0.0.1', '::1' ] ) ); // phpcs:ignore
	}

	/**
	 * Client UserAgent String.
	 *
	 * @return string
	 */
	private function __user_agent() {
		global $wp_version;

		return 'ABSP/' . $this->clientVersion . ' (' . $this->getName() . '; ' . ucfirst( $this->type ) . '/' . $this->project_version . ') WordPress/ ' . $wp_version . ' ' . home_url() . ';';
	}

	/**
	 * Send request to remote endpoint.
	 *
	 * @param array $args {
	 *      @type array  $body     Parameters/Data that being sent.
	 *      @type string $route    Route to send the request to.
	 *      @type string $server   Which Server.
	 *      @type bool   $blocking Block Execution Until the server response back or timeout.
	 * }
	 *
	 * @return array|WP_Error   Array of results including HTTP headers or WP_Error if the request failed.
	 */
	public function request( $args = [] ) {

		$args = wp_parse_args(
			$args,
			[
				'body'     => [],
				'route'    => '',
				'url'      => '',
				'server'   => '',
				'method'   => 'POST',
				'blocking' => false,
				'timeout'  => 45, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			]
		);

		// Request URL
		if ( $args['route'] || 'license' === $args['server'] ) {
			$url = $this->endpoint( $args['route'], $args['server'] );
		} else if ( $args['url'] && esc_url_raw( $args['url'], [ 'https' ] ) ) {
			$url = esc_url_raw( $args['url'], [ 'https' ] );
		} else {
			return new WP_Error( 'url-route-missing', __( 'No Route or URL was set for the request.', 'absp-services' ) );
		}

		// Request Headers
		$headers = [
			//'Content-Type' => 'application/json',
			'user-agent'   => $this->__user_agent(),
			'Accept'       => 'application/json',
		];


		/**
		 * Before request to api server.
		 *
		 * @param array $params
		 * @param string $route
		 * @param array $headers
		 * @param string $clientVersion
		 * @param string $url
		 */
		do_action( $this->getSlug() . '_before_request', $args['body'], $args['route'], $headers, $this->clientVersion, $url );

		if ( ! empty( $args['route'] ) ) {
			/**
			 * Before request to api server to route.
			 *
			 * @param array $params
			 * @param string $route
			 * @param array $headers
			 * @param string $clientVersion
			 * @param string $url
			 */
			do_action( $this->getSlug() . '_before_request_' . $args['route'], $args['body'], $args['route'], $headers, $url );
		}

		/**
		 * Request Blocking mode.
		 * Set it to true for debugging the response with after request action.
		 *
		 * @param bool $blocking
		 */
		$blocking = (bool) apply_filters( $this->getSlug() . '_request_blocking_mode', $args['blocking'] );
		$method   = strtoupper( $args['method'] );
		$timeout  = $this->validate_timeout( $args );

		unset( $args['blocking'], $args['method'], $args['timeout'] );

		$body = array_merge(
			$args['body'],
			[
				'hash'     => $this->getHash(), // @TODO Remove this after updating tracker.
				'resource' => $this->getHash(), // wc-am returns "resources" key don't get confused around it.
				'version'  => $this->getProjectVersion(),
				'client'   => $this->getClientVersion(),
			]
		);

		$request_args = [
			'method'      => $method,
			'timeout'     => $timeout, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			'sslverify'   => apply_filters( 'https_local_ssl_verify', true ),
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => $blocking,
			'headers'     => $headers,
			'body'        => $body,
			'cookies'     => [],
		];

		// Dev Args.

		$url = esc_url_raw( $url );

		if ( $this->is_debug() ) {
			// phpcs:disable
			// Debugging only
			$response = wp_remote_request( $url, $request_args );
			// phpcs:enable
		} else {
			// Vip doesn't have post method. only _request & _get.
			if ( function_exists( 'vip_safe_wp_remote_request' ) ) {
				$response = vip_safe_wp_remote_request( $url, '', 10, $timeout, 20, $request_args );
			} else {
				$response = wp_safe_remote_request( $url, $request_args );
			}
		}

		/**
		 * After request to api server.
		 *
		 * @param array $response
		 * @param string $route
		 */
		do_action( $this->getSlug() . '_after_request', $response, $args['route'] );

		if ( ! empty( $args['route'] ) ) {
			/**
			 * After request to api server to route.
			 *
			 * @param array $response
			 * @param string $route
			 */
			do_action( $this->getSlug() . '_after_request_' . $args['route'], $response, $args['route'] );
		}

		return $response;
	}

	/**
	 * @param array $args
	 *
	 * @return float|int
	 */
	protected function validate_timeout( $args ) {
		$is_post_request = 0 === strcasecmp( 'POST', $args['method'] );

		// WP-VipCom default timeout is 1.
		$timeout = isset( $args['timeout'] ) && $args['timeout'] ? abs( $args['timeout'] ) : 1; // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout

		if ( defined( 'WP_CLI' ) && WP_CLI && $is_post_request ) {
			if ( 30 < $timeout ) {
				// Remote POST request timeouts are capped at 30 seconds in WP-CLI for performance and stability reasons.
				$timeout = 30;
			}
		} elseif ( \is_admin() && $is_post_request ) {
			if ( 15 < $timeout ) {
				// Remote POST request timeouts are capped at 15 seconds for admin requests for performance and stability reasons.
				$timeout = 15;
			}
		} else {
			// Frontend Request.
			if ( $timeout > 5 ) {
				// Remote request timeouts are capped at 5 seconds for performance and stability reasons.
				$timeout = 5;
			}
		}

		return $timeout;
	}

	/**
	 * Get Version of this client.
	 *
	 * @return string
	 */
	public function getClientVersion() {
		return $this->clientVersion;
	}

	/**
	 * Get API URI.
	 *
	 * @param string $server Default null.
	 *
	 * @return string
	 */
	public function getServiceEndpoint( $server = null ) {
		return isset( $this->service_endpoints[ $server ] ) ? $this->service_endpoints[ $server ] : $this->service_endpoints['tracker'];
	}

	/**
	 * Get API Version using by this client.
	 *
	 * @return string
	 */
	public function getApiVersion() {
		return $this->apiVersion;
	}

	/**
	 * Get Hash of current Plugin/Theme.
	 *
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Get Plugin/Theme Name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Store Product ID.
	 *
	 * @return bool|int
	 */
	public function getProductId() {
		return $this->product_id;
	}

	/**
	 * Get Plugin/Theme file.
	 *
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Get Plugin/Theme base name.
	 *
	 * @return string
	 */
	public function getBasename() {
		return $this->basename;
	}

	/**
	 * Get Plugin/Theme Slug.
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * Get Plugin/Theme Project Version.
	 *
	 * @return string
	 */
	public function getProjectVersion() {
		return $this->project_version;
	}

	/**
	 * Get Project Type Plugin/Theme.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
}
// End of file class-client.php.
