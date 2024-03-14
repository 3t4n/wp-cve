<?php namespace BDroppy\Services\Config;

class apiConfig extends baseConfig {
    protected $base = 'api';


    public function isLogin()
    {
       return $this->get('api-token',false) &&
           ( $this->get('api-token-for-user',false) == $this->get('api-email',false));
    }
}