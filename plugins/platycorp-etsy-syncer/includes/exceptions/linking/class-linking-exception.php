<?php
namespace platy\etsy;

class LinkingException extends EtsySyncerException
{
    function __construct($msg){
        parent::__construct($msg);
    }
}
