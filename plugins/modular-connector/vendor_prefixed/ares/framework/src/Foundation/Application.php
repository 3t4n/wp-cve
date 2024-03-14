<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Foundation;

use Modular\ConnectorDependencies\Illuminate\Events\EventServiceProvider;
use Modular\ConnectorDependencies\Illuminate\Filesystem\Filesystem;
use Modular\ConnectorDependencies\Illuminate\Foundation\Application as FoundationApplication;
use Modular\ConnectorDependencies\Illuminate\Foundation\PackageManifest as FoundationPackageManifest;
use Modular\ConnectorDependencies\Illuminate\Log\LogServiceProvider;
use Modular\ConnectorDependencies\Illuminate\Routing\RoutingServiceProvider;
/** @internal */
class Application extends FoundationApplication
{
    /**
     * The Laravel framework version.
     *
     * @var string
     */
    public const VERSION = 'Ares 2.x (Laravel ' . parent::VERSION . ')';
    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        parent::registerBaseBindings();
        $this->singleton(FoundationPackageManifest::class, function () {
            return new PackageManifest(new Filesystem(), $this->basePath(), $this->getCachedPackagesPath());
        });
    }
    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
        $this->register(new LogServiceProvider($this));
        $this->register(new RoutingServiceProvider($this));
    }
    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases()
    {
        $aliases = ['app' => [self::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Container\Container::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Foundation\Application::class, \Modular\ConnectorDependencies\Psr\Container\ContainerInterface::class], 'blade.compiler' => [\Modular\ConnectorDependencies\Illuminate\View\Compilers\BladeCompiler::class], 'config' => [\Modular\ConnectorDependencies\Illuminate\Config\Repository::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Config\Repository::class], 'db' => [\Modular\ConnectorDependencies\Illuminate\Database\DatabaseManager::class, \Modular\ConnectorDependencies\Illuminate\Database\ConnectionResolverInterface::class], 'db.connection' => [\Modular\ConnectorDependencies\Illuminate\Database\Connection::class, \Modular\ConnectorDependencies\Illuminate\Database\ConnectionInterface::class], 'events' => [\Modular\ConnectorDependencies\Illuminate\Events\Dispatcher::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Events\Dispatcher::class], 'files' => [\Modular\ConnectorDependencies\Illuminate\Filesystem\Filesystem::class], 'filesystem' => [\Modular\ConnectorDependencies\Illuminate\Filesystem\FilesystemManager::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Filesystem\Factory::class], 'filesystem.disk' => [\Modular\ConnectorDependencies\Illuminate\Contracts\Filesystem\Filesystem::class], 'filesystem.cloud' => [\Modular\ConnectorDependencies\Illuminate\Contracts\Filesystem\Cloud::class], 'log' => [\Modular\ConnectorDependencies\Illuminate\Log\LogManager::class, \Modular\ConnectorDependencies\Psr\Log\LoggerInterface::class], 'queue' => [\Modular\ConnectorDependencies\Illuminate\Queue\QueueManager::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Queue\Factory::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Queue\Monitor::class], 'queue.connection' => [\Modular\ConnectorDependencies\Illuminate\Contracts\Queue\Queue::class], 'queue.failer' => [\Modular\ConnectorDependencies\Illuminate\Queue\Failed\FailedJobProviderInterface::class], 'request' => [\Modular\ConnectorDependencies\Illuminate\Http\Request::class, \Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Request::class], 'view' => [\Modular\ConnectorDependencies\Illuminate\View\Factory::class, \Modular\ConnectorDependencies\Illuminate\Contracts\View\Factory::class], 'validator' => [\Modular\ConnectorDependencies\Illuminate\Validation\Factory::class, \Modular\ConnectorDependencies\Illuminate\Contracts\Validation\Factory::class]];
        foreach ($aliases as $key => $value) {
            foreach ($value as $alias) {
                $this->alias($key, $alias);
            }
        }
    }
    /**
     * Return public URI of asset
     *
     * @param string $path
     * @return string
     */
    public function publicUri(string $path = '')
    {
        $path = '/public' . ($path ? '/' . \ltrim($path, '/') : $path);
        // Check if is plugin
        if (\file_exists($this->basePath('./init.php'))) {
            return \plugin_dir_url($this->basePath()) . $path;
        }
        return \get_template_directory_uri() . $path;
    }
}
