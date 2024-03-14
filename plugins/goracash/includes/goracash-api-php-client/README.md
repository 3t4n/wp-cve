# Goracash APIs Client Library for PHP #

[![Code Climate](https://codeclimate.com/github/Goracash/goracash-api-php-client/badges/gpa.svg)](https://codeclimate.com/github/Goracash/goracash-api-php-client)
[![Test Coverage](https://codeclimate.com/github/Goracash/goracash-api-php-client/badges/coverage.svg)](https://codeclimate.com/github/Goracash/goracash-api-php-client/coverage)
[![Issue Count](https://codeclimate.com/github/Goracash/goracash-api-php-client/badges/issue_count.svg)](https://codeclimate.com/github/Goracash/goracash-api-php-client)
[![Latest Stable Version](https://poser.pugx.org/goracash/apiclient/v/stable)](https://packagist.org/packages/goracash/apiclient)
[![Total Downloads](https://poser.pugx.org/goracash/apiclient/downloads)](https://packagist.org/packages/goracash/apiclient)
[![Latest Unstable Version](https://poser.pugx.org/goracash/apiclient/v/unstable)](https://packagist.org/packages/goracash/apiclient)
[![License](https://poser.pugx.org/goracash/apiclient/license)](https://packagist.org/packages/goracash/apiclient)


## Description ##
The Goracash API Client Library enables you to work with Goracash APIs on your server.

## Requirements ##
* [PHP 5.3 or higher](http://www.php.net/)
* [PHP JSON extension](http://php.net/manual/en/book.json.php)

## Installation ##

For the latest installation and setup instructions, see [the documentation](https://account.goracash.com/docs/webservice.installation.md).

## Basic Example ##
See the examples/ directory for examples of the key client features.
```PHP
<?php

  require_once 'goracash-api-php-client/src/autoload.php'; // or wherever autoload.php is located
  
  $client = new Goracash\Client();
  $client->setClientId('YOUR_CLIENT_ID');
  $client->setClientSecret('YOUR_CLIENT_SECRET');
  $client->setApplicationName("Client_Library_Examples");
  
  $date_lbound = '2015-04-03 00:00:00';
  $date_ubound = '2015-04-05 00:00:00';
  $options = array('limit' => 2);
  
  $service = new Goracash\Service\LeadAcademic($client);
  $leads = $service->getLeads($date_lbound, $date_ubound, $options);

  foreach ($leads as $lead) {
    echo "Id :", $lead['id'], "<br /> \n";
    echo "Date :", $lead['date'], "<br /> \n";
    echo "Status :", $lead['status'], "<br /> \n";
    echo "Status date:", $lead['status_date'], "<br /> \n";
    echo "Level:", $lead['level'], "<br /> \n";
    echo "Subject :", $lead['subject'], "<br /> \n";
    echo "Payout :", $lead['payout'], "<br /> \n";
    echo "Payout date :", $lead['payout_date'], "<br /> \n";
    echo "Trackers :<br /> \n";
    foreach ($lead['trackers'] as $tracker) {
        echo "Tracker id :", $tracker['id'], "<br /> \n";
        echo "Tracker title :", $tracker['title'], "<br /> \n";
        echo "Tracker slug :", $tracker['slug'], "<br /> \n";
    }
  }
  
```

## Frequently Asked Questions ##

### What do I do if something isn't working? ###

If there is a specific bug with the library, please file a issue in the Github issues tracker, including a (minimal) example of the failing code and any specific errors retrieved. Feature requests can also be filed, as long as they are core library requests, and not-API specific: for those, refer to the documentation for the individual APIs for the best place to file requests. Please try to provide a clear statement of the problem that the feature would address.
