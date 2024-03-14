<?php

// Register the classes to make available for the developers
// The key will be used to access the class, for example:
// FluentSupportApi('tickets') or FluentSupportApi->tickets

return [
    'tickets'   => 'FluentSupport\App\Api\Classes\Tickets',
    'customers' => 'FluentSupport\App\Api\Classes\Customers',
    'agents'    => 'FluentSupport\App\Api\Classes\Agents',
    'products'  => 'FluentSupport\App\Api\Classes\Products',
    'tags'      => 'FluentSupport\App\Api\Classes\Tags'
];
