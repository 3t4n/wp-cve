<?php

namespace Modular\ConnectorDependencies\Illuminate\Foundation\Exceptions;

use Modular\ConnectorDependencies\Illuminate\Filesystem\Filesystem;
use Modular\ConnectorDependencies\Illuminate\Support\Arr;
use Modular\ConnectorDependencies\Whoops\Handler\PrettyPageHandler;
/** @internal */
class WhoopsHandler
{
    /**
     * Create a new Whoops handler for debug mode.
     *
     * @return \Whoops\Handler\PrettyPageHandler
     */
    public function forDebug()
    {
        return \Modular\ConnectorDependencies\tap(new PrettyPageHandler(), function ($handler) {
            $handler->handleUnconditionally(\true);
            $this->registerApplicationPaths($handler)->registerBlacklist($handler)->registerEditor($handler);
        });
    }
    /**
     * Register the application paths with the handler.
     *
     * @param  \Whoops\Handler\PrettyPageHandler  $handler
     * @return $this
     */
    protected function registerApplicationPaths($handler)
    {
        $handler->setApplicationPaths(\array_flip($this->directoriesExceptVendor()));
        return $this;
    }
    /**
     * Get the application paths except for the "vendor" directory.
     *
     * @return array
     */
    protected function directoriesExceptVendor()
    {
        return Arr::except(\array_flip((new Filesystem())->directories(\Modular\ConnectorDependencies\base_path())), [\Modular\ConnectorDependencies\base_path('vendor')]);
    }
    /**
     * Register the blacklist with the handler.
     *
     * @param  \Whoops\Handler\PrettyPageHandler  $handler
     * @return $this
     */
    protected function registerBlacklist($handler)
    {
        foreach (\Modular\ConnectorDependencies\config('app.debug_blacklist', \Modular\ConnectorDependencies\config('app.debug_hide', [])) as $key => $secrets) {
            foreach ($secrets as $secret) {
                $handler->blacklist($key, $secret);
            }
        }
        return $this;
    }
    /**
     * Register the editor with the handler.
     *
     * @param  \Whoops\Handler\PrettyPageHandler  $handler
     * @return $this
     */
    protected function registerEditor($handler)
    {
        if (\Modular\ConnectorDependencies\config('app.editor', \false)) {
            $handler->setEditor(\Modular\ConnectorDependencies\config('app.editor'));
        }
        return $this;
    }
}
