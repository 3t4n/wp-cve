<?php
namespace platy\etsy;

class NoCurrentShopException extends EtsySyncerException
{
    function __construct(){
        parent::__construct("No current etsy shop");
    }
}

