<?php

namespace Modular\ConnectorDependencies\Illuminate\Database\Events;

use Modular\ConnectorDependencies\Illuminate\Contracts\Database\Events\MigrationEvent as MigrationEventContract;
use Modular\ConnectorDependencies\Illuminate\Database\Migrations\Migration;
/** @internal */
abstract class MigrationEvent implements MigrationEventContract
{
    /**
     * A migration instance.
     *
     * @var \Illuminate\Database\Migrations\Migration
     */
    public $migration;
    /**
     * The migration method that was called.
     *
     * @var string
     */
    public $method;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Database\Migrations\Migration  $migration
     * @param  string  $method
     * @return void
     */
    public function __construct(Migration $migration, $method)
    {
        $this->method = $method;
        $this->migration = $migration;
    }
}
