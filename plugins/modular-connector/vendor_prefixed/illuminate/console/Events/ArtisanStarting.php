<?php

namespace Modular\ConnectorDependencies\Illuminate\Console\Events;

/** @internal */
class ArtisanStarting
{
    /**
     * The Artisan application instance.
     *
     * @var \Illuminate\Console\Application
     */
    public $artisan;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Application  $artisan
     * @return void
     */
    public function __construct($artisan)
    {
        $this->artisan = $artisan;
    }
}
