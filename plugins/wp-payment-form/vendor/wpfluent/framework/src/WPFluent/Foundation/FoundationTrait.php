<?php

namespace WPPayForm\Framework\Foundation;

trait FoundationTrait
{
    use HooksRemovalTrait;
    
    /**
     * Determine the environment
     * @return string
     */
    public function env()
    {
        if (defined(WP_DEBUG) && WP_DEBUG) {
            return 'dev';
        }
        
        return $this->config->get('app.env');
    }

    /**
     * Make the custom hook name for the plugin.
     * @param  string $prefix
     * @param  string $hook
     * @return string
     */
    public function hook($prefix, $hook)
    {
        return $prefix . $hook;
    }

    /**
     * Parse the handler for the rest request
     * @param  string|Closure $handler
     * @param  string $ns
     * @return mixed
     */
    public function parseRestHandler($handler, $ns = '')
    {
        if ($handler instanceof \Closure) {
            return $handler;
        }

        if (is_array($handler)) {

            if (is_object($handler[0])) {
                return $handler;
            }

            if (is_string($handler[0])) {
                $handler = $handler[0] . '@' . $handler[1];
            }
        }

        $handler = $ns ? $ns . '\\' . $handler : $handler;

        return $this->getControllerNamespace($handler) . '\\' . $handler;
    }

    /**
     * Parse the policy handler
     * @param  string|array $handler
     * @return mixed
     */
    public function parsePolicyHandler($handler)
    {
        if (is_string($handler)) {

            if (function_exists($handler)) return $handler;

            $handler = $this->getPolicyNamespace($handler) . '\\' . $handler;

            if (is_string($handler) && strpos($handler, '@') !== false) {

                list($class, $method) = explode('@', $handler);

                if (!method_exists($class, $method)) {
                    $method = 'verifyRequest';
                }

                $instance = $this->make($class);

                $handler = [$instance, $method];
            }

        } else if (is_array($handler)) {
            list($class, $method) = $handler;

            if (is_string($class)) {
                $handler = $this->getPolicyNamespace($handler) . '\\' . $class . '::' . $method;
            }
        }

        return $handler;
    }

    /**
     * Register an action hook
     * @param string $action
     * @param mixed $handler
     * @param integer $priority
     * @param integer $numOfArgs
     */
    public function addAction($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        return add_action(
            $action,
            $this->parseHookHandler($handler),
            $priority,
            $numOfArgs
        );
    }

    /**
     * Register a custom action hook
     * @param string $action
     * @param mixed $handler
     * @param integer $priority
     * @param integer $numOfArgs
     */
    public function addCustomAction($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        $prefix = $this->config->get('app.hook_prefix');

        return $this->addAction(
            $this->hook($prefix, $action), $handler, $priority, $numOfArgs
        );
    }

    /**
     * Dispatch an action
     * @return null
     */
    public function doAction()
    {
        return call_user_func_array('do_action', func_get_args());
    }

    /**
     * Dispatch a custom action
     * @return null
     */
    public function doCustomAction()
    {
        $args = func_get_args();

        $prefix = $this->config->get('app.hook_prefix');

        $args[0] = $this->hook($prefix, $args[0]);

        return call_user_func_array('do_action', $args);
    }

    /**
     * Register a filter hook
     * @param string $action
     * @param mixed $handler
     * @param integer $priority
     * @param integer $numOfArgs
     */
    public function addFilter($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        return add_filter(
            $action,
            $this->parseHookHandler($handler),
            $priority,
            $numOfArgs
        );
    }

    /**
     * Register a custom filter hook
     * @param string $action
     * @param mixed $handler
     * @param integer $priority
     * @param integer $numOfArgs
     */
    public function addCustomFilter($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        $prefix = $this->config->get('app.hook_prefix');

        return $this->addFilter(
            $this->hook($prefix, $action), $handler, $priority, $numOfArgs
        );
    }

    /**
     * Dispatc a filter
     * @return mixed
     */
    public function applyFilters()
    {
        return call_user_func_array('apply_filters', func_get_args());
    }

    /**
     * Dispatch a custom filter
     * @return mixed
     */
    public function applyCustomFilters()
    {
        $args = func_get_args();
        $prefix = $this->config->get('app.hook_prefix');
        $args[0] = $this->hook($prefix, $args[0]);

        return call_user_func_array('apply_filters', $args);
    }

    /**
     * Register a short code
     * @param string $action
     * @param null
     */
    public function addShortcode($action, $handler)
    {
        return add_shortcode(
            $action,
            $this->parseHookHandler($handler)
        );
    }

    /**
     * Execute a shortcode
     * @param  mixed $content
     * @param  boolean $ignore_html
     * @return mixed
     */
    public function doShortcode($content, $ignore_html = false)
    {
        return do_shortcode($content, $ignore_html);
    }

    /**
     * Parse a hookm handler
     * @param  mixed $handler
     * @return mixed
     */
    public function parseHookHandler($handler)
    {
        if (is_string($handler)) {

            if (function_exists($handler)) return $handler;
            
            if (count($array = preg_split('/::|@/', $handler)) < 2) {
                $array[] = 'handle';
            }

            list($class, $method) = $array;

            $class = $this->makeInstance($class);

            return is_callable($class) ? $class : [$class, $method];

        } else if (is_array($handler)) {
            list($class, $method) = $handler;
            if (is_string($class)) {
                $class = $this->makeInstance($class);
            }
            return [$class, $method];
        }

        return $handler;
    }

    /**
     * Chdeck if handler has fqn
     * @param  string|Closure $handler
     * @return boolean
     */
    public function hasNamespace($handler)
    {
        if ($handler instanceof \Closure) {
            return false;
        };
        
        $parts = array_filter(explode('\\', $handler));
        
        return count($parts) > 1;
    }

    /**
     * Resolve the namespace for a controller
     * @param  string $handler
     * @return mixed
     */
    public function getControllerNamespace($handler)
    {
        if ($this->hasNamespace($handler)) {
            return '';
        }

        return $this->controllerNamespace;
    }

    /**
     * Resolve the namespace for a policy
     * @param  string $handler
     * @return mixed
     */
    public function getPolicyNamespace($handler)
    {
        if ($this->hasNamespace($handler)) {
            return '';
        }

        return $this->policyNamespace;
    }

    /**
     * Make an instance by the container
     * @param  string $class
     * @return mixed
     */
    public function makeInstance($class)
    {
        if ($this->hasNamespace($class)) {
            $instance = $this->make($class);
        } else {
            $instance = $this->make($this->handlerNamespace . '\\' . $class);
        }

        return $instance;
    }

    /**
     * Retrieve the base url
     * @param  string $url
     * @return string
     */
    public function url($url = '')
    {
        return $this->baseUrl.ltrim($url, '/');
    }

    /**
     * Add ajax action
     * @param string $action
     * @param string|Clousure $handler
     * @param int $priority
     * @param string $scope
     */
    private function addAjaxAction($action, $handler, $priority, $scope)
    {
        if ($scope == 'admin') {
            return add_action(
                'wp_ajax_'.$action,
                $this->parseHookHandler($handler),
                $priority
            );
        }

        if ($scope == 'public') {
            return add_action(
                'wp_ajax_nopriv_'.$action,
                $this->parseHookHandler($handler),
                $priority
            );
        }
    }

    /**
     * Add ajax actions including non_prive
     * @param string $action
     * @param string|Clousure $handler
     * @param int $priority
     */
    public function addAjaxActions($action, $handler, $priority = 10)
    {
        $this->addAjaxAction($action, $handler, $priority, 'admin');
        $this->addAjaxAction($action, $handler, $priority, 'public');
    }

    /**
     * Add ajax action for privilaged user
     * @param string $action
     * @param string|Clousure $handler
     * @param int $priority
     */
    public function addAdminAjaxAction($action, $handler, $priority = 10)
    {
        return $this->addAjaxAction($action, $handler, $priority, 'admin');
    }

    /**
     * Add ajax action for non-privilaged user
     * @param string $action
     * @param string|Clousure $handler
     * @param int $priority
     */
    public function addPublicAjaxAction($action, $handler, $priority = 10)
    {
        return $this->addAjaxAction($action, $handler, $priority, 'public');
    }
}
