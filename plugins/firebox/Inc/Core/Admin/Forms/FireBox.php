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

namespace FireBox\Core\Admin\Forms;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FireBox\Core\Helpers\BoxHelper;

class FireBox
{
	/**
	 * All Settings for the FireBox Metabox
	 * 
	 * @return  array
	 */
	public static function getSettings()
	{
		$settings = [
			'id' => 'FireBoxMetaTabs',
			'vertical' => true,
			'tabs_menu_sticky' => true,
			'mobile_menu' => true,
			'show_tab_title' => true,
			'data' => self::getForms()
		];

		return apply_filters('firebox/box/settings/edit', $settings);
	}

	private static function getForms()
	{
		$forms = [
			'design' => (new FireBox\Design())->getSettings(),
			'behavior' => (new FireBox\Behavior())->getSettings(),
			'display_conditions' => (new FireBox\DisplayConditions())->getSettings(),
			'actions' => (new FireBox\Actions())->getSettings(),
			'advanced' => (new FireBox\Advanced())->getSettings()
		];

		

		return $forms;
	}

	/**
	 * Gets default data to pass to form
	 * 
	 * @return  array
	 */
	public static function getBindData()
	{
		// get box meta from a template
		$box = BoxHelper::getBoxFromTemplateURL();

		if (isset($box['template']))
		{
			return $box['template']['params'];
		}

		// get box meta from existing item
		$meta = \FireBox\Core\Helpers\BoxHelper::getMeta(get_the_ID());

		if (empty($meta))
		{
			return [];
		}

		return $meta;
	}
}