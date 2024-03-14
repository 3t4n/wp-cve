<?php

namespace Modular\ConnectorDependencies\Illuminate\Http\Exceptions;

use RuntimeException;
use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Response;
/** @internal */
class HttpResponseException extends RuntimeException
{
    /**
     * The underlying response instance.
     *
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;
    /**
     * Create a new HTTP response exception instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    /**
     * Get the underlying response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
