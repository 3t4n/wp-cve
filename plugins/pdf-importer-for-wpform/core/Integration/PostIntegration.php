<?php


namespace rnpdfimporter\core\Integration;


use Exception;

class PostIntegration
{
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function RemotePost($url, $data)
    {
        $response= wp_remote_post($url,$data);
        if(\is_wp_error($response))
            throw new Exception($response->get_error_message());
        return $response;
    }

}