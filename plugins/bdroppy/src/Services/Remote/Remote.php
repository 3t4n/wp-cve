<?php

namespace BDroppy\Services\Remote;

if (!defined('ABSPATH')) exit;

use BDroppy\Init\Core;

class Remote
{
    public $main;
    public $product;
    public $catalog;
    public $order;

    public function __construct(Core $core)
    {
        $this->main     = new MainRemote($core);
        $this->product  = new ProductRemote($core);
        $this->catalog  = new CatalogRemote($core);
        $this->order  = new OrderRemote($core);
    }

}