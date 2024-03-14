<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\API;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class API
{
	public function __construct()
	{
        add_action( 'rest_api_init', [$this, 'register_routes'] );
	}

	/**
	 * Register all found routes
	 * 
	 * @return  void
	 */
	public function register_routes()
	{
		// find all routes
		$routes = array_diff( scandir( __DIR__ . '/Routes' ), [ '.', '..', 'index.php' ] );
		
		foreach ($routes as $route)
		{
			$route = str_replace('.php', '', $route);
			
			$class = '\FPFramework\API\Routes\\' . $route;
			$api = new $class($this);
			$api->register();
		}
	}
}