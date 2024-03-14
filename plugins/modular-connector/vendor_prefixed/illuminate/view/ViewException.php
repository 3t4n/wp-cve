<?php

namespace Modular\ConnectorDependencies\Illuminate\View;

use ErrorException;
use Modular\ConnectorDependencies\Illuminate\Container\Container;
use Modular\ConnectorDependencies\Illuminate\Support\Reflector;
/** @internal */
class ViewException extends ErrorException
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        $exception = $this->getPrevious();
        if (Reflector::isCallable($reportCallable = [$exception, 'report'])) {
            return Container::getInstance()->call($reportCallable);
        }
        return \false;
    }
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $exception = $this->getPrevious();
        if ($exception && \method_exists($exception, 'render')) {
            return $exception->render($request);
        }
    }
}
