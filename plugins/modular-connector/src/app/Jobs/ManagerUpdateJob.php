<?php

namespace Modular\Connector\Jobs;

use Modular\Connector\Events\ManagerItemsUpdated;
use Modular\Connector\Facades\Manager;

class ManagerUpdateJob extends AbstractJob
{
    /**
     * @var string
     */
    protected string $mrid;

    /**
     * @param string $mrid
     */
    public function __construct(string $mrid)
    {
        $this->mrid = $mrid;
    }

    public function handle()
    {
        $items = Manager::update();

        ManagerItemsUpdated::dispatch($this->mrid, $items);

        return $items;
    }
}