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

namespace FireBox\Core\Tables;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Includes\DB;

class Box extends DB
{
	public function __construct()
	{
		$this->table_name  = 'posts';
		$this->primary_key = 'ID';
	}

	/**
	 * Get columns and formats 
	 *
	 * @return  array
	*/
	public function get_columns()
	{
		return array(
			'ID'		  => '%d',
			'post_author' => '%d',
			'post_status' => '%s',
			'post_type'	  => '%s'
		);
	}
}