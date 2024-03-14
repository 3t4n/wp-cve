<?php
namespace platy\etsy;

class AttributeInvalidException extends EtsySyncerException
{
    function __construct($msg){
        parent::__construct($msg);
    }
}
