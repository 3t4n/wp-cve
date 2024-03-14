<?php

namespace platy\etsy;

class EmptyVariationException extends EtsySyncerException
{
    function __construct($attr){
        parent::__construct("No variations for " . $attr);
    }
}

