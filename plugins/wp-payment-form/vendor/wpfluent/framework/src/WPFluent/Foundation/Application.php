<?php

namespace WPPayForm\Framework\Foundation;

use InvalidArgumentException;
use WPPayForm\Framework\Foundation\Config;
use WPPayForm\Framework\Container\Container;
use WPPayForm\Framework\Foundation\ComponentBinder;
use WPPayForm\Framework\Foundation\FoundationTrait;
use WPPayForm\Framework\Foundation\AsyncRequestTrait;
use WPPayForm\Framework\Foundation\CronTaskSchedulerTrait;

class Application extends Container
{
    use FoundationTrait;
    use AsyncRequestTrait;
    use CronTaskSchedulerTrait;

    /**
     * Main plugin file's absolute path
     * @var string
     */
    protected $file = null;

    /**
     * Plugin's base url
     * @var string
     */
    protected $baseUrl = null;

    /**
     * Plugin's base path
     * @var string
     */
    protected $basePath = null;

    /**
     * Default namespace for hook's handlers
     * @var string
     */
    protected $handlerNamespace = null;

    /**
     * Default namespace for controllers
     * @var string
     */
    protected $controllerNamespace = null;

    /**
     * Default namespace for policy handlers
     * @var string
     */
    protected $permissionNamespace = null;

    /**
     * Composer JSON
     * @var null|array
     */
    protected static $composer = null;

    /**
     * Construct the application instance
     * 
     * @param string $file The main plugin file's absolute path
     * @return null
     */
    public function __construct($file = null)
    {
        $this->init($file);
        $this->setAppLevelNamespace();
        $this->bootstrapApplication();
    }

    /**
     * Init the application instance
     * 
     * @param string $file The main plugin file's absolute path
     * 
     * @return null
     */
    protected function init($file)
    {
        $this['__pluginfile__'] = $this->file = $file;
        $this->basePath = plugin_dir_path($this->file);
        $this->baseUrl = plugin_dir_url($this->file);
    }

    /**
     * Set the default application level namespaces to resolve
     * the controllers, policies and various hook handlers.
     *
     * @return null
     */
    protected function setAppLevelNamespace()
    {
        $composer = $this->getComposer();

        $psr4 = array_flip($composer['autoload']['psr-4']);

        $this->policyNamespace = $psr4['app/'] . 'Http\Policies';

        $this->handlerNamespace = $psr4['app/'] . 'Hooks\Handlers';
        
        $this->controllerNamespace = $psr4['app/'] . 'Http\Controllers';

        $this['__namespace__'] = $composer['extra']['wpfluent']['namespace']['current'];
    }

    /**
     * Get the composer data as an array
     * 
     * @param  string $section Specific key
     * 
     * @return array partial or full composer data array
     */
    public function getComposer($section = null)
    {
        if (is_null(static::$composer)) {
            static::$composer = json_decode(
                file_get_contents($this->basePath . 'composer.json'), true
            );
        }

        return $section ? static::$composer[$section] : static::$composer;
    }

    /**
     * Bootstrap the application.
     * 
     * @return null
     */
    protected function bootstrapApplication()
    {
        $this->bindAppInstance();
        $this->bindPathsAndUrls();
        $this->loadConfigIfExists();
        $this->registerTextdomain();
        $this->bindCoreComponents();
        $this->requireCommonFiles($this);
        $this->registerAsyncActions();
        $this->addRestApiInitAction($this);
    }

    /**
     * Bind application instance in the container.
     * 
     * @return null
     */
    protected function bindAppInstance()
    {
        App::setInstance($this);
        $this->instance('app', $this);
        $this->instance(__CLASS__, $this);
    }

    /**
     * Bind the paths and urls
     * 
     * @return null
     */
    protected function bindPathsAndUrls()
    {
        $this->bindUrls();
        $this->basePaths();
    }

    /**
     * Bind urls
     * 
     * @return null
     */
    protected function bindUrls()
    {
        $this['url.assets'] = $this->baseUrl . 'assets/';
    }

    /**
     * Bind paths
     * 
     * @return null
     */
    protected function basePaths()
    {
        $this['path'] = $this->basePath;
        $this['path.app'] = $this->basePath . 'app/';
        $this['path.hooks'] = $this['path.app'] . 'Hooks/';
        $this['path.http'] = $this['path.app'] . 'Http/';
        $this['path.controllers'] = $this['path.http'] . 'Controllers/';
        $this['path.config'] = $this->basePath . 'config/';
        $this['path.assets'] = $this->basePath . 'assets/';
        $this['path.resources'] = $this->basePath . 'resources/';
        $this['path.views'] = $this['path.app'] . 'Views/';
    }

    /**
     * Load application's config and set
     * the data in the Config instance.
     * 
     * @return null
     */
    protected function loadConfigIfExists()
    {
        $data = [];

        if (is_dir($this['path.config'])) {
            foreach (glob($this['path.config'] . '*.php') as $file) {
                $data[basename($file, '.php')] = require($file);
            }
        }

        $this->instance('config', new Config($data));
    }

    /**
     * Register plugin's text domain
     * 
     * @return null
     */
    protected function registerTextdomain()
    {
        $this->addAction('init', function() {
            load_plugin_textdomain(
                $this->config->get('app.text_domain'), false, $this->textDomainPath()
            );
        });
    }

    /**
     * Resolve the text domain path.
     * 
     * @return null
     */
    protected function textDomainPath()
    {
        return basename($this['path']) . $this->config->get('app.domain_path');
    }

    /**
     * Bind the components of the framework into the container so
     * they'll be available throughout the application life cycle.
     * 
     * @return null
     */
    protected function bindCoreComponents()
    {
        (new ComponentBinder($this))->bindComponents();
    }

    /**
     * Load (include) the files where hooks are registered.
     * 
     * @param self $app
     * 
     * @return null
     */
    protected function requireCommonFiles($app)
    {
        require_once $this->basePath . 'app/Hooks/actions.php';
        require_once $this->basePath . 'app/Hooks/filters.php';

        if (file_exists($includes = $this->basePath . 'app/Hooks/includes.php')) {
            require_once $includes;
        }
    }

    /**
     * Register the rest api init actions and routes
     * 
     * @param self $app
     */
    protected function addRestApiInitAction($app)
    {
        $this->addAction('rest_api_init', function($wpRestServer) use ($app) {
            try {
                $this->registerRestRoutes($app->router);
            } catch (InvalidArgumentException $e) {
                return $app->response->json([
                    'message' => $e->getMessage()
                ], $e->getCode() ?: 500);
            }
        });
    }

    /**
     * Register rest routes.
     * 
     * @param \WPPayForm\Framework\Http\Router $router
     * 
     * @return null
     */
    protected function registerRestRoutes($router)
    {
        $router->registerRoutes(
            $this->requireRouteFile($router)
        );
    }

    /**
     * Load (include) routes
     * 
     * @param \WPPayForm\Framework\Http\Router $router
     * @return null
     */
    protected function requireRouteFile($router)
    {
        require_once $this['path.http'] . 'Routes/routes.php';
    }
}
