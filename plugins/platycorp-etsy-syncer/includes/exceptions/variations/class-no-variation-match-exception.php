<?php
namespace platy\etsy;

class NoVariationMatchException extends EtsySyncerException
{
    function __construct($msg = ""){
        parent::__construct("No variation match in this inventory: $msg");
    }
}

