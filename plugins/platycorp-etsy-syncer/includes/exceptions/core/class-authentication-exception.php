<?php
namespace platy\etsy;

class AuthenticationException extends EtsySyncerException
{
    function __construct(){
        parent::__construct("Could not authenticate");
    }
}

