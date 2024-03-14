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

class Manager
{
	/**
	 * Root Namespace of the API calls
	 * 
	 * @var  string
	 */
	const ROOT_NAMESPACE = 'fpframework';

	/**
	 * REST base name.
	 * 
	 * With empty REST base:
	 * /{ROOT_NAMESPACE}/v{VERSION}/{ENDPOINT}
	 * 
	 * With valid REST base:
	 * /{ROOT_NAMESPACE}/v{VERSION}/{REST_BASE}/{ENDPOINT}
	 * 
	 * @var  string
	 */
	const REST_BASE = '';

	/**
	 * Version of API
	 * 
	 * @var  string
	 */
	const VERSION = '1';
}