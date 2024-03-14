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

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class UserHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'User';

		parent::__construct($provider);
	}

	/**
	 * Returns all user roles
	 * 
	 * @return  array
	 */
	public static function getUserRoles()
	{
		$roles['guest'] = [
			'name' => fpframework()->_('FPF_GUEST')
		];

		$userRoles = wp_roles();
		$userRoles = (array) $userRoles->roles;

		$roles = array_merge($roles, $userRoles);

		return $roles;
	}

	/**
	 * Parses given data to a key,value array
	 * 
	 * @param   array  $groups
	 * 
	 * @return  array
	 */
	public static function parseData($groups)
	{
		$groups = (array) $groups;

		if (empty($groups))
		{
			return [];
		}
		
		$data = [];

		foreach ($groups as $name => $group)
		{
			$group = (array) $group;

			if (!isset($group['name']))
			{
				continue;
			}
			
			$data[] = [
				'id' => $name,
				'title' => $group['name']
			];
		}
		
		return $data;
	}
	
	/**
	 * Returns the current user
	 * 
	 * @return  object
	 */
	public static function getUser()
	{
		$factory = new \FPFramework\Base\Factory();
		return $factory->getUser();
	}
}