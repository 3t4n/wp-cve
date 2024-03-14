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

class Referrer extends URLBase
{
   	/**
   	 *  Pass Referrer URL. 
   	 *
   	 *  @return  bool   Returns true if the Referrer URL contains any of the selection URLs 
   	 */
   	public function pass()
   	{
		// Make sure the referer server variable is available
		if (!isset($_SERVER['HTTP_REFERER']))
		{
			return;
		}
		
		// Deprecated - Start
		// TODO: To be removed in the future
		// If we have selected referrers from the pre-defined list then merge them with the ones the user has entered
		if ($referrers_from_predefined_list = $this->params->get('predefined_list', []))
		{
			$selection = array_merge($this->value(), $referrers_from_predefined_list);
			$selection = array_filter(array_unique($selection));
			$this->setSelection($selection);
		}
		// Deprecated - End

		return $this->passURL($this->value());
    }

    /**
     *  Returns the condition's value
     * 
     *  @return string Referrer URL
     */
	public function value()
	{
		return $_SERVER['HTTP_REFERER'];
	}
}