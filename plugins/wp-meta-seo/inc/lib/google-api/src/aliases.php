<?php

if (class_exists('WPMSGoogle_Client', false)) {
    // Prevent error with preloading in PHP 7.4
    // @see https://github.com/googleapis/google-api-php-client/issues/1976
    return;
}

$classMap = [
    'WPMSGoogle\\Client' => 'WPMSGoogle_Client',
    'WPMSGoogle\\Service' => 'WPMSGoogle_Service',
    'WPMSGoogle\\AccessToken\\Revoke' => 'WPMSGoogle_AccessToken_Revoke',
    'WPMSGoogle\\AccessToken\\Verify' => 'WPMSGoogle_AccessToken_Verify',
    'WPMSGoogle\\Model' => 'WPMSGoogle_Model',
    'WPMSGoogle\\Utils\\UriTemplate' => 'WPMSGoogle_Utils_UriTemplate',
    'WPMSGoogle\\AuthHandler\\Guzzle6AuthHandler' => 'WPMSGoogle_AuthHandler_Guzzle6AuthHandler',
    'WPMSGoogle\\AuthHandler\\Guzzle7AuthHandler' => 'WPMSGoogle_AuthHandler_Guzzle7AuthHandler',
    'WPMSGoogle\\AuthHandler\\Guzzle5AuthHandler' => 'WPMSGoogle_AuthHandler_Guzzle5AuthHandler',
    'WPMSGoogle\\AuthHandler\\AuthHandlerFactory' => 'WPMSGoogle_AuthHandler_AuthHandlerFactory',
    'WPMSGoogle\\Http\\Batch' => 'WPMSGoogle_Http_Batch',
    'WPMSGoogle\\Http\\MediaFileUpload' => 'WPMSGoogle_Http_MediaFileUpload',
    'WPMSGoogle\\Http\\REST' => 'WPMSGoogle_Http_REST',
    'WPMSGoogle\\Task\\Retryable' => 'WPMSGoogle_Task_Retryable',
    'WPMSGoogle\\Task\\Exception' => 'WPMSGoogle_Task_Exception',
    'WPMSGoogle\\Task\\Runner' => 'WPMSGoogle_Task_Runner',
    'WPMSGoogle\\Collection' => 'WPMSGoogle_Collection',
    'WPMSGoogle\\Service\\Exception' => 'WPMSGoogle_Service_Exception',
    'WPMSGoogle\\Service\\Resource' => 'WPMSGoogle_Service_Resource',
    'WPMSGoogle\\Service\\Analytics' => 'WPMSGoogle_Service_Analytics',
    'WPMSGoogle\\Exception' => 'WPMSGoogle_Exception',
];

foreach ($classMap as $class => $alias) {
    class_alias($class, $alias);
}

/**
 * This class needs to be defined explicitly as scripts must be recognized by
 * the autoloader.
 */
class WPMSGoogle_Task_Composer extends \WPMSGoogle\Task\Composer
{
}

if (\false) {
  class WPMSGoogle_AccessToken_Revoke extends \WPMSGoogle\AccessToken\Revoke {}
  class WPMSGoogle_AccessToken_Verify extends \WPMSGoogle\AccessToken\Verify {}
  class WPMSGoogle_AuthHandler_AuthHandlerFactory extends \WPMSGoogle\AuthHandler\AuthHandlerFactory {}
  class WPMSGoogle_AuthHandler_Guzzle5AuthHandler extends \WPMSGoogle\AuthHandler\Guzzle5AuthHandler {}
  class WPMSGoogle_AuthHandler_Guzzle6AuthHandler extends \WPMSGoogle\AuthHandler\Guzzle6AuthHandler {}
  class WPMSGoogle_AuthHandler_Guzzle7AuthHandler extends \WPMSGoogle\AuthHandler\Guzzle7AuthHandler {}
  class WPMSGoogle_Client extends \WPMSGoogle\Client {}
  class WPMSGoogle_Collection extends \WPMSGoogle\Collection {}
  class WPMSGoogle_Exception extends \WPMSGoogle\Exception {}
  class WPMSGoogle_Http_Batch extends \WPMSGoogle\Http\Batch {}
  class WPMSGoogle_Http_MediaFileUpload extends \WPMSGoogle\Http\MediaFileUpload {}
  class WPMSGoogle_Http_REST extends \WPMSGoogle\Http\REST {}
  class WPMSGoogle_Model extends \WPMSGoogle\Model {}
  class WPMSGoogle_Service extends \WPMSGoogle\Service {}
  class WPMSGoogle_Service_Exception extends \WPMSGoogle\Service\Exception {}
  class WPMSGoogle_Service_Resource extends \WPMSGoogle\Service\Resource {}
  class WPMSGoogle_Task_Exception extends \WPMSGoogle\Task\Exception {}
  class WPMSGoogle_Task_Retryable implements \WPMSGoogle\Task\Retryable {}
  class WPMSGoogle_Task_Runner extends \WPMSGoogle\Task\Runner {}
  class WPMSGoogle_Utils_UriTemplate extends \WPMSGoogle\Utils\UriTemplate {}
}
