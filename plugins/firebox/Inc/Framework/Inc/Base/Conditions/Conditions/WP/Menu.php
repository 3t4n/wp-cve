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

namespace FPFramework\Base\Conditions\Conditions\WP;

defined('ABSPATH') or die;

use FPFramework\Base\Conditions\Condition;

class Menu extends Condition 
{
	protected $itemID = null;

	public function __construct($options, $factory)
	{
		parent::__construct($options, $factory);

        $current_object_id = \FPFramework\Helpers\WPHelper::getPageID();
		
		$this->itemID = \FPFramework\Helpers\MenuHelper::getMenuIdByPostId($current_object_id);
	}
	
	/**
	 *  Pass check for menu items
	 *
	 *  @return  bool
	 */
	public function pass()
	{
		$includeChildren = $this->params->get('inc_children', false);
		
    	// Pass if selection is empty or the itemid is missing
    	if (empty($this->getSelection()))
        {
            return false;
        }

		// return true if menu is in selection and we are not including child items only
		if (in_array($this->itemID, $this->getSelection()))
		{
			return ($includeChildren != 2);
		}

		// Let's discover child items. 
		// Obviously if the option is disabled return false.
		if (!$includeChildren)
		{
			return false;
		}

		// Get menu item parents
		$parent_ids = $this->getParentIds($this->itemID);
		$parent_ids = array_diff($parent_ids, ['1']);

		foreach ($parent_ids as $id)
		{
			if (!in_array($id, $this->selection))
			{
				continue;
			}

			return true;
		}

		return false;
	}

	/**
     *  Returns the assignment's value
     * 
     *  @return integer Menu ID
     */
	public function value()
	{
		return $this->itemID;
	}
}