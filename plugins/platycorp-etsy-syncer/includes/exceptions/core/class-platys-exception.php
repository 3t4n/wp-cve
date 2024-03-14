<?php
namespace platy\etsy;

class PlatysException extends EtsySyncerException
{
    function __construct(){
        parent::__construct("No platys");
    }
}

