<?php
namespace platy\etsy;

class TagTooLongException extends EtsySyncerException
{
    function __construct($tag){
        parent::__construct("Tag '$tag' is too long. Visit the settings to ignore this error");
    }
}

