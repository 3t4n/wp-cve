<?php

/**
 * @var $router FluentConnect\Framework\Http\Router
 */

$router->get('/welcome', 'WelcomeController@index');


$router->prefix('integrations')->withPolicy('UserPolicy')->group(function ($router) {
    $router->get('/', 'IntegrationController@index');
    $router->get('/{id}', 'IntegrationController@getInfo')->int('id');
    $router->put('/{id}', 'IntegrationController@update')->int('id');
    $router->delete('/{id}', 'IntegrationController@delete')->int('id');

    $router->get('providers', 'IntegrationProviders@getAll');
    $router->get('providers/{provider_slug}', 'IntegrationProviders@find')->alphaNum('provider_slug');
    $router->post('providers/{provider_slug}', 'IntegrationController@save')->alphaNum('provider_slug');

});

$router->prefix('reports')->withPolicy('UserPolicy')->group(function ($router) {
    $router->get('/dashboard', 'ReportsController@dashboardStat');
    $router->get('/install-fluentcrm', 'ReportsController@installFluentCRM');


    $router->get('/logs', 'LogsController@get');

});

$router->prefix('feeds')->withPolicy('UserPolicy')->group(function ($router) {

    $router->get('/', 'FeedsController@get');
    $router->post('/', 'FeedsController@create');

    $router->get('/{id}', 'FeedsController@getFeed')->int('id');
    $router->put('/{id}', 'FeedsController@updateFeed')->int('id');
    $router->put('/{id}/publish', 'FeedsController@publishFeed')->int('id');
    $router->delete('/{id}', 'FeedsController@deleteFeed')->int('id');

    $router->get('/trigger_fields', 'FeedsController@getTriggerFields');
    $router->get('/action_fields', 'FeedsController@getActionFields');

});

