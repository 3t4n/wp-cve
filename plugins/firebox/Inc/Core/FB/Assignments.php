<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\FB;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class Assignments
{
    /**
	 *  Box Class Instance
	 *
	 *  @var  Box
	 */
    private $box_instance;
    
    /**
	 *  Box Object
	 *
	 *  @var  Object
	 */
    private $box;
    
    /**
	 *  Factory
	 *
	 *  @var  object
	 */
	protected $factory;

	/**
	 *  Local assignments list
	 *
	 *  @var  array
	 */
	private $assignments = [
		'cookietype',
        
    ];

	/**
	 * Class Constructor
	 *
     * @param   object  $box_instance  The Box Class Instance
	 * @param   object  $factory       The Framework Class Instance
     * 
     * @return  void
	 */
	public function __construct($box_instance = null, $factory = null)
	{
        $this->box_instance = $box_instance;
        $this->box = $this->box_instance->getBox();

        if (!$factory)
        {
            $factory = new \FPFramework\Base\Factory();
        }
        $this->factory = $factory;
    }

    /**
     *  Pass all checks
     *
     *  @return  boolean  Returns true if all assignments pass
     */
    public function passAll()
    {
        $pass = true;
        
        foreach ($this->assignments as $key => $assignment)
        {
            // Break if not passed
            if (!$pass)
            {
                break;
            }

            $assignmentData = explode('|', $assignment);

            $assignment = $assignmentData[0];

            $method = 'pass' . $assignment;

            // Skip item if there is no assosiated method
            if (!method_exists($this, $method))
            {
                continue;
            }
            
            $assign = 'assign_' . $assignment;
            
            // Skip item if assignment is missing
            if (!$this->box->params->exists($assign) && !$this->box->params->exists($assign . '_param_type'))
            {
                continue;
            }
            
            $pass = $this->$method();
        }
        
        return $pass;
    }
    
    /**
     *  Pass Check for Box Cookie
     *
     *  @return  bool
     */
    private function passCookieType()
    {
        // Skip if assignment is disabled
        if ($this->box->params->get('assign_cookietype') == 'never')
        {
            return true;
        }

        // Skip if box is on Test Mode and a Super User is logged in
        if ($this->box->params->get('testmode') && is_user_logged_in() && current_user_can('manage_options'))
        {
            return true;
        }

        return !$this->box_instance->getCookie()->exist();
    }

    
}