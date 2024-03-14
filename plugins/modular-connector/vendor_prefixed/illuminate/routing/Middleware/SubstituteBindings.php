<?php

namespace Modular\ConnectorDependencies\Illuminate\Routing\Middleware;

use Closure;
use Modular\ConnectorDependencies\Illuminate\Contracts\Routing\Registrar;
use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\ModelNotFoundException;
/** @internal */
class SubstituteBindings
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;
    /**
     * Create a new bindings substitutor.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @return void
     */
    public function __construct(Registrar $router)
    {
        $this->router = $router;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $this->router->substituteBindings($route = $request->route());
            $this->router->substituteImplicitBindings($route);
        } catch (ModelNotFoundException $exception) {
            if ($route->getMissing()) {
                return $route->getMissing()($request, $exception);
            }
            throw $exception;
        }
        return $next($request);
    }
}
