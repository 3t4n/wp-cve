<?php
/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*                                                                   */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

//declare(strict_types=1);

namespace OneTeamSoftware\Proxies;

if (!class_exists(__NAMESPACE__ . '\\LazyClassProxy')):

class LazyClassProxy
{
	protected $instance;
	protected $className;

	public function __construct($className, &$instance = null)
	{
		$this->className = $className;
		$this->instance = &$instance;
	}

	public function &getInstance()
	{
		if (!is_object($this->instance)) {
			$this->instance = $this->createInstance();
		}

		return $this->instance;
	}

	public function __call($methodName, $arguments)
	{
		$value = null;
		$instance = &$this->getInstance();
		if (is_object($instance) && method_exists($instance, $methodName)) {
			$value = call_user_func_array(array($instance, $methodName), $arguments);
		}

		return $value;
	}

	public function __get($name)
	{
		$value = null;
		$instance = &$this->getInstance();
		if (is_object($instance)) {
			$value = $instance->$name;
		}

		return $value;
	}

	public function __set($name, $value)
	{
		$instance = &$this->getInstance();
		if (is_object($instance)) {
			$instance->$name = $value;
		}
	}

	public function __isset($name)
	{
		$instance = &$this->getInstance();
		return is_object($instance) && isset($instance->$name);
	}

	public function __unset($name)
	{
		$instance = &$this->getInstance();
		if (is_object($instance) && isset($instance->$name)) {
			unset($instance->$name);
		}
	}

	protected function &createInstance()
	{
		$instance = null;
		if (class_exists($this->className)) {
			$className = $this->className;
			$instance = new $className();
		}

		return $instance;
	}
}

endif;