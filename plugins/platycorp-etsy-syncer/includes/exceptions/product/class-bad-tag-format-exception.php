<?php
namespace platy\etsy;

class BadTagFormatException extends EtsySyncerException
{
    function __construct($tag){
        parent::__construct("Tag '$tag' is has illegal format. Visit the settings to ignore this error");
    }
}

