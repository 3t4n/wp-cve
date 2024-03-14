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

class UserGroup extends Condition
{
    /**
     *  Returns the assignment's value
     * 
     *  @return array User groups
     */
	public function value()
	{
		return !empty($this->user->roles) ? array_values($this->user->roles) : $this->getAuthorisedGroups();
    }
    
    private function getAuthorisedGroups()
    {
        return [
            'guest' => fpframework()->_('FPF_GUEST')
        ];
    }

	/**
     *  Returns User Groups map (ID => Name)
     *
     *  @return array
     */
    protected function getGroups()
    {
        $_groupsHash = md5('FPFramework\Assignments\User_groupsHash');
        
		// check cache
		if ($groups = wp_cache_get($_groupsHash))
		{
			return $groups;
        }

        $groups = \FPFramework\Helpers\UserHelper::getUserRoles();
        $groups = \FPFramework\Helpers\UserHelper::parseData($groups);
        
		// set cache
		wp_cache_set($_groupsHash, $groups);

        return $groups;
    }
}
