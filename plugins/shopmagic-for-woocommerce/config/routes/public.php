<?php

use WPDesk\ShopMagic\Components\Routing\RoutesConfigurator;
use WPDesk\ShopMagic\Marketing\Controller\MailTrackingController;
use WPDesk\ShopMagic\Marketing\Controller\SubscriptionPreferencesPage;

return static function ( RoutesConfigurator $configurator ) {
	$configurator
		->add( 'communication-preferences' )
		->authorize( '__return_true' )
		->controller(
			[
				SubscriptionPreferencesPage::class,
				'display_preferences',
			]
		);

	$configurator
		->add( 'track/sm-click' )
		->authorize( '__return_true' )
		->controller(
			[
				MailTrackingController::class,
				'click',
			]
		);

	$configurator
		->add( 'track/sm-open' )
		->authorize( '__return_true' )
		->controller(
			[
				MailTrackingController::class,
				'open',
			]
		);
};
