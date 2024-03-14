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

namespace FireBox\Core\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Helpers\SearchDropdownHelper;

class Form extends \FPFramework\Helpers\SearchDropdownProviderHelper
{
	/**
	 * Class Name.
	 * 
	 * @var  string
	 */
	protected $class_name = 'Form';

	/**
	 * Provider prefix.
	 * 
	 * @var  string
	 */
	protected $provider_prefix = '\FireBox\Core\Helpers\DataProviders\\';

	/**
	 * Parses given data to a key, value, lang array
	 * 
	 * @param   array  $items
	 * 
	 * @return  array
	 */
	public static function parseData($items)
	{
		return $items;
	}

	/**
	 * Returns all forms.
	 * 
	 * @return  array
	 */
	public static function getForms()
	{
		return \FireBox\Core\Helpers\Form\Form::getParsedForms();
	}
}