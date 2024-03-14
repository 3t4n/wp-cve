<?php

namespace Modular\ConnectorDependencies\Illuminate\Foundation\Exceptions;

use Modular\ConnectorDependencies\Illuminate\Support\Facades\View;
/** @internal */
class RegisterErrorViewPaths
{
    /**
     * Register the error view paths.
     *
     * @return void
     */
    public function __invoke()
    {
        View::replaceNamespace('errors', \Modular\ConnectorDependencies\collect(\Modular\ConnectorDependencies\config('view.paths'))->map(function ($path) {
            return "{$path}/errors";
        })->push(__DIR__ . '/views')->all());
    }
}
