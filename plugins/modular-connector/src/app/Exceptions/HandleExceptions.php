<?php

namespace Modular\Connector\Exceptions;

use Modular\Connector\Services\Helpers\Utils;
use Modular\ConnectorDependencies\Ares\Framework\Foundation\Bootstrap\HandleExceptions as FoundationHandleExceptions;
use Modular\ConnectorDependencies\Illuminate\Contracts\Foundation\Application;

class HandleExceptions extends FoundationHandleExceptions
{
    /**
     * Bootstrap the given application.
     *
     * @param \Modular\ConnectorDependencies\Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        if (Utils::isModularRequest()) {
            self::$reservedMemory = \str_repeat('x', 10240);
            $this->app = $app;

            set_error_handler([$this, 'handleError']);
            set_exception_handler([$this, 'handleException']);
            register_shutdown_function([$this, 'handleShutdown']);
        }
    }

    /**
     * Report PHP deprecations, or convert PHP errors to ErrorException instances.
     *
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @param array $context
     * @return void
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if ($this->isDeprecation($level)) {
            return $this->handleDeprecation($message, $file, $line);
        }

        if (\error_reporting() & $level) {
            // Because many WordPress plugins/themes contain errors, we should only report the error to avoid stopping normal execution.
            if (!$this->app->config->get('app.debug')) {
                $this->handleException(new \ErrorException($message, 0, $level, $file, $line));
            } else {
                throw new \ErrorException($message, 0, $level, $file, $line);
            }
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param \Throwable $e
     * @return void
     */
    public function handleException(\Throwable $e)
    {
        self::$reservedMemory = null;

        try {
            $this->getExceptionHandler()->report($e);
        } catch (\Exception $e) {
            //
        }

        if ($this->app->runningInConsole()) {
            $this->renderForConsole($e);
        } else if ($this->app->config->get('app.debug')) {
            $this->renderHttpResponse($e);
        }
    }
}
