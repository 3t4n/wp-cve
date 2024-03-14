[![pipeline status](https://gitlab.com/wpdesk/wp-notice/badges/master/pipeline.svg)](https://gitlab.com/wpdesk/wp-notice/pipelines) 
[![coverage report](https://gitlab.com/wpdesk/wp-notice/badges/master/coverage.svg?job=integration+test+lastest+coverage)](https://gitlab.com/wpdesk/wp-notice/commits/master) 
[![Latest Stable Version](https://poser.pugx.org/wpdesk/wp-notice/v/stable)](https://packagist.org/packages/wpdesk/wp-notice) 
[![Total Downloads](https://poser.pugx.org/wpdesk/wp-notice/downloads)](https://packagist.org/packages/wpdesk/wp-notice) 
[![Latest Unstable Version](https://poser.pugx.org/wpdesk/wp-notice/v/unstable)](https://packagist.org/packages/wpdesk/wp-notice) 
[![License](https://poser.pugx.org/wpdesk/wp-notice/license)](https://packagist.org/packages/wpdesk/wp-notice) 

# wp-notice

A simple, yet very useful library for WordPress plugins allowing to display the different kind of notices in the admin area. It can be easily used to:

* Display the simple error, warning, success and info notices,
* Display the permanently dismissible notices,
* Handle the dismiss functionality with AJAX requests.

## Requirements

PHP 5.5 or later.

## Installation via Composer

In order to install the bindings via [Composer](http://getcomposer.org/) run the following command:

```bash
composer require wpdesk/wp-notice
```

Next, use the Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading) to use them:

```php
require_once 'vendor/autoload.php';
```

## Manual installation

If you prefer not to use the Composer you can also [download the latest library release](https://gitlab.com/wpdesk/wp-notice/-/jobs/artifacts/master/download?job=library). Once it is done, simply include the init.php file to use the Notices.

```php
require_once('/path/to/wp-desk/wp-notice/init.php');
```

## Getting Started

### Notices usage example

```php
$notice = wpdesk_wp_notice('Notice text goes here');

// Is equivalent to:
$notice = WPDeskWpNotice('Notice text goes here');

// Is equivalent to:
$notice = \WPDesk\Notice\Factory::notice('Notice text goes here');

// Is equivalent to:
$notice = new \WPDesk\Notice\Notice('Notice text goes here'); 
```

Please mind that the Notice must be used before WordPress `admin_notices` action. You can find WordPress admin actions order listed [here](https://codex.wordpress.org/Plugin_API/Action_Reference#Actions_Run_During_an_Admin_Page_Request).

## Permanently dismissible notices

### AJAX handler

In order to use permanently dismissible notices the AJAX handler needs to be created first and the hooks initialized:

```php
wpdesk_init_wp_notice_ajax_handler();

// Is equivalent to:
( new \WPDesk\Notice\AjaxHandler() )->hooks();
```

### Displaying the permanently dismissible notices

Use the following code for the permanently dismissible notice to be displayed:

```php
wpdesk_permanent_dismissible_wp_notice( 'Notice text goes here', 'notice-name' );

// Is equivalent to
$notice = new \WPDesk\Notice\PermanentDismissibleNotice( 'Notice text goes here', 'notice-name' );
```

## Project documentation

PHPDoc: https://wpdesk.gitlab.io/wp-notice/index.html 
