<?php

namespace Modular\ConnectorDependencies\Illuminate\Contracts\Support;

/** @internal */
interface DeferringDisplayableValue
{
    /**
     * Resolve the displayable value that the class is deferring.
     *
     * @return \Illuminate\Contracts\Support\Htmlable|string
     */
    public function resolveDisplayableValue();
}
