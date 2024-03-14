<?php
namespace platy\etsy;

class VariationImagesException extends EtsySyncerException
{
    function __construct(){
        parent::__construct("Can only upload variation images if there is exactly one variation type");
    }
}
