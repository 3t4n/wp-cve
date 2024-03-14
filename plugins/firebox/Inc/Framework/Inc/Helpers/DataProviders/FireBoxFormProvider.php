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

namespace FPFramework\Helpers\DataProviders;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Interfaces\GetSelectedItems;
use FPFramework\Base\Interfaces\GetSearchItems;
use FPFramework\Base\Interfaces\GetItems;
use FPFramework\Helpers\SearchDropdownHelper;
use FPFramework\Helpers\FireBoxFormHelper;
use FPFramework\Helpers\Plugins\FireBox\Form;

class FireBoxFormProvider implements GetSelectedItems, GetSearchItems, GetItems
{
	/**
	 * Returns items based on offset and limit
	 * 
	 * @param   integer  $offset
	 * @param   integer  $limit
	 * 
	 * @return  array
	 */
	public function getItems($offset = 0, $limit = SearchDropdownHelper::SELECTION_ITEMS)
	{
		$forms = Form::getForms($offset, $limit);
		return FireBoxFormHelper::_parseData($forms);
	}

	/**
	 * Gets boxes from the Selected Items
	 * 
	 * @param   array   $items
	 * 
	 * @return  array
	 */
    public function getSelectedItems($items = [])
    {
		$forms = Form::getFormsByIDs($items);
		return FireBoxFormHelper::_parseData($forms);
    }

	/**
	 * Searches and returns an array of items via the name
	 * 
	 * @param   string  $name
	 * @param   array  	$no_ids  List of already added items
	 * 
	 * @return  array
	 */
    public function getSearchItems($name, $no_ids = [])
    {
		$forms = Form::getForms(0, 999999, $no_ids, $name);
		return FireBoxFormHelper::_parseData($forms);
	}
}