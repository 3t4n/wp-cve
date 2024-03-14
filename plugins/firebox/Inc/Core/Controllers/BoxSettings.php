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

namespace FireBox\Core\Controllers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Form;
use FPFramework\Base\FieldsParser;
use FPFramework\Base\Ui\Tabs;

class BoxSettings extends BaseController
{
	/**
	 * The form settings name
	 * 
	 * @var  string
	 */
	const settings_name = 'firebox_settings';
	
	/**
	 * Render the page content
	 * 
	 * @return  void
	 */
	public function render()
	{
		// page content
		add_action('firebox/settings_page', [$this, 'settingsPageContent']);
		
		// render layout
		firebox()->renderer->admin->render('pages/settings');
	}

	/**
	 * Load required media files
	 * 
	 * @return void
	 */
	public function addMedia()
	{
		// load geoip js
		wp_register_script(
			'fpf-geoip',
			FPF_MEDIA_URL . 'admin/js/fpf_geoip.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script('fpf-geoip');
	}

	/**
	 * Callback used to handle the processing of settings.
	 * Useful when using a Repeater field to remove the template from the list of submitted items.
	 * 
	 * @param   array  $input
	 * 
	 * @return  void
	 */
	public function processBoxSettings($input)
	{
		// create a unique db option to use it on all plugins to fetch the geo license key
		$geo_license_key = '';
		if (isset($input['geo_license_key']) && !empty($input['geo_license_key']))
		{
			$geo_license_key = $input['geo_license_key'];
		}
		update_option('fpf_geo_license_key', $geo_license_key);

		
		
		// Filters the fields value
		\FPFramework\Helpers\FormHelper::filterFields($input, \FireBox\Core\Admin\Forms\Settings::getSettings());

		add_settings_error(self::settings_name, 'settings_updated', fpframework()->_('FPF_SETTINGS_SAVED'), 'success');
		
		return $input;
	}

    

	/**
	 * What the settings page will contain
	 * 
	 * @return  void
	 */
	public function settingsPageContent()
	{
		$fieldsParser = new FieldsParser([
			'fields_name_prefix' => 'firebox_settings'
		]);

		$settings = \FireBox\Core\Admin\Forms\Settings::getSettings();
		foreach ($settings['data'] as $key => $value)
		{
			ob_start();
			$fieldsParser->renderContentFields($value);
			$html = ob_get_contents();
			ob_end_clean();

			$settings['data'][$key]['title'] = $value['title'];
			$settings['data'][$key]['content'] = $html;
		}

		// render settings as tabs
		$tabs = new Tabs($settings);

		// render form
		$form = new Form($tabs->render(), [
			'section_name' => self::settings_name
		]);
        
		echo $form->render();
	}
}