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

namespace FPFramework\Base\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

abstract class SmartTag
{
	/**
	 * Factory Class
	 *
	 * @var object
	 */
    protected $factory;

    /**
     * Useful data used by a Smart Tag
     * 
     * @var  array
     */
    protected $data = null;

    /**
     * Smart Tags Configuration Options
     * 
     * @var  array
     */
    protected $options;

    /**
     * Given options
     * 
     * @var  array
     */
    protected $parsedOptions;

    public function __construct($factory = null, $options = null)
    {
        if (!$factory)
        {
            $factory = new \FPFramework\Base\Factory();
        }
        $this->factory = $factory;
        
        $this->parsedOptions = isset($options['options']) ? $options['options'] : new Registry();

        $this->options = $options;
    }

    /**
     * Set the data
     * 
     * @param   array  $data
     * 
     * @return  void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * This method runs before replacements and determines whether the class can be executed and do replacements or not.
     * 
     * THE PROBLEM: 
     * 
     * Let's say we have a bunch of Smart Tags in a namespaced folder and we register them using the register() method. 
     * The Smart Tags include, Foo and Bar. Let's say our replacement subject is: 'lorem {foo.x} ipsum {foo.y} lorem ipsum {bar.x}' 
     * and we'd like to replace {foo.x} and {foo.y} and leave {bar.x} untouched. Right now this is not possible. 
     * All 3 Smart Tags will be replaced in the subject because all classes are already registered.
     * 
     * We need a way to determine during runtime whether a Smart Tag can run or not.
     * 
     * We could write a new method so 3rd party extension can register individual classes conditionally but this would add more work on the extension's side.
     * 
     * @return boolean 
     */
    public function canRun()
    {
        return true;
    }
}