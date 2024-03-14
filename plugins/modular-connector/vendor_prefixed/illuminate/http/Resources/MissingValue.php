<?php

namespace Modular\ConnectorDependencies\Illuminate\Http\Resources;

/** @internal */
class MissingValue implements PotentiallyMissing
{
    /**
     * Determine if the object should be considered "missing".
     *
     * @return bool
     */
    public function isMissing()
    {
        return \true;
    }
}
