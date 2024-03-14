<?php

/**
 * All registered action's handlers should be in app\Hooks\Handlers,
 * addAction is similar to add_action and addCustomAction is just a
 * wrapper over add_action which will add a prefix to the hook name
 * using the plugin slug to make it unique in all wordpress plugins,
 * ex: $app->addCustomAction('foo', ['FooHandler', 'handleFoo']) is
 * equivalent to add_action('slug-foo', ['FooHandler', 'handleFoo']).
 */

/**
 * @var $app FluentConnect\Framework\Foundation\Application
 */



$app->addAction('admin_menu', 'AdminMenuHandler@add');

$app->addAction('init', 'WebhookReceiverHandler@route');


/*
 * Init Integration Providers
 */

(new \FluentConnect\App\Services\ThriveCart\ThriveCart())->init();
(new \FluentConnect\App\Services\Actions\ActionsInit())->init();

//\FluentConnect\App\Services\ConnectStores::addTriggerProvider('wp', [
//    'title'                        => 'WordPress',
//    'logo'                         => FLUENT_CONNECT_PLUGIN_URL . 'assets/images/wordpress.png',
//    'description'                  => 'Do actions based on ThriveCart events like order succes, order refunds etc',
//    'require_integration_selector' => false,
//    'enabled'                      => true
//]);
//
//\FluentConnect\App\Services\ConnectStores::addTrigger('wp', 'profile_update', \FluentConnect\App\Services\Triggers\WP\ProfileUpdated::class);

(new \FluentConnect\App\Hooks\Handlers\TriggerInitHandler())->init();
