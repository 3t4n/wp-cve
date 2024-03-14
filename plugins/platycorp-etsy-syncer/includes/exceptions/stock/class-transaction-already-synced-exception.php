<?php
namespace platy\etsy;

class TransactionAlreadySyncedException extends EtsySyncerException
{
    function __construct($transaction_id){
        parent::__construct("transaction $transaction_id already synced");
    }
}
