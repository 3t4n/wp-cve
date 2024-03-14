<?php

namespace Modular\Connector\Events;

use Modular\ConnectorDependencies\Illuminate\Contracts\Queue\ShouldQueue;

class ManagerHealthUpdated extends AbstractEvent implements ShouldQueue
{
}
