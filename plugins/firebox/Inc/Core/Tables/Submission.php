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

class Submission extends DB
{
	public function __construct()
	{
		$this->table_name  = 'firebox_submissions';
	}
	
	/**
	 * Get columns and formats 
	 *
	 * @return  array
	*/
	public function get_columns()
	{
		return [
			'id'		  => '%d',
			'form_id'	  => '%s',
			'visitor_id'  => '%s',
			'user_id'	  => '%d',
			'state'		  => '%d',
			'created_at'  => '%s',
			'modified_at' => '%s'
		];
	}
}