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

class PostsHelper
{
	/**
	 * Posts Base Helper
	 * 
	 * @var  PostsBaseHelper
	 */
	private $helper;

	public function __construct($helper = null)
	{
		if (!$helper)
		{
			$helper = new PostsBaseHelper();
		}
		
		$this->helper = $helper;
	}

	/**
	 * Parses given data to a key,value array
	 * 
	 * @param   array  $items
	 * 
	 * @return  array
	 */
	public static function parseData($items)
	{
		return PostsBaseHelper::parseData($items);
	}

	/**
	 * Returns items based on offset and limit
	 * 
	 * @param   integer  $offset
	 * @param   integer  $limit
	 * @param   String  $post_type
	 * 
	 * @return  array
	 */
	public function getItems($offset = 0, $limit = SearchDropdownHelper::SELECTION_ITEMS, $post_type = 'post')
	{
		return $this->helper->getItems($offset, $limit, $post_type);
	}

	/**
	 * Gets Posts from the Selected Items
	 * 
	 * @param   array  $items
	 * @param   String  $post_type
	 * 
	 * @return  array
	 */
	public function getSelectedItems($items, $post_type = 'post')
	{
		$post_type = !is_string($post_type) ? 'post' : $post_type;

		return $this->helper->getSelectedItems($items, $post_type);
	}

	/**
	 * Searches the posts and returns an array of items
	 * 
	 * @param   String  $name
	 * @param   array  	$no_ids  List of already added items
	 * @param   String  $post_type
	 * 
	 * @return  array
	 */
	public function getSearchItems($name, $no_ids = null, $post_type = 'post')
	{
		return $this->helper->getSearchItems($name, $no_ids, $post_type);
	}
}