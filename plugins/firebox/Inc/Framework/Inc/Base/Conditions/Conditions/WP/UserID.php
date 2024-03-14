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

class UserID extends Condition
{
	/**
     *  Returns the condition's value
     * 
     *  @return int User ID
     */
	public function value()
	{
		return $this->user->ID;
	}

	/**
	 * A one-line text that describes the current value detected by the rule. Eg: The current time is %s.
	 *
	 * @return string
	 */
	public function getValueHint()
	{
		return sprintf(fpframework()->_('FPF_DISPLAY_CONDITIONS_HINT_' . strtoupper($this->getName())), $this->user->display_name . '(' . $this->user->user_email . ')');
	}
}