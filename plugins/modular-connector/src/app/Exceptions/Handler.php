<?php

namespace Modular\Connector\Exceptions;

use Modular\ConnectorDependencies\Ares\Framework\Foundation\Exceptions\Handler as ExceptionHandler;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\View;
use Modular\ConnectorDependencies\Illuminate\Support\ViewErrorBag;
use Modular\ConnectorDependencies\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Sentry.io function.
     * @param \Throwable $exception
     * @throws \Throwable
     */
    public function report(\Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render the given HttpException.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $this->registerErrorViewPaths();

        if (View::exists($view = $this->getHttpExceptionView($e))) {
            die(View::make($view, ['errors' => new ViewErrorBag(), 'exception' => $e])->render());
        }

        return $this->convertExceptionToResponse($e);
    }
}
