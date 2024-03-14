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
 * Factory class to fetch Registry objects
 */
class Factory
{
	/**
	 * Format instances container - for backward compatibility with AbstractRegistryFormat::getInstance().
	 *
	 * @var    FormatInterface[]
	 */
	protected static $formatInstances = array();

	/**
	 * Returns an AbstractRegistryFormat object, only creating it if it doesn't already exist.
	 *
	 * @param   string  $type     The format to load
	 * @param   array   $options  Additional options to configure the object
	 *
	 * @return  FormatInterface  Registry format handler
	 *
	 * @throws  \InvalidArgumentException
	 */
	public static function getFormat($type, array $options = array())
	{
		// Sanitize format type.
		$type = strtolower(preg_replace('/[^A-Z0-9_]/i', '', $type));

		/*
		 * Only instantiate the object if it doesn't already exist.
		 * @deprecated 2.0 Object caching will no longer be supported, a new instance will be returned every time
		 */
		if (!isset(self::$formatInstances[$type]))
		{
			$localNamespace = __NAMESPACE__ . '\\Format';
			$namespace      = isset($options['format_namespace']) ? $options['format_namespace'] : $localNamespace;
			$class          = $namespace . '\\' . ucfirst($type);

			if (!class_exists($class))
			{
				// Were we given a custom namespace?  If not, there's nothing else we can do
				if ($namespace === $localNamespace)
				{
					throw new \InvalidArgumentException(sprintf('Unable to load format class for type "%s".', $type), 500);
				}

				$class = $localNamespace . '\\' . ucfirst($type);

				if (!class_exists($class))
				{
					throw new \InvalidArgumentException(sprintf('Unable to load format class for type "%s".', $type), 500);
				}
			}

			self::$formatInstances[$type] = new $class;
		}

		return self::$formatInstances[$type];
	}
}
