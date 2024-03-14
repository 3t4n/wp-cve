<?php

namespace platy\etsy;

class BadFormatException extends EtsySyncerException
{
    function __construct($str, $for = "title"){
        parent::__construct("Bad format for $for: " . $str);
    }
}

