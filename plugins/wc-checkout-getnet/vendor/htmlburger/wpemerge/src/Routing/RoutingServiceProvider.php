<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <hi@atanas.dev>
 * @copyright 2017-2019 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\WPEmerge\Routing;

use CoffeeCode\Pimple\Container;
use CoffeeCode\WPEmerge\Routing\Conditions\ConditionFactory;
use CoffeeCode\WPEmerge\ServiceProviders\ExtendsConfigTrait;
use CoffeeCode\WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide routing dependencies
 *
 * @codeCoverageIgnore
 */
class RoutingServiceProvider implements ServiceProviderInterface {
	use ExtendsConfigTrait;

	/**
	 * Key=>Class dictionary of condition types
	 *
	 * @var array<string, string>
	 */
	protected static $condition_types = [
		'url' => Conditions\UrlCondition::class,
		'custom' => Conditions\CustomCondition::class,
		'multiple' => Conditions\MultipleCondition::class,
		'negate' => Conditions\NegateCondition::class,
		'post_id' => Conditions\PostIdCondition::class,
		'post_slug' => Conditions\PostSlugCondition::class,
		'post_status' => Conditions\PostStatusCondition::class,
		'post_template' => Conditions\PostTemplateCondition::class,
		'post_type' => Conditions\PostTypeCondition::class,
		'query_var' => Conditions\QueryVarCondition::class,
		'ajax' => Conditions\AjaxCondition::class,
		'admin' => Conditions\AdminCondition::class,
	];

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$this->extendConfig( $container, 'routes', [
			'web' => [
				'definitions' => '',
				'attributes'  => [
					'middleware' => ['web'],
					'namespace' => 'App\\Controllers\\Web\\',
					'handler' => 'CoffeeCode\\WPEmerge\\Controllers\\WordPressController@handle',
				],
			],
			'admin' => [
				'definitions' => '',
				'attributes'  => [
					'middleware' => ['admin'],
					'namespace' => 'App\\Controllers\\Admin\\',
				],
			],
			'ajax' => [
				'definitions' => '',
				'attributes'  => [
					'middleware' => ['ajax'],
					'namespace' => 'App\\Controllers\\Ajax\\',
				],
			],
		] );

		/** @var Container $container */
		$container[ WPEMERGE_ROUTING_CONDITION_TYPES_KEY ] = static::$condition_types;

		$container[ WPEMERGE_ROUTING_ROUTER_KEY ] = function ( $c ) {
			return new Router(
				$c[ WPEMERGE_ROUTING_CONDITIONS_CONDITION_FACTORY_KEY ],
				$c[ WPEMERGE_HELPERS_HANDLER_FACTORY_KEY ]
			);
		};

		$container[ WPEMERGE_ROUTING_CONDITIONS_CONDITION_FACTORY_KEY ] = function ( $c ) {
			return new ConditionFactory( $c[ WPEMERGE_ROUTING_CONDITION_TYPES_KEY ] );
		};

		$container[ WPEMERGE_ROUTING_ROUTE_BLUEPRINT_KEY ] = $container->factory( function ( $c ) {
			return new RouteBlueprint( $c[ WPEMERGE_ROUTING_ROUTER_KEY ], $c[ WPEMERGE_VIEW_SERVICE_KEY ] );
		} );

		$app = $container[ WPEMERGE_APPLICATION_KEY ];
		$app->alias( 'route', WPEMERGE_ROUTING_ROUTE_BLUEPRINT_KEY );
		$app->alias( 'routeUrl', WPEMERGE_ROUTING_ROUTER_KEY, 'getRouteUrl' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		// Nothing to bootstrap.
	}
}
