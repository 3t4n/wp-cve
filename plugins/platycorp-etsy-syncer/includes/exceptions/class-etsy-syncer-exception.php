<?php
namespace platy\etsy;

class EtsySyncerException extends \RuntimeException
{
    private $listing_id;
    function __construct($message, $listing_id = 0){
        parent::__construct($message);
        $this->listing_id = $listing_id;
    }

    function get_listing_id(){
        return $this->listing_id;
    }

    function update_listing_id($listing_id){
        $this->listing_id = $listing_id;
    }

}

