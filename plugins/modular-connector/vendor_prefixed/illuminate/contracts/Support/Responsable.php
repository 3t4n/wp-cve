<?php

namespace Modular\ConnectorDependencies\Illuminate\Contracts\Support;

/** @internal */
interface Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request);
}
