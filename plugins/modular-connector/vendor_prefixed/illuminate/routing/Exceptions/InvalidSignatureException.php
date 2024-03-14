<?php

namespace Modular\ConnectorDependencies\Illuminate\Routing\Exceptions;

use Modular\ConnectorDependencies\Symfony\Component\HttpKernel\Exception\HttpException;
/** @internal */
class InvalidSignatureException extends HttpException
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(403, 'Invalid signature.');
    }
}
