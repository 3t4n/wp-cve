<?php

namespace Modular\ConnectorDependencies\Illuminate\Foundation\Console;

use Modular\ConnectorDependencies\Illuminate\Console\Command;
use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\ConnectorDependencies\Symfony\Component\Finder\Finder;
use Modular\ConnectorDependencies\Symfony\Component\Finder\SplFileInfo;
/** @internal */
class ViewCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:cache';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Compile all of the application's Blade templates";
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('view:clear');
        $this->paths()->each(function ($path) {
            $this->compileViews($this->bladeFilesIn([$path]));
        });
        $this->info('Blade templates cached successfully!');
    }
    /**
     * Compile the given view files.
     *
     * @param  \Illuminate\Support\Collection  $views
     * @return void
     */
    protected function compileViews(Collection $views)
    {
        $compiler = $this->laravel['view']->getEngineResolver()->resolve('blade')->getCompiler();
        $views->map(function (SplFileInfo $file) use($compiler) {
            $compiler->compile($file->getRealPath());
        });
    }
    /**
     * Get the Blade files in the given path.
     *
     * @param  array  $paths
     * @return \Illuminate\Support\Collection
     */
    protected function bladeFilesIn(array $paths)
    {
        return \Modular\ConnectorDependencies\collect(Finder::create()->in($paths)->exclude('vendor')->name('*.blade.php')->files());
    }
    /**
     * Get all of the possible view paths.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function paths()
    {
        $finder = $this->laravel['view']->getFinder();
        return \Modular\ConnectorDependencies\collect($finder->getPaths())->merge(\Modular\ConnectorDependencies\collect($finder->getHints())->flatten());
    }
}
