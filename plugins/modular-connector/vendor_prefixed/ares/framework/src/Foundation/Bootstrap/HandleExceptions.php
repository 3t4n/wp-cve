<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Foundation\Bootstrap;

use Modular\ConnectorDependencies\Illuminate\Contracts\Foundation\Application;
use Modular\ConnectorDependencies\Illuminate\Foundation\Bootstrap\HandleExceptions as FoundationHandleExceptions;
/** @internal */
class HandleExceptions extends FoundationHandleExceptions
{
    /**
     * Bootstrap the given application.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        self::$reservedMemory = \str_repeat('x', 10240);
        $this->app = $app;
        if (!$app->isProduction()) {
            \error_reporting(-1);
            \set_error_handler([$this, 'handleError']);
            \set_exception_handler([$this, 'handleException']);
            \register_shutdown_function([$this, 'handleShutdown']);
        } else {
            \ini_set('display_errors', 'Off');
        }
    }
}
