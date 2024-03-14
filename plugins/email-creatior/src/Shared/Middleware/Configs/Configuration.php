<?php
return apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'Filter\Shared\Middleware\Configs\middlewares',
	[
		'IsUserLoggedIn'                       => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsUserLoggedIn',
		'IsPostExistMiddleware'                => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsPostExistMiddleware',
		'IsPostAuthorMiddleware'               => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsPostAuthorMiddleware',
		'IsApplyBillingToCategoriesMiddleware' => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsApplyBillingToCategoriesMiddleware',
		'IsApplyBillingToMaxOrderMiddleware'   => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsApplyBillingToMaxOrderMiddleware',
		'IsApplyBillingToMinOrderMiddleware'   => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsApplyBillingToMinOrderMiddleware',
		'IsApplyToBillingCountriesMiddleware'  => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsApplyToBillingCountriesMiddleware',
		'IsUserPackageFreeMiddleware'          => 'WilokeEmailCreator\Shared\Middleware\Middlewares\IsUserPackageFreeMiddleware',
	]
);
