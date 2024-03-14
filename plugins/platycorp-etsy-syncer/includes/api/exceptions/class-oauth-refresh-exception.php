<?php
namespace platy\etsy\api;

class OAuthRefreshException extends OAuthException {


    function __construct($error_msg){
        parent::__construct($error_msg, 405); // not sure about this status code
    }

}