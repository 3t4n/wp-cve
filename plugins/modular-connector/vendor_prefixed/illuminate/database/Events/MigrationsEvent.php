<?php

namespace Modular\ConnectorDependencies\Illuminate\Database\Events;

use Modular\ConnectorDependencies\Illuminate\Contracts\Database\Events\MigrationEvent as MigrationEventContract;
/** @internal */
abstract class MigrationsEvent implements MigrationEventContract
{
    /**
     * The migration method that was invoked.
     *
     * @var string
     */
    public $method;
    /**
     * Create a new event instance.
     *
     * @param  string  $method
     * @return void
     */
    public function __construct($method)
    {
        $this->method = $method;
    }
}
