<?php
namespace platy\etsy;

class NoSuchPostException extends EtsySyncerException
{
    function __construct($post_id){
        parent::__construct("No such post $post_id");
    }
}