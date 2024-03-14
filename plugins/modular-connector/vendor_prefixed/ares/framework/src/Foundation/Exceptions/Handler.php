<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Foundation\Exceptions;

use Modular\ConnectorDependencies\Illuminate\Foundation\Exceptions\Handler as FoundationHandler;
use Modular\ConnectorDependencies\Illuminate\Http\Exceptions\HttpResponseException;
use Modular\ConnectorDependencies\Illuminate\Validation\ValidationException;
/** @internal */
class Handler extends FoundationHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, \Throwable $e)
    {
        $e = $this->prepareException($this->mapException($e));
        foreach ($this->renderCallbacks as $renderCallback) {
            if (\is_a($e, $this->firstClosureParameterType($renderCallback))) {
                $response = $renderCallback($e, $request);
                if (!\is_null($response)) {
                    return $response;
                }
            }
        }
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } else {
            if ($e instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($e, $request);
            }
        }
        return $request->expectsJson() ? $this->prepareJsonResponse($request, $e) : $this->prepareResponse($request, $e);
    }
    /**
     * Get the Whoops handler for the application.
     *
     * @return \Whoops\Handler\Handler
     */
    protected function whoopsHandler()
    {
        return (new WhoopsHandler())->forDebug();
    }
    /**
     * Get the default context variables for logging.
     *
     * @return array
     */
    protected function context()
    {
        try {
            return \array_filter(['userId' => \get_current_user_id()]);
        } catch (\Throwable $e) {
            return [];
        }
    }
}
