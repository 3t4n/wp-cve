<?php
namespace platy\etsy;

class ProductNotVariableException extends EtsySyncerException
{
    function __construct(){
        parent::__construct("Product not variable");
    }

}
