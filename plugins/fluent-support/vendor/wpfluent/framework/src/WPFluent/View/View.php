<?php

namespace FluentSupport\Framework\View;

use Exception;

class View
{
	/**
     * Application Instance
     * @var FluentSupport\Framework\Foundation\Application
     */
	protected $app;

	/**
	 * View file path
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * View data
	 * @var array
	 */
	protected $data = [];
	
	/**
	 * Shared data inall the views
	 * @var array
	 */
	protected static $sharedData = [];

	/**
	 * Construct the view instamce
	 * @param [type] $app [description]
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Generate and echo/print a view file
	 * @param  string $path
	 * @param  array  $data
	 * @return void
	 */
	public function render($path, $data = [])
	{
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->make($path, $data);
	}

	/**
	 * Generate a view file
	 * @param  string $path
	 * @param  array  $data
	 * @return string [generated html]
	 * @throws Exception
	 */
	public function make($path, $data = [])
	{
		if (file_exists($this->path = $this->resolveFilePath($path))) {
			$this->data = $data;
			return $this;
		}

		throw new Exception("The view file [{$this->path}] doesn't exists!");
	}

	/**
	 * Resolve the view file path
	 * @param  string $path
	 * @return string
	 */
	protected function resolveFilePath($path)
	{
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);

        return $this->app['path.views'] . $path .'.php';
	}

	/**
	 * Evaluate the view file
	 * @param  string $path
	 * @param  string $data
	 * @return $this
	 */
	protected function renderContent()
	{
		$renderOutput = function($app) {
			ob_start() && extract(
				$this->gatherData(), EXTR_SKIP
			);

			include $this->path;

			return ltrim(ob_get_clean());
		};

		return $renderOutput($this->app);
	}

	/**
	 * Gether shared & view data
	 * @return array
	 */
	protected function gatherData()
	{
		return array_merge(static::$sharedData, $this->data);
	}

	/**
	 * Share global data for any view
	 * @param  string $key
	 * @param  mixed $value
	 * @return void
	 */
	public function share($key, $value)
	{
		static::$sharedData[$key] = $value;
	}

	/**
	 * Provides a fluent interface to set data
	 * @param  mixed $key
	 * @param  mixed $data
	 * @return $this
	 */
	public function with($name, $data = [])
	{
		if (is_array($name)) {
			foreach ($name as $key => $value) {
				$this->__set($key, $value);
			}
		} else {
			$this->__set($name, $data);
		}
		
		return $this;
	}

	/**
	 * Set view path (used for micro)
	 * @param [type] $path [description]
	 */
	public function setViewPath($path)
	{
		$this->app['path.views'] = $path;
	}

	/**
	 * Setter for the view
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * Dump the view result
	 * @return string
	 */
	public function __toString()
	{
		return $this->renderContent();
	}
}
