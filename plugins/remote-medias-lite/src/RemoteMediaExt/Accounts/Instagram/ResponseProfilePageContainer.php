<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Instagram;

use WPRemoteMediaExt\Guzzle\Service\Command\ResponseClassInterface;
use WPRemoteMediaExt\Guzzle\Service\Command\OperationCommand;

class ResponseProfilePageContainer implements ResponseClassInterface
{
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse();

        $content = $response->getBody(true);

        //As of April 2018, only need to get queryId from user ProfilePageContainer.js

        // With lookbehind
        // $pattern = '/(?<=queryId:\")[0-9A-z]{32,32}/';
        $pattern = '/profilePosts\.byUserId\.get\(t\)\)\?o\.pagination\:o\}\,queryId:\"([0-9A-z]{32,32})/';

        $matches = array();
        $matchFound = preg_match($pattern, $content, $matches);

        if (!$matchFound || empty($matches[0]) || empty($matches[1])) {
            return array();
        }
        
        return array(
            'queryId' => $matches[1]
        );
    }
}
