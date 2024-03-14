<?php

namespace Modular\Connector\Events;

use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\ShouldQueue;

class ManagerItemsUpgraded extends AbstractEvent implements ShouldQueue
{
}
