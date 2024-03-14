<?php

namespace Modular\ConnectorDependencies\Illuminate\Queue\Console;

use Modular\ConnectorDependencies\Illuminate\Console\Command;
use Modular\ConnectorDependencies\Illuminate\Contracts\Cache\Repository as Cache;
use Modular\ConnectorDependencies\Illuminate\Support\InteractsWithTime;
/** @internal */
class RestartCommand extends Command
{
    use InteractsWithTime;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:restart';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart queue worker daemons after their current job';
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;
    /**
     * Create a new queue restart command.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public function __construct(Cache $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->cache->forever('illuminate:queue:restart', $this->currentTime());
        $this->info('Broadcasting queue restart signal.');
    }
}
