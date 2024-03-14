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

namespace FireBox\Core\Form\Actions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Ajax
{
	public function __construct()
	{
		$this->setupAjax();
    }
    
	/**
	 * Setup ajax requests
	 * 
	 * @return  void
	 */
	public function setupAjax()
	{
		add_action('wp_ajax_fb_get_integration_lists', [$this, 'fb_get_integration_lists']);
    }

	/**
	 * Retrieve the Integration list given the API Key.
	 * 
	 * @return  void
	 */
	public function fb_get_integration_lists()
	{
		// Verify nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
			echo wp_json_encode([
				'error' => true,
				'message' => fpframework()->_('FPF_CANNOT_VERIFY_REQUEST')
			]);
			wp_die();
        }

		// Ensure we have an integration
		$integration = isset($_POST['integration']) ? sanitize_text_field($_POST['integration']) : null;
		if (!$integration)
		{
			echo wp_json_encode([
				'error' => true,
				'message' => fpframework()->_('FPF_NO_INTEGRATION_SUPPLIED')
			]);
			wp_die();
		}

		$help_text = '<a href="https://www.fireplugins.com/docs/firebox/how-to-connect-firebox-with-' . strtolower($integration) . '#find_my_api_key" target="_blank">' . fpframework()->_('FPF_WHERE_TO_FIND_API_KEY') . '</a>';

		// Ensure we have an API key
		$api_key = isset($_POST['api_key']) ? sanitize_text_field($_POST['api_key']) : null;
		if (!$api_key && $api_key !== 'skip')
		{
			echo wp_json_encode([
				'error' => true,
				'message' => fpframework()->_('FPF_PLEASE_ENTER_AN_API_KEY'),
				'help' => $help_text
			]);
			wp_die();
		}

		// Validate integration class
		$class = '\FPFramework\Base\Integrations\\' . $integration;
		if (!class_exists($class))
		{
			echo wp_json_encode([
				'error' => true,
				'message' => fpframework()->_('FPF_NO_SUCH_INTEGRATION_EXISTS')
			]);
			wp_die();
		}

		$integrationClass = new $class([
			'api' => $api_key
		]);

		// Ensure getLists method exists
		if (!method_exists($integrationClass, 'getLists'))
		{
			echo wp_json_encode([
				'error' => true,
				'message' => fpframework()->_('FPF_INTEGRATION_INVALID')
			]);
			wp_die();
		}

		$error = false;
		$message = '';

		try {
			$lists = $integrationClass->getLists();
			if (!is_array($lists) || !count($lists))
			{
				$error = true;
				$string = $api_key !== 'skip' ? 'FPF_API_KEY_INVALID_OR_INTEGRATION_ACCOUNT_HAS_NO_LISTS' : 'FPF_INTEGRATION_ACCOUNT_HAS_NO_LISTS';
				$message = sprintf(fpframework()->_($string), $integration);
				if ($api_key === 'skip')
				{
					$help_text = '';
				}
			}
			else
			{
				$message = [
					'lists' => [
						// Placeholder
						[
							'label' => fpframework()->_('FPF_SELECT_A_LIST'),
							'value' => null
						]
					]
				];
				foreach ($lists as $list)
				{
					$message['lists'][] = [
						'label' => $list['name'],
						'value' => $list['id']
					];
				}
			}
		}
		catch (\Exception $e)
		{
			$error = true;
			$message = $e->getMessage();
		}

		$payload = [
			'error' => $error,
			'message' => $message
		];

		if (!empty($help_text))
		{
			$payload['help'] = $help_text;
		}

		echo wp_json_encode($payload);
		wp_die();
	}
}