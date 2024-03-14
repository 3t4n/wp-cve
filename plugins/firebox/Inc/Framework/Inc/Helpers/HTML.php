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

class HTML
{
	/**
	 * Generates a Pro button
	 * 
	 * @param   string  $label
	 * @param   string  $feature_label
	 * @param   string  $plugin
	 * 
	 * @return  string
	 */
	public static function renderProButton($label = '', $feature_label = '', $plugin = '')
	{
		if (!is_string($label) && !is_string($feature_label))
		{
			return;
		}
		
		$class = '\FPFramework\Base\Fields\Pro';

		$options = [
			'type' => 'Pro',
			'plugin' => $plugin,
			'label' => $label,
			'feature_label' => $feature_label,
			'render_group' => false
		];
		
		$field = new $class($options);

		ob_start();
		$field->render();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Generates a FPToggle input
	 * 
	 * @param   string  $atts
	 * 
	 * @return  string
	 */
	public static function renderFPToggle($atts)
	{
		if (!$atts && !is_array($atts))
		{
			return;
		}
		
		$class = '\FPFramework\Base\Fields\FPToggle';

		$options = [
			'type' => 'FPToggle',
			'render_group' => false
		];

		$options = array_merge($options, $atts);

		$field = new $class($options);

		ob_start();
		$field->render();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Renders a heading
	 * 
	 * @param   array  $atts
	 * 
	 * @return  string
	 */
	public static function renderHeading($atts)
	{
		if (!$atts && !is_array($atts))
		{
			return;
		}
		
		$class = '\FPFramework\Base\Fields\Heading';

		$options = [
			'type' => 'Heading',
			'render_group' => false
		];

		$options = array_merge($options, $atts);

		$field = new $class($options);

		ob_start();
		$field->render();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Renders the opening of a div
	 * 
	 * @param   array  $atts
	 * 
	 * @return  string
	 */
	public static function renderStartDiv($atts)
	{
		if (!$atts && !is_array($atts))
		{
			return;
		}

		$class = '\\FPFramework\\Base\\Fields\\CustomDiv';

		$options = ['type' => 'CustomDiv'];
		$options = array_merge($options, $atts);
		
		$field = new $class($options);
		
		ob_start();
		$field->render();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Renders the closing of a div
	 * 
	 * @return  string
	 */
	public static function renderEndDiv()
	{
		$class = '\\FPFramework\\Base\\Fields\\CustomDiv';

		$options = [
			'type' => 'CustomDiv',
			'position' => 'end'
		];

		$field = new $class($options);
		
		ob_start();
		$field->render();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Renders an image of the pro feature with an Upgrade to Pro button.
	 * 
	 * @param   array  $atts
	 * 
	 * @return  string
	 */
	public static function renderImageProFeature($atts = [])
	{
		if (!$atts)
		{
			return;
		}

		fpframework()->renderer->upgrades->render('pro_image', $atts);
	}

	/**
	 * Renders a modal.
	 * 
	 * @param   array  $payload
	 * @param   boolean  $return
	 * 
	 * @return  mixed
	 */
	public static function renderModal($payload = [], $return = false)
	{
		if (!$payload && !is_array($payload))
		{
			return;
		}

		if (!is_bool($return))
		{
			return;
		}

		// JS
		wp_register_script(
			'fpframework-modal',
			FPF_MEDIA_URL . 'admin/js/fpf_modal.js',
			[],
			FPF_VERSION,
			true
		);
		wp_enqueue_script('fpframework-modal');

		if ($return)
		{
			return fpframework()->renderer->admin->render('modal', $payload, $return);
		}
		
		fpframework()->renderer->admin->render('modal', $payload);
	}

	/**
	 * Render the conditions list.
	 * 
	 * @param   array	$opts
	 * 
	 * @return  string
	 */
	public static function renderConditions($opts = [])
	{
		$class = '\FPFramework\Base\Fields\Conditions';

		$options = [
			'type' => 'SearchDropdown',
			'path' => '\FPFramework\Helpers\ConditionsHelper',
			'render_group' => false
		];
		$options = array_merge($options, $opts);
		$field = new $class($options);
		
		ob_start();
		$field->render();
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}