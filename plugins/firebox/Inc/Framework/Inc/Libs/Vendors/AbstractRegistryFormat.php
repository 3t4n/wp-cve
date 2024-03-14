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

namespace FPFramework\Libs\Vendors;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

/**
 * Abstract Format for Registry
 */
abstract class AbstractRegistryFormat implements FormatInterface
{
	/**
	 * @var    AbstractRegistryFormat[]  Format instances container.
	 */
	protected static $instances = array();

	/**
	 * Returns a reference to a Format object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param   string  $type     The format to load
	 * @param   array   $options  Additional options to configure the object
	 *
	 * @return  AbstractRegistryFormat  Registry format handler
	 *
	 * @throws  \InvalidArgumentException
	 */
	public static function getInstance($type, array $options = array())
	{
		return \FPFramework\Libs\Vendors\Factory::getFormat($type, $options);
	}
}
