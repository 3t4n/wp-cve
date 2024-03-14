<?php

namespace Modular\ConnectorDependencies\Illuminate\Http\Client\Events;

use Modular\ConnectorDependencies\Illuminate\Http\Client\Request;
use Modular\ConnectorDependencies\Illuminate\Http\Client\Response;
/** @internal */
class ResponseReceived
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Client\Request
     */
    public $request;
    /**
     * The response instance.
     *
     * @var \Illuminate\Http\Client\Response
     */
    public $response;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Http\Client\Request  $request
     * @param  \Illuminate\Http\Client\Response  $response
     * @return void
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
