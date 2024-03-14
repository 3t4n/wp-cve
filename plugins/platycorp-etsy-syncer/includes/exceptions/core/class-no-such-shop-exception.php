<?php
namespace platy\etsy;

class NoSuchShopException extends EtsySyncerException
{
    function __construct($name){
        parent::__construct("No such shop " . $name);
    }
}

