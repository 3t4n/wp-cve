<?php

namespace Modular\ConnectorDependencies\Ares\View\Engines;

use Modular\ConnectorDependencies\Illuminate\View\Engines\CompilerEngine as CompilerEngineFoundation;
use Modular\ConnectorDependencies\Illuminate\View\ViewException;
/** @internal */
class CompilerEngine extends CompilerEngineFoundation
{
    /**
     * Handle a view exception.
     *
     * @param \Throwable $e
     * @param int $obLevel
     * @return void
     *
     * @throws \Throwable
     */
    protected function handleViewException(\Throwable $e, $obLevel)
    {
        $e = new ViewException($this->getMessage($e), 0, 1, $e->getFile(), $e->getLine(), null);
        while (\ob_get_level() > $obLevel) {
            \ob_end_clean();
        }
        throw $e;
    }
}
