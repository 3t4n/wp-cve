<?php

class SocialHttpRequestHandler
{
    const HEADER_JSON = 'application/json';
    const HEADER_FORM_DATA = 'application/x-www-form-urlencoded';
    
    private $_ch = null;
    
    private $_url = '';
    
    private $_headers = array();
    
    private $_post_data = array();
    
    private $_timeout = 10;
    
    private $_get_resp_header = false;
    
    private $_return_transfer = true;
    
    private $_follow = true;
    
    private $_resp = '';
    
    private $_info = '';
    
    private $_error_info = null;
    
    public function setUrl($url)
    {
        $this->_url = $url;
        
        return $this;
    }
    
    public function setHeaders($headers)
    {
        $this->_headers = $headers;
        
        return $this;
    }
    
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
        
        return $this;
    }
    
    public function setReturnTrasnsfer($has_return_transfer)
    {
        $this->_return_transfer = $has_return_transfer;
        
        return $this;
    }
    
    public function setFollowUrl($is_follow)
    {
        $this->_follow = $is_follow;
        
        return $this;
    }
    
    public function setContentTypeAsJSON()
    {
        $this->_headers[] = 'Content-Type: '.self::HEADER_JSON;
        
        return $this;
    }
    
    public function setPostData($data)
    {
        $this->_post_data = $data;
        
        return $this;
    }
    
    public function resetRequest()
    {
        $this->_resp = '';
        $this->_info = '';
        $this->_error_info = null;
        $this->_headers = array();
        
        return $this;
    }
    
    public function exec($url = '')
    {
        try
        {
            if(!empty($url))
                $this->_url = $url;
            
            if(empty($this->_url))
                throw new Exception('URL is empty');

            if(!empty($this->_post_data)) {
                $response = wp_remote_post($url, array('body' => $this->_post_data, 'timeout' => 10));

                if (is_wp_error( $response ) || !isset($response['body'])) {
                    throw new Exception('Request failed');
                }

                $this->_resp = $response['body'];
            }
            else {
                $response = wp_remote_get( $url );
                if (is_wp_error( $response ) || !isset($response['body'])) {
                    throw new Exception('Request failed');
                }

                $this->_resp = $response['body'];
                
            }

        }
        catch(Exception $e)
        {
            $this->_error_info = $e;
        }
        
        return $this;
    }
    
    public function getResponse()
    {
        return $this->_resp;
    }
    
    public function getRequestInfo()
    {
        return $this->_info;
    }
    
    public function getErrorInfo()
    {
        return $this->_error_info;
    }
}
