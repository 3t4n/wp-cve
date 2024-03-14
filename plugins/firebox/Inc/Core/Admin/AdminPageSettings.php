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

namespace FireBox\Core\Admin;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class AdminPageSettings
{
	/**
	 * Register Settings Data
	 * 
	 * @var  array
	 */
	const registerSettingsData = [
		// FireBox Global Settings Page
		[
			'option_group' => 'firebox_settings',
			'option_name' => 'firebox_settings',
			'controller' => '\FireBox\Core\Controllers\BoxSettings',
			'process_data_method' => 'processBoxSettings'
		],
		// FireBox Import Page
		[
			'option_group' => 'firebox_import',
			'option_name' => 'firebox_import',
			'controller' => '\FireBox\Core\Controllers\BoxImport',
			'process_data_method' => 'processBoxesImport'
		],
		// FireBox Submission Edit Page
		[
			'option_group' => 'firebox_submission',
			'option_name' => 'firebox_submission',
			'controller' => '\FireBox\Core\Controllers\Submissions',
			'process_data_method' => 'processSubmissionEdit'
		],
	];

	public function __construct()
	{
		add_action('admin_init', [$this, 'registerSettings']);
	}

	/**
	 * Registers FireBox Settings and FireBox Import Settings sections
	 * in order to be able to submit the forms.
	 * 
	 * @return  void
	 */
	public function registerSettings()
	{
		foreach (self::registerSettingsData as $key => $setting)
		{
			if (!class_exists($setting['controller']))
			{
				continue;
			}
			
			$controller = new $setting['controller']();
			
			register_setting($setting['option_group'], $setting['option_name'], [$controller, $setting['process_data_method']]);
		}
	}

}