<?php

namespace Modular\ConnectorDependencies\Illuminate\Http\Client\Events;

use Modular\ConnectorDependencies\Illuminate\Http\Client\Request;
/** @internal */
class RequestSending
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Client\Request
     */
    public $request;
    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Http\Client\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
