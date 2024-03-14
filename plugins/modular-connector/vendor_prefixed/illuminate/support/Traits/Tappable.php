<?php

namespace Modular\ConnectorDependencies\Illuminate\Support\Traits;

/** @internal */
trait Tappable
{
    /**
     * Call the given Closure with this instance then return the instance.
     *
     * @param  callable|null  $callback
     * @return $this|\Illuminate\Support\HigherOrderTapProxy
     */
    public function tap($callback = null)
    {
        return \Modular\ConnectorDependencies\tap($this, $callback);
    }
}
