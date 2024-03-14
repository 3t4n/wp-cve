<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\WordPress\Routing;

class Router
{
	private $arrRoutesParams  = array();
	private $parameterPattern = '/{([\w\d]+)}/';
	private $valuePattern     = '(?P<$1>[^\/]+)';
	private $valuePatternReplace = '([^\/]+)';

	private $httpRequestMethod = null;

	private $routes = array();
	private $namespace = null;

	private function __construct()
	{

		$this->httpRequestMethod = \strtoupper($_SERVER['REQUEST_METHOD']);

		$this->routes[$this->httpRequestMethod] = array();

		add_action('init', array($this, 'startListening'), PHP_INT_MAX );

		add_action('parse_request', array($this, 'parseRequest'));

		add_action('wp_loaded', function(){


			//flush_rewrite_rules(true);


		}, PHP_INT_MAX);


	}


	public function startListening()
	{
		if(!isset($this->arrRoutesParams[$this->httpRequestMethod]))
			return;

		add_rewrite_tag('%mch_rewrite_route%', '(.+)');

		foreach($this->arrRoutesParams[$this->httpRequestMethod] as $id => $routesParams){

			$params = array(
				'id' => $id,
				'parameters' => array()
			);

			$uri = '^' . preg_replace(
					$this->parameterPattern,
					$this->valuePatternReplace,
					str_replace('/', '\\/', $routesParams['uri'])
				);

			$url = 'index.php?';
			$matches = array();

			if (preg_match_all($this->parameterPattern, $routesParams['uri'], $matches))
			{
				foreach ($matches[1] as $key => $param)
				{
					add_rewrite_tag('%mch_rewrite_param_' . $param . '%', '(.+)');
					$url .= 'mch_rewrite_param_' . $param . '=$matches[' . ($key + 1) . ']&';
					$params['parameters'][$param] = null;
				}
			}

			add_rewrite_rule($uri . '$', $url . 'mch_rewrite_route=' . urlencode(json_encode($params)), 'top');

		}

	}


	public function parseRequest($wp)
	{
//		print_r($wp);exit;
//		print_r($wp->query_vars);exit;
		if(empty($wp->query_vars['mch_rewrite_route'])) {

			return;
		}

		$data = json_decode(\stripcslashes(urldecode($wp->query_vars['mch_rewrite_route'])), true);

		$route = null;

		if (isset($data['id']) && isset($this->routes[$this->httpRequestMethod][$data['id']]))
		{
			$route = $this->routes[$this->httpRequestMethod][$data['id']];
		}
		elseif (isset($data['name']) && isset($this->routes['named'][$data['name']]))
		{
			$route = $this->routes['named'][$data['name']];
		}

		if ( ! isset($route))
		{
			return;
		}

		if ( ! isset($data['parameters']))
		{
			$data['parameters'] = array();
		}

		foreach ($data['parameters'] as $key => $val)
		{
			if ( ! isset($wp->query_vars['mch_rewrite_param_' . $key]))
			{
				return;
			}

			$data['parameters'][$key] = $wp->query_vars['mch_rewrite_param_' . $key];
		}

		$this->call($route['uses'], $data['parameters']);

	}



	public function registerRoute($httpMethod, $arrRouteParameters)
	{
		if($arrRouteParameters instanceof \Closure) {
			$arrRouteParameters = array('uses' => $arrRouteParameters);
		}

		if(!isset($arrRouteParameters['uri']) || !isset($arrRouteParameters['uses']))
			throw new \InvalidArgumentException('Missing definition for route');

		$httpMethod = strtoupper($httpMethod);

		$arrRouteParameters['uri'] = ltrim($arrRouteParameters['uri'], '/');

		isset($this->arrRoutesParams[$httpMethod]) ?: $this->arrRoutesParams[$httpMethod] = array();
		$this->arrRoutesParams[$httpMethod][] = $arrRouteParameters;

		isset($this->routes[$httpMethod]) ?: $this->routes[$httpMethod] = array();

		$this->routes[$httpMethod][] = $arrRouteParameters;

		if(isset($route['as']))
		{
			isset($this->routes['named']) ?: $this->routes['named'] = array();
			$this->routes['named'][$httpMethod . '::' . $this->namespaceAs($route['as'])] = $route;
		}
	}

	protected function namespaceAs($as)
	{
		if ($this->namespace === null)
		{
			return $as;
		}
		return $this->namespace . '::' . $as;
	}

	public static function getInstance()
	{
		static $routerInstance = null;
		return null !== $routerInstance ? $routerInstance :  $routerInstance = new self();
	}


	private function call($callback, array $parameters = array())
	{
		$dependencies = array();
		$closureReflector =  new \ReflectionFunction($callback);

//		print_r($parameters);
//		print_r($closureReflector->getParameters());exit;

		foreach ($closureReflector->getParameters() as $parameter)
		{
			if(\array_key_exists($parameter->name, $parameters)){
				$dependencies[] = $parameters[$parameter->name];
				unset($parameters[$parameter->name]);
			}
		}

		$dependencies = \array_merge($dependencies, $parameters);

		\call_user_func_array($callback, $dependencies);
	}


}