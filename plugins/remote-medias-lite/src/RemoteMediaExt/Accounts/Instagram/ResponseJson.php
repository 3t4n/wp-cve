<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Instagram;

use WPRemoteMediaExt\Guzzle\Service\Command\ResponseClassInterface;
use WPRemoteMediaExt\Guzzle\Service\Command\OperationCommand;

class ResponseJson implements ResponseClassInterface
{
    public static function fromCommand(OperationCommand $command)
    {
        $response = $command->getResponse();

        $json = $response->getBody(true);
        //Old way
        // $html = strstr($html, '{"static_root');
        // $html = strstr($html, '</script>', true);
        // $html = substr($html, 0, -1);

        //New way November 2017
        // $marker = "window._sharedData = ";
        // $json = strstr($json, $marker);
        // $json = substr($json, strlen($marker));
        // $json = strstr($json, '</script>', true);
        // $json = substr($json, 0, -1);
    
        // print_r(json_decode($json));
        return json_decode($json);
    }
}
