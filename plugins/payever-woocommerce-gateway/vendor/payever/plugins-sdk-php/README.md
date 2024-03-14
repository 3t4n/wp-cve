# PHP SDK for payever plugin interactions - internal, not for public use
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/payeverworldwide/sdk-php/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Latest Stable Version](https://poser.pugx.org/payever/plugins-sdk-php/v/stable)](https://packagist.org/packages/payever/plugins-sdk-php)
[![Total Downloads](https://poser.pugx.org/payever/plugins-sdk-php/downloads)](https://packagist.org/packages/payever/plugins-sdk-php)
[![License](https://poser.pugx.org/payever/plugins-sdk-php/license)](https://packagist.org/packages/payever/plugins-sdk-php)

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

### Composer

The preferred method is via [composer][3]. Follow the
[installation instructions][4] if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require payever/plugins-sdk-php
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
