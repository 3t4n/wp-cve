<?php
namespace platy\etsy;

class NotStockManagedException extends EtsySyncerException
{
    function __construct($pid){
        parent::__construct("product $pid is not stock managed");
    }
}
