<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   604-1097 View St                                        */
/* OF         Victoria, BC, V8V 0G9                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\AutoLoader;

if (!class_exists(__NAMESPACE__ . '\\AutoLoader')):

class AutoLoader
{
	protected $namespace;
	protected $includePath;

	public function __construct($includePath, $namespace)
	{
		$this->namespace = trim($namespace, '\\') . '\\';
		$this->includePath = $includePath;
	}

	public function autoload($class)
	{
		if (strpos($class, $this->namespace) === 0) {
			$filePath = $this->includePath . '/' . str_replace('\\', '/', substr($class, strlen($this->namespace))) . '.php';
			if (file_exists($filePath)) {
				include_once($filePath);
			}
		}
	}

	public function register()
	{
		spl_autoload_register(array($this, 'autoload'));
	}
}

endif;