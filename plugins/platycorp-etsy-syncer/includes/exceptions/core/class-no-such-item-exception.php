<?php

namespace platy\etsy\orders;
use platy\etsy\EtsySyncerException;


class NoSuchEtsyItemException extends EtsySyncerException{
    function __construct($type, $transaction_id){
        parent::__construct("No such item type $type for etsy transaction $transaction_id");
    }
}