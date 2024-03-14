<?php namespace BDroppy\Services\WooCommerce;



use BDroppy\Init\Core;

class WC
{
    public $importer;
    public $syncOrder;
    public $resource;
    public function __construct(Core $core)
    {
        $this->resource = new Resource();
        $this->syncOrder = new SyncOrder($core);
        $this->importer = new Importer($this,$core);
    }

}