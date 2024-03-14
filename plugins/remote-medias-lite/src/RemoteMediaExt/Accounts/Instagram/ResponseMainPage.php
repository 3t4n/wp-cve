<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Instagram;

use WPRemoteMediaExt\Guzzle\Service\Command\ResponseClassInterface;
use WPRemoteMediaExt\Guzzle\Service\Command\OperationCommand;

class ResponseMainPage implements ResponseClassInterface
{
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse();

        $content = $response->getBody(true);

        //As of April 2018,
        //need to get URI of user ProfilePageContainer.js
        //need to get user channel id
        $data = array(
            'profilePageContainerUri' => '',
            'channelId' => '',
            'csrf_token' => ''
        );

        $pattern = '/static\/(.)*\/ProfilePageContainer.js\/([0-9A-z]*)\.js/';

        $matches = array();
        $matchFound = preg_match($pattern, $content, $matches);
        if ($matchFound && !empty($matches[0])) {
            $data['profilePageContainerUri'] = $matches[0];
        }

        $pattern = '/\"logging_page_id\"\:\"profilePage\_([0-9]*)/';

        $matches = array();
        $matchFound = preg_match($pattern, $content, $matches);
        if ($matchFound && !empty($matches[0]) && !empty($matches[1])) {
            $data['channelId'] = $matches[1];
        }

        $pattern = '/\"csrf_token\"\:\"([A-z0-9]*)\"/';

        $matches = array();
        $matchFound = preg_match($pattern, $content, $matches);
        if ($matchFound && !empty($matches[0]) && !empty($matches[1])) {
            $data['csrf_token'] = $matches[1];
        }
        $pattern = '/\"rhx_gis\"\:\"([A-z0-9]*)\"/';

        $matches = array();
        $matchFound = preg_match($pattern, $content, $matches);
        if ($matchFound && !empty($matches[0]) && !empty($matches[1])) {
            $data['rhx_gis'] = $matches[1];
        }
        
        return $data;
    }
}
