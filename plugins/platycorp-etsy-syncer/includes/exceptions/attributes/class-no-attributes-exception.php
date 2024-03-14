<?php
namespace platy\etsy;

class NoAttributesException extends EtsySyncerException
{
    function __construct($tax_id, $prop_id = 0){
        parent::__construct("No attributes for taxonomy id $tax_id" . 
            empty($prop_id) ? "" : " and property id $prop_id");
    }
}
