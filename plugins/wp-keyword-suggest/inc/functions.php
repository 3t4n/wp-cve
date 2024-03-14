<?php

class wpks_api
{
    private $keywords = NULL;

    function __construct()
    {
    
    }
    
    function __destruct()
    {
        $this->keywords = NULL;
    }
    
    public function request( $keyword = NULL )
    {
        if($keyword == NULL)
            return;

        /*
        This plugin makes to calls to seowp.es, the first one to create the URLs of the autocomplete service, and the second one to filter and complete the results.
        */
        $q = str_replace(' ', '+', $keyword);
        $intense = get_option( 'wpks_intense' );
        $url = "http://www.seowp.es/wpks/generate_urls.php?q=$q&intense=$intense";

        $rsp_json = wp_remote_fopen($url);
        $array_urls = json_decode($rsp_json);

        if(is_array($array_urls))
        {
           foreach($array_urls as $item)
            {
                $params[$item->service] = utf8_encode(wp_remote_fopen($item->url));
            }

            $url = 'http://www.seowp.es/wpks/keywords_list.php';
            $args = array(
                            'method' => 'POST',
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => array(),
                            'body' => $params,
                            'cookies' => array()
                        );
            $response = wp_remote_post($url, $args);
            if ( is_wp_error( $response ) )
            {
               return "Application Error";
            }
            else
                return $response['body']; 
        }
        else
        {
            return "Application Error";
        }
        
    }
}

?>
