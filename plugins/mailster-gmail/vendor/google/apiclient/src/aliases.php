<?php
/**
 * @license Apache-2.0
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

if (class_exists('Mailster_Gmail_Google_Client', false)) {
    // Prevent error with preloading in PHP 7.4
    // @see https://github.com/googleapis/google-api-php-client/issues/1976
    return;
}

$classMap = [
    'Mailster\Gmail\Google\\Client' => 'Mailster_Gmail_Google_Client',
    'Mailster\Gmail\Google\\Service' => 'Mailster_Gmail_Google_Service',
    'Mailster\Gmail\Google\\AccessToken\\Revoke' => 'Mailster_Gmail_Google_AccessToken_Revoke',
    'Mailster\Gmail\Google\\AccessToken\\Verify' => 'Mailster_Gmail_Google_AccessToken_Verify',
    'Mailster\Gmail\Google\\Model' => 'Mailster_Gmail_Google_Model',
    'Mailster\Gmail\Google\\Utils\\UriTemplate' => 'Mailster_Gmail_Google_Utils_UriTemplate',
    'Mailster\Gmail\Google\\AuthHandler\\Guzzle6AuthHandler' => 'Mailster_Gmail_Google_AuthHandler_Guzzle6AuthHandler',
    'Mailster\Gmail\Google\\AuthHandler\\Guzzle7AuthHandler' => 'Mailster_Gmail_Google_AuthHandler_Guzzle7AuthHandler',
    'Mailster\Gmail\Google\\AuthHandler\\Guzzle5AuthHandler' => 'Mailster_Gmail_Google_AuthHandler_Guzzle5AuthHandler',
    'Mailster\Gmail\Google\\AuthHandler\\AuthHandlerFactory' => 'Mailster_Gmail_Google_AuthHandler_AuthHandlerFactory',
    'Mailster\Gmail\Google\\Http\\Batch' => 'Mailster_Gmail_Google_Http_Batch',
    'Mailster\Gmail\Google\\Http\\MediaFileUpload' => 'Mailster_Gmail_Google_Http_MediaFileUpload',
    'Mailster\Gmail\Google\\Http\\REST' => 'Mailster_Gmail_Google_Http_REST',
    'Mailster\Gmail\Google\\Task\\Retryable' => 'Mailster_Gmail_Google_Task_Retryable',
    'Mailster\Gmail\Google\\Task\\Exception' => 'Mailster_Gmail_Google_Task_Exception',
    'Mailster\Gmail\Google\\Task\\Runner' => 'Mailster_Gmail_Google_Task_Runner',
    'Mailster\Gmail\Google\\Collection' => 'Mailster_Gmail_Google_Collection',
    'Mailster\Gmail\Google\\Service\\Exception' => 'Mailster_Gmail_Google_Service_Exception',
    'Mailster\Gmail\Google\\Service\\Resource' => 'Mailster_Gmail_Google_Service_Resource',
    'Mailster\Gmail\Google\\Exception' => 'Mailster_Gmail_Google_Exception',
];

foreach ($classMap as $class => $alias) {
    class_alias($class, $alias);
}

/**
 * This class needs to be defined explicitly as scripts must be recognized by
 * the autoloader.
 */
class Mailster_Gmail_Google_Task_Composer extends \Mailster\Gmail\Google\Task\Composer
{
}

/** @phpstan-ignore-next-line */
if (\false) {
    class Mailster_Gmail_Google_AccessToken_Revoke extends \Mailster\Gmail\Google\AccessToken\Revoke
    {
    }
    class Mailster_Gmail_Google_AccessToken_Verify extends \Mailster\Gmail\Google\AccessToken\Verify
    {
    }
    class Mailster_Gmail_Google_AuthHandler_AuthHandlerFactory extends \Mailster\Gmail\Google\AuthHandler\AuthHandlerFactory
    {
    }
    class Mailster_Gmail_Google_AuthHandler_Guzzle5AuthHandler extends \Mailster\Gmail\Google\AuthHandler\Guzzle5AuthHandler
    {
    }
    class Mailster_Gmail_Google_AuthHandler_Guzzle6AuthHandler extends \Mailster\Gmail\Google\AuthHandler\Guzzle6AuthHandler
    {
    }
    class Mailster_Gmail_Google_AuthHandler_Guzzle7AuthHandler extends \Mailster\Gmail\Google\AuthHandler\Guzzle7AuthHandler
    {
    }
    class Mailster_Gmail_Google_Client extends \Mailster\Gmail\Google\Client
    {
    }
    class Mailster_Gmail_Google_Collection extends \Mailster\Gmail\Google\Collection
    {
    }
    class Mailster_Gmail_Google_Exception extends \Mailster\Gmail\Google\Exception
    {
    }
    class Mailster_Gmail_Google_Http_Batch extends \Mailster\Gmail\Google\Http\Batch
    {
    }
    class Mailster_Gmail_Google_Http_MediaFileUpload extends \Mailster\Gmail\Google\Http\MediaFileUpload
    {
    }
    class Mailster_Gmail_Google_Http_REST extends \Mailster\Gmail\Google\Http\REST
    {
    }
    class Mailster_Gmail_Google_Model extends \Mailster\Gmail\Google\Model
    {
    }
    class Mailster_Gmail_Google_Service extends \Mailster\Gmail\Google\Service
    {
    }
    class Mailster_Gmail_Google_Service_Exception extends \Mailster\Gmail\Google\Service\Exception
    {
    }
    class Mailster_Gmail_Google_Service_Resource extends \Mailster\Gmail\Google\Service\Resource
    {
    }
    class Mailster_Gmail_Google_Task_Exception extends \Mailster\Gmail\Google\Task\Exception
    {
    }
    interface Mailster_Gmail_Google_Task_Retryable extends \Mailster\Gmail\Google\Task\Retryable
    {
    }
    class Mailster_Gmail_Google_Task_Runner extends \Mailster\Gmail\Google\Task\Runner
    {
    }
    class Mailster_Gmail_Google_Utils_UriTemplate extends \Mailster\Gmail\Google\Utils\UriTemplate
    {
    }
}
