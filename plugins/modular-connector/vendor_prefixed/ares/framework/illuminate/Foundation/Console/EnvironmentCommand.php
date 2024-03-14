<?php

namespace Modular\ConnectorDependencies\Illuminate\Foundation\Console;

use Modular\ConnectorDependencies\Illuminate\Console\Command;
/** @internal */
class EnvironmentCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'env';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display the current framework environment';
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->line('<info>Current application environment:</info> <comment>' . $this->laravel['env'] . '</comment>');
    }
}
