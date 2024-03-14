<?php

namespace WunderAuto\Types\Parameters\Order;

use WunderAuto\Types\Parameters\ProParameter;

/**
 * Class ReorderLink
 */
class ReorderUrl extends ProParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'reorder_url';
        $this->description = __(
            'Returns URL for reordering the same items in a new order',
            'wunderauto'
        );
        $this->objects     = ['order'];
    }
}
