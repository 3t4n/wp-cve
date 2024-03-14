<?php
namespace platy\etsy;

class NoSuchListingException extends EtsySyncerException
{
    function __construct(){
        parent::__construct("No such listing");
    }
}