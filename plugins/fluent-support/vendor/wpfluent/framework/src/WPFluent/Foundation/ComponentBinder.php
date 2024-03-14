<?php

namespace FluentSupport\Framework\Foundation;

use FluentSupport\Framework\Support\Str;
use FluentSupport\Framework\View\View;
use FluentSupport\Framework\Http\URL;
use FluentSupport\Framework\Http\Router;
use FluentSupport\Framework\Support\Facade;
use FluentSupport\Framework\Support\Pipeline;
use FluentSupport\Framework\Request\Request;
use FluentSupport\Framework\Response\Response;
use FluentSupport\Framework\Events\Dispatcher;
use FluentSupport\Framework\Database\Orm\Model;
use FluentSupport\Framework\Validator\Validator;
use FluentSupport\Framework\Foundation\RequestGuard;
use FluentSupport\Framework\Database\ConnectionResolver;
use FluentSupport\Framework\Database\Query\WPDBConnection;
use FluentSupport\Framework\Pagination\AbstractCursorPaginator;
use FluentSupport\Framework\Pagination\AbstractPaginator;
use FluentSupport\Framework\Pagination\CursorPaginator;
use FluentSupport\Framework\Pagination\Cursor;

class ComponentBinder
{
    /**
     * The application instance
     * @var \FluentSupport\Framework\Foundation\Application
     */
    protected $app = null;

    /**
     * List of bindings
     * @var array
     */
    protected $bindables = [
        'Request',
        'Response',
        'Validator',
        'View',
        'Events',
        'DB',
        'URL',
        'Router',
        'Paginator',
        'Pipeline',
    ];

    /**
     * Construct the binder
     * @param \FluentSupport\Framework\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->registerFacadeResolver(
            $this->app = $app
        );
    }

    /**
     * Bind all the components in to the container.
     * @return null
     */
    public function bindComponents()
    {
        foreach ($this->bindables as $value) {
            $method = "bind{$value}";
            $this->{$method}();
        }

        $this->extendBindings($this->app);

        $this->loadGlobalFunctions($this->app);

        $this->registerResolvingEvent($this->app);
    }

    /**
     * Register resolving event into the container.
     * @param  \FluentSupport\Framework\Foundation\Application $app
     * @return null
     */
    protected function registerResolvingEvent($app)
    {
        $app->resolving(RequestGuard::class, function($request) use ($app) {

            if (method_exists($request, 'authorize')) {
                !$request->authorize() && $request->abort(401);
            }

            $request->merge($request->beforeValidation());
            $request->validate();
            $request->merge($request->afterValidation());
        });
    }

    /**
     * Register the dynamic facade resolver.
     * @param  \FluentSupport\Framework\Foundation\Application $app
     * @return null
     */
    protected function registerFacadeResolver($app)
    {
        Facade::setFacadeApplication($app);
 
        spl_autoload_register(function($class) use ($app) {

            $ns = substr(($fqn = __NAMESPACE__), 0, strpos($fqn, '\\'));

            if (Str::contains($class, ($facade = $ns.'\Facade'))) {

                $this->createFacadeFor($facade, $class, $app);
            }
        });
    }

    /**
     * Create a facade resolver class dynamically
     * @param  string $facade
     * @param  string $class
     * @param  \FluentSupport\Framework\Foundation\Application $app
     * @return null
     */
    protected function createFacadeFor($facade, $class, $app)
    {
        $facadeAccessor = $this->resolveFacadeAccessor($facade, $class, $app);

        $anonymousClass = new class($facadeAccessor) extends Facade {

            protected static $facadeAccessor;

            public function __construct($facadeAccessor) {
                static::$facadeAccessor = $facadeAccessor;
            }

            protected static function getFacadeAccessor() {
                return static::$facadeAccessor;
            }
        };

        class_alias(get_class($anonymousClass), $class, true);
    }

    /**
     * Resolve the binding name.
     * @param  string $facade
     * @param  string $class
     * @param  \FluentSupport\Framework\Foundation\Application $app
     * @return string
     */
    protected function resolveFacadeAccessor($facade, $class,$app)
    {
        $name = strtolower(trim(str_replace($facade, '', $class), '\\'));
        
        if ($name == 'route') $name = 'router';

        if ($app->bound($name)) {
            return $name;
        }
    }

    /**
     * Bind the request instance into the container.
     * @return null
     */
    protected function bindRequest()
    {
        $this->app->singleton(Request::class, function ($app) {
            return new Request($app, $_GET, $_POST, $_FILES);
        });

        $this->app->alias(Request::class, 'request');
    }

    /**
     * Bind the reesponse instance into the container.
     * @return null
     */
    protected function bindResponse()
    {
        $this->app->singleton(Response::class, function($app) {
            return new Response($app);
        });

        $this->app->alias(Response::class, 'response');
    }

    /**
     * Bind the request validator into the container.
     * @return null
     */
    protected function bindValidator()
    {
        $this->app->bind(Validator::class, function($app) {
            return new Validator;
        });

        $this->app->alias(Validator::class, 'validator');
    }

    /**
     * Bind the view instance into the container.
     * @return null
     */
    protected function bindView()
    {
        $this->app->bind(View::class, function($app) {
            return new View($app);
        });

         $this->app->alias(View::class, 'view');
    }

    /**
     * Bind the event dispatcher instance into the container.
     * @return null
     */
    protected function bindEvents()
    {
        $this->app->singleton(Dispatcher::class, function($app) {
            return new Dispatcher($app);
        });

        $this->app->alias(Dispatcher::class, 'events');
    }

    /**
     * Bind the db (query builder) instance into the container.
     * @return null
     */
    protected function bindDB()
    {
        $this->app->singleton('db', function($app) {
            return new WPDBConnection(
                $GLOBALS['wpdb'], $app->config->get('database')
            );
        });

        Model::setEventDispatcher($this->app['events']);
        
        Model::setConnectionResolver(new ConnectionResolver);
    }

    /**
     * Bind the URL instance into the container.
     * @return null
     */
    protected function bindURL()
    {
        $this->app->bind('url', function($app) {
            return new URL;
        });
    }

    /**
     * Bind the router instance into the container.
     * @return null
     */
    protected function bindRouter()
    {
        $this->app->singleton('router', function($app) {
            return new Router($app);
        });
    }

    /**
     * Bind the paginator instance into the container.
     * @return null
     */
    protected function bindPaginator()
    {
        AbstractPaginator::currentPathResolver(function () {
            return $this->app['request']->url();
        });
        
        AbstractPaginator::currentPageResolver(function ($pageName = 'page') {
            $page = $this->app['request']->get($pageName);

            if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
                return $page;
            }

            return 1;
        });

        AbstractPaginator::queryStringResolver(function () {
            return $this->app['request']->query();
        });

        AbstractCursorPaginator::currentCursorResolver(function ($cursorName = 'cursor') {
            return Cursor::fromEncoded($this->app['request']->get($cursorName));
        });
    }

    /**
     * Bind the pipeline instance into the container.
     * @return null
     */
    protected function bindPipeline()
    {
        $this->app->bind(Pipeline::class, function ($app) {
            return new Pipeline($app);
        });

        $this->app->alias(Pipeline::class, 'pipeline');    
    }

    /**
     * Load other bindings the developers might
     * have added in the application level.
     * 
     * @param  \FluentSupport\Framework\Foundation\Application $app
     * @return null
     */
    protected function extendBindings($app)
    {
        $bindings = $app['path'] . 'boot/bindings.php';

        if (is_readable($bindings)) {
            require_once $bindings;
        }
    }

    /**
     * Load the plugin's global functions
     * @param  \FluentSupport\Framework\Foundation\Application $app
     * @return null
     */
    protected function loadGlobalFunctions($app)
    {
        $globals = $app['path'] . 'boot/globals.php';
        
        if (is_readable($globals)) {
            require_once $globals;
        }
    }
}
