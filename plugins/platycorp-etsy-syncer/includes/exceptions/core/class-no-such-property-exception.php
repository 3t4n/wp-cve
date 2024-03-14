<?php
namespace platy\etsy;

class NoSuchPropertyException extends EtsySyncerException
{
    function __construct($message){
        parent::__construct($message);
    }
}
