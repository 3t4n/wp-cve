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

namespace FPFramework\Base\Conditions\Conditions;

defined('ABSPATH') or die;

use FPFramework\Base\Conditions\Condition;

class NewReturningVisitor extends Condition
{
	public function pass()
	{
		// Get visitor instance
		$visitor = new \FPFramework\Base\Visitor();

		// Create and update cookies as needed
		$visitor->createOrUpdateCookie();

		// Check if user is new
		$isNew = $visitor->isNew();

		return $this->operator === 'new' ? $isNew : !$isNew;
	}
}