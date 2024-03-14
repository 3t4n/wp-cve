<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.widget.input');

/**
 * Base class to implement a search bar.
 *
 * @since 	1.6
 */
class UISearchBar implements UIInput
{
	/**
	 * The layout name.
	 *
	 * @var string
	 */
	private $tpl;

	/**
	 * Class constructor.
	 *
	 * @param 	string 	$tpl 	The layout name.
	 */
	public function __construct($tpl = null)
	{
		$this->tpl = is_null($tpl) ? 'default' : $tpl;
	}

	/**
	 * @override
	 * Call this method to build and return the HTML of the input.
	 *
	 * @return 	string 	The input HTML.
	 */
	public function display()
	{
		ob_start();
		$included 	= VAPLoader::import('libraries.widget.searchbar.layouts.' . $this->tpl);
		$html 		= ob_get_contents();
		ob_end_clean();
		
		if (!$included)
		{
			throw new Exception('Search bar [' . $this->tpl . '] layout not found!');
		}

		return $html;
	}
}
