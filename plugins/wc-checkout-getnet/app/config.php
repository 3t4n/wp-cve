<?php
/**
 * WP Emerge configuration.
 *
 * @link https://docs.wpemerge.com/#/framework/configuration
 *
 * @package WcGetnet
 */

return [
	/**
	 * Array of service providers you wish to enable.
	 */
	'providers'           => [
		\CoffeeCode\WPEmergeAppCore\AppCore\AppCoreServiceProvider::class,
		\CoffeeCode\WPEmergeAppCore\Assets\AssetsServiceProvider::class,
		\CoffeeCode\WPEmergeAppCore\Avatar\AvatarServiceProvider::class,
		\CoffeeCode\WPEmergeAppCore\Config\ConfigServiceProvider::class,
		\CoffeeCode\WPEmergeAppCore\Image\ImageServiceProvider::class,
		\CoffeeCode\WPEmergeAppCore\Sidebar\SidebarServiceProvider::class,
		\WcGetnet\Routing\RouteConditionsServiceProvider::class,
		\WcGetnet\View\ViewServiceProvider::class,
		\WcGetnet\WordPress\AssetsServiceProvider::class,
		\WcGetnet\WordPress\AdminServiceProvider::class,
		\WcGetnet\WooCommerce\WcGetnetProvider::class,
		\WcGetnet\Controllers\Admin\ControllerServiceProvider::class,
	],

	/**
	 * Array of route group definitions and default attributes.
	 * All of these are optional so if we are not using
	 * a certain group of routes we can skip it.
	 * If we are not using routing at all we can skip
	 * the entire 'routes' option.
	 */
	'routes'              => [
		'web'   => [
			'definitions' => __DIR__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php',
			'attributes'  => [
				'namespace' => 'WcGetnet\\Controllers\\Web\\',
			],
		],
		'admin' => [
			'definitions' => __DIR__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'admin.php',
			'attributes'  => [
				'namespace' => 'WcGetnet\\Controllers\\Admin\\',
			],
		],
		'ajax'  => [
			'definitions' => __DIR__ . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'ajax.php',
			'attributes'  => [
				'namespace' => 'WcGetnet\\Controllers\\Ajax\\',
			],
		],
	],

	/**
	 * View Composers settings.
	 */
	'view_composers'      => [
		'namespace' => 'WcGetnet\\ViewComposers\\',
	],

	/**
	 * Register middleware class aliases.
	 * Use fully qualified middleware class names.
	 *
	 * Internal aliases that you should avoid overriding:
	 * - 'flash'
	 * - 'old_input'
	 * - 'csrf'
	 * - 'user.logged_in'
	 * - 'user.logged_out'
	 * - 'user.can'
	 */
	'middleware'          => [
		// phpcs:ignore
		// 'mymiddleware' => \WcGetnet\Middleware\MyMiddleware::class,
	],

	/**
	 * Register middleware groups.
	 * Use fully qualified middleware class names or registered aliases.
	 * There are a couple built-in groups that you may override:
	 * - 'web'      - Automatically applied to web routes.
	 * - 'admin'    - Automatically applied to admin routes.
	 * - 'ajax'     - Automatically applied to ajax routes.
	 * - 'global'   - Automatically applied to all of the above.
	 * - 'wpemerge' - Internal group applied the same way 'global' is.
	 *
	 * Warning: The 'wpemerge' group contains some internal WP Emerge
	 * middleware which you should avoid overriding.
	 */
	'middleware_groups'   => [
		'global' => [],
		'web'    => [],
		'ajax'   => [],
		'admin'  => [],
	],

	/**
	 * Optionally specify middleware execution order.
	 * Use fully qualified middleware class names.
	 */
	'middleware_priority' => [
		// phpcs:ignore
		// \WcGetnet\Middleware\MyMiddlewareThatShouldRunFirst::class,
		// \WcGetnet\Middleware\MyMiddlewareThatShouldRunSecond::class,
	],

	/**
	 * Custom directories to search for views.
	 * Use absolute paths or leave blank to disable.
	 * Applies only to the default PhpViewEngine.
	 */
	'views' => [ dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'views' ],

	/**
	 * App Core configuration.
	 */
	'app_core'            => [
		'path' => dirname( __DIR__ ),
		'url'  => plugin_dir_url( WC_GETNET_PLUGIN_FILE ),
	],

	/**
	 * Other config goes after this comment.
	 */

];
