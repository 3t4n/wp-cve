<?php

namespace Modular\ConnectorDependencies\Illuminate\Validation;

/** @internal */
interface DatabasePresenceVerifierInterface extends PresenceVerifierInterface
{
    /**
     * Set the connection to be used.
     *
     * @param  string  $connection
     * @return void
     */
    public function setConnection($connection);
}
