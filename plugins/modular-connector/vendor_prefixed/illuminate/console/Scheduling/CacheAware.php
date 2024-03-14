<?php

namespace Modular\ConnectorDependencies\Illuminate\Console\Scheduling;

/** @internal */
interface CacheAware
{
    /**
     * Specify the cache store that should be used.
     *
     * @param  string  $store
     * @return $this
     */
    public function useStore($store);
}
