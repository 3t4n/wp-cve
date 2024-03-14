<?php

declare( strict_types=1 );

use WPDesk\ShopMagic\Api\Controller;
use WPDesk\ShopMagic\Components\Routing\Argument;
use WPDesk\ShopMagic\Components\Routing\ArgumentCollection;
use WPDesk\ShopMagic\Components\Routing\IntArgument;
use WPDesk\ShopMagic\Components\Routing\StringArgument;

return static function ( \WPDesk\ShopMagic\Components\Routing\RoutesConfigurator $routes ) {
	$routes->add( '/automations' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 ),
					( new Argument( 'filters' ) )
						->type( 'object' )
				)
			)
			->controller( [ Controller\AutomationController::class, 'index' ] );

	$routes->add( '/automations/count' )
			->controller( [ Controller\AutomationController::class, 'count' ] );

	$routes->add( '/automations/(?P<id>[\d]+)' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->controller( [ Controller\AutomationController::class, 'show' ] );

	$routes->add( '/automations/(?P<id>[\d]+)/children' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->controller( [ Controller\AutomationController::class, 'list_children' ] );

	$routes->add( '/automations/(?P<id>[\d]+)' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'id' ) )
						->required()
				)
			)
			->controller( [ Controller\AutomationController::class, 'update' ] )
			->methods( [ 'PATCH', 'PUT' ] );

	$routes->add( '/automations/(?P<id>[\d]+)' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->controller( [ Controller\AutomationController::class, 'delete' ] )
			->methods( 'DELETE' );

	$routes->add( '/automations' )
			->args(
				new ArgumentCollection(
					new Argument( 'name' ),
					new Argument( 'event' ),
					new Argument( 'actions' ),
					new Argument( 'filters' )
				)
			)
			->controller( [ Controller\AutomationController::class, 'create' ] )
			->methods( 'POST' );

	$routes->add( '/automations/stats' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 )
				)
			)
			->controller( [ Controller\MailTrackingController::class, 'per_automation' ] );

	$routes->add( '/outcomes' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 )
				)
			)
			->controller( [ Controller\OutcomesController::class, 'index' ] );

	$routes->add( '/outcomes/(?P<id>[\d]+)' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->controller( [ Controller\OutcomesController::class, 'show' ] );

	$routes->add( '/outcomes/(?P<id>[\d]+)' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->methods( 'DELETE' )
			->controller( [ Controller\OutcomesController::class, 'delete' ] );

	$routes->add( '/outcomes/count' )
			->controller( [ Controller\OutcomesController::class, 'count' ] );

	$routes->add( '/clients' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 )
				)
			)
			->controller( [ Controller\CustomerController::class, 'index' ] );

	$routes->add( '/clients/stats' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 )
				)
			)
			->controller( [ Controller\MailTrackingController::class, 'per_customer' ] );

	$routes->add( '/guests' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 )
				)
			)
			->controller( [ Controller\CustomerController::class, 'guests' ] );

	$routes->add( '/guests/(?P<id>[\d]+)' )
			->args(
				new ArgumentCollection(
					new IntArgument( 'id' )
				)
			)
			->methods( 'DELETE' )
			->controller( array( Controller\CustomerController::class, 'delete_guest' ) );

	$routes->add( '/guests/count' )
			->controller( array( Controller\CustomerController::class, 'guests_count' ) );

	$routes->add( '/automations/recipes' )
			->controller( array( Controller\RecipesController::class, 'index' ) );

	$routes->add( '/subscribers' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 ),
					( new Argument( 'filters' ) )
						->type( 'object' ),
					( new Argument( 'order' ) )
						->type( 'object' )
				)
			)
			->controller( array( Controller\SubscribersController::class, 'index' ) );

	$routes->add( '/subscribers/(?P<id>[\d]+)' )
			->args(
				new ArgumentCollection(
					new IntArgument( 'id' )
				)
			)
			->methods( 'DELETE' )
			->controller( [ Controller\SubscribersController::class, 'delete' ] );

	$routes->add( '/subscribers/count' )
			->args(
				new ArgumentCollection(
					( new Argument( 'filters' ) )
					->type( 'object' )
				)
			)
			->controller( [ Controller\SubscribersController::class, 'count' ] );

	$routes->add( '/settings' )
			->controller( [ Controller\SettingsController::class, 'index' ] );

	$routes->add( '/settings' )
			->controller( [ Controller\SettingsController::class, 'update' ] )
			->methods( [ 'POST' ] );

	$routes->add( '/resources/events' )
			->controller( [ Controller\ResourcesController::class, 'events' ] );

	$routes->add( '/resources/filters' )
			->args( new ArgumentCollection( new Argument( 'event_slug' ) ) )
			->controller( [ Controller\ResourcesController::class, 'filters' ] );

	$routes->add( '/resources/placeholders' )
			->args( new ArgumentCollection( new Argument( 'event_slug' ) ) )
			->controller( [ Controller\ResourcesController::class, 'placeholders' ] );

	$routes->add( '/resources/actions' )
			->controller( [ Controller\ResourcesController::class, 'actions' ] );

	$routes->add( '/resources/actions/(?P<action>[\w]+)/test' )
			->methods( 'POST' )
			->controller( [ Controller\ResourcesController::class, 'test_action' ] );

	$routes->add( '/resources/marketing-list' )
			->controller( [ Controller\MarketingListsController::class, 'fields' ] );

	$routes->add( '/resources/shortcode' )
			->controller( [ Controller\MarketingListsController::class, 'shortcode_fields' ] );

	$routes->add( '/lists' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 ),
					( new Argument( 'filters' ) )
						->type( 'object' ),
					( new Argument( 'order' ) )
						->type( 'object' )
				)
			)
			->controller( [ Controller\MarketingListsController::class, 'index' ] );

	$routes->add( '/lists/count' )
			->args(
				new ArgumentCollection(
					( new Argument( 'filters' ) )
					->type( 'object' )
				)
			)
			->controller( [ Controller\MarketingListsController::class, 'count' ] );

	$routes->add( '/lists/(?P<id>[\d]+)/subscribers/count' )
			->args( new ArgumentCollection( new IntArgument( 'id' ) ) )
			->controller( [ Controller\MarketingListsController::class, 'subscribers_count' ] );

	$routes->add( '/lists/(?P<id>[\d]+)/subscribers' )
			->args( new ArgumentCollection( new IntArgument( 'id' ) ) )
			->controller( [ Controller\SubscribersController::class, 'get' ] );

	$routes->add( '/lists/(?P<id>[\d]+)/subscribers' )
			->methods( 'POST' )
			->args( new ArgumentCollection( new IntArgument( 'id' ) ) )
			->controller( [ Controller\SubscribersController::class, 'import' ] );

	$routes->add( '/lists' )
			->methods( 'POST' )
			->controller( [ Controller\MarketingListsController::class, 'create' ] );

	$routes->add( '/lists/(?P<id>[\d]+)' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->methods( [ 'PUT', 'PATCH' ] )
			->controller( [ Controller\MarketingListsController::class, 'update' ] );

	$routes->add( '/lists/(?P<id>[\d]+)' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->methods( 'DELETE' )
			->controller( [ Controller\MarketingListsController::class, 'delete' ] );

	$routes->add( '/lists/(?P<id>[\d]+)' )
			->args( new ArgumentCollection( new Argument( 'id' ) ) )
			->controller( [ Controller\MarketingListsController::class, 'show' ] );

	$routes->add( '/queue' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'page' ) )
						->minimum( 1 )
						->default( 1 ),
					( new IntArgument( 'pageSize' ) )
						->default( 20 )
						->minimum( 1 )
						->maximum( 100 )
				)
			)
			->controller( [ Controller\QueueController::class, 'index' ] );

	$routes->add( '/queue/(?P<id>[\d]+)' )
			->args(
				new ArgumentCollection(
					( new IntArgument( 'id' ) )
					->required()
				)
			)
			->methods( 'DELETE' )
			->controller( [ Controller\QueueController::class, 'cancel' ] );

	$routes->add( '/queue/count' )
			->controller( [ Controller\QueueController::class, 'count' ] );

	$routes->add( '/tracker' )
			->controller( [ Controller\MailTrackingController::class, 'index' ] );

	$routes->add( '/analytics/outcomes/aggregate' )
			->controller( [ Controller\StatisticsController::class, 'outcomes' ] );

	$routes->add( '/analytics/emails/aggregate' )
			->controller( [ Controller\StatisticsController::class, 'email' ] );

	$routes->add( '/analytics/top-stats' )
			->controller( [ Controller\StatisticsController::class, 'top_stats' ] );

	$routes->add( '/products/search' )
			->args(
				new ArgumentCollection(
					( new StringArgument( 's' ) )
						->minLength( 3 )
						->required()
				)
			)
			->controller( [ Controller\ProductController::class, 'search' ] );

	$routes->add( '/products' )
			->args(
				new ArgumentCollection(
					( new StringArgument( 'include' ) )
						->pattern( '([\d]+,?)+' )
						->required()
				)
			)
			->controller( [ Controller\ProductController::class, 'index' ] );

	$routes->add( '/log' )
		->args(
			new ArgumentCollection(
				( new StringArgument( 'message' ) )
				->required(),
				( new StringArgument( 'level' ) ),
				( new Argument( 'context' ))
			)
		)
		->methods( 'POST' )
		->controller( [ Controller\LogController::class, 'log' ] );
};
