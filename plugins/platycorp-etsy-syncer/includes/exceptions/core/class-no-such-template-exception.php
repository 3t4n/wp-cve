<?php
namespace platy\etsy;

class NoSuchTemplateException extends EtsySyncerException
{
    function __construct($name){
        parent::__construct("No such template " . $name);
    }
}

