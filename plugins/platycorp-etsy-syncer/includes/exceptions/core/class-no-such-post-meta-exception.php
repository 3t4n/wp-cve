<?php
namespace platy\etsy;

class NoSuchPostMetaException extends EtsySyncerException
{
    function __construct($shop_id, $post_id, $meta_key){
        parent::__construct("No meta key $meta_key for post id $post_id and shop id $shop_id");
    }
}
