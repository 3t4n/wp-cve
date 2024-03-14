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

namespace FPFramework\Libs;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Cookie
{
    /**
     * The cookie name.
     * 
     * @var  string
     */
	protected $name;
	
	/**
	 * Cookie data.
	 * 
	 * @var  array
	 */
	protected $data;
    
	/**
	 * Constructor
     * 
     * @param   string  $name
	 */
	public function __construct($name = null)
	{
		$this->name = $name;
		
		// Set the data source.
		$this->data = & $_COOKIE;
	}

	/**
	 * Sets a value
	 *
	 * @param   string   $name      Name of the value to set.
	 * @param   mixed    $value     Value to assign to the input.
	 * @param   array    $options   An associative array which may have any of the keys expires, path, domain,
	 *                              secure, httponly and samesite. The values have the same meaning as described
	 *                              for the parameters with the same name. The value of the samesite element
	 *                              should be either Lax or Strict. If any of the allowed options are not given,
	 *                              their default values are the same as the default values of the explicit
	 *                              parameters. If the samesite element is omitted, no SameSite cookie attribute
	 *                              is set.
	 *
	 * @return  void
	 */
	public function set($name, $value, $options = array())
	{
		// BC layer to convert old method parameters.
		if (is_array($options) === false)
		{
			$argList = func_get_args();

			$options = array(
				'expires'  => isset($argList[2]) === true ? $argList[2] : 0,
				'path'     => isset($argList[3]) === true ? $argList[3] : '',
				'domain'   => isset($argList[4]) === true ? $argList[4] : '',
				'secure'   => isset($argList[5]) === true ? $argList[5] : false,
				'httponly' => isset($argList[6]) === true ? $argList[6] : false,
			);
		}

		// Set the cookie
		if (version_compare(PHP_VERSION, '7.3', '>='))
		{
			setcookie($name, $value, $options);
		}
		else
		{
			// Using the setcookie function before php 7.3, make sure we have default values.
			if (array_key_exists('expires', $options) === false)
			{
				$options['expires'] = 0;
			}

			if (array_key_exists('path', $options) === false)
			{
				$options['path'] = '';
			}

			if (array_key_exists('domain', $options) === false)
			{
				$options['domain'] = '';
			}

			if (array_key_exists('secure', $options) === false)
			{
				$options['secure'] = false;
			}

			if (array_key_exists('httponly', $options) === false)
			{
				$options['httponly'] = false;
			}

			setcookie($name, $value, $options['expires'], $options['path'], $options['domain'], $options['secure'], $options['httponly']);
		}

		$this->data[$name] = $value;
    }
    
    /**
     * Returns cookie's value if found
     * 
	 * @param   string  $name
	 * @param   string  $default
	 * 
     * @return  mixed
     */
    public function get($name = null, $default = null)
    {
		$name = $name ? $name : ($this->name ? $this->name : $default);

        if (isset($this->data[$name]))
        {
            return $this->data[$name];
        }
        
        return null;
    }

    /**
     * Checks whether the cookie exists
     * 
	 * @param   string  $name
	 * 
     * @return  boolean
     */
    public function exist($name = null)
    {
		$name = !$name ? $this->name : $name;

        if (!$this->get($name))
        {
            return false;
        }
        
        return true;
    }
    
    /**
     * Deletes cookie if found
     * 
     * @return  mixed
     */
    public function delete()
    {
        if (!$this->exist())
        {
            return false;
        }
        
        unset($this->data[$this->name]);
    }
}