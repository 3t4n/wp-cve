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

use WP_REST_Server;

abstract class EndpointController extends Endpoint
{
	/**
	 * Get API namespace
	 * 
	 * @return  string
	 */
	public function get_namespace()
	{
		return Manager::ROOT_NAMESPACE . '/v' . Manager::VERSION;
	}

	/**
	 * Get API REST base
	 * 
	 * @return  string
	 */
	public function get_rest_base()
	{
		return Manager::REST_BASE;
	}
}