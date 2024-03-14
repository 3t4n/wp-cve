<?php

namespace Modular\Connector\Events;

use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\ShouldQueue;

class ManagerItemsUpdated extends AbstractEvent implements ShouldQueue
{
}
