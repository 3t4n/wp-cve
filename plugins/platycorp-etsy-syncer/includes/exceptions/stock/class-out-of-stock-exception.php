<?php
namespace platy\etsy;

class ProductOutOfStockException extends EtsySyncerException
{
    function __construct(){
        parent::__construct("Cant upload out of stock product");
    }
}
