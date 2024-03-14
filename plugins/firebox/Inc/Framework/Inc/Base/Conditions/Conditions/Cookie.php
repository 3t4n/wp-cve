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

class Cookie extends Condition
{
    /**
     * When we need to compare the user's value with the cookie value, we change the $selection to the value entered by the user.
     *
     * @return string
     */
    public function prepareSelection()
    {
        if (in_array($this->operator, ['exists', 'empty']))
        {
            return $this->getSelection();
        }

        return $this->params->get('content', '');
    }

    /**
     *  Return the value of the cookie as stored in the user's browser
     * 
     *  @return string The value of the cookie
     */
	public function value()
	{
        /**
         * $this->selection is not used here as prepareSelection() above, called in \FPFramework\Base\Conditions\Condition->setSelection() method changes its value
         * and thus we do not always have the correct Cookie Name to search for.
         * 
         * $this->options->get('selection') will always have the correct cookie name.
         */
		return $this->factory->getCookie($this->options->get('selection'));
	}
}