<?php

namespace Modular\ConnectorDependencies\Illuminate\Contracts\Translation;

/** @internal */
interface HasLocalePreference
{
    /**
     * Get the preferred locale of the entity.
     *
     * @return string|null
     */
    public function preferredLocale();
}
