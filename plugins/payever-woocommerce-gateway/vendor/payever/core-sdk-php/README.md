# PHP SDK for payever plugin interactions - internal, not for public use
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Latest Stable Version](https://poser.pugx.org/payever/core-sdk-php/v/stable)](https://packagist.org/packages/payever/core-sdk-php)
[![Total Downloads](https://poser.pugx.org/payever/core-sdk-php/downloads)](https://packagist.org/packages/payever/core-sdk-php)
[![License](https://poser.pugx.org/payever/core-sdk-php/license)](https://packagist.org/packages/payever/core-sdk-php)

This repository contains the open source PHP SDK that allows you to access payever from your PHP app.

This library follows semantic versioning. Read more on [semver.org][1].

Please note: this SDK is used within the payever plugins. It is NOT suitable for custom API integrations. If you would like to integrate with us via API, please visit https://docs.payever.org/shopsystems/api and follow the instructions and code examples provided there. 

## Troubleshooting 

If you faced an issue you can contact us via official support channel - support@getpayever.com

## Requirements

* [PHP 5.4.0 and later][2]
* PHP cURL extension

## Installation

You can use **Composer**

The preferred method is via [composer][3]. Follow the
[installation instructions][4] if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require payever/core-sdk-php
```

## Documentation

Raw HTTP API docs can be found here - https://docs.payever.org/shopsystems/api

### Enums

The are several list of fixed string values used inside API. For convenience they are represented as constants and grouped into classes.

* Core
    - [`ChannelSet`](lib/Payever/Core/Enum/ChannelSet.php) - list of available payever API channels

### API Clients

HTTP API communication with payever happens through API clients. There are several of them for different API categories:

- [`PaymentsApiClient`](#paymentsapiclient)
- [`ThirdPartyApiClient`](#thirdpartyapiclient)
- [`ProductsApiClient`](#productsapiclient)
- [`InventoryApiClient`](#inventoryapiclient)

Each one is described in details below.

#### Configuration

Each API client requires configuration object as the first argument of client's constructor. 
In order to get the valid configuration object you need to have valid API credentials:

- Client ID
- Client Secret
- Business UUID

Additionally, you need to tell which API channel you're using:

```php
use Payever\Sdk\Core\ClientConfiguration;
use Payever\Sdk\Core\Enum\ChannelSet;

$clientId = 'your-oauth2-client-id';
$clientSecret = 'your-oauth2-client-secret';
$businessUuid = '88888888-4444-4444-4444-121212121212';

$clientConfiguration = new ClientConfiguration();

$clientConfiguration
    ->setClientId($clientId)
    ->setClientSecret($clientSecret)
    ->setBusinessUuid($businessUuid)
    ->setChannelSet(ChannelSet::CHANNEL_MAGENTO)
    ->setApiMode(ClientConfiguration::API_MODE_LIVE)
;
```
NOTE: All examples below assume you have [`ClientConfiguration`](lib/Payever/Core/ClientConfiguration.php) instantiated inside `$clientConfiguration` variable.

##### Logging

You can setup logging of all API interactions by providing [PSR-3](https://www.php-fig.org/psr/psr-3/) compatible logger instance.

In case if you don't have PSR-3 compatible logger at hand - this SKD contains simple file logger:
```php
use Psr\Log\LogLevel;
use Payever\Sdk\Core\Logger\FileLogger;

$logger = new FileLogger(__DIR__.'/payever.log', LogLevel::INFO);
$clientConfiguration->setLogger($logger);
```

## License

Please see the [license file][6] for more information.

[1]: http://semver.org
[2]: http://www.php.net/
[3]: https://getcomposer.org
[4]: https://getcomposer.org/doc/00-intro.md
[5]: ../../releases
[6]: LICENSE.md
[7]: ../../issues
