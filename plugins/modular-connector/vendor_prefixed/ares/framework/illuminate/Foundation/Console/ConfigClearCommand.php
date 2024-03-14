<?php

namespace Modular\ConnectorDependencies\Illuminate\Foundation\Console;

use Modular\ConnectorDependencies\Illuminate\Console\Command;
use Modular\ConnectorDependencies\Illuminate\Filesystem\Filesystem;
/** @internal */
class ConfigClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'config:clear';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the configuration cache file';
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * Create a new config clear command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->files->delete($this->laravel->getCachedConfigPath());
        $this->info('Configuration cache cleared!');
    }
}
